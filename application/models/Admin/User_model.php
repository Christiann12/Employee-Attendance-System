<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    private $table = "users";
    public function __construct() {
        parent::__construct();

        //create table if it doesn't exist
        if (!$this->db->table_exists($this->table) )
        {
            $this->load->dbforge();
            // define table fields
            $fields = array(
                'userId' => array(
                'type' => 'VARCHAR',
                'constraint' =>20,
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
                'constraint' =>50,
                ),
                'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
                ),
                'userRole' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
                ),
                'remember_me_token' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                )
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('userId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }
    // save user to database
    public function addUser($data = []){
        return $this->db->insert($this->table,$data);
    }
    //update user
    public function editUser($data = []){
        return $this->db->where('userId',$data['userId'])->update($this->table,$data); 
    }
    //delete user
    public function deleteUser($id = ''){
        $this->db->where('userId',$id)->delete($this->table);
		if ($this->db->affected_rows()) {
			return true;
		} 
        else {
			return false;
		}
    }
    // get account for login
    public function checkCredentials($data = []){
        return $this->db->select("*")
			->from($this->table)
			->where('email',$data['email'])
			->where('password',$data['password'])
			->get()
			->row();
    }
    public function getCurrentUserCookie($token = ''){
        return $this->db->select("*")
			->from($this->table)
			->where('remember_me_token',$token)
			->get()
			->row();
    }
    
    //get table data
    public function getTableData(){
         return $this->db->select("*")->from($this->table)->get()->result();
    }
    public function getUser($id = ''){
		return $this->db->select("*")->from($this->table)->where('userId',$id)->get()->row();
	}
}