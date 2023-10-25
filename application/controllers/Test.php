<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{

	public function index()
	{

		$this->load->model('compliance');

		$time_start = microtime(true); // Start counting
		$dataq1 = $this->compliance->get_compliance_by_area_q2([1, 2, 3, 4, 5], '2021-01-01', '2021-01-02');
		$time_end = microtime(true);
		$execution_timeq1 = ($time_end - $time_start);

		$time_start = microtime(true); // Start counting
		$dataq2 = $this->compliance->get_compliance_by_area_q2([1, 2, 3, 4, 5], '2021-01-01', '2021-01-02');
		$time_end = microtime(true);
		$execution_timeq2 = ($time_end - $time_start);
		echo "<b>with CTE<b><br/>";
		echo '<b>Total Execution Time:</b><br/> ' . $execution_timeq1 . ' seconds';


		echo "<br/><br/><b>without CTE<b><br/>";
		echo '<b>Total Execution Time:</b><br/> ' . $execution_timeq2 . ' seconds';
		echo "<pre>";
		var_dump($dataq1);
		echo "</pre>";
		echo "<pre>";
		var_dump($dataq2);
		echo "</pre>";


	}
}
