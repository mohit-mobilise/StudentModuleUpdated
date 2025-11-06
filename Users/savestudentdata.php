<?php
session_start();
require '../connection.php';
require '../AppConf.php';

$AdmissionId = $_SESSION['userid'];

if (empty($AdmissionId)) {
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Please login from your respected ERP");
	exit();
}

$filledalready = $_POST['already_filled'];
$sadmission = $_POST['sadmission'];

$hcp_fields = '';
$hcpnames = '';
$hcp_data = '';

if ($_POST['stu_class'] == '6to8') {

	for ($i = 1; $i <= 112; $i++) {
		$cnt = 1;
		if ($i == 97 || $i == 98 || $i == 99 || $i == 100) {
			$hcpname = 'hcp' . $i . '_' . $cnt;

			if (empty($_POST[$hcpname])) {
				$cnt = 2;
				$hcpname = 'hcp' . $i . '_' . $cnt;
				if (empty($_POST[$hcpname])) {
					if ($filledalready != 'Yes') {
						continue;
					}
				}
			}

// 			for ($j = 1; $j <= 2; $j++) {
			for ($j = 1; $j <= 1; $j++) {    
				$hcpname = 'hcp' . $i . '_' . $j;
				if (empty($_POST[$hcpname])) {
					if ($filledalready != 'Yes') {
						continue;
					}
				}
				$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

				if ($filledalready == 'Yes') {
					$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
				} else {
					$hcp_fields .= "'" . $hcp_field . "',";
					$hcpnames .= "`" . $hcpname . "`,";
				}
			}
		} else {
			$hcpname = 'hcp' . $i;
			if (empty($_POST[$hcpname])) {
				if ($filledalready != 'Yes') {
					continue;
				}
			}

			if ($hcpname == 'hcp111') {
				if (empty($_POST['hcp110'])) {
					continue;
				}
			}

			$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

			if ($filledalready == 'Yes') {
				$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
			} else {
				$hcp_fields .= "'" . $hcp_field . "',";
				$hcpnames .= "`" . $hcpname . "`,";
			}
		}
	}

	$hcp_fields = rtrim($hcp_fields, ',');
	$hcpnames = rtrim($hcpnames, ',');
	$hcp_data = rtrim($hcp_data, ',');

	$check_validity = mysqli_query($Con, "select * from hcpreport_data where sadmission='$sadmission'");
	if (mysqli_num_rows($check_validity) > 0) {
		$insertSql = mysqli_query($Con, "update hcpreport_data set " . $hcp_data . " where sadmission='$sadmission'");
	} else {
		$insertSql = mysqli_query($Con, "insert into hcpreport_data (" . $hcpnames . ",`sadmission`) values(" . $hcp_fields . ",'$sadmission')");
	}

	if ($insertSql) {
		echo json_encode(['status' => 'success', 'info' => 'Your data has been saved', 'alreadyfilled' => 'Yes']);
	} else {
		if (empty($hcpnames) && $filledalready != 'Yes') {
			echo json_encode(['status' => 'failed', 'info' => 'Fill atleast one field']);
		} else {
			echo json_encode(['status' => 'failed', 'info' => 'Something went wrong']);
		}
	}
} else if ($_POST['stu_class'] == '3to5') {

	for ($i = 1; $i <= 113; $i++) {
		$cnt = 1;
		if ($i == 97 || $i == 98 || $i == 99 || $i == 100) {
			$hcpname = 'hcp' . $i . '_' . $cnt;

			if (empty($_POST[$hcpname])) {
				$cnt = 2;
				$hcpname = 'hcp' . $i . '_' . $cnt;
				if (empty($_POST[$hcpname])) {
					if ($filledalready != 'Yes') {
						continue;
					}
				}
			}

// 			for ($j = 1; $j <= 2; $j++) {
			for ($j = 1; $j <= 1; $j++) {    
				$hcpname = 'hcp' . $i . '_' . $j;
				if (empty($_POST[$hcpname])) {
					if ($filledalready != 'Yes') {
						continue;
					}
				}
				$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

				if ($filledalready == 'Yes') {
					$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
				} else {
					$hcp_fields .= "'" . $hcp_field . "',";
					$hcpnames .= "`" . $hcpname . "`,";
				}
			}

			if ($i == 100) {
				for ($k = 1; $k <= 2; $k++) {

					$hcpname = 'hcp' . $i . '_1' . '_' . $k;
					if (empty($_POST[$hcpname])) {
						if ($filledalready != 'Yes') {
							continue;
						}
					}

					$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

					if ($filledalready == 'Yes') {
						$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
					} else {
						$hcp_fields .= "'" . $hcp_field . "',";
						$hcpnames .= "`" . $hcpname . "`,";
					}

					$hcpname = 'hcp' . $i . '_2' . '_' . $k;
					if (empty($_POST[$hcpname])) {
						if ($filledalready != 'Yes') {
							continue;
						}
					}

					$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

					if ($filledalready == 'Yes') {
						$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
					} else {
						$hcp_fields .= "'" . $hcp_field . "',";
						$hcpnames .= "`" . $hcpname . "`,";
					}
				}
			}
		} else {
			$hcpname = 'hcp' . $i;
			if (empty($_POST[$hcpname])) {
				if ($filledalready != 'Yes') {
					continue;
				}
			}

			if ($hcpname == 'hcp111') {
				if (empty($_POST['hcp110'])) {
					continue;
				}
			}

			$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

			if ($filledalready == 'Yes') {
				$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
			} else {
				$hcp_fields .= "'" . $hcp_field . "',";
				$hcpnames .= "`" . $hcpname . "`,";
			}
		}
	}

	$hcp_fields = rtrim($hcp_fields, ',');
	$hcpnames = rtrim($hcpnames, ',');
	$hcp_data = rtrim($hcp_data, ',');

	$check_validity = mysqli_query($Con, "select * from hcpreport_data where sadmission='$sadmission'");
	if (mysqli_num_rows($check_validity) > 0) {
		$insertSql = mysqli_query($Con, "update hcpreport_data set " . $hcp_data . " where sadmission='$sadmission'");
	} else {
		$insertSql = mysqli_query($Con, "insert into hcpreport_data (" . $hcpnames . ",`sadmission`) values(" . $hcp_fields . ",'$sadmission')");
	}

	if ($insertSql) {
		echo json_encode(['status' => 'success', 'info' => 'Your data has been saved', 'alreadyfilled' => 'Yes']);
	} else {
		if (empty($hcpnames) && $filledalready != 'Yes') {
			echo json_encode(['status' => 'failed', 'info' => 'Fill atleast one field']);
		} else {
			echo json_encode(['status' => 'failed', 'info' => 'Something went wrong']);
		}
	}
} else if ($_POST['parentfeedback'] == 'Yes') {
	for ($i = 1; $i <= 11; $i++) {
		$hcpname = 'pf' . $i;
		if (empty($_POST[$hcpname])) {
			if ($filledalready != 'Yes') {
				continue;
			}
		}

		$hcp_field = filter_var($_POST[$hcpname], FILTER_SANITIZE_STRING);

		if ($filledalready == 'Yes') {
			$hcp_data .= "`" . $hcpname . "`='" . $hcp_field . "',";
		} else {
			$hcp_fields .= "'" . $hcp_field . "',";
			$hcpnames .= "`" . $hcpname . "`,";
		}
	}

	$hcp_fields = rtrim($hcp_fields, ',');
	$hcpnames = rtrim($hcpnames, ',');
	$hcp_data = rtrim($hcp_data, ',');

	$check_validity = mysqli_query($Con, "select * from hcp_parentfeedback where sadmission='$sadmission'");
	if (mysqli_num_rows($check_validity) > 0) {
		$insertSql = mysqli_query($Con, "update hcp_parentfeedback set " . $hcp_data . " where sadmission='$sadmission'");
	} else {
		$insertSql = mysqli_query($Con, "insert into hcp_parentfeedback (" . $hcpnames . ",`sadmission`) values(" . $hcp_fields . ",'$sadmission')");
	}

	if ($insertSql) {
		echo json_encode(['status' => 'success', 'info' => 'Your data has been saved', 'alreadyfilled' => 'Yes']);
	} else {
		if (empty($hcpnames) && $filledalready != 'Yes') {
			echo json_encode(['status' => 'failed', 'info' => 'Fill atleast one field']);
		} else {
			echo json_encode(['status' => 'failed', 'info' => 'Something went wrong']);
		}
	}
}
