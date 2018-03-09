<?php

//class for login
class Login extends CI_Controller {

	//path to storage folder
	public $STORAGE_PATH='/Users/milanlazarevic/Desktop/testStorage';
	//path parser in os. mac, linux='/'; windows='\\\\';
	public $PARSE_SIGN= '/';
	//error message in settings change
	public $err_message='';

	//show login page
	public function index(){
		//load conf file
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
		//if flag is zero that mean that login is not necesery and user can continue to data explore
		if ($trueFlag=='0'){
			$this->session->set_userdata('logedZero', 1);
			redirect('DataExplorer/index');
		}
		//if user is already loged in he can continue to data explore
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index');
		}
		//load login page view
		$this->load->view('templates/header.php');
		$this->load->view('login');
		$this->load->view('templates/foother.php');
	}

	//method for checking password from login form
	public function loginF(){
		//load conf file
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
		//if flag is zero that mean that login is not necesery and user can continue to data explore
		if ($trueFlag=='0'){
			$this->session->set_userdata('logedZero', 1);
			redirect('DataExplorer/index');
		}
		//if user is already loged in he can continue to data explore
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index');
		}
		//get password from form
		$FolderName = $this->input->post("password");
		//check if password is correct, if it is go to data explore
		if(strcmp($FolderName, $truePass)==0){
			$this->session->set_userdata('logedIn', 1);
			redirect('DataExplorer/index');
		}
		else{
			//else, stay on login page
			redirect('Login/index');
		}
	}

	//metod for cearing session and getting to login page
	public function logout(){
		$this->session->unset_userdata('curr_path');
		$this->session->unset_userdata('logedIn');
		$this->session->unset_userdata('logedZero');
		$this->session->unset_userdata('currPage');
		$this->session->unset_userdata('err_message');
		redirect('Login/index');
	}

	public function settings(){
		$this->session->set_userdata('currPage', 2);
		//load conf file
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
		if(!$this->session->has_userdata('err_message')){
			$this->session->set_userdata('err_message', '');
		}
		$data = array(
			'flagStatus' => $trueFlag,
			'flagError' => $this->session->userdata('err_message')
		);
		$this->load->view('templates/header.php');
		$this->load->view('settings', $data);
		$this->load->view('templates/foother.php');
	}

	public function settingsSub(){
		//load conf file
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

		$newPass = $this->input->post("newPassword");
		$oldPass = $this->input->post("oldPassword");

		if($oldPass!==$truePass){
			$this->session->set_userdata('err_message', 'Wrong password');
			redirect('Login/settings');
		}
		if($newPass!==''){
			$textLine1='<pass>' . $newPass . '</pass>';
		}
		else{
			$textLine1='<pass>' . $truePass . '</pass>';
		}
		if(empty($this->input->post("flag"))){
			$textLine2="<flag>1</flag>";
		}
		else{
			$textLine2="<flag>0</flag>";
		}
		echo $this->input->post("flag");
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.txt';
		$myfile = fopen($destPath, "w");
		fwrite($myfile, $textLine1 . $textLine2);
		fclose($myfile);
		$this->session->set_userdata('err_message', 'Changes saved');
		redirect('Login/settings');
	}
}
