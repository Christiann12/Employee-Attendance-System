<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeScan extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
        $this->load->helper('cookie');
        $this->load->model('Admin/Attendance_model');
        $this->load->model('Admin/Employee_model');
	}

	public function index($id="")
	{
        $data['empData'] = $this->Employee_model->getEmpData($id);
        $attendanceDetail = $this->Attendance_model->getTimeIn($data['empData']->empId);
        
        if(empty($attendanceDetail)){
            $this->load->view('Pages/General/InputInformationTimein.php',$data);
        }
        else{
            $this->load->view('Pages/General/InputInformationTimeout.php',$data);
        }
	}

    public function saveTimein(){
        $empData = $this->Employee_model->getEmpData($this->input->post("EmpId"));
        $this->form_validation->set_rules('EmpId', 'Name' ,'required');
        if (empty($_FILES['attachment']['name'])){
			$this->form_validation->set_rules('attachment', 'Attachment' ,'required');
		}

        //load config for upload library
		$config['upload_path']   = APPPATH.'assets/attachments/images/';
		$config['allowed_types'] = 'jpg|jpeg|jpe|png';
		$config['max_size']      = 0;
		$config['max_width']     = 0;
		$config['max_height']    = 0;
		$config['overwrite']     = false;

        // Helpers
		$this->load->helper('url');
		$this->load->library('upload', $config);
		$filename = "";
		
		$name = 'attachment';

        $timein = date("H:i" , strtotime($empData->timein));
		$timeout = date("H:i" , strtotime($empData->timeout));

		$currentTime = date("H:i");
            
		if ($this->isBetween($timein,$timeout,$currentTime)) 
		{
			$status_in = 'Late';
		} else {
			$status_in = 'On time';
		}
        if (strtolower(date('l')) == strtolower($empData->dayoff)) 
        {
            $row["dayoff"] = "Yes";
        } else {	
            
            $row["dayoff"] = "No";
        }

        $postData = array(
            "empId" => $empData->empId,
            "timeinf" => $currentTime,
            "datetimein" => date('Y-m-d'),
            "late" => $status_in,
            "timeinsched" => $empData->timein,
            "timeoutsched" => $empData->timeout,
        );
        
        if (strtolower(date('l')) == strtolower($empData->dayoff)) 
        {
            $postData["dayoff"] = "Yes";
        } else {	
            
            $postData["dayoff"] = "No";
        }
        if($this->form_validation->run() === true){
            if ( ! $this->upload->do_upload($name) ) {
                $this->session->set_flashdata('errorEmpDashboard',$this->upload->display_errors());
                redirect('EmployeeScan/'.$this->input->post("EmpId"));
            } 
            else{
                $upload =  $this->upload->data();
                $postData['pictureUrlTimein	'] = $upload['file_name'];
                if($this->Attendance_model->create($postData)){
					$this->session->set_flashdata('successEmpDashboard','Success!');
                    redirect('EmployeeDashboard');
				}
				else {
					$this->session->set_flashdata('errorEmpDashboard','Time in Failed');
                    redirect('EmployeeScan/'.$this->input->post("EmpId"));
				}
            }
        }
        else{
            $this->session->set_flashdata('errorEmpDashboard',validation_errors());
            redirect('EmployeeScan/'.$this->input->post("EmpId"));
        }
    }
    public function saveTimeOut(){
        $empData = $this->Employee_model->getEmpData($this->input->post("EmpId"));
        $attendanceDetail = $this->Attendance_model->getTimeIn($empData->empId);
        $this->form_validation->set_rules('EmpId', 'Name' ,'required');
        if (empty($_FILES['attachment']['name'])){
			$this->form_validation->set_rules('attachment', 'Attachment' ,'required');
		}

        //load config for upload library
		$config['upload_path']   = APPPATH.'assets/attachments/images/';
		$config['allowed_types'] = 'jpg|jpeg|jpe|png';
		$config['max_size']      = 0;
		$config['max_width']     = 0;
		$config['max_height']    = 0;
		$config['overwrite']     = false;

        // Helpers
		$this->load->helper('url');
		$this->load->library('upload', $config);
		$filename = "";
		
		$name = 'attachment';
            
        $postData = array(
            "empId" => $empData->empId,
            "datetimeout" => date('Y-m-d'),
            
        );
        if($attendanceDetail->timeoutf == 'EMPTY'){
            $postData['timeoutf'] = date("H:i");;
        }
        else{
            $postData['timeouts'] = date("H:i");;
        }
        if($this->form_validation->run() === true){
            if ( ! $this->upload->do_upload($name) ) {
                $this->session->set_flashdata('errorEmpDashboard',$this->upload->display_errors());
                redirect('EmployeeScan/'.$this->input->post("EmpId"));
            } 
            else{
                $upload =  $this->upload->data();
                $postData['pictureUrlTimeout'] = $upload['file_name'];
                if($this->Attendance_model->updateRecord($postData,$attendanceDetail->attendanceId)){
					$this->session->set_flashdata('successEmpDashboard','Success!');
                    redirect('EmployeeDashboard');
				}
				else {
					$this->session->set_flashdata('errorEmpDashboard','Time out Failed');
                    redirect('EmployeeScan/'.$this->input->post("EmpId"));
				}
            }
        }
        else{
            $this->session->set_flashdata('errorEmpDashboard',validation_errors());
            redirect('EmployeeScan/'.$this->input->post("EmpId"));
        }
    }  
    public function break($id=""){
        $data['empData'] = $this->Employee_model->getEmpData($id);
        $attendanceDetail = $this->Attendance_model->getTimeIn($data['empData']->empId);
        $currentTime = date("H:i");
        $postData = array();
        
        if($attendanceDetail->timeoutf == "EMPTY"){
            $postData['timeoutf'] = $currentTime;
        }
        else if($attendanceDetail->timeins == "EMPTY"){
            $postData['timeins'] = $currentTime;
        }

        if (!empty($postData)) {
            if($this->Attendance_model->updateRecord($postData,$attendanceDetail->attendanceId)){
                $this->session->set_flashdata('successEmpDashboard','Success Break');
                if (!empty($postData['timeoutf'] )) {
                    $this->session->set_flashdata('successEmpDashboard','Success Break Time Out');
                } else {
                    $this->session->set_flashdata('successEmpDashboard','Success Break Time In');
                }
                
                redirect('EmployeeDashboard');
            }
            else{
                $this->session->set_flashdata('successEmpDashboard','Failed');
                redirect('EmployeeDashboard');
            }
        } else {
            $this->session->set_flashdata('errorEmpDashboard','You can\'t take a break anymore');
            redirect('EmployeeDashboard');
        }
        
    }
    function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}
}
