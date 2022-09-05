<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagement extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/User_model');
	}
	//load page
	public function index()
	{
        
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "UserManagement";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('AdminLogin');
		}
	}
	public function editUser($id = '')
	{
        $data['userData'] = $this->User_model->getUser($id);
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "UserEditPage";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('AdminLogin');
		}
	}
	//save user
	public function saveUser(){
		$this->form_validation->set_rules('userFirstname', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP');
		$this->form_validation->set_rules('userLastname', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP');
		$this->form_validation->set_rules('userEmail', 'Email' ,'required|callback_email_check');
		$this->form_validation->set_rules('userPassword', 'Password' ,'required');
		$this->form_validation->set_rules('userRePassword', 'Confirm Password' ,'required|matches[userPassword]');
		
		$postData = array(
            "userId" => "USR-".$this->randStrGen(2,7),
            "fname" => ucfirst(strtolower($this->input->post("userFirstname"))),
            "lname" => ucfirst(strtolower($this->input->post("userLastname"))),
            "password" => md5($this->input->post("userPassword")),
            "email" => strtolower($this->input->post("userEmail")),
            "userRole" => 'Admin',
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
		// echo 'test'; 
		$this->form_validation->set_rules('userFirstname', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP');
		$this->form_validation->set_rules('userLastname', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP');
		$this->form_validation->set_rules('userEmail', 'Email' ,'required|callback_email_check_edit['.$this->input->post('userIdField').']');
		
		if($this->input->post("userPassword") != ''){
			$this->form_validation->set_rules('userPassword', 'Password' ,'required');
			$this->form_validation->set_rules('userRePassword', 'Confirm Password' ,'required|matches[userPassword]');
		}
		
		$postData = array(
            "userId" => $this->input->post('userIdField'),
            "fname" => ucfirst(strtolower($this->input->post("userFirstname"))),
            "lname" => ucfirst(strtolower($this->input->post("userLastname"))),
            // "password" => md5($this->input->post("userPassword")),
            "email" => strtolower($this->input->post("userEmail")),
            "userRole" => 'Admin',
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
            redirect('EditUser/'.$this->input->post("userIdField"));
        }
        else{
			$this->session->set_flashdata('failEditUser',validation_errors());
            redirect('EditUser/'.$this->input->post("userIdField"));
        }
	}
	//delete user
	public function deleteUser($id = ''){
	
		if($this->User_model->deleteUser($id)){
			$this->session->set_flashdata('successAddUser','Delete Success');
		}
		else{
			$this->session->set_flashdata('failAddUser','Delete Failed');
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
		
		// $productss = $this->inventory_model->productList();
		$json_data['data'] = $data1;
		echo json_encode($json_data);
	}
	public function signout(){
		$array_items = array('isLogIn', 'userId', 'firstName', 'lastName', 'email','userRole');
		$this->session->unset_userdata($array_items);
		redirect('AdminLogin');
	}
	//functions
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
}
