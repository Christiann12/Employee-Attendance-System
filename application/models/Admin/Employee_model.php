<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {
    private $table = "employee";
    public function __construct() {
        parent::__construct();

        //create table if it doesn't exist
        if (!$this->db->table_exists($this->table) )
        {
            $this->load->dbforge();
            // define table fields
            $fields = array(
                'empId' => array(
                'type' => 'VARCHAR',
                'constraint' =>20,
                ),
                'secretId' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                ),
                'fname' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                ),
                'lname' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                ),
                'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => md5('PaxForcePassword1234')
                ),
                'dayoff' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'dayoff'
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
                )
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('empId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }
    // save user to database
    public function addEmployee($data = []){
        return $this->db->insert($this->table,$data);
    }
     // upload batch
     public function addEmployeeBatch($data = []){
        return $this->db->insert_batch($this->table, $data);
    }
    public function saveEdit($data = []){
        return $this->db->where('empId',$data['empId'])->update($this->table,$data); 
    }
    //get table data
    public function getTableData(){
        return $this->db->select("*")->from($this->table)->get()->result();
    }
    public function getEmp($id = ''){
        return $this->db->select("*")->from($this->table)->where('empId',$id)->get()->row();
    }
    // employee table
    public function getData(){
        return $this->db->select("*")->from($this->table)->get()->result();
    }
    //delete user
    public function deleteEmployee($id = ''){
        $this->db->where('empId',$id)->delete($this->table);
		if ($this->db->affected_rows()) {
			return true;
		} 
        else {
			return false;
		}
    }
    // import schedule
    public function editSchedule($data = []){
        return $this->db->update_batch($this->table, $data, 'empId'); 
    }
    // schudule table
    public function getTableFiltered(){
        return $this->db->select("*")->from($this->table)->where('timein !=','timein')->where('timeout !=','timeout')->where('dayoff !=','dayoff')->get()->result();
    }
    public function checkCredentialsEmployee($data = []){
        return $this->db->select("*")
			->from($this->table)
			->where('empId',$data['empId'])
			->where('password',$data['password'])
			->get()
			->row();
    }
}