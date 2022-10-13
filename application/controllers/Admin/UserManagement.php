<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagement extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('encryption');
		$this->load->library('session');
		$this->load->model('Admin/User_model');
	}
	//load page
	public function index()
	{
        
		if($this->session->userdata('isLogIn') === true){
			$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
			if (!empty($userData)) {
				$data['page'] = "UserManagement";
				$this->load->view('HeaderAndFooter/Header.php');
				$this->load->view('Pages/Admin/Wrapper.php',$data);
				$this->load->view('HeaderAndFooter/Footer.php');
			} else {
				redirect('AdminLogin');
			}
			
		}
		else{
			if(!empty(get_cookie('remember_me_token'))){
				$userData = $this->User_model->getCurrentUserCookie(get_cookie('remember_me_token'));
				if (!empty($userData)) {
					$this->session->set_userdata([
						'isLogIn'     => true,
						'userRole'     => $userData->userRole,
						'userId'     => $userData->userId,
						'firstName'     => $userData->fname,
						'lastName'  => $userData->lname,
						'email'       => $userData->email,
					]);
					redirect('UserManagement');
				} else {
					redirect('AdminLogin');
				}
			}
			else{
				redirect('AdminLogin');
			}
		}
	}
	public function editUser($id = '')
	{
		$urlData = urldecode($this->safe_decode($id));
        $data['userData'] = $this->User_model->getUser($this->encryption->decrypt($urlData) == '' ? null : $this->encryption->decrypt($urlData));
		if(!empty($data['userData'])){
			if($this->session->userdata('isLogIn') === true){
				$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
				if (!empty($userData)) {
					$data['page'] = "UserEditPage";
					$this->load->view('HeaderAndFooter/Header.php');
					$this->load->view('Pages/Admin/Wrapper.php',$data);
					$this->load->view('HeaderAndFooter/Footer.php');
				} else {
					redirect('AdminLogin');
				}
				
			}
			else{
				if(!empty(get_cookie('remember_me_token'))){
					$userData = $this->User_model->getCurrentUserCookie(get_cookie('remember_me_token'));
					if (!empty($userData)) {
						$this->session->set_userdata([
							'isLogIn'     => true,
							'userRole'     => $userData->userRole,
							'userId'     => $userData->userId,
							'firstName'     => $userData->fname,
							'lastName'  => $userData->lname,
							'email'       => $userData->email,
						]);
						redirect('EditUser/'.$this->safe_encode(urlencode($this->encryption->encrypt($data['userData']->userId))));
					} else {
						redirect('AdminLogin');
					}
				}
				else{
					redirect('AdminLogin');
				}
			}
		}
		else{
			redirect('UserManagement');
		}
	}
	//save user
	public function saveUser(){
		$this->form_validation->set_rules('userFirstname', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('userLastname', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('userEmail', 'Email' ,'required|callback_email_check|max_length[100]');
		$this->form_validation->set_rules('userPassword', 'Password' ,'required|callback_checkPasswordStrength|max_length[50]');
		$this->form_validation->set_rules('userRePassword', 'Confirm Password' ,'required|matches[userPassword]');
		$this->form_validation->set_rules('userRole', 'User Role' ,'required');
		
		$postData = array(
            "userId" => "USR-".$this->randStrGen(2,7),
            "fname" => ucfirst(strtolower($this->input->post("userFirstname"))),
            "lname" => ucfirst(strtolower($this->input->post("userLastname"))),
            "password" => md5($this->input->post("userPassword")),
            "email" => strtolower($this->input->post("userEmail")),
            "userRole" => $this->input->post("userRole"),
        );

		if($this->form_validation->run() === true){
			if ($this->User_model->addUser($postData)){
				$this->session->set_flashdata('successAddUser','Add Success');
			}
			else{
				$this->session->set_flashdata('failAddUser','Add Failed');
			}
            redirect('UserManagement');
        }
        else{
			$this->session->set_flashdata('failAddUser',validation_errors());
            redirect('UserManagement');
        }
	}
	//update user
	public function saveEdit(){
		$this->form_validation->set_rules('userFirstname', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('userLastname', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('userEmail', 'Email' ,'required|callback_email_check_edit['.$this->input->post('userIdField').']|max_length[100]');
		$this->form_validation->set_rules('userRole', 'User Role' ,'required');
		if($this->input->post("userPassword") != ''){
			$this->form_validation->set_rules('userPassword', 'Password' ,'required|callback_checkPasswordStrength|max_length[50]');
			$this->form_validation->set_rules('userRePassword', 'Confirm Password' ,'required|matches[userPassword]');
		}
		
		$postData = array(
            "userId" => $this->input->post('userIdField'),
            "fname" => ucfirst(strtolower($this->input->post("userFirstname"))),
            "lname" => ucfirst(strtolower($this->input->post("userLastname"))),
            // "password" => md5($this->input->post("userPassword")),
            "email" => strtolower($this->input->post("userEmail")),
            "userRole" => $this->input->post("userRole"),
        );

		if($this->input->post("userPassword") != ''){
			$postData['password'] = md5($this->input->post("userPassword"));
		}

		if($this->form_validation->run() === true){
			if ($this->User_model->editUser($postData)){
				$this->session->set_flashdata('successEditUser','Edit Success');
				if($this->session->userdata('userId') == $this->input->post('userIdField')){
					$this->session->set_userdata([
						'isLogIn'     => true,
						'userRole'     => $postData['userRole'],
						'userId'     => $postData['userId'],
						'firstName'     => $postData['fname'],
						'lastName'  => $postData['lname'],
						'email'       => $postData['email'],
					]);
				}
			}
			else{
				$this->session->set_flashdata('failEditUser','Edit Failed');
			}
            redirect('EditUser/'.$this->safe_encode(urlencode($this->encryption->encrypt($this->input->post("userIdField")))));
        }
        else{
			$this->session->set_flashdata('failEditUser',validation_errors());
            redirect('EditUser/'.$this->safe_encode(urlencode($this->encryption->encrypt($this->input->post("userIdField")))));
        }
	}
	//delete user
	public function deleteUser($id = ''){
		$urlData = urldecode($this->safe_decode($id));
		$newId = $this->encryption->decrypt($urlData) == '' ? null : $this->encryption->decrypt($urlData);
		if($newId != $this->session->userdata('userId')){
			if($this->User_model->deleteUser($newId)){
				$this->session->set_flashdata('successAddUser','Delete Success');
			}
			else{
				$this->session->set_flashdata('failAddUser','Delete Failed');
			}
		}
		else{
			$this->session->set_flashdata('failAddUser','You can\'t delete your account!');
		}
		
		redirect('UserManagement');
	}
	//login function
	public function checkUser(){
		$this->form_validation->set_rules('emailLogin', 'Email' ,'required');
		$this->form_validation->set_rules('passwordLogin', 'Password' ,'required');

		$postData = array(
			'password' => md5($this->input->post('passwordLogin')),
            'email' => $this->input->post('emailLogin'),
		);

		if($this->form_validation->run() === true){
			$userData = $this->User_model->checkCredentials($postData);
			if(!empty($userData)){
				if($this->input->post('rememberme')){

					!empty(get_cookie('count')) ? delete_cookie('count'):null;

					$token = $this->randStrGen(1,15);

					$cookie = array(
						'name'   => 'remember_me_token',
						'value'  => $token,
						'expire' => '1209600',  // Two weeks
					);

					set_cookie($cookie);
					$data = array(
						'remember_me_token' => $token
					);
					$this->db->where('userId',$userData->userId)->update('users',$data); 

					$this->session->set_flashdata('successLogin','Login Successful');
		
					$this->session->set_userdata([
						'isLogIn'     => true,
						'userRole'     => $userData->userRole,
						'userId'     => $userData->userId,
						'firstName'     => $userData->fname,
						'lastName'  => $userData->lname,
						'email'       => $userData->email,
					]);
					
					redirect('AdminDashboard');
				}
				else{
					!empty(get_cookie('count')) ? delete_cookie('count'):null;

					$this->session->set_flashdata('successLogin','Login Successful');
		
					$this->session->set_userdata([
						'isLogIn'     => true,
						'userRole'     => $userData->userRole,
						'userId'     => $userData->userId,
						'firstName'     => $userData->fname,
						'lastName'  => $userData->lname,
						'email'       => $userData->email,
					]);
					
					redirect('AdminDashboard');
				}
			}
			else{

				$temp = array();
				$time='';
				if(!empty(get_cookie('count'))){
					$temp = explode("-",get_cookie('count'));
				}

				$countVal = !empty(get_cookie('count')) ? $temp[0] + 1 : 1;

				if($countVal == 5):
					$time = strtotime("+5 min");
				elseif($countVal == 10):
					$time = strtotime("+30 min");
				elseif($countVal == 15):
					$time = strtotime("+1 hour");
				elseif($countVal >= 20):
					$time = strtotime("+1 day");
				endif;
				
				$arr = array($countVal,$time);

				$countCookie = array(
					'name'   => 'count',
					'value'  => implode("-",$arr),
					'expire' => '86400',  // 1day
				);

				set_cookie($countCookie);

				$this->session->set_flashdata('errorLogin','Incorrect Email or Password');
				redirect('AdminLogin');
			}
		}
		else{
			$this->session->set_flashdata('errorLogin',validation_errors());
			redirect('AdminLogin');
		}
	}
	//get table data
	public function UserTableAjax(){
		$data1 = $this->User_model->getTableData();

		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['id'] = $listItem->userId;
			$row['fname'] = $listItem->fname;
			$row['lname'] = $listItem->lname;
			$row['email'] = $listItem->email;
			$row['userRole'] = $listItem->userRole;
			$row['encId'] = $this->safe_encode(urlencode($this->encryption->encrypt($listItem->userId)));
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	public function signout(){
		if(!empty(get_cookie('remember_me_token'))){
			delete_cookie('remember_me_token');
			$data = array(
				'remember_me_token' => NULL
			);
			$this->db->where('userId',$this->session->userdata('userId'))->update('users',$data); 
		}
		$array_items = array('isLogIn', 'userId', 'firstName', 'lastName', 'email','userRole');
		$this->session->unset_userdata($array_items);
		
		redirect('AdminLogin');
	}
	//functions
	function safe_encode($string){
		return str_replace("%", ":", $string);
	}
	function safe_decode($string){
		return str_replace(":", "%", $string);
	}
	public function randStrGen($mode = null, $len = null){
        $result = "";
        if($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for($i = 0; $i <= $len; $i++) {
                $randItem = array_rand($charArray);
                $result .="".$charArray[$randItem];
        }
        return $result;
    }
	public function email_check($email){
		$emailCount = $this->db->select('email')->where('email',$email)->get('users')->num_rows();
		if ($emailCount > 0) {
            $this->form_validation->set_message('email_check', 'The {field} field must contain a unique value.');
            return false;
        } else {
            return true;
        }
	}
	public function email_check_edit($email,$id){
		$emailCount = $this->db->select('email')->where('email',$email)->get('users')->num_rows();
		$data = $this->db->select('email,userId')->where('email',$email)->get('users')->row();
		if ($emailCount > 0 && $data->userId != $id) {
            $this->form_validation->set_message('email_check_edit', 'The {field} field must contain a unique value.');
            return false;
        } else {
            return true;
        }
	}
	public function checkFieldIfHasNum($text = ''){
		if( preg_match('~[0-9]+~', $text)){
			$this->form_validation->set_message('checkFieldIfHasNum', 'The {field} has numeric value!');
            return false;
		}
		else{
			return true;
		}
	}
	public function checkFieldIfHasSP($text = ''){
		if( preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $text)){
			$this->form_validation->set_message('checkFieldIfHasSP', 'The {field} has special character!');
            return false;
		}
		else{
			return true;
		}
	}
	public function checkPasswordStrength($password = ''){
		if( strlen($password) < 8 ){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must be 8 characters long');
            return false;
		}
		if( !preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 special character');
            return false;
		}
		if( !preg_match('/[A-Z]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 upper character');
            return false;
		}
		if( !preg_match('/[a-z]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 lower character');
            return false;
		}
		else{
			return true;
		}
	}
}
