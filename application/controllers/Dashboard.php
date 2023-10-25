<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function index()
	{
		if ($this->input->get('view')) {
			$area_ids = $this->input->get('area_ids');
			$date_from = $this->input->get('date_from');
			$date_to = $this->input->get('date_to');
		}

		$this->load->model('compliance');
		$areas = $this->compliance->get_all_area();
		$this->load->view('dashboard', ['areas' => $areas]);
	}
}
