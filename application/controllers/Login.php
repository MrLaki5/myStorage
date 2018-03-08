<?php


class Login extends CI_Controller {

	//path to storage folder
	public $STORAGE_PATH='/Users/milanlazarevic/Desktop/testStorage';
	//path parser in os. mac, linux='/'; windows='\\\\';
	public $PARSE_SIGN= '/';

	public function index()
	{
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.txt';
		$fh = fopen($destPath,'r');	
		if ($line = fgets($fh)) {
			$truePass='';
			for($i=6; $i<strlen($line); $i++){
				if($line[$i]=='<'){
					break;
				}
				$truePass.=$line[$i];
			}
		}
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
		if ($trueFlag=='0'){
			$this->session->set_userdata('logedZero', 1);
			//$this->session->set_userdata('logedIn', 1);
			redirect('DataExplorer/index', 'refresh');
		}
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index', 'refresh');
		}
		$this->load->view('templates/header.php');
		$this->load->view('login');
		$this->load->view('templates/foother.php');
	}

	public function loginF(){
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.txt';
		$fh = fopen($destPath,'r');		
		if ($line = fgets($fh)) {
			$truePass='';
			for($i=6; $i<strlen($line); $i++){
				if($line[$i]=='<'){
					break;
				}
				$truePass.=$line[$i];
			}
		}
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
		if ($trueFlag=='0'){
			$this->session->set_userdata('logedZero', 1);
			//$this->session->set_userdata('logedIn', 1);
			redirect('DataExplorer/index', 'refresh');
		}
		if($this->session->has_userdata('logedIn')){
			redirect('DataExplorer/index', 'refresh');
		}
		//get password from form
		$FolderName = $this->input->post("password");
		//check if password is right
		//echo $FolderName . "   ";
		//echo $truePass;
		//echo strlen($truePass);
		if(strcmp($FolderName, $truePass)==0){
			$this->session->set_userdata('logedIn', 1);
			redirect('DataExplorer/index', 'refresh');
		}
		else{
			redirect('Login/index', 'refresh');
		}
	}

	public function logout(){
		$this->session->unset_userdata('curr_path');
		$this->session->unset_userdata('logedIn');
		$this->session->unset_userdata('logedZero');
		redirect('Login/index', 'refresh');
	}
}
