<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		date_default_timezone_set('Asia/Singapore');
        $this->load->library('user_agent');
		$checkTime = strtotime('08:18');
		echo 'Check Time : '.date('H:i:s', $checkTime);
		echo '<hr>';

		$loginTime = strtotime('8:00');
		$diff = ($checkTime - $loginTime);
		echo 'Login Time : '.date('H:i:s', $loginTime).'<br>';
		echo ($diff < 0)? 'Late!' : 'Right time!'; echo '<br>';
		echo 'Time diff in sec: '.$diff;

		echo '<hr>';

		// $loginTime = strtotime('09:00:59');
		// $diff = $checkTime - $loginTime;
		// echo 'Login Time : '.date('H:i:s', $loginTime).'<br>';
		// echo ($diff < 0)? 'Late!' : 'Right time!';

		// echo '<hr>';

		// $loginTime = strtotime('09:00:00');
		// $diff = $checkTime - $loginTime;
		// echo 'Login Time : '.date('H:i:s', $loginTime).'<br>';
		// echo ($diff < 0)? 'Late!' : 'Right time!';
		// echo '<hr>';
		$date = date('H:i');
		echo $date;
	}
}
