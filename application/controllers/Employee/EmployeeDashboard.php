<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/Attendance_model');
	}

	public function index()
	{
		$data['attendanceDetail'] = $this->Employee_model->getEmp($this->session->userdata('employeeId'));
		$data['circularData'] = $this->circularProgressBar();


        if($this->session->userdata('isLogInEmployee') === true){
			$data['page'] = "EmployeeDashboard";
			$this->load->view('HeaderAndFooter/HeaderEmployee.php');
			$this->load->view('Pages/Employee/WrapperEmployee.php',$data);
			$this->load->view('HeaderAndFooter/FooterEmployee.php');
		}
		else{
			redirect('');
		}
	}
	public function generateTable(){
		$data1 = $this->Attendance_model->getTableDataByEmployee();
		
		// $productss = $this->inventory_model->productList();
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->timein;
			$row['data2'] = $listItem->timeout;
			$row['data3'] = $listItem->datetimein;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	public function circularProgressBar(){
		$attendanceDetail = $this->Employee_model->getEmp($this->session->userdata('employeeId'));

		$timein = date("H:i" , strtotime($attendanceDetail->timein));
		$timeout = date("H:i" , strtotime($attendanceDetail->timeout));

		$currentTime = date("H:i");
		$data = [];
		if($this->isBetween($timein,$timeout,$currentTime)){
			if($timein < $timeout){
				$timein = strtotime($attendanceDetail->timein);
					$timeout = strtotime($attendanceDetail->timeout);
					$diff  = ($timeout - $timein);

					$time1 = strtotime(date('H:i'));
					$time2 = strtotime($attendanceDetail->timeout);
					$diff2 = ($time2 - $time1);
					
					$temp1 = $diff / 3600;
					$temp2 = $diff2 / 3600;
					$newhour = $temp1 - $temp2;
					$percent = round(($newhour / $temp1)*100,2);
			}
			else{
				$timein = strtotime('today '.$attendanceDetail->timein);
				$timeout = strtotime('tomorrow '.$attendanceDetail->timeout);
				$diff  = ($timeout - $timein);

				
					$currentTime = strtotime(date('H:i'));
					$timeout2 = strtotime($attendanceDetail->timeout);
					if($currentTime > $timeout2){
						$timeout2 = strtotime('tomorrow '.$attendanceDetail->timeout);
						$diff2 = ($timeout2 - $currentTime);
					}
					else{
						$diff2 = ($timeout2 - $currentTime);
					}
				
				$temp1 = $diff / 3600;
				$temp2 = $diff2 / 3600;
				$newhour = $temp1 - $temp2;
				$percent = round(($newhour / $temp1)*100,2);
			}
			$data['message'] = $temp2 > 1? floor($temp2).' hours left' : floor($temp2).' hour left';
			$data['percent'] = $percent;
		}
		else{
			$data['message'] = 'Not your schedule yet!';
			$data['percent'] = 0;
		}
		return $data;
	}
	function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}
}
