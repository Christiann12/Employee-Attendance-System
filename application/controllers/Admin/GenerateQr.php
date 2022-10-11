<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GenerateQr extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
	}

	public function index()
	{
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "GenerateQr";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
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
				redirect('GenerateQr');
			}
			else{
				redirect('AdminLogin');
			}
		}
	}

	public function generateQr(){
		$data['data'] = $this->Employee_model->getData();

		if($this->session->userdata('isLogIn') === true){
			$this->load->view('Pages/General/QrPage.php',$data);
		}
		else{
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
				$this->load->view('Pages/General/QrPage.php',$data);
			}
			else{
				redirect('AdminLogin');
			}
		}
	}
}
