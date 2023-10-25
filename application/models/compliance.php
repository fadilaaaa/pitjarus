<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compliance extends CI_Model
{
	public function get_compliance_by_area(array $area_ids)
	{
		$area_ids_string = implode(',', $area_ids);
		$area_selected_CASE = '';
		foreach ($area_ids as $area_id) {
			$area_selected_CASE .= "CAST(
			MAX(CASE WHEN area_id = $area_id THEN TotalCompliance 
			/ TotalRow ELSE NULL END))*100 AS SIGNED AS Area$area_id";
			if ($area_id != end($area_ids)) {
				$area_selected_CASE .= ',';
			}
		}
		$query = "
		WITH ComplianceData AS (
    SELECT
        pb.brand_name AS BrandName,
        s.area_id,
        SUM(rp.compliance) AS TotalCompliance,
        COUNT(rp.product_id) AS TotalRow
    FROM
        report_product rp
    JOIN
        product p ON rp.product_id = p.product_id
    JOIN
        product_brand pb ON p.brand_id = pb.brand_id
    JOIN
        store s ON rp.store_id = s.store_id
    WHERE
        s.area_id IN (" . $area_ids_string . ")
    GROUP BY
        pb.brand_name, s.area_id
)
SELECT
    BrandName,
	" . $area_selected_CASE . "
FROM ComplianceData
GROUP BY BrandName;	
		";
		$data = $this->db->query($query);
		return $data->result_array();
	}

	public function get_compliance_by_area_q2(array $area_ids)
	{
		$area_ids_string = implode(',', $area_ids);
		$area_selected_CASE = '';
		foreach ($area_ids as $area_id) {

			$area_selected_CASE .= "
			CAST(
				SUM(CASE WHEN s.area_id = $area_id THEN rp.compliance ELSE 0 END) 
				/ SUM(CASE WHEN s.area_id = $area_id THEN 1 ELSE 0 END)*100
				AS SIGNED) AS Area$area_id
			";
			if ($area_id != end($area_ids)) {
				$area_selected_CASE .= ',';
			}
		}
		$query = "
		SELECT pb.brand_name AS BrandName,
		$area_selected_CASE
		FROM report_product rp
		JOIN product p ON rp.product_id = p.product_id
		JOIN product_brand pb ON p.brand_id = pb.brand_id
		JOIN store s ON rp.store_id = s.store_id
		WHERE s.area_id IN ($area_ids_string)
		GROUP BY pb.brand_name
	";
		$data = $this->db->query($query);
		return $data->result_array();
	}
}
?>