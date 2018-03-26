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
		//if root for shared links exist delete it from session
		if($this->session->has_userdata('root_link')){
			$this->session->unset_userdata('root_link');
		}
		//load conf file
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

	//metod for viewing settings page
	public function settings(){
		//set session part for nav bar
		$this->session->set_userdata('currPage', 2);
		//load conf file
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
			if(!$this->session->has_userdata('logedIn')){
				redirect('Login/logout');
			}
			if($this->session->has_userdata('logedZero')){
				$this->session->unset_userdata('logedZero');
			}
		}
		else{
			//if zero flag is active in file but not in session, set it
			if(!$this->session->has_userdata('logedZero')){
				$this->session->set_userdata('logedZero',1);
			}
		}
		//set session for error message
		if(!$this->session->has_userdata('err_message')){
			$this->session->set_userdata('err_message', '');
		}
		//load data for view
		$data = array(
			'flagStatus' => $trueFlag,
			'flagError' => $this->session->userdata('err_message')
		);
		//load settings view
		$this->load->view('templates/header.php');
		$this->load->view('settings', $data);
		$this->load->view('templates/foother.php');
	}

	//method for submiting settings 
	public function settingsSub(){
		//load conf file
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
		//load new password and current passwrod from view form
		$newPass = $this->input->post("newPassword");
		$oldPass = $this->input->post("oldPassword");
		//if current password is not correct set message and return
		if($oldPass!==$truePass){
			$this->session->set_userdata('err_message', 'Wrong password');
			redirect('Login/settings');
		}
		//if new passwrod is set (different from '') add it to conf file
		if($newPass!==''){
			$textLine1='<pass>' . $newPass . '</pass>';
		}
		else{
			$textLine1='<pass>' . $truePass . '</pass>';
		}
		//check if deleteing all links is needed
		if(!empty($this->input->post("flag1"))){
			//if it is, write to confFiles/links.php only first access line
			$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'links.php';
			$myfile = fopen($destPath, "w");
			fwrite($myfile, "<?php exit('Access is forbidden'); ?>\n");
			fclose($myfile);
		}
		//set the flag state from form to file
		if(empty($this->input->post("flag"))){
			$textLine2="<flag>1</flag>";
		}
		else{
			$textLine2="<flag>0</flag>";
		}
		//open conf file and write data to it
		$destPath= FCPATH . 'confFiles' . $this->PARSE_SIGN . 'conf.php';
		$myfile = fopen($destPath, "w");
		fwrite($myfile, $firstLine . $textLine1 . $textLine2);
		fclose($myfile);
		//set output message and return to settings view
		$this->session->set_userdata('err_message', 'Changes saved');
		redirect('Login/settings');
	}

	//metod for showing php info, if nedded change protected to public
	protected function info(){
		echo phpinfo();
	}
}
