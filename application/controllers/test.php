<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		date_default_timezone_set('Asia/Singapore');
        $this->load->library('user_agent');

		$timein = date("H:i" , strtotime("7:00"));
		$timeout = date("H:i" , strtotime("15:00"));

		$timeinniemp = date("H:i" , strtotime("6:50"));

		$currentTime = date("00:00");

		$test = "empty";

		if ($this->isBetween($timein,$timeout,$currentTime)){
			$test= 'Under Time: ' . gmdate("H:i:s", ( strtotime($currentTime) - strtotime($timeinniemp) ));
		}
		else{
			if($timeout == $currentTime || $this->isBetween($timeout,date("H:i" , strtotime($timeout."+15min")),$currentTime)){
				// $test= 'On Time';
				$test= "On Time: " . gmdate("H:i:s", ( strtotime($currentTime) - strtotime($timeinniemp) ));
			}
			else{
			
				$test= "Over Time: " . gmdate("H:i:s", ( strtotime($currentTime) - strtotime($timeinniemp) ));
			}
		}

		echo $test;
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
