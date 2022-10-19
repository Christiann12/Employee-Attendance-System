<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {
    private $table = "attendance";
    public function __construct() {
        parent::__construct();

        //create table if it doesn't exist
        if (!$this->db->table_exists($this->table) )
        {
            $this->load->dbforge();
            // define table fields
            $fields = array(
                'attendanceId' => array(
                'type' => 'INT',
                'constraint' =>20,
                'auto_increment' => TRUE
                ),
                'empId' => array(
                'type' => 'VARCHAR',
                'constraint' =>20,
                ),
                'ut_ot' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'unknown'
                ),
                'workhour' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'unknown'
                ),
                'late' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'unknown'
                ),
                'timein' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timein'
                ),
                'timeout' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timeout'
                ),
                'timeinsched' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timeinsched'
                ),
                'timeoutsched' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timeoutsched'
                ),
                'datetimein' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'datetimein'
                ),
                'datetimeout' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'datetimeout'
                ),
                'pictureUrl' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => 'empty'
                ),
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('attendanceId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }
    public function create($data = []){
        return $this->db->insert($this->table,$data);
    }
    public function getTableData($empId = '' , $date = ''){
        $this->db->select("attendance.*,employee.fname,employee.lname")->from($this->table)->join('employee', 'attendance.empId = employee.empId', 'left');
        
        if($empId != ''){
            $this->db->where('attendance.empId',$empId);
        }
        if($date != ''){
            $this->db->where('attendance.datetimein',date('Y-m-d',strtotime($date)));
        }
        return $this->db->get()->result();
    }
    public function getData($id = ''){
        return $this->db->select("*")->from($this->table)->where('empId',$id)->where('date',date("Y-m-d"))->where('timein !=','timein')->get()->row();
    }
    public function getTimeIn($id = ''){
        return $this->db->select("*")->from($this->table)->where('empId',$id)->where('timeout','timeout')->get()->row();
    }
    public function updateRecord($data = []){
        return $this->db->where('empId',$data['empId'])->where('timeout','timeout')->update($this->table,$data); 
    }
    public function getTableDataByEmployee(){
        return $this->db->select("*")->from($this->table)->where('empId',$this->session->userdata('employeeId'))->get()->result();
    }
    public function getNoPresent(){
        return $this->db->select('COUNT("empId")')->from($this->table)->where('datetimein',date('Y-m-d'))->group_by('empId')->get()->num_rows();
    }
    public function getLate(){
        return $this->db->select('COUNT("empId")')->from($this->table)->where('datetimein',date('Y-m-d'))->where('late','Late')->group_by('empId')->get()->num_rows();
    }
    public function getMonthCountLate($data = ''){
        return $this->db->select('*')->from($this->table)->where('MONTH(datetimein)',$data)->where('YEAR(datetimein)',date('Y'))->where('late','Late')->get()->num_rows();
    }
    public function getMonthCountOntime($data = ''){
        return $this->db->select('*')->from($this->table)->where('MONTH(datetimein)',$data)->where('YEAR(datetimein)',date('Y'))->where('late','On time')->get()->num_rows();
    }
    public function getTardinessMeasure($mode =''){
        return $this->db->select('*')->from($this->table)->where('empId',$this->session->userdata('employeeId'))->where('MONTH(datetimein)',date('m'))->where('YEAR(datetimein)',date('Y'))->where('late',$mode)->get()->num_rows();
    }
    // upload batch
    public function addBatch($data = []){
        $this->db->query('LOCK TABLE attendance WRITE');
        $result =  $this->db->insert_batch($this->table, $data);
        $this->db->query('UNLOCK TABLES');
        return $result;
    }
}