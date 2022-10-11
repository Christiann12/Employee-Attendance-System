<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->model('Admin/User_model');
	}

	public function index()
	{
        if(!empty(get_cookie('remember_me_token'))){
			$userData = $this->User_model->getCurrentUserCookie(get_cookie('remember_me_token'));
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
			$this->load->view('Pages/General/Login.php');			
		}
	}
	public function getCookie(){
		echo get_cookie('remember_me_token');
	}
}
