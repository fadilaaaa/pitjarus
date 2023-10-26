<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('compliance');
	}
	public function index()
	{
		$selected_area_ids = [];
		$areas = $this->compliance->get_all_area();
		if ($this->input->get('area_ids') && $this->input->get('date_from') && $this->input->get('date_to')) {
			$area_ids = $this->input->get('area_ids[]');
			$date_from = $this->input->get('date_from');
			$date_to = $this->input->get('date_to');
			$complience = $this->compliance->get_compliance_by_area_q2($area_ids, $date_from, $date_to);
			$selected_area_ids = $area_ids;
		} else {
			foreach ($areas as $key => $area) {
				$area_ids[] = $area['area_id'];
			}
			$complience = $this->compliance->get_compliance_by_area_q2($area_ids);
		}

		$this->load->view('dashboard', [
			'areas' => $areas,
			'complience' => $complience,
			'selected_area_ids' => $selected_area_ids,
			'date_from' => $date_from ? $date_from : '',
			'date_to' => $date_to ? $date_to : '',
		]);
	}
}
