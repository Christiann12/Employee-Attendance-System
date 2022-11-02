<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScanQrAndroid extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
	}

	public function index()
	{
        if($this->session->userdata('isLogInEmployee') === true){
			$userData = $this->db->get_where('employee', array('empId' => $this->session->userdata('employeeId')))->row();
			if (!empty($userData)) {
				$this->load->view('Pages/General/QrScanAndroid.php');
			} else {
				redirect('');
			}
			
		}
		else{
			if(!empty(get_cookie('remember_me_token_employee'))){
				$userData = $this->Employee_model->getCurrentUserCookie(get_cookie('remember_me_token_employee'));
				if (!empty($userData)) {
					$this->session->set_userdata([
						'isLogInEmployee'     => true,
						'employeeId'     => $userData->empId,
						'secretIdEmployee'     => $userData->secretId,
						'firstNameEmployee'     => $userData->fname,
						'lastNameEmployee'  => $userData->lname,
						'employeeTimein'  => $userData->timein,
						'employeeTimeout'  => $userData->timeout,
						'employeeDayoff'  => $userData->dayoff,
					]);
					$this->load->view('Pages/General/QrScanAndroid.php');
				} else {
					redirect('');
				}
				
			}
			else{
				redirect('');
			}
		}
	}
	public function upload(){
		if(empty($_FILES['attachment']['name'])){
			$this->session->set_flashdata('uploadFail','Please Upload Your QR Code');
			redirect('General/ScanQrAndroid');
		}
		if(!str_contains(strtolower($_FILES['attachment']['type']), 'image')){
			$this->session->set_flashdata('uploadFail','Upload a correct file type');
			redirect('General/ScanQrAndroid');
		}

		$test = APPPATH.'assets/attachments/images/temp'.$this->session->userdata('employeeId').'.png';
		file_put_contents($test, file_get_contents($_FILES['attachment']['tmp_name']));
		$file_name='temp'.$this->session->userdata('employeeId').'.png';
		$file = base_url('application/assets/attachments/images/temp'.$this->session->userdata('employeeId').'.png');

		$url = 'http://zxing.org/w/decode?u='.urlencode($file);
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		$html = curl_exec($handle);
		libxml_use_internal_errors(true); // Prevent HTML errors from displaying
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$link = $doc->getElementsByTagName('pre')->item(0);
		// echo 'Link text: ' . $link->nodeValue;

		if(!empty($link)){
			if($link->nodeValue != $this->session->userdata('secretIdEmployee')){
				$this->session->set_flashdata('uploadFail','This is not your QR Code');
				unlink(APPPATH.'assets/attachments/images/'.$file_name);
				redirect('General/ScanQrAndroid');
			}
			else{
				unlink(APPPATH.'assets/attachments/images/'.$file_name);
				redirect('EmployeeScan/'.$link->nodeValue);
			}
		}
		else{
			$this->session->set_flashdata('uploadFail','The image is not a QR Code');
			unlink(APPPATH.'assets/attachments/images/'.$file_name);
			redirect('General/ScanQrAndroid');
		}
	}
}
