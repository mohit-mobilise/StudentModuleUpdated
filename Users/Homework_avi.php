<?php
// Include necessary files and start the session
require '../connection.php';
require 'commonApiFile.php';
require '../AppConf.php';
session_start();

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

// Retrieve session variables with null coalescing to prevent PHP 8.2 warnings
$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$currentdate = date("Y-m-d");

// Current date variables
$date = htmlspecialchars(date('Y-m-d'));
$currentDateDMY = date('d M Y', strtotime($date));

// If StudentClass is not set in session, retrieve from request
if (empty($StudentClass)) {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
}

// Fetch distinct classes from class_master
$classes = [];
$ssqlClass = "SELECT DISTINCT `class` FROM `class_master`";
$resultClass = mysqli_query($Con, $ssqlClass);
if ($resultClass) {
    while ($row = mysqli_fetch_assoc($resultClass)) {
        $classes[] = $row['class'];
    }
    mysqli_free_result($resultClass);
} else {
    error_log("Class Query Error: " . mysqli_error($Con));
}

// -------------------------------------------------------------------------
// 1. FETCH EXAMS (from student_datesheet) FOR THE "SCHEDULES -> EXAMS" BLOCK
// -------------------------------------------------------------------------
$exams = array();
try {
    $ssqlExams = "SELECT 
                     `examtype`,
                     `notice`,
                     `status`,
                     DATE_FORMAT(`NoticeDate`,'%d-%m-%Y') AS `NoticeDateDMY`,
                     `NoticeDate` AS `NoticeDateRaw`,
                     DATE_FORMAT(`NoticeEndDate`,'%d-%m-%Y') AS `NoticeEndDateDMY`,
                     `NoticeEndDate` AS `NoticeEndDateRaw`,
                     `noticefilename`
                  FROM `student_datesheet`
                  WHERE `status`='Active'
                    AND `sclass`='$StudentClass'
                  ORDER BY `NoticeDate` ASC
                  LIMIT 3";
    $resExams = mysqli_query($Con, $ssqlExams);
    while($row = mysqli_fetch_assoc($resExams)) {
        $exams[] = $row;
    }
} catch(Exception $e) {
    // handle error if needed
}

// Handle assignment submissions (for assignments)
if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"] == "yes") {
    $ssql = "SELECT `subject`, `class`, `remark`, 
                     DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                     DATE_FORMAT(`assignmentcompletiondate`, '%d-%m-%Y') AS `assignmentcompletiondate`, 
                     `assignmentURL` 
              FROM `assignment` 
              WHERE `status` = 'Active'";
    if (!empty($_REQUEST["date_from"])) {
        // Use prepared statement to prevent SQL injection
        $date_from = validate_input($_REQUEST["date_from"] ?? '', 'string', 20);
        $date_to = validate_input($_REQUEST["date_to"] ?? '', 'string', 20);
        $ssql .= " AND `assignmentdate` >= ? 
                   AND `assignmentdate` <= ? 
                   AND `class` = ? 
                   ORDER BY `datetime` DESC";
        
        $stmt = mysqli_prepare($Con, $ssql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $date_from, $date_to, $StudentClass);
            mysqli_stmt_execute($stmt);
            $reslt = mysqli_stmt_get_result($stmt);
        } else {
            error_log("Assignment query preparation failed: " . mysqli_error($Con));
            $reslt = false;
        }
    } else {
        $ssql .= " ORDER BY `datetime` DESC";
        $reslt = mysqli_query($Con, $ssql);
    }
} else {
    $ssql1 = "SELECT `subject`, `class`, `remark`, 
                      DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                      DATE_FORMAT(`assignmentcompletiondate`, '%d-%m-%Y') AS `assignmentcompletiondate`, 
                      `assignmentURL` 
               FROM `assignment` 
               WHERE `class` = '$StudentClass' 
                 AND `status` = 'Active' 
               ORDER BY `datetime` DESC";
    $reslt1 = mysqli_query($Con, $ssql1);
}

// Fetch current financial year
$rsCFY = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status` = 'Active'");
$rowCurrentFy = mysqli_fetch_row($rsCFY);
$CurrentFY = $rowCurrentFy[0];

// Fetch fee history for the student
$sqlfeehistory = mysqli_query($Con, "
    SELECT `receipt_no`, `cr_amnt`, 
           DATE_FORMAT(`datetime`, '%d-%m-%Y') AS `date`, 
           `cheque_status`, `dr_amnt`, `ChequeBounceRemark` 
    FROM `fees` 
    WHERE `sadmission` = '$StudentId' 
      AND `status` = '1' 
      AND `FinancialYear` = '$CurrentFY' 
      AND `cheque_status` IN ('Clear', 'Bounce', 'Online')
");

// Attendance Data Fetching (example data)
$daily_attendance = [
    'Mon' => 'Present',
    'Tue' => 'Absent',
    'Wed' => 'Halfday',
    'Thu' => 'Present',
    'Fri' => 'Present',
    'Sat' => 'Absent',
    'Sun' => 'Present'
];

$present_count = $absent_count = $halfday_count = 0;
foreach ($daily_attendance as $status) {
    switch (strtolower($status)) {
        case 'present': $present_count++; break;
        case 'absent': $absent_count++; break;
        case 'halfday': $halfday_count++; break;
    }
}

$display_start_date = date('d M Y', strtotime('-7 days'));
$display_end_date = date('d M Y');

$chart_data = [];
foreach ($daily_attendance as $day => $status) {
    switch (strtolower($status)) {
        case 'present': $value = 1; break;
        case 'halfday': $value = 0.5; break;
        case 'absent': $value = 0; break;
        default: $value = null;
    }
    $chart_data[] = ['day' => $day, 'value' => $value];
}
$chart_data_json = json_encode($chart_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
	<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="new-style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dps-users-style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/open-sans.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dps-landing-page-style.css">
	<style>
	    /* Custom styles – ensure your dynamic color classes are defined */
	    .subject-box, .date-box { padding: 10px; color: #fff; border-radius: 4px; font-weight: bold; text-align: center; }
	</style>
</head>
<body>

<?php include 'Header/header_new.php'; ?>

<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php'; ?>
    <main class="page-content" style="margin-top:50px;">
        <!------------------ Start Landing Page --------------------->
        <div class="main-content p-3">
            <div class="header d-flex justify-content-between align-items-center">
                <h3 class="mb-4 text">Student Dashboard</h3>
            </div>

            <!-- Profile Section and Quick Links -->
            <div class="row">
                <div class="col-lg-4 mb-sm-3 mb-lg-0 dps-upper-card">
                    <div class="profile-card">
                        <img src="../../../Admin/StudentManagement/StudentPhotos/profile.jpg" alt="Student" />
                        <div>
                            <?php
                                $endpoint = "s-webservies/GetUserDetail.php";
                                $params = ['sadmission' => $StudentId];
                                $response = getCurlData($endpoint, $params);
                                if (isset($response['error'])) {
                                    echo "cURL Error: " . htmlspecialchars($response['error']) . "<br>";
                                } elseif (isset($response['items']) && !empty($response['items'])) {
                                    $student = $response['items'][0];
                            ?>
                                <h5><?php echo htmlspecialchars($student['Student_name']); ?></h5>
                                <p class="text-white">Class: <?php echo htmlspecialchars($student['Class']); ?><br />Roll No: <?php echo htmlspecialchars($student['Roll_no']); ?></p>
                            <?php } else { echo "<h5>No Student Data Found</h5>"; } ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-0 pb-3">
                            <div class="header mt-3 mb-2 px-3 border-bottom">
                                <h5 class="card-title mb-3 p-0">Quick Links</h5>
                            </div>
                            <div class="px-3 quick-links">
                                <div class="row">
                                    <div class="col-lg-6 col-6">
                                        <div class="my-3 quicklink-align" id="quickbuttons">
                                            <img class="quick1" src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> 
                                            <a class="querytext" href="StudentDateSheet.php">Exam Date Sheet</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="my-3 quicklink-align" id="quickbuttons1">
                                            <img class="quick2" src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> 
                                            <a class="querytext" href="Transport.php">Transport Details</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="my-3 quicklink-align" id="quickbuttons2">
                                            <img class="quick3" src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> 
                                            <a class="querytext" href="Holiday.php">Holiday List</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="my-3 quicklink-align" id="quickbuttons3">
                                            <img class="quick4" src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> 
                                            <a class="querytext" href="ReportCard_Portal.php">Report Card</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Homework and Notice Board -->
                <div class="col-lg-8">
                    <div class="row h-100">
                        <!-- Homework Section -->
                        <div class="col-lg-6 mb-sm-3 mb-lg-0 h-100">
                            <div class="card dps-upper-card h-100">
                                <div class="card-body p-0 pb-3">
                                    <div class="header mt-3 d-flex justify-content-between align-items-center mb-2 px-3 border-bottom">
                                        <h5 class="card-title mb-3 p-0">Home Works</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <p class="last-date-value mb-1"><?php echo htmlspecialchars($currentDateDMY); ?></p>&nbsp;&nbsp;
                                            <a href="Homework.php" target="_blank">
                                                <img src="assets/img/Frame 1597883679.svg" class="mb-3" alt="angle-right">
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Homework List -->
                                    <div class="homework-list ms-3 d-flex flex-column gap-2">
                                        <?php
                                            // Initialize a counter for Homework items
                                            $srno = 0;
                                            if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"] == "yes") {
                                                if (!empty($_REQUEST["date_from"]) && !empty($_REQUEST["date_to"])) {
                                                    // Use prepared statement to prevent SQL injection
                                                    $date_from = validate_input($_REQUEST["date_from"] ?? '', 'string', 20);
                                                    $date_to = validate_input($_REQUEST["date_to"] ?? '', 'string', 20);
                                                    $ssqlHomework = "SELECT `srno`, `subject`, `homework`, 
                                                                        DATE_FORMAT(`homeworkdate`, '%d-%m-%Y') AS `homeworkdate`, 
                                                                        `homeworkimage` 
                                                                     FROM `homework_master` 
                                                                     WHERE `status` = 'Active' 
                                                                       AND `sclass` = '$StudentClass' 
                                                                       AND `homeworkdate` >= '$date_from' 
                                                                       AND `homeworkdate` <= '$date_to' 
                                                                     ORDER BY `homeworkdate` DESC";
                                                    $resHomework = mysqli_query($Con, $ssqlHomework);
                                                    if ($resHomework) {
                                                        if (mysqli_num_rows($resHomework) > 0) {
                                                            while ($rowa = mysqli_fetch_assoc($resHomework)) {    
                                                                $srno++;
                                                                $subject = htmlspecialchars($rowa['subject']);
                                                                $homework = htmlspecialchars($rowa['homework']);
                                                                $homeworkdate = htmlspecialchars($rowa['homeworkdate']);
                                                                $homeworkimage = htmlspecialchars($rowa['homeworkimage']);
                                                                // Fetch corresponding classwork
                                                                $classwork_query = "SELECT DISTINCT `classwork` 
                                                                                    FROM `classwork_master` 
                                                                                    WHERE `classworkdate` >= '$date_from' 
                                                                                      AND `classworkdate` <= '$date_to' 
                                                                                      AND `sclass` = '$StudentClass' 
                                                                                      AND `subject` = '$subject'";
                                                                $sql_r = mysqli_query($Con, $classwork_query);
                                                                $classwork = '';
                                                                if ($sql_r && mysqli_num_rows($sql_r) > 0) {
                                                                    while ($row = mysqli_fetch_row($sql_r)) {
                                                                        $classwork .= htmlspecialchars($row[0]) . "<br>";
                                                                    }
                                                                }
                                                                $formatted_homeworkdate = date('d M Y', strtotime($homeworkdate));
                                                                
                                                                // Determine dynamic color class based on the counter
                                                                if (($srno - 1) == 0) { $colorClass = 'blue'; }
                                                                elseif (($srno - 1) == 1) { $colorClass = 'dps-light-green'; }
                                                                elseif (($srno - 1) == 2) { $colorClass = 'dps-light-red'; }
                                                                elseif (($srno - 1) == 3) { $colorClass = 'dps-light-blue'; }
                                                                elseif (($srno - 1) == 4) { $colorClass = 'dps-light-yellow'; }
                                                                elseif (($srno - 1) == 5) { $colorClass = 'dps-dark-red'; }
                                                                else { $colorClass = 'blue'; }
                                                                ?>
                                                                <div class="homework-item d-flex align-items-center gap-3">
                                                                    <div class="subject-box d-flex justify-content-center align-items-center text-center physics <?php echo $colorClass; ?>">
                                                                        <?php echo $subject; ?>
                                                                    </div>
                                                                    <div class="details">
                                                                        <p class="author mb-0">By : Admin</p>
                                                                        <p class="task mb-0"><?php echo nl2br($homework); ?></p>
                                                                        <p class="due-date mb-0">Due by : <?php echo $formatted_homeworkdate; ?></p>
                                                                        <?php if (!empty($classwork)): ?>
                                                                            <p class="classwork mb-0">Classwork: <?php echo $classwork; ?></p>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($homeworkimage)): ?>
                                                                            <a href="../Admin/Academics/DailyWorkFiles/<?php echo $homeworkimage; ?>" target="_blank">
                                                                                <button class="btn btn-sm btn-primary mt-2"><i class="fa fa-download"></i></button>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<h5 class='no-data-font-on-landing-page'>No Home Work Data Found</h5>";
                                                        }
                                                    } else {
                                                        echo "<h5 class='no-data-font-on-landing-page'>Unable to fetch homework data at this time.</h5>";
                                                    }
                                                } else {
                                                    echo "<h5 class='no-data-font-on-landing-page'>Please select both Date From and Date To.</h5>";
                                                }
                                            } else {
                                                // Latest 10 active homework entries
                                                $ssqlHomework = "SELECT `srno`, `subject`, `homework`, 
                                                                    DATE_FORMAT(`homeworkdate`, '%d-%m-%Y') AS `homeworkdate`, 
                                                                    `homeworkimage` 
                                                                 FROM `homework_master` 
                                                                 WHERE `status` = 'Active' 
                                                                   AND `sclass` = '$StudentClass' 
                                                                 ORDER BY `homeworkdate` DESC 
                                                                 LIMIT 10";
                                                $resHomework = mysqli_query($Con, $ssqlHomework);
                                                if ($resHomework) {
                                                    if (mysqli_num_rows($resHomework) > 0) {
                                                        while ($rowa = mysqli_fetch_assoc($resHomework)) {    
                                                            $srno++;
                                                            $subject = htmlspecialchars($rowa['subject']);
                                                            $homework = htmlspecialchars($rowa['homework']);
                                                            $homeworkdate = htmlspecialchars($rowa['homeworkdate']);
                                                            $homeworkimage = htmlspecialchars($rowa['homeworkimage']);
                                                            $newDate = date("Y-m-d", strtotime($homeworkdate));
                                                            $classwork_query = "SELECT DISTINCT `classwork` 
                                                                                    FROM `classwork_master` 
                                                                                    WHERE `classworkdate` = '$newDate' 
                                                                                      AND `sclass` = '$StudentClass' 
                                                                                      AND `subject` = '$subject'";
                                                            $sql_r = mysqli_query($Con, $classwork_query);
                                                            $classwork = '';
                                                            if ($sql_r && mysqli_num_rows($sql_r) > 0) {
                                                                while ($row = mysqli_fetch_row($sql_r)) {
                                                                    $classwork .= htmlspecialchars($row[0]) . "<br>";
                                                                }
                                                            }
                                                            $formatted_homeworkdate = date('d M Y', strtotime($homeworkdate));
                                                            
                                                            if (($srno - 1) == 0) { $colorClass = 'blue'; }
                                                            elseif (($srno - 1) == 1) { $colorClass = 'dps-light-green'; }
                                                            elseif (($srno - 1) == 2) { $colorClass = 'dps-light-red'; }
                                                            elseif (($srno - 1) == 3) { $colorClass = 'dps-light-blue'; }
                                                            elseif (($srno - 1) == 4) { $colorClass = 'dps-light-yellow'; }
                                                            elseif (($srno - 1) == 5) { $colorClass = 'dps-dark-red'; }
                                                            else { $colorClass = 'blue'; }
                                                            ?>
                                                            <div class="homework-item d-flex align-items-center gap-3">
                                                                <div class="subject-box d-flex justify-content-center align-items-center text-center physics <?php echo $colorClass; ?>">
                                                                    <?php echo $subject; ?>
                                                                </div>
                                                                <div class="details">
                                                                    <p class="author mb-0">By : Admin</p>
                                                                    <p class="task mb-0"><?php echo nl2br($homework); ?></p>
                                                                    <p class="due-date mb-0">Due by : <?php echo $formatted_homeworkdate; ?></p>
                                                                    <?php if (!empty($classwork)): ?>
                                                                        <p class="classwork mb-0">Classwork: <?php echo $classwork; ?></p>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($homeworkimage)): ?>
                                                                        <a href="../Admin/Academics/DailyWorkFiles/<?php echo $homeworkimage; ?>" target="_blank">
                                                                            <button class="btn btn-sm btn-primary mt-2"><i class="fa fa-download"></i></button>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<h5 class='no-data-font-on-landing-page'>No Home Work Data Found</h5>";
                                                    }
                                                } else {
                                                    echo "<h5 class='no-data-font-on-landing-page'>Unable to fetch homework data at this time.</h5>";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notice Board Section (unchanged) -->
                        <div class="col-lg-6 h-100">
                            <div class="card dps-upper-card h-100">
                                <div class="card-body p-0 pb-3 h-100">
                                    <div class="header mt-3 d-flex justify-content-between align-items-center mb-2 px-3 border-bottom">
                                        <h5 class="card-title mb-3 p-0">Notice Board</h5>
                                        <a href="Notices.php" target="_blank">
                                            <img src="assets/img/Frame 1597883679.svg" class="mb-3" alt="angle-right">
                                        </a>
                                    </div>
                                    <?php
                                        $endpoint = "s-webservies/Get_Notice.php";
                                        $params = array(
                                            'sclass' => $StudentClass,
                                            'limit' => 0
                                        );
                                        $response = getCurlData($endpoint, $params);
                                        if (isset($response['error'])) {
                                            echo "cURL Error: " . htmlspecialchars($response['error']) . "<br>";
                                        } elseif (isset($response['items']) && !empty($response['items'])) {
                                            $notices = $response['items'];
                                            foreach ($notices as $key => $notice) {
                                                $date = htmlspecialchars($notice['datetime']);
                                                $noticeDate = date('d M', strtotime($date));
                                                $noticeDate2 = date('d M Y', strtotime($date));
                                    ?>
                                        <a href="circular_view.php?txtCircularId=<?php echo urlencode($notice['srno']); ?>" target="_blank">
                                            <div class="notice px-3 d-flex mt-3 justify-content-between align-items-center mb-3">
                                                <div class="event">
                                                    <div class="date-box d-flex justify-content-center align-items-center 
                                                        <?php 
                                                            if ($key == 0) { echo 'blue'; } 
                                                            elseif ($key == 1) { echo 'dps-light-green'; } 
                                                            elseif ($key == 2) { echo 'dps-light-red'; } 
                                                            elseif ($key == 3) { echo 'dps-light-blue'; } 
                                                            elseif ($key == 4) { echo 'dps-light-yellow'; } 
                                                            elseif ($key == 5) { echo 'dps-dark-red'; } 
                                                            else { echo 'blue'; } 
                                                        ?>">
                                                        <p class="datee mb-0"><?php echo $noticeDate; ?></p>
                                                    </div>
                                                    <div class="event-details ms-2">
                                                        <p class="event-title mb-0"><?php echo htmlspecialchars_decode($notice['noticetitle']); ?></p>
                                                        <div class="event-added">
                                                            <img src="assets/img/calendar.svg" alt="calendar">
                                                            <p class="added-date mb-0">Added on : <?php echo $noticeDate2; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <img src="assets/img/angle-right.svg" alt="angle-right">
                                            </div>
                                        </a>
                                    <?php 
                                            }
                                        } else {
                                            echo "<h5>Notices not found!</h5>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedules Section -->
            <div class="row mt-4">
                <div class="col-lg-8 mb-sm-3 mb-lg-0">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="header mt-3 d-flex justify-content-between align-items-center mb-2 px-3 border-bottom">
                                <h5 class="card-title mb-3 p-0">Schedules</h5>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 px-3 border-end">
                                    <div class="calendar-header d-flex justify-content-between align-items-center mb-2">
                                        <button class="btn btn-light btn-sm">
                                            <img src="assets/img/flowbite_angle-left-outline.svg" alt="angle-left">
                                        </button>
                                        <span>February 2024</span>
                                        <button class="btn btn-light btn-sm">
                                            <img src="assets/img/flowbite_angle-right-outline.svg" alt="angle-right">
                                        </button>
                                    </div>
                                    <ul class="calendar-days">
                                        <li>M</li>
                                        <li>T</li>
                                        <li>W</li>
                                        <li>T</li>
                                        <li>F</li>
                                        <li>S</li>
                                        <li>S</li>
                                    </ul>
                                    <ul class="calendar-dates">
                                        <li>1</li>
                                        <li>2</li>
                                        <li>3</li>
                                        <li>4</li>
                                        <li>5</li>
                                        <li>6</li>
                                        <li>7</li>
                                        <li>8</li>
                                        <li>9</li>
                                        <li>10</li>
                                        <li>11</li>
                                        <li>12</li>
                                        <li>13</li>
                                        <li>14</li>
                                        <li>15</li>
                                        <li>16</li>
                                        <li class="active-date">17</li>
                                        <li>18</li>
                                        <li>19</li>
                                        <li>20</li>
                                        <li>21</li>
                                        <li>22</li>
                                        <li>23</li>
                                        <li>24</li>
                                        <li>25</li>
                                        <li>26</li>
                                        <li>27</li>
                                        <li>28</li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 p-0 px-3">
                                    <div class="exam-section">
                                        <div class="section-title">Exams</div>
                                        <div class="exam-list">
                                            <?php 
                                            if(!empty($exams)):
                                                // Use foreach with $key to determine dynamic color for each exam
                                                foreach($exams as $key => $exam):
                                                    $examtype     = $exam['examtype'];
                                                    $noticeDate   = $exam['NoticeDateRaw'];
                                                    $noticeDateDMY= $exam['NoticeDateDMY'];
                                                    $today  = strtotime(date('Y-m-d'));
                                                    $examDt = strtotime($noticeDate);
                                                    $daysRemaining = ceil(($examDt - $today)/86400);
                                                    if($daysRemaining < 0){ $daysRemaining = 0; }
                                                    $examTime = "01:30 - 02:15 PM";
                                                    // Dynamic color loop for exams
                                                    if ($key == 0) { $examColor = 'blue'; }
                                                    elseif ($key == 1) { $examColor = 'dps-light-green'; }
                                                    elseif ($key == 2) { $examColor = 'dps-light-red'; }
                                                    elseif ($key == 3) { $examColor = 'dps-light-blue'; }
                                                    elseif ($key == 4) { $examColor = 'dps-light-yellow'; }
                                                    elseif ($key == 5) { $examColor = 'dps-dark-red'; }
                                                    else { $examColor = 'blue'; }
                                            ?>
                                            <div class="exam-card mb-2">
                                                <div class="exam-details">
                                                    <div class="exam-header d-flex justify-content-between align-items-center">
                                                        <p class="exam-name mb-0"><?php echo $examtype; ?></p>
                                                        <div class="exam-status">
                                                            <img src="assets/img/mingcute_time-line.svg" alt="watch">
                                                            <p class="status-text mb-0"><?php echo $daysRemaining; ?> Days More</p>
                                                        </div>
                                                    </div>
                                                    <div class="exam-meta d-flex justify-content-between align-items-center">
                                                        <div class="exam-time">
                                                            <img src="assets/img/mingcute_time-line1.svg" alt="watch">
                                                            <p class="time-text mb-0"><?php echo $examTime; ?></p>
                                                        </div>
                                                        <!-- Apply dynamic color class to the exam-date box -->
                                                        <div class="exam-date <?php echo $examColor; ?>">
                                                            <img src="assets/img/stash_data-date.svg" alt="calendar">
                                                            <p class="date-text mb-0"><?php echo $noticeDateDMY; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach;
                                            else: ?>
                                                <h5 class="ms-2">No Exams Found</h5>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Fee Reminder, Attendance, and Assignments -->
            <div class="row mt-4">
                <div class="col-lg-4 mb-sm-3 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body p-0 pb-3">
                            <div class="header mt-3 d-flex justify-content-between align-items-center px-3 border-bottom">
                                <h5 class="card-title mb-3 p-0">Fee Reminder</h5>
                                <a href="MyFees.php" target="_blank">
                                    <img src="assets/img/Frame 1597883679.svg" class="mb-3" alt="angle-right">
                                </a>
                            </div>
                            <div class="header d-flex justify-content-between align-items-center">
                                <div class="col-12 d-flex flex-column">
                                    <div class="nav fee-rimander-nav px-2" id="v-pills-tab" role="tablist">
                                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                                          aria-selected="true">Regular Fee</button>
                                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                                          aria-selected="false">Miscellaneous Fee</button>
                                    </div>
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <!-- Regular Fee Tab -->
                                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                            <?php
                                                $regular_fee_endpoint = "s-webservies/GetRegularFees.php";
                                                $regular_fee_params = ['sadmission' => $StudentId];
                                                $regular_fee_response = getCurlData($regular_fee_endpoint, $regular_fee_params);
                                                if (isset($regular_fee_response['error'])) {
                                                    echo "<p class='text-danger'>Error fetching regular fees: " . htmlspecialchars($regular_fee_response['error']) . "</p>";
                                                } elseif (isset($regular_fee_response['items']) && !empty($regular_fee_response['items'])) {
                                                    $regular_fees = $regular_fee_response['items'];
                                                    foreach ($regular_fees as $fee) {
                                                        $fee_name = htmlspecialchars($fee['HeadName']);
                                                        $fee_type = htmlspecialchars($fee['HeadType']);
                                                        $fee_amount = number_format(floatval($fee['HeadAmount']), 2);
                                                        $fee_due_date = htmlspecialchars(date('d M Y', strtotime($fee['LastDate'])));
                                                        $remarks = htmlspecialchars($fee['Remarks']);
                                                        switch (strtolower($fee_type)) {
                                                            case 'tuition':
                                                                $fee_icon = 'tuition_icon.svg';
                                                                $fee_color_class = 'bg-primary';
                                                                break;
                                                            case 'hostel':
                                                                $fee_icon = 'hostel_icon.svg';
                                                                $fee_color_class = 'bg-success';
                                                                break;
                                                            default:
                                                                $fee_icon = 'default-icon.svg';
                                                                $fee_color_class = 'bg-primary';
                                                        }
                                            ?>
                                                        <div class="d-flex justify-content-between align-items-center px-3 py-2 mb-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <img src="assets/img/<?php echo htmlspecialchars($fee_icon); ?>" alt="<?php echo htmlspecialchars($fee_name); ?>" width="24" height="24">
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0"><?php echo $fee_name; ?></p>
                                                                    <?php if (!empty($remarks)): ?>
                                                                        <small class="text-muted"><?php echo $remarks; ?></small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <p class="mb-0"><i class="fas fa-rupee-sign"></i> <?php echo $fee_amount; ?></p>
                                                                <small class="text-muted">Due: <?php echo $fee_due_date; ?></small>
                                                            </div>
                                                        </div>
                                            <?php
                                                    }
                                                } else {
                                                    echo "<h5 class='px-3 no-data-font-on-landing-page'>No Regular Fee Reminders Found</h5>";
                                                }
                                            ?>
                                        </div>
                                        <!-- Miscellaneous Fee Tab -->
                                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                            <?php
                                                $misc_fee_endpoint = "s-webservies/GetMiscellaneousFees.php";
                                                $misc_fee_params = ['sadmission' => $StudentId];
                                                $misc_fee_response = getCurlData($misc_fee_endpoint, $misc_fee_params);
                                                if (isset($misc_fee_response['error'])) {
                                                    echo "<p class='text-danger'>Error fetching miscellaneous fees: " . htmlspecialchars($misc_fee_response['error']) . "</p>";
                                                } elseif (isset($misc_fee_response['items']) && !empty($misc_fee_response['items'])) {
                                                    $misc_fees = $misc_fee_response['items'];
                                                    foreach ($misc_fees as $fee) {
                                                        $fee_name = htmlspecialchars($fee['fee_name']);
                                                        $fee_amount = number_format(floatval($fee['amount']), 2);
                                                        $fee_due_date = htmlspecialchars(date('d M Y', strtotime($fee['due_date'])));
                                                        $fee_icon = htmlspecialchars($fee['icon']);
                                                        $fee_color_class = htmlspecialchars($fee['color_class']);
                                            ?>
                                                        <div class="d-flex justify-content-between align-items-center px-3 py-2 mb-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <img src="<?php echo htmlspecialchars($fee_icon); ?>" alt="<?php echo htmlspecialchars($fee_name); ?>" width="24" height="24">
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0"><?php echo $fee_name; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <p class="mb-0"><i class="fas fa-rupee-sign"></i> <?php echo $fee_amount; ?></p>
                                                                <small class="text-muted">Due: <?php echo $fee_due_date; ?></small>
                                                            </div>
                                                        </div>
                                            <?php
                                                    }
                                                } else {
                                                    echo "<h5 class='px-3 no-data-font-on-landing-page'>No Miscellaneous Fees Found</h5>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Section -->
                        <div class="col-lg-4 mb-sm-3 mb-lg-0">
                            <div class="card attendance-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                        <h5 class="card-title mb-0 p-0">Attendance</h5>
                                        <i class="fas fa-arrow-right text-gray-500"></i>
                                    </div>
                                    <div class="row text-center dps-attendance-sheet"> 
                                        <div class="col border-right">
                                            <p class="text-sm text-gray-500">Present</p>
                                            <p class="text-xl font-semibold text-gray-800"><?php echo $present_count; ?></p>
                                        </div>
                                        <div class="col border-right">
                                            <p class="text-sm text-gray-500">Absent</p>
                                            <p class="text-xl font-semibold text-gray-800"><?php echo $absent_count; ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="text-sm text-gray-500">Halfday</p>
                                            <p class="text-xl font-semibold text-gray-800"><?php echo $halfday_count; ?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="left_div">
                                            <canvas id="attendanceChart" width="50" height="50"></canvas>
                                        </div>
                                        <div class="right_div">
                                            <div class="d-flex align-items-center me-3">
                                                <div class="w-3 h-3 bg-green-500 rounded-circle me-2" style="width:10px;height:10px;"></div>
                                                <p class="text-sm text-gray-800 mb-0">Present</p>
                                            </div>
                                            <div class="d-flex align-items-center me-3">
                                                <div class="w-3 h-3 bg-blue-500 rounded-circle me-2" style="width:10px;height:10px;"></div>
                                                <p class="text-sm text-gray-800 mb-0">Half Day</p>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="w-3 h-3 bg-red-500 rounded-circle me-2" style="width:10px;height:10px;"></div>
                                                <p class="text-sm text-gray-800 mb-0">Absent</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg bottom-attendance">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="text-sm font-semibold text-gray-800 mb-0">Last 7 Days</p>
                                            <p class="text-sm text-gray-500 mb-0"><?php echo $display_start_date.' - '.$display_end_date; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <?php
                                            foreach($daily_attendance as $day=>$status){
                                                $status_class='bg-secondary';
                                                if(strtolower($status)==='present')  $status_class='bg-success';
                                                if(strtolower($status)==='halfday') $status_class='bg-primary';
                                                if(strtolower($status)==='absent')  $status_class='bg-danger';
                                                echo '<div class="attend-day '.$status_class.' text-white text-center" style="width:25px;height:25px;line-height:25px;border-radius:50%;">'
                                                     .htmlspecialchars(substr($day,0,1)).'</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignments Section with dynamic color loop -->
                        <div class="col-lg-4">
                            <div class="card h-100">
                                <div class="card-body p-0 pb-3">
                                    <div class="header mt-3 d-flex justify-content-between align-items-center mb-3 px-3 border-bottom">
                                        <h5 class="card-title mb-3 p-0">Assignments</h5>
                                        <a href="Assignment.php" target="_blank">
                                            <img src="assets/img/Frame 1597883679.svg" class="mb-3" alt="arrow">
                                        </a>
                                    </div>
                                    <?php
                                        $endpoint = "s-webservies/GetAssignment.php";
                                        $params = ['sclass' => $StudentClass];
                                        $response = getCurlData($endpoint, $params);
                                        if (isset($response['error'])) {
                                            echo "cURL Error: " . htmlspecialchars($response['error']) . "<br>";
                                        } elseif (isset($response['items']) && !empty($response['items'])) {
                                           $assignments = $response['items'];
                                           foreach ($assignments as $key => $assignment) {
                                                $date = htmlspecialchars($assignment['assignmentdate']);
                                                $assignmentdate = date('d M Y', strtotime($date));
                                                
                                                // Determine dynamic color class based on the loop index
                                                if ($key == 0) { $colorClass = 'blue'; }
                                                elseif ($key == 1) { $colorClass = 'dps-light-green'; }
                                                elseif ($key == 2) { $colorClass = 'dps-light-red'; }
                                                elseif ($key == 3) { $colorClass = 'dps-light-blue'; }
                                                elseif ($key == 4) { $colorClass = 'dps-light-yellow'; }
                                                elseif ($key == 5) { $colorClass = 'dps-dark-red'; }
                                                else { $colorClass = 'blue'; }
                                    ?>
                                    <div class="notice px-3 d-flex justify-content-between align-items-center mb-4 noti">
                                        <div class="event">
                                            <div class="date-box d-flex justify-content-center align-items-center <?php echo $colorClass; ?>">
                                                <img src="assets/img/tabler_file-type-zip.svg" alt="zip">
                                            </div>
                                            <div class="event-details ms-2">
                                                <p class="event-title mb-0"><?php echo $assignment['remark']; ?></p>
                                                <div class="event-added">
                                                    <img src="assets/img/calendar.svg" alt="calendar">
                                                    <p class="added-date mb-0">Added on : <?php echo $assignmentdate; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?php echo $assignment['assignmentURL']; ?>" target="_blank">
                                            <img src="assets/img/material-symbols_download-rounded.svg" alt="download">
                                        </a>
                                    </div>
                                    <?php }
                                        } else {
                                            echo "<h5>No Assignment Found</h5>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Class Faculties Carousel -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-0 pb-3">
                            <div class="header mt-3 d-flex justify-content-between align-items-center mb-2 px-3 border-bottom">
                                <h5 class="card-title mb-3 p-0">Class Faculties</h5>
                                <div class="fac-arrow d-flex justify-content-between align-items-center mb-2">
                                    <button class="btn btn-light btn-sm" data-bs-target="#facultyCarousel" data-bs-slide="prev">
                                        <img src="assets/img/flowbite_angle-left-outline.svg" class="gap-2" alt="angle-left">
                                    </button>
                                    <button class="btn btn-light btn-sm" data-bs-target="#facultyCarousel" data-bs-slide="next">
                                        <img src="assets/img/flowbite_angle-right-outline.svg" alt="angle-right">
                                    </button>
                                </div>
                            </div>
                            <!-- Carousel Start -->
                            <div id="facultyCarousel" class="carousel slide" data-bs-ride="carousel">
                                <?php
                                    $endpoint = "s-webservies/get_subject_teacher.php";
                                    $params = ['sadmission' => $StudentId];
                                    $response = getCurlData($endpoint, $params);
                                    if (isset($response['error'])) {
                                        echo "cURL Error: " . htmlspecialchars($response['error']) . "<br>";
                                    } elseif (isset($response['extra']) && !empty($response['extra'])) {
                                        $class_teacher = isset($response['extra']['class_teacher']) ? $response['extra']['class_teacher'] : [];
                                        $subject_teacher = isset($response['extra']['subject_teacher']) ? $response['extra']['subject_teacher'] : [];
                                        if (!empty($class_teacher)) {
                                            $class_teacher = array_map(function ($teacher) {
                                                return array_merge($teacher, ['is_class_teacher' => true]);
                                            }, [$class_teacher]);
                                        }
                                        $facultyData = !empty($class_teacher) && !empty($subject_teacher)
                                            ? array_merge($class_teacher, $subject_teacher)
                                            : (!empty($class_teacher) ? $class_teacher : $subject_teacher);
                                        $chunks = array_chunk($facultyData, 4);
                                ?>
                                <div class="carousel-inner">
                                    <?php foreach ($chunks as $index => $chunk): ?>
                                      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <div class="row px-3 mt-2">
                                          <?php foreach ($chunk as $faculty): ?>
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                              <div class="fac-card">
                                                <div class="fac-card-content">
                                                  <div class="fac-profile">
                                                    <img class="fac-avatar" src="<?= $faculty['profile_image'] != '' ? $faculty['profile_image'] : '../../../Admin/StudentManagement/StudentPhotos/profile.jpg'; ?>" alt="Avatar">
                                                    <div class="fac-info">
                                                      <p class="fac-name mb-0"><?= htmlspecialchars($faculty['name']) ?></p>
                                                      <p class="fac-subject">
                                                          <?= isset($faculty['is_class_teacher']) ? 'Class Teacher' : ''; ?>
                                                          <?= isset($faculty['subject']) ? ' ' . htmlspecialchars($faculty['subject']) : ''; ?>
                                                      </p>
                                                    </div>
                                                  </div>
                                                  <div class="fac-contact">
                                                    <div class="fac-phone">
                                                      <img src="assets/img/solar_phone-linear.svg" alt="phone">
                                                      <a href="tel:<?= htmlspecialchars($faculty['phone']) ?>" class="fac-phone-number"><?= htmlspecialchars($faculty['mobile_number']) ?></a>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          <?php endforeach; ?>
                                        </div>
                                      </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php } else {
                                    echo "<h5 class='no-data-font-on-landing-page'>No Faculty Found</h5>";
                                }
                                ?>
                            </div>
                            <!-- Carousel End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------------------ End Landing Page --------------------->
    </main>
    <!--end page contents-->
</div>
</body>
</html>

<!-- Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Custom Scripts -->
<script>
    function Validate2() {
        document.getElementById("frmStudentMaster").submit();
    }
    $(".sidebar-dropdown > a").click(function() {
        $(".sidebar-submenu").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".sidebar-dropdown").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this).next(".sidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
    });
    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });
    window.onload = function(){
        if(screen.width >= 576) {
            $(".page-wrapper").addClass("toggled");
        }
    }
</script>
<!-- Chart.js Attendance Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var chartData = <?php echo $chart_data_json; ?>;
    var labels = chartData.map(function(item) { return item.day; });
    var dataValues = chartData.map(function(item) { return item.value; });
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    var attendanceChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Present', 'Half Day', 'Absent'],
            datasets: [{
                data: [
                    dataValues.filter(v => v === 1).length,
                    dataValues.filter(v => v === 0.5).length,
                    dataValues.filter(v => v === 0).length
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.6)',
                    'rgba(23, 162, 184, 0.6)',
                    'rgba(220, 53, 69, 0.6)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) { label += ': '; }
                            label += context.parsed;
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
