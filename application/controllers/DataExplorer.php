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
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.php';
		$fh = fopen($destPath,'r');
		$firstLine = fgets($fh);
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
			if($this->session->has_userdata('root_link')){
				$this->session->unset_userdata('root_link');
			}
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
			if($this->session->has_userdata('root_link')){
				$this->session->unset_userdata('root_link');
			}
		}
	}

	//method for session check if user is correctly loged in or user is accessing with sahred link
	protected function logedCheckerWithLinks(){
		//open conf file
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.php';
		$fh = fopen($destPath,'r');
		$firstLine = fgets($fh);
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
		//check if flag is zero or user is loged in or user is accessing iwth sahred link
		if(!($trueFlag=='0')){
			if(!$this->session->has_userdata('logedIn') && !$this->session->has_userdata('root_link')){
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
			if($this->session->has_userdata('root_link')){
				$this->session->unset_userdata('root_link');
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
		$this->logedCheckerWithLinks();
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
		if($this->session->has_userdata('root_link')){
			$relativePath = substr($filePath, strlen($this->session->userdata('root_link')));	
		}
		else{
			$relativePath = substr($filePath, strlen($this->STORAGE_PATH));	
		}
		$fileText='';
		//check if selected file is dir
		if(!is_dir($this->session->userdata('curr_path'))){
			//selected file is not dir
			//get extension
			$file_extension= pathinfo($this->session->userdata('curr_path'), PATHINFO_EXTENSION);
			$file_extension= strtolower($file_extension);
			//check if file is picture
			if($file_extension=='jpg' || $file_extension=='bnp' || $file_extension=='jpeg' || $file_extension=='png'){
				//copy video to play folder
				$this->copyFileToPlay();
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'fileExtension' => $file_extension,
					'PARSE_SIGN' => $this->PARSE_SIGN,
					'relativePath' => $relativePath
				);
				//load view of picture
				$this->load->view('templates/header.php');
				$this->load->view('imageView', $data);
				$this->load->view('templates/foother.php');
				return;
			}
			//check if file is video
			if($file_extension=='mp4' || $file_extension=='mov'){
				//copy video to play folder
				$this->copyFileToPlay();
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'relativePath' => $relativePath,
					'PARSE_SIGN' => $this->PARSE_SIGN,
					'fileExtension' => $file_extension
				);
				//load view of video
				$this->load->view('templates/header.php');
				$this->load->view('videoView', $data);
				$this->load->view('templates/foother.php');
				return;
			}
			//check if file is audio
			if($file_extension=='mp3'){
				//copy audio to play folder
				$this->copyFileToPlay();
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'relativePath' => $relativePath,
					'PARSE_SIGN' => $this->PARSE_SIGN,
					'fileExtension' => $file_extension
				);
				//load view of audio
				$this->load->view('templates/header.php');
				$this->load->view('audioView', $data);
				$this->load->view('templates/foother.php');
				return;
			}
			//check if file is pdf
			if($file_extension=='pdf'){
				//copy pdf to play folder
				$this->copyFileToPlay();
				//create data for view
				$data = array(
					'isRoot' => $isRootCh,
					'relativePath' => $relativePath,
					'PARSE_SIGN' => $this->PARSE_SIGN,
					'fileExtension' => $file_extension
				);
				//load view of pdf
				$this->load->view('templates/header.php');
				$this->load->view('pdfView', $data);
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
				'PARSE_SIGN' => $this->PARSE_SIGN,
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
	protected function checkIfrootDir(){
		//check if root_link exist (user checked with shared link)
		if($this->session->has_userdata('root_link')){
			if(strlen($this->session->userdata('curr_path'))==strlen($this->session->userdata('root_link'))){
				return true;
			}
		}
		else{
			if(strlen($this->session->userdata('curr_path'))==strlen($this->STORAGE_PATH)){
				return true;
			}
		}
		return false;
	}

	//method for going back in path
	public function BackDirection(){
		$this->logedCheckerWithLinks();
		//get path of father dir
		$tempFilePath=dirname($this->session->userdata('curr_path'));
		//change it if its not root
		//check if root_link exist (user checked with shared link)
		if($this->session->has_userdata('root_link')){
			if(strlen($tempFilePath)>=strlen($this->session->userdata('root_link'))){
				$this->session->set_userdata('curr_path', $tempFilePath);
			}
		}
		else{
			if(strlen($tempFilePath)>=strlen($this->STORAGE_PATH)){
				$this->session->set_userdata('curr_path', $tempFilePath);
			}
		}
		//load view	
		redirect('DataExplorer/index');
	}

	//method for going back to root
	public function resetFolder(){
		$this->logedCheckerWithLinks();
		//check if root_link exist (user checked with shared link)
		if($this->session->has_userdata('root_link')){
			$this->session->set_userdata('curr_path', $this->session->userdata('root_link'));
		}
		else{
			$this->session->unset_userdata('curr_path');
		}
		redirect('DataExplorer/index');
	}

	//method for ziping and downloading folder
	protected function zipFolder($folderName){
		$folderName=urldecode($folderName);
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
		chmod(base_url() . $download, 0777);
		//download zip file
		$data=file_get_contents("zip/download.zip");
		if(strlen($folderName)==strlen($this->STORAGE_PATH)){
			$fileName= "root";
		}
		else{
			$fileName= pathinfo($folderName, PATHINFO_FILENAME);
		}		
		force_download( $fileName . ".zip", $data);
	}

	//method for downloading file or folder
	public function DownloadFile($fileName=""){
		$this->logedCheckerWithLinks();
		$fileName=urldecode($fileName);
		//get file path
		if($fileName==""){
			$tempFilePath= $this->session->userdata('curr_path');
		}
		else{
			$tempFilePath= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName;
		}
		//check if file is dir
		if(!is_dir($tempFilePath)){
			//data contents
			$data=file_get_contents($tempFilePath);
			//if its not dir, download it
			$fileName= pathinfo($tempFilePath, PATHINFO_BASENAME);
			force_download($fileName, $data);
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
		//set permission to copied file
		//exec('sudo chmod 777 '.$target);
		chmod($target, 0777);
		redirect('DataExplorer/index');
	}

	//method for rename file load view
	public function RenameFileView($fileName){
		$this->logedChecker();
		//decode name of file
		$fileName=urldecode($fileName);
		//load data of file
		$data = array(
			'fileName' => $fileName
		);
		//load view of rename
		$this->load->view('templates/header.php');
		$this->load->view('rename', $data);
		$this->load->view('templates/foother.php');
	}

	//method for rename file or dir
	public function RenameFile($fileName){
		$this->logedChecker();
		//decode name of file
		$fileName=urldecode($fileName);
		//get new name
		$newFileName = $this->input->post("newName");
		if($newFileName==""){
			redirect('DataExplorer/RenameFileView/' . $fileName);
			return;
		}
		$newFileName=urldecode($newFileName);
		//get path of file
		$oldFileName= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName;
		//set new path of file
		$newFileName= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $newFileName;
		//get extension if file
		if(!is_dir($oldFileName)){
			$newFileName .= "." . pathinfo($oldFileName, PATHINFO_EXTENSION);
		}
		//rename file
		rename($oldFileName, $newFileName);
		//remove links with specific files
		$this->innerShareRenameLink($oldFileName, $newFileName);
		redirect('DataExplorer/index');
		//exec('sudo chmod 777 '.$newFileName);
		chmod($newFileName, 0777);
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
			$this->innerShareDeleteLink($fileName);
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
				$this->innerShareDeleteLink($file);
			}
		}
		//delete current dir
		rmdir($dirPath);
		$this->innerShareDeleteLink(substr($dirPath, 0, -1));
	}

	//method for copying video and audio to server folder for playing
	protected function copyFileToPlay(){
		//get curr date
		$curr_date= date('Y-m-d');
		$curr_date= hash('md2', $curr_date);
		//get extension of video
		$file_extension= pathinfo($this->session->userdata('curr_path'), PATHINFO_EXTENSION);
		//get name of video
		$file_name= pathinfo($this->session->userdata('curr_path'), PATHINFO_FILENAME);
		//set up video name date part
		$videoName= $curr_date;
		$videoName .= "_";
		//set up video name, real name part
		$videoName .= hash('md2', $file_name);
		//video name for checking
		$videoNameForCheck = $videoName;
		$videoNameForCheck .= "." . $file_extension;
		//get all files from video folder
		$files=scandir(FCPATH . 'play');
		//set temp flag which is used to cehck if file is already in play folder
		$tempFlag=0;
		//remove files that have old date (not current)
		foreach ($files as $file) {
			if($file=='.' || $file=='..' || $file=='index.html'){
				continue;
			}
			$pieces = explode("_", $file);
			if($curr_date!=$pieces[0]){
				unlink(FCPATH . 'play' . $this->PARSE_SIGN . $file);
			}
			else{
				//file already exists in play folder
				if($file==$videoNameForCheck){
					$tempFlag=1;
				}
			}
		}
		//if file exists, return
		if($tempFlag==1){
			return;
		}
		//get path of video folder on server
		$destPath= FCPATH . 'play' . $this->PARSE_SIGN . $videoName . '.' . $file_extension;
		//get path of source place of video
		$sourcePath= $this->session->userdata('curr_path');
		//copy video
		copy($sourcePath, $destPath);
		//set permission to copied file
		//exec('sudo chmod 777 '.$destPath);
		chmod($destPath, 0777);
	}

	/*row in links.php is with date when row is created, path to current folder and new row char
	etc: 2018-03-20 07:33:08pm/filePath/something/\n
	row is hashed and send to user*/

	//method for creation of link for current folder or file
	public function createShareLink($fileName=''){
		//build folder or file path to txt var
		if($fileName==''){
			$txt= $this->session->userdata('curr_path');
		}
		else{
			$fileName=urldecode($fileName);
			$txt= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName; 
		}
		//write path to /confFiles/links.php
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'a');
		fwrite($fh, date("Y-m-d h:i:sa").$txt . "\n");
		fclose($fh);
		//load view again
		redirect('DataExplorer/index');
	}

	//method for deletng link for current folder or file
	public function deleteShareLink($fileName=''){
		//build folder or file path to txt var
		if($fileName==''){
			$txt= $this->session->userdata('curr_path') . "\n";
		}
		else{
			$fileName=urldecode($fileName);
			$txt= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName . "\n"; 
		}
		//load all rows from /confFiles/links.php all exept curent path
		$newFileText='';
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'r');
		$line = fgets($fh);
		$newFileText .=$line;
		while($line = fgets($fh)){
			//remove date from line in links and leave only path
			$line1=substr($line, 21);
			if($line1!=$txt){
				$newFileText .=$line;
			}
		}
		fclose($fh);
		//write loaded string to /confFiles/links.php
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'w');
		fwrite($fh, $newFileText);
		fclose($fh);
		//load view again
		redirect('DataExplorer/index');
	}

	//method for finding link for current folder or file
	public function showShareLink($fileName=''){
		//string for ret value
		$retString = '';
		//build folder or file path to txt var
		if($fileName==''){
			if(!$this->checkIfrootDir()){
				$fileName= pathinfo($this->session->userdata('curr_path'), PATHINFO_FILENAME);
			}
			else{
				$fileName="Root";
			}
			$txt= $this->session->userdata('curr_path') . "\n";
		}
		else{
			$fileName=urldecode($fileName);
			$txt= $this->session->userdata('curr_path') . $this->PARSE_SIGN . $fileName . "\n"; 
		}
		//check if path exists in confFiles/links
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'r');
		$line = fgets($fh);
		while($line = fgets($fh)){
			//remove date from line in links and leave only path
			$line1=substr($line, 21);
			if($line1==$txt){
				//if exists hash row from links into ret value
				$retString .= hash('md2', $line);
				break;
			}
		}
		fclose($fh);
		//load view for link showing
		$data = array(
			'fileName' => $fileName,
			'linkText' => $retString
		);
		if($retString==''){
			redirect('DataExplorer/index');
		}
		$this->load->view('templates/header.php');
		$this->load->view('shareLinkView', $data);
		$this->load->view('templates/foother.php');
	}

	//method for receiving shared link view request  
	public function FileShare($fileCode){
		//load /confFiles/links.php and check all lines to see if fileCode exists
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'r');
		$line = fgets($fh);
		while($line = fgets($fh)){
			//remove date from line in links and leave only path
			$line1=substr($line, 21);
			$lineAct=substr($line1, 0, -1);
			$line= hash('md2', $line);
			if($line==$fileCode){
				fclose($fh);
				//if exists check if user is loged in
				if(!$this->session->has_userdata('logedIn')){
					//if not set root folder to requested file
					$this->session->set_userdata('root_link', $lineAct);
				}
				//set curr folder to requested file
				$this->session->set_userdata('curr_path', $lineAct);			
				redirect('DataExplorer/index');
				return;
			}
		}
		fclose($fh);
		redirect('Login/index');
	}

	//method for deleteing shared link when file or dir is deleted
	protected function innerShareDeleteLink($filePath){
		$newFileText='';
		//add \n part because in file deleting it doesnt exists
		$filePath.="\n";
		//load all rows from congFiles/links.php and add all exept filePath
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'r');
		$line = fgets($fh);
		$newFileText .=$line;
		while($line = fgets($fh)){
			$line1=substr($line, 21);
			if($line1!=$filePath){
				$newFileText .=$line;
			}
		}
		fclose($fh);
		//save all loaded rows
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'w');
		fwrite($fh, $newFileText);
		fclose($fh);
	}

	//method for deleteing shared link when file or dir is deleted
	protected function innerShareRenameLink($filePath, $newFilePath){
		$newFileText='';
		//load all rows from congFiles/links.php and add all exept filePath, that one change
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'r');
		$line = fgets($fh);
		$newFileText .=$line;
		while($line = fgets($fh)){
			$line1=substr($line, 21);
			if (!(strpos($line1, $filePath) !== false)) {
				$newFileText .=$line;
			}
			else{
				$lineTmp= substr($line, 0, 21);
				$lineTmp.= $newFilePath;
				$lineTmp.= substr($line1, strlen($filePath));
				$newFileText .=$lineTmp;
			}
		}
		fclose($fh);
		//save all loaded rows
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
		$fh = fopen($destPath,'w');
		fwrite($fh, $newFileText);
		fclose($fh);
	}
}