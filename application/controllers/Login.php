<?php

//class for login
class Login extends CI_Controller {

	//path to storage folder
	public $STORAGE_PATH='/Users/milanlazarevic/Desktop/testStorage';
	//path parser in os. mac, linux='/'; windows='\\\\';
	public $PARSE_SIGN= '/';

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
		}
		//load flag from cong file
		if ($line = fgets($fh)) {
			$trueFlag='';
			for($i=6; $i<strlen($line); $i++){
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
			redirect('DataExplorer/index', 'refresh');
		}
		//if user is already loged in he can continue to data explore
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index', 'refresh');
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
		}
		//load flag from conf file
		if ($line = fgets($fh)) {
			$trueFlag='';
			for($i=6; $i<strlen($line); $i++){
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
			redirect('DataExplorer/index', 'refresh');
		}
		//if user is already loged in he can continue to data explore
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index', 'refresh');
		}
		//get password from form
		$FolderName = $this->input->post("password");
		//check if password is correct, if it is go to data explore
		if(strcmp($FolderName, $truePass)==0){
			$this->session->set_userdata('logedIn', 1);
			redirect('DataExplorer/index', 'refresh');
		}
		else{
			//else, stay on login page
			redirect('Login/index', 'refresh');
		}
	}

	//metod for cearing session and getting to login page
	public function logout(){
		$this->session->unset_userdata('curr_path');
		$this->session->unset_userdata('logedIn');
		$this->session->unset_userdata('logedZero');
		redirect('Login/index', 'refresh');
	}
}
