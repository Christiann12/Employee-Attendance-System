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

            $timein = date("H:i" , strtotime($data['empData']->timein));
            $timeout = date("H:i" , strtotime($data['empData']->timeout));

            $currentTime = date("H:i");
            
            $postData = array(
                'empId' => $data['empData']->empId,
                "datetimeout" => date('Y-m-d'),
                'timeout' => $currentTime,
            );

            if(strtolower(date('l',strtotime($attendanceDetail->datetimein))) == strtolower($data['empData']->dayoff)){
                $postData['ut_ot'] = 'Overtime-DayOff';
                $postData['workhour'] = gmdate("H:i:s", ( strtotime($currentTime) - strtotime($attendanceDetail->timein) ));
            }
            else if ($this->isBetween($timein,$timeout,$currentTime)){
                $postData['ut_ot'] = 'Under Time';
                $postData['workhour'] = gmdate("H:i:s", ( strtotime($currentTime) - strtotime($attendanceDetail->timein) ));
            }
            else if ($timeout == $currentTime || $this->isBetween($timeout,date("H:i" , strtotime($timeout."+15min")),$currentTime)){
                $postData['ut_ot'] = 'On Time';
                $postData['workhour'] = gmdate("H:i:s", ( strtotime($currentTime) - strtotime($attendanceDetail->timein) ));
            }
            else{
                $postData['ut_ot'] = 'Over Time';
                $postData['workhour'] = gmdate("H:i:s", ( strtotime($currentTime) - strtotime($attendanceDetail->timein) )) <= strtotime("16 hour") ? 
                gmdate("H:i:s", ( strtotime($currentTime) - strtotime($attendanceDetail->timein) )) :
                gmdate("H:i:s", ( strtotime(date("16:00")) - strtotime(date("0:00")))) ;
            }

            if($this->Attendance_model->updateRecord($postData)){
                redirect('EmployeeDashboard');
            }
        
        
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

        $postData = array(
            "empId" => $empData->empId,
            "timein" => $currentTime,
            "timeinsched" => $empData->timein,
            "timeoutsched" => $empData->timeout,
            "datetimein" => date('Y-m-d'),
            "late" => $status_in,
        );

        if($this->form_validation->run() === true){
            if ( ! $this->upload->do_upload($name) ) {
                $this->session->set_flashdata('failInput',$this->upload->display_errors());
                redirect('EmployeeScan/'.$this->input->post("EmpId"));
            } 
            else{
                $upload =  $this->upload->data();
                $postData['pictureUrl'] = $upload['file_name'];
                if($this->Attendance_model->create($postData)){
					$this->session->set_flashdata('successInput','Success!');
                    redirect('EmployeeDashboard');
				}
				else {
					$this->session->set_flashdata('failInput','Time in Failed');
                    redirect('EmployeeScan/'.$this->input->post("EmpId"));
				}
            }
        }
        else{
            $this->session->set_flashdata('failInput',validation_errors());
            redirect('EmployeeScan/'.$this->input->post("EmpId"));
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
