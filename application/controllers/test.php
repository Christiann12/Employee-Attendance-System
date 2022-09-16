<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		date_default_timezone_set('Asia/Singapore');
        $this->load->library('user_agent');

		$currenttime = strtotime('2022-9-16 23:59');
		$schedTimeout = strtotime('2022-9-16 17:30');

		// $timein = date("H:i" , strtotime('22:00'));
		// $timeout = date("H:i" , strtotime('5:00'));

		// $currentTime = date("H:i" , strtotime('23:30'));
            

		// if ($this->isBetween($timein,$timeout,$currentTime)) 
		// {
		// 	$status_in = 'Late';
		// } else {
		// 	$status_in = 'On time';
		// }

		// echo $status_in;

		echo (( $currenttime - $schedTimeout ) / 60)/15;
	}
	function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}

	// time in 0:00
	// time out 7:00

	// current time is inbetween time in timeout? Early Out

	// If not?

	// check if scheduleTimeout < currenttime ? // do normal

	// else scheduleTimeout > currenttime ? add day to current time
}
