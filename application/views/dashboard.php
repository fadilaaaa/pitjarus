<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<!--     Fonts and icons     -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<!-- CSS Files -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<link id="pagestyle" href="<?= base_url('assets/css/argon-dashboard.css') ?>" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body class=" bg-gray-100">
	<div class="container" style="max-width:100vw;">
		<form action="<?= base_url() ?>" method="get">
			<div class="row">
				<div class="col-md-3 form-group">
					<label class="form-control-label" for="area">choose Area:</label>
					<select class="form-control" name="area_ids[]" id="area" multiple="multiple">
						<?php foreach ($areas as $key => $area) {
							$selected = "";
							if (in_array($area['area_id'], $selected_area_ids))
								$selected = "selected";
							echo "<option $selected value='$area[area_id]'>$area[area_name]</option>";
						} ?>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="example-date-input" class="form-control-label">Date From</label>
					<input value="<?= $date_from ?>" name="date_from" class="form-control" type="date"
						id="example-date-input">
				</div>
				<div class="form-group col-md-3">
					<label for="example-date-input" class="form-control-label">Date To</label>
					<input value="<?= $date_to ?>" name="date_to" class="form-control" type="date"
						id="example-date-input">
				</div>
				<div class="form-group col-md-3 ">
					<button class="btn btn-primary mt-4" name="view" type="submit">View</button>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-md-12 mt-4">
				<div class="card">
					<div class="card-header pb-0">
						<h2 class="mb-0">Complience</h2>
					</div>
					<div class="card-body pt-4 p-3 row">
						<div class="col-md-6">
							<div class="chart">
								<canvas id="bar-chart" class="chart-canvas" height="200px"></canvas>
							</div>
						</div>
						<div class="col-md-6">
							<table id="complienceTable">
								<thead>
									<tr>

										<th>Brand</th>
										<?php foreach ($complience[0] as $key => $row) {
											if ($key == "BrandName")
												continue;
											echo "<th>" . $areas[$key - 1]['area_name'] . "</th>";
										} ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($complience as $key => $row) {
										echo "<tr>";
										foreach ($row as $key => $value) {
											if ($key == "BrandName")
												echo "<td>" . $value . "</td>";
											else
												echo "<td>" . $value . "%</td>";
										}
										echo "</tr>";
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
		integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
	<script src="<?= base_url('assets/js/plugins/chartjs.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/plugins/Chart.extension.js') ?>"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
		$(document).ready(function () {
			$('#area').select2();
			$('#complienceTable').DataTable({
				paging: false,
				searching: false,
			});
			const data = {
				labels: [
					<?php foreach ($complience[0] as $key => $row) {
						if ($key == "BrandName")
							continue;
						echo '"' . $areas[$key - 1]['area_name'] . '",';
					} ?>
				],
				datasets: [
					<?php foreach ($complience as $key => $row) {
						$datas = [];
						$datasets = "";
						foreach ($row as $key => $value) {
							if ($key == "BrandName")
								continue;
							$datas[] = $value / 100;
						}
						$randomColor = substr(md5(rand()), 0, 6);
						echo ("{
						label: '$row[BrandName]',
						backgroundColor: '#$randomColor',
						data: [" . implode(',', $datas) . "]
						},");
					}

					?>
				]
			}
			console.log(data);
			new Chart('bar-chart', {
				type: 'bar',
				data: data,
				options: {
					scales: {
						y: {
							ticks: {
								format: {
									style: 'percent'
								}
							}
						}
					}
				}
			})
		});
	</script>
</body>

</html>