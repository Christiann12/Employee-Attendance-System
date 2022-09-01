<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
        $this->load->library('user_agent');
		echo $this->input->ip_address();
	}
}
