<?php

//class for manipulation over data on storage
class DataExplorer extends CI_Controller {

	//path to storage folder
	public $STORAGE_PATH='/Users/milanlazarevic/Desktop/testStorage';
	//path parser in os. mac, linux='/'; windows='\\\\';
	public $PARSE_SIGN= '/';

	//method for session check if user is correctly loged in
	protected function logedChecker(){
		//open conf file
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.txt';
		$fh = fopen($destPath,'r');
		//load password from conf file		
		if ($line = fgets($fh)) {
			$truePass='';
			for($i=6; $i<strlen($line); $i++){
				if($line[$i]=='<'){
					break;
				}
				$truePass.=$line[$i];
			}
			for(;$line[$i]!=='>';$i++);
			$i++;
			//load flag from conf file
			$trueFlag='';
			for($i+=6; $i<strlen($line); $i++){
				if($line[$i]=='<'){
					break;
				}
				$trueFlag.=$line[$i];
			}
		}
		fclose($fh);
		//check if flag is zero or user is loged in
		if(!($trueFlag=='0')){
			if(!$this->session->has_userdata('logedIn')){
				redirect('Login/logout');
			}
			if($this->session->has_userdata('logedZero')){
				$this->session->unset_userdata('logedZero');
			}
		}
		else{
			if(!$this->session->has_userdata('logedZero')){
				$this->session->set_userdata('logedZero',1);
			}
		}
	}

	//method for showing content of dir
	public function index($currFolder=''){
		//if session part for change pass exists, delete it
		if($this->session->has_userdata('err_message')){
			$this->session->unset_userdata('err_message');
		}
		//check if user is properly loged in
		$this->logedChecker();
		//set current page for header
		$this->session->set_userdata('currPage', 1);
		//decode for blank sign currentli checked folder (on start its empty)
		$currFolder=urldecode($currFolder);
		//if session is empty, set it to the root folder (on start its empty)
		if(!$this->session->has_userdata('curr_path')){
			$this->session->set_userdata('curr_path', $this->STORAGE_PATH);
		}
		//if folder is selected add folder name to the path and clear it from path
		if($currFolder !== ''){
			$temp_path= $this->session->userdata('curr_path');
			$temp_path .= $this->PARSE_SIGN . $currFolder;
			$this->session->set_userdata('curr_path', $temp_path);
			redirect('DataExplorer/index');
		}
		//check if file is root dir (for back option)
		$isRootCh=$this->checkIfrootDir();
		//find relative file to the dir from root
		$filePath = $this->session->userdata('curr_path');
		$relativePath = substr($filePath, strlen($this->STORAGE_PATH));	
		$fileText='';
		//check if selected file is dir
		if(!is_dir($this->session->userdata('curr_path'))){
			//selected file is not dir
			//get extension
			$file_extension= pathinfo($this->session->userdata('curr_path'), PATHINFO_EXTENSION);
			//check if file is picture
			if($file_extension=='jpg' || $file_extension=='bnp' || $file_extension=='jpeg' || $file_extension=='png'){
				//load picture to data byte array
				$image = fopen($this->session->userdata('curr_path'), 'r');
				$ImageData = fread($image,filesize($this->session->userdata('curr_path')));
				fclose($image);
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'fileImage' => $ImageData,
					'relativePath' => $relativePath
				);
				//load view of picture
				$this->load->view('templates/header.php');
				$this->load->view('imageView', $data);
				$this->load->view('templates/foother.php');
				return;
			}
			//check if file is video
			if($file_extension=='mp4'){
				//copy video to play folder
				$this->copyVideo();
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'relativePath' => $relativePath,
					'fileExtension' => $file_extension
				);
				//load view of video
				$this->load->view('templates/header.php');
				$this->load->view('videoView', $data);
				$this->load->view('templates/foother.php');
				return;
			}
			//load byte array of file
			$fh = fopen($this->session->userdata('curr_path'),'r');			
			while ($line = fgets($fh)) {
				$fileText .=$line;
			}
			fclose($fh);
			//create data for view
			$data = array(
				'isRoot' => $isRootCh,
				'fileText' => $fileText,
				'relativePath' => $relativePath
			);
			//load view of file
			$this->load->view('templates/header.php');
			$this->load->view('fileView', $data);
			$this->load->view('templates/foother.php');
			return;
		}	
		//scand dir for all files in it
		$files=scandir($this->session->userdata('curr_path'));
		//create data for view
		$data = array(
			'files' => $files,
			'isRoot' => $isRootCh,
			'PARSE_SIGN' => $this->PARSE_SIGN,
			'relativePath' => $relativePath
		);
		//load view of dir
		$this->load->view('templates/header.php');
		$this->load->view('dataExplore', $data);
		$this->load->view('templates/foother.php');
	}

	//method for checking if path is root dir (for back option)
	public function checkIfrootDir(){
		if(strlen($this->session->userdata('curr_path'))==strlen($this->STORAGE_PATH)){
			return true;
		}
		return false;
	}

	//method for going back in path
	public function BackDirection(){
		$this->logedChecker();
		//get path of father dir
		$tempFilePath=dirname($this->session->userdata('curr_path'));
		//change it if its not root
		if(strlen($tempFilePath)>=strlen($this->STORAGE_PATH)){
			$this->session->set_userdata('curr_path', $tempFilePath);
		}
		//load view	
		redirect('DataExplorer/index');
	}

	//method for goint back to root
	public function resetFolder(){
		$this->logedChecker();
		$this->session->unset_userdata('curr_path');
		redirect('DataExplorer/index');
	}

	//method for ziping and downloading folder
	public function zipFolder($folderName){
		$this->logedChecker();
		// Get real path for our folder
		$rootPath = realpath($folderName);
		// Initialize archive object
		$zip = new ZipArchive();
		$download = 'zip/download.zip';
		$zip->open($download, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ($files as $name => $file){
			// Skip directories (they would be added automatically)
			if (!$file->isDir()){
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
		}
		// Zip archive will be created only after closing object
		$zip->close();		
		// http headers for zip downloads
		force_download("download.zip", "zip/download.zip");
		/*header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"download.zip\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($download));
		ob_end_flush();
		@readfile($download);*/
	}

	//method for downloading file or folder
	public function DownloadFile($fileName){
		$this->logedChecker();
		//get file path
		$tempFilePath= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName;
		//check if file is dir
		if(!is_dir($tempFilePath)){
			//if its not dir, download it
			force_download($fileName, $tempFilePath);
		}
		else{
			//if its dir, zip it and download it
			$this->zipFolder($tempFilePath);
		}
	}

	//method for creating dir
	public function CreateDirectory(){
		$this->logedChecker();
		//get folder name from form
		$FolderName = $this->input->post("FolderName");
		//check if its not blank
		if($FolderName == ''){
			redirect('DataExplorer/index');
		}
		//create path
		$TempFilePath=$this->session->userdata('curr_path') . $this->PARSE_SIGN . $FolderName;
		//if doesnt exist, create it
		if (!file_exists($TempFilePath)) {
			mkdir($TempFilePath, 0777, true);
		}
		redirect('DataExplorer/index');
	}

	//method for uploading file
	public function UploadFile(){
		$this->logedChecker();
		//get path of file
		$info = pathinfo($_FILES['userFile']['name']);
		$fName = $info['basename'];
		//get path of place where to put it
		$target = $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fName;
		//move uploaded file
		move_uploaded_file( $_FILES['userFile']['tmp_name'], $target);
		redirect('DataExplorer/index');
	}

	//method for remove file or dir
	public function RemoveFile($fileName){
		$this->logedChecker();
		//decode name of file
		$fileName=urldecode($fileName);
		//get path of file
		$fileName= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName;
		//check if dir
		if(is_dir($fileName)){
			//if its dir delete it with special method
			$this->deleteDir($fileName);
		}
		else{
			//if its file delete it
			unlink($fileName);
		}
		redirect('DataExplorer/index');
	}

	//recursive method for deleting dir
	public function deleteDir($dirPath) {
		$this->logedChecker();
		//check if its dir
		if (! is_dir($dirPath)) {
			return;
		}
		//add / to end
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		//get all files in dir and delete them first
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				//delete recursively dir
				self::deleteDir($file);
			} else {
				//delete file
				unlink($file);
			}
		}
		//delete current dir
		rmdir($dirPath);
	}

	//method for copying video to server folder for playing
	public function copyVideo(){
		//get extension of video
		$file_extension= pathinfo($this->session->userdata('curr_path'), PATHINFO_EXTENSION);
		//get path of video folder on server
		$destPath= FCPATH . 'video' . $this->PARSE_SIGN . 'video.' . $file_extension;
		//get path of source place of video
		$sourcePath= $this->session->userdata('curr_path');
		//copy video
		copy($sourcePath, $destPath);
	}
}
