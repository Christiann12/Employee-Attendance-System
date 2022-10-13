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
		//Login Locking Algorithm
		$data = array();

		if(!empty(get_cookie('count'))){
			$temp = array();

			$temp = explode("-",get_cookie('count'));
	
			if(!empty($temp[1])){
				if( $temp[1] - time() > 0){
					// $data['timeremaining'] =  $temp[0] > 20 ? gmdate("H:i:s", ( $temp[1] - time() )) : gmdate("i:s", ( $temp[1] - time() ));
					$data['timeremaining'] = date("F j, Y H:i:s",$temp[1]);
					// $data['timeremaining'] = $temp[1];
					$data['status'] =  true;
				}
			}
		}
		

        if(!empty(get_cookie('remember_me_token'))){
			$userData = $this->User_model->getCurrentUserCookie(get_cookie('remember_me_token'));
			if(!empty($userData)){
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
				$this->load->view('Pages/General/Login.php',$data);			
			}
		}
		else{
			$this->load->view('Pages/General/Login.php',$data);			
		}
	}
	public function getCookie(){
		echo get_cookie('remember_me_token');
	}
}
