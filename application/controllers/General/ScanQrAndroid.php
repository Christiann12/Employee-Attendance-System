<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScanQrAndroid extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index()
	{
        if($this->session->userdata('isLogInEmployee') === true){
			$this->load->view('Pages/General/QrScanAndroid.php');
		}
		else{
			redirect('');
		}
	}
	
}
