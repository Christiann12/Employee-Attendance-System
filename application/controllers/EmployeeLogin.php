<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeLogin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
	}

	public function index()
	{
		//Login Locking Algorithm
		$data = array();

		if(!empty(get_cookie('count_employee'))){
			$temp = array();

			$temp = explode("-",get_cookie('count_employee'));
	
			if(!empty($temp[1])){
				if( $temp[1] - time() > 0){
					// $data['timeremaining'] =  $temp[0] > 20 ? gmdate("H:i:s", ( $temp[1] - time() )) : gmdate("i:s", ( $temp[1] - time() ));
					$data['timeremaining'] = date("F j, Y H:i:s",$temp[1]);
					$data['status'] =  true;
				}
			}
		}
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
				redirect('EmployeeDashboard');
			} else {
				$this->load->view('Pages/General/EmployeeLogin.php', $data);
			}
		}
		else{
			$this->load->view('Pages/General/EmployeeLogin.php', $data);
		}
	}

	public function checkUser(){
		$this->form_validation->set_rules('empId', 'Employee ID' ,'required');
		$this->form_validation->set_rules('passwordLogin', 'Password' ,'required');

		$postData = array(
			'password' => md5($this->input->post('passwordLogin')),
            'empId' => $this->input->post('empId'),
		);

		if($this->form_validation->run() === true){
			$userData = $this->Employee_model->checkCredentialsEmployee($postData);
			if(!empty($userData)){

				!empty(get_cookie('count_employee')) ? delete_cookie('count_employee'):null;

				$this->session->set_flashdata('successLoginEmployee','Login Successful');

				if($this->input->post('rememberme')){
					$token = $this->randStrGen(1,15);

					$cookie = array(
						'name'   => 'remember_me_token_employee',
						'value'  => $token,
						'expire' => '2419200',  // four weeks
					);

					set_cookie($cookie);
					$data = array(
						'remember_me_token_employee' => $token
					);
					$this->db->where('empId',$userData->empId)->update('employee',$data); 
				}
		
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
				
				redirect('EmployeeDashboard');
			}
			else{

				$temp = array();
				$time = '';
				if(!empty(get_cookie('count_employee'))){
					$temp = explode("-",get_cookie('count_employee'));
				}

				$countVal = !empty(get_cookie('count_employee')) ? $temp[0] + 1 : 1;

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
					'name'   => 'count_employee',
					'value'  => implode("-",$arr),
					'expire' => '86400',  // 1day
				);

				set_cookie($countCookie);

				$this->session->set_flashdata('errorLoginEmployee','Incorrect Employee ID or Password');
				redirect('');
			}
		}
		else{
			$this->session->set_flashdata('errorLoginEmployee',validation_errors());
			redirect('');
		}
	}

	public function signout(){
		if(!empty(get_cookie('remember_me_token_employee'))){
			delete_cookie('remember_me_token_employee');
			$data = array(
				'remember_me_token_employee' => NULL
			);
			$this->db->where('empId',$this->session->userdata('employeeId'))->update('employee',$data); 
		}
		$array_items = array('isLogInEmployee', 'employeeId', 'firstNameEmployee', 'lastNameEmployee','employeeTimein','employeeTimeout','employeeDayoff');
		$this->session->unset_userdata($array_items);
		redirect('');
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
}
