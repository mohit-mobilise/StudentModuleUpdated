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
$StudentName = $_SESSION['StudentName'] ?? '';
$name = $StudentName; // Set $name for use in queries
$currentdate = date("Y-m-d");

// Current date variables
$date = htmlspecialchars(date('Y-m-d'));
$currentDateDMY = date('d M Y', strtotime($date));

// If StudentClass is not set in session, retrieve from request or database
if (empty($StudentClass)) {
    $StudentClass = isset($_REQUEST["cboClass"]) ? $_REQUEST["cboClass"] : '';
    
    // If still empty, get from database
    if (empty($StudentClass) && !empty($StudentId)) {
        $classQuery = "SELECT `sclass` FROM `student_master` WHERE `sadmission` = '$StudentId' LIMIT 1";
        $classResult = mysqli_query($Con, $classQuery);
        if ($classResult && mysqli_num_rows($classResult) > 0) {
            $classRow = mysqli_fetch_assoc($classResult);
            $StudentClass = $classRow['sclass'] ?? '';
        }
    }
    
    // Final fallback to '10' if still empty
    if (empty($StudentClass)) {
        $StudentClass = '10';
    }
}

// Fetch MasterClass from student_master for menu filtering
$Master = '';
if (!empty($StudentId)) {
    $masterQuery = "SELECT `MasterClass` FROM `student_master` WHERE `sadmission` = '$StudentId' LIMIT 1";
    $masterResult = mysqli_query($Con, $masterQuery);
    if ($masterResult && mysqli_num_rows($masterResult) > 0) {
        $masterRow = mysqli_fetch_assoc($masterResult);
        $Master = $masterRow['MasterClass'] ?? '';
    }
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
    // Get current month's start and end dates
    $currentMonthStart = date('Y-m-01');
    $currentMonthEnd = date('Y-m-t');
    
    $ssqlExams = "SELECT 
                     `srno`,
                     `examtype`,
                     `notice`,
                     `status`,
                     `datetime` as exam_time,
                     DATE_FORMAT(`NoticeDate`,'%d-%m-%Y') AS `NoticeDateDMY`,
                     `NoticeDate` AS `NoticeDateRaw`,
                     DATE_FORMAT(`NoticeEndDate`,'%d-%m-%Y') AS `NoticeEndDateDMY`,
                     `NoticeEndDate` AS `NoticeEndDateRaw`,
                     `noticefilename`
                  FROM `student_datesheet`
                  WHERE `status`='Active'
                    AND `sclass`='$StudentClass'
                    AND `NoticeDate` BETWEEN '$currentMonthStart' AND '$currentMonthEnd'
                  ORDER BY `NoticeDate` ASC";
    $resExams = mysqli_query($Con, $ssqlExams);
    while($row = mysqli_fetch_assoc($resExams)) {
        $exams[] = $row;
    }
} catch(Exception $e) {
    // handle error if needed
}

// Get all exam dates for the current month for calendar highlighting
$examDates = array();
foreach($exams as $exam) {
    $examDates[] = date('Y-m-d', strtotime($exam['NoticeDateRaw']));
}

// Get current month's calendar data
$currentMonth = isset($_REQUEST['month']) ? intval($_REQUEST['month']) : date('n');
$currentYear = isset($_REQUEST['year']) ? intval($_REQUEST['year']) : date('Y');

// Validate month and year
if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = date('n');
}
if ($currentYear < 2000 || $currentYear > 2100) {
    $currentYear = date('Y');
}

$firstDay = date('w', strtotime("$currentYear-$currentMonth-01"));
$daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
$monthName = date('F Y', strtotime("$currentYear-$currentMonth-01"));

// Get the start and end dates for the selected month
$monthStart = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
$monthEnd = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));

// Fetch exams for the selected month
$exams = array();
try {
    $ssqlExams = "SELECT 
                     `srno`,
                     `examtype`,
                     `notice`,
                     `status`,
                     `datetime` as exam_time,
                     DATE_FORMAT(`NoticeDate`,'%d-%m-%Y') AS `NoticeDateDMY`,
                     `NoticeDate` AS `NoticeDateRaw`,
                     DATE_FORMAT(`NoticeEndDate`,'%d-%m-%Y') AS `NoticeEndDateDMY`,
                     `NoticeEndDate` AS `NoticeEndDateRaw`,
                     `noticefilename`
                  FROM `student_datesheet`
                  WHERE `status`='Active'
                    AND `sclass`='$StudentClass'
                    AND `NoticeDate` BETWEEN '$monthStart' AND '$monthEnd'
                  ORDER BY `NoticeDate` ASC";
    $resExams = mysqli_query($Con, $ssqlExams);
    while($row = mysqli_fetch_assoc($resExams)) {
        $exams[] = $row;
    }
} catch(Exception $e) {
    // handle error if needed
}

// Get all exam dates for the selected month for calendar highlighting
$examDates = array();
foreach($exams as $exam) {
    $examDates[] = date('Y-m-d', strtotime($exam['NoticeDateRaw']));
}

// Handle assignment submissions
if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"] == "yes") {
    $ssql = "SELECT `subject`, `class`, `remark`, 
                     DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                     DATE_FORMAT(`assignmentcompletiondate`, '%d-%m-%Y') AS `assignmentcompletiondate`, 
                     `assignmentURL` 
              FROM `assignment` 
              WHERE `status` = 'Active'";

    // Apply date filters if provided
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
    // Fetch all active assignments for the class
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

// **Attendance Data Fetching**
// Fetch attendance data for the last 7 days
$daily_attendance = [];
$present_count = 0;
$absent_count = 0;
$leave_count = 0;

// Get dates for last 7 days
$dates = [];
for($i = 6; $i >= 0; $i--) {
    $dates[] = date('Y-m-d', strtotime("-$i days"));
}

// Fetch attendance data from database
$ssqlAttendance = "SELECT 
                    DATE_FORMAT(`attendancedate`, '%a') as day,
                    `attendance` as status
                  FROM `attendance` 
                  WHERE `sadmission` = '$StudentId'
                    AND `attendancedate` BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()
                  ORDER BY `attendancedate` ASC";

$resultAttendance = mysqli_query($Con, $ssqlAttendance);
if ($resultAttendance) {
    while ($row = mysqli_fetch_assoc($resultAttendance)) {
        $day = $row['day'];
        $status = strtolower($row['status']);
        
        // Store attendance status for each day
        $daily_attendance[$day] = $status;
        
        // Count attendance types
        switch ($status) {
            case 'p':
                $present_count++;
                break;
            case 'a':
                $absent_count++;
                break;
            case 'l':
                $leave_count++;
                break;
        }
    }
} else {
    error_log("Attendance Query Error: " . mysqli_error($Con));
}

// If no attendance data found, set default values
if (empty($daily_attendance)) {
    $daily_attendance = [
        'Mon' => 'N/A',
        'Tue' => 'N/A',
        'Wed' => 'N/A',
        'Thu' => 'N/A',
        'Fri' => 'N/A',
        'Sat' => 'N/A',
        'Sun' => 'N/A'
    ];
}

// Define display dates for attendance (Last 7 days)
$display_start_date = date('d M Y', strtotime('-6 days'));
$display_end_date = date('d M Y');

// Prepare data for Chart.js
$chart_data = [];
foreach ($daily_attendance as $day => $status) {
    // Map statuses to numerical values for the chart
    switch (strtolower($status)) {
        case 'present':
            $value = 1;
            break;
        case 'leave':
            $value = 0.5;
            break;
        case 'absent':
            $value = 0;
            break;
        default:
            $value = null; // for N/A
    }
    $chart_data[] = ['day' => $day, 'value' => $value];
}

// Encode data to JSON for JavaScript
$chart_data_json = json_encode($chart_data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> ||Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
   <link rel="stylesheet" type="text/css" href="assets/css/dps-users-style.css">
 <link rel="stylesheet" type="text/css" href="assets/css/open-sans.css">
  <link rel="stylesheet" type="text/css" href="assets/css/dps-landing-page-style.css">
 <style>
.home-page-student-portel #show-sidebar {
    background: #283897;
    padding-left: 8px;
    height: 25px;
    top: 0px;
    padding-top: 3px;
}
.calendar-dates li.has-exam {
    background-color: #e3f2fd;
    border-radius: 50%;
    cursor: pointer;
}
.calendar-dates li.has-exam:hover {
    background-color: #bbdefb;
}
p.task.mb-0 {
    -webkit-line-clamp: 2;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    word-wrap: break-word;
    width: 85%;
}
.homework-list {
    height: 420px;
    overflow-y: auto;
}
.homework-list-assign{
      height: 300px;
    overflow-y: auto;
}
.exam-list {
    height: 220px;
    overflow-y: auto;
}
p.event-title.mb-0 {
    -webkit-line-clamp: 2;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    word-wrap: break-word;
}
 </style>
</head>
<body class="home-page-student-portel">


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme">
    
    <?php include 'new_sidenav.php';?>
     
<main class="page-content" style="margin-top:50px;">
         <!------------------start savi----landing page-------------------------------->
         <div class="main-content p-3">
  <div class="header d-flex justify-content-between align-items-center">
    <h3 class="mb-4 text">Student Dashboard</h3>
  </div>

  <!-- Profile Section and Quick Links -->
  <div class="row">
    <div class="col-lg-4 mb-sm-3 mb-lg-0 dps-upper-card">
      <div class="profile-card">
        <?php
            // Direct database query to get student details
            $ssql = "SELECT `sname` as `Student_name`, `sadmission`, `sclass` as `Class`, 
                    `srollno` as `Roll_no`, `ProfilePhoto` 
                    FROM `student_master` 
                    WHERE `sadmission` = '$StudentId'";
            
            $result = mysqli_query($Con, $ssql);
            if ($result && mysqli_num_rows($result) > 0) {
                $student = mysqli_fetch_assoc($result);
                $photo = $student['ProfilePhoto'] != '' ? 
                         $BaseURL.'Admin/StudentManagement/StudentPhotos/'.$student['ProfilePhoto'] : 
                         'tabs/student/profile.png';
        ?>
        <img src="<?php echo htmlspecialchars($photo); ?>" alt="Student" />
        <div>
              <h5><?php echo htmlspecialchars($student['Student_name']); ?></h5>
              <p class="text-white">Class: <?php echo htmlspecialchars($student['Class']); ?><br />Roll No: <?php echo htmlspecialchars($student['Roll_no']); ?></p>
        </div>
        <?php } else {
            echo "<img src=\"tabs/student/profile.png\" alt=\"Student\" />";
            echo "<div><h5>No Student Data Found</h5></div>";
        }
        ?>
      </div>
      <div class="card">
        <div class="card-body  p-0 pb-3">
          <div class="header mt-3 mb-2 px-3 border-bottom">
            <h5 class="card-title mb-3 p-0">Quick Links</h5>
          </div>

          <div class=" px-3 quick-links">
            <div class="row ">
              <div class="col-lg-6 col-6 ">
                <div class="my-3 quicklink-align" id="quickbuttons">
                  <img class="quick1" src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> <a
                    class="querytext" href="StudentDateSheet.php">Exam Date Sheet</a>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="my-3 quick-1 quicklink-align" id="quickbuttons1"><img class="quick2"
                    src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> <a class="querytext"
                    href="Transport.php">Transport Details </a> </div>
              </div>
              <div class="col-lg-6 col-6 ">
                <div class="my-3 quicklink-align" id="quickbuttons2"><img class="quick3"
                    src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> <a class="querytext"
                    href="Holiday.php">Holiday List</a> </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="my-3 quicklink-align" id="quickbuttons3"><img class="quick4"
                    src="assets/img/material-symbols_feed-outline.svg" alt="quicklinks"> <a class="querytext"
                    href="ReportCard_Portal.php">Report Card </a> </div>
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
                                    <div class="homework-list mx-3">
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
                                                                       AND `sclass` = ? 
                                                                       AND `homeworkdate` >= ? 
                                                                       AND `homeworkdate` <= ? 
                                                                     ORDER BY `homeworkdate` DESC";
                                                    $stmtHomework = mysqli_prepare($Con, $ssqlHomework);
                                                    if ($stmtHomework) {
                                                        mysqli_stmt_bind_param($stmtHomework, "sss", $StudentClass, $date_from, $date_to);
                                                        mysqli_stmt_execute($stmtHomework);
                                                        $resHomework = mysqli_stmt_get_result($stmtHomework);
                                                    } else {
                                                        error_log("Homework query preparation failed: " . mysqli_error($Con));
                                                        $resHomework = false;
                                                    }
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
                                                                    <div class="details mx-1">
                                                                        <p class="author mb-0">By : Admin</p>
                                                                        <p class="task mb-0"><?php echo nl2br($homework); ?></p>
                                                                        <p class="due-date mb-0">Due by : <?php echo $formatted_homeworkdate; ?></p>
                                                                        <?php if (!empty($classwork)): ?>
                                                                            <p class="classwork mb-0">Classwork: <?php echo $classwork; ?></p>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($homeworkimage)): ?>
                                                                            <a href="<?php echo htmlspecialchars($homeworkimage); ?>" target="_blank" class="download-btn">
                                                                           <i class="fa fa-download" style="color:#283897; margin-right: 15px; margin-top: -25px;float: right;"></i>
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
                                                // Latest 5 active homework entries
                                                $ssqlHomework = "SELECT `srno`, `subject`, `homework`, 
                                                                    DATE_FORMAT(`homeworkdate`, '%d-%m-%Y') AS `homeworkdate`, 
                                                                    `homeworkimage` 
                                                                 FROM `homework_master` 
                                                                 WHERE `status` = 'Active' 
                                                                   AND `sclass` = '$StudentClass' 
                                                                 ORDER BY `homeworkdate` DESC 
                                                                 LIMIT 25";
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
                                                                <div class="details mx-1">
                                                                    <p class="author mb-0">By : Admin</p>
                                                                    <p class="task mb-0"><?php echo nl2br($homework); ?></p>
                                                                    <p class="due-date mb-0">Due by : <?php echo $formatted_homeworkdate; ?></p>
                                                                    <?php if (!empty($classwork)): ?>
                                                                        <p class="classwork mb-0">Classwork: <?php echo $classwork; ?></p>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($homeworkimage)): ?>
                                                                        <a href="<?php echo htmlspecialchars($homeworkimage); ?>" target="_blank" class="download-btn">
                                                                             <i class="fa fa-download" style="color:#283897; margin-right: 15px; margin-top: -25px;float: right;"></i>
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
                                    <div class="homework-list">
                                    <?php
                                        // Define the array of colors to cycle through
                                        $colors = ['#28389726', '#34a86626', '#ef661a26', '#199cda26', '#fcbd4c26', '#da202026'];
                                        
                                        // Function to get a darker variant by removing the transparency
                                        function getDarkerColor($color) {
                                            if (strlen($color) == 9) {
                                                return substr($color, 0, 7);
                                            }
                                            return $color;
                                        }

                                        // Fetch notices directly from database using prepared statement
                                        $current_date = date('Y-m-d');
                                        $start_date = date('Y-m-d', strtotime('-3 months'));
                                        
                                        // Use prepared statement to prevent SQL injection
                                        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                                                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                                                  FROM `student_notice` 
                                                  WHERE `sclass`=? AND `status`='Active' 
                                                  AND (`sname`='All' OR `sname`=?) 
                                                  AND `NoticeDate` BETWEEN ? AND ? 
                                                  ORDER BY `datetime` DESC LIMIT 10";
                                          
                                        $stmtNotice = mysqli_prepare($Con, $query);
                                        if ($stmtNotice) {
                                            mysqli_stmt_bind_param($stmtNotice, "ssss", $StudentClass, $name, $start_date, $current_date);
                                            mysqli_stmt_execute($stmtNotice);
                                            $sql = mysqli_stmt_get_result($stmtNotice);
                                        } else {
                                            error_log("Notice query preparation failed: " . mysqli_error($Con));
                                            $sql = false;
                                        }
                                        
                                        if (!$sql) {
                                            error_log('Notice query failed: ' . mysqli_error($Con));
                                        }

                                        // Initialize counter for color cycling
                                        $counter = 0;
                                        
                                        if (mysqli_num_rows($sql) > 0) {
                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                $noticetitle = $row['noticetitle'];
                                                $NoticeDate = date('d M Y', strtotime($row['NoticeDate']));
                                                $noticefilename = $row['noticefilename'];
                                                $srno = $row['srno'];
                                                $Attachment1URL = $row['Attachment1URL'];
                                                $Attachment2URL = $row['Attachment2URL'];
                                                $Attachment3URL = $row['Attachment3URL'];

                                                // Assign color from the array using counter
                                                $icon_color = $colors[$counter % count($colors)];
                                                $darker_color = getDarkerColor($icon_color);
                                                $counter++;

                                                $noticeDate = date('d M', strtotime($row['NoticeDate']));
                                                $noticeDate2 = date('d M Y', strtotime($row['NoticeDate']));
                                    ?>
                                        <a href="circular_view.php?txtCircularId=<?php echo urlencode($srno); ?>" target="_blank">
                                            <div class="notice px-3 d-flex mt-3 justify-content-between align-items-center mb-3">
                                                <div class="event">
                                                    <div class="date-box d-flex justify-content-center align-items-center 
                                                        <?php 
                                                            if ($counter == 1) { echo 'blue'; } 
                                                            elseif ($counter == 2) { echo 'dps-light-green'; } 
                                                            elseif ($counter == 3) { echo 'dps-light-red'; } 
                                                            elseif ($counter == 4) { echo 'dps-light-blue'; } 
                                                            elseif ($counter == 5) { echo 'dps-light-yellow'; } 
                                                            else { echo 'blue'; } 
                                                        ?>">
                                                        <?php echo $noticeDate; ?>
                                                    </div>
                                                    <div class="event-details ms-2">
                                                        <p class="event-title mb-0"><?php echo htmlspecialchars_decode($noticetitle); ?></p>
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
                                            echo "<h5>No notices found!</h5>";
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

  <!-- Schedules Section-->
  <div class="row mt-4">
    <!-- Column with 8 width -->
    <div class="col-lg-8 mb-sm-3 mb-lg-0">
      <div class="card" style="height: 300px;">
        <div class="card-body p-0">
          <div class="header mt-3 d-flex justify-content-between align-items-center mb-2 px-3 border-bottom">
            <h5 class="card-title mb-3 p-0">Schedules</h5>
             <div class="section-title d-flex justify-content-between align-items-center">
                        <span>Exams for <?php echo $monthName; ?></span>
                        <a href="StudentDateSheet.php" class="text-decoration-none">
                            <img src="assets/img/Frame 1597883679.svg" alt="more">
                        </a>
                    </div>
          </div>
          <!-- <div class="header">
            <h5 class="card-title">Schedules</h5>
          </div> -->

          <div class="row">
            <!-- First half of Column 8 -->
            <div class="col-lg-6 px-3 border-end">
              <div class="calendar-header d-flex justify-content-between align-items-center mb-2">
                <button class="btn btn-light btn-sm" onclick="changeMonth(-1)">
                  <img src="assets/img/flowbite_angle-left-outline.svg" alt="angle-left">
                </button>
                <span><?php echo $monthName; ?></span>
                <button class="btn btn-light btn-sm" onclick="changeMonth(1)">
                  <img src="assets/img/flowbite_angle-right-outline.svg" alt="angle-right">
                </button>
              </div>

              <!-- Calendar Days -->
              <ul class="calendar-days">
                <li>M</li>
                <li>T</li>
                <li>W</li>
                <li>T</li>
                <li>F</li>
                <li>S</li>
                <li>S</li>
              </ul>

              <!-- Calendar Dates -->
              <ul class="calendar-dates">
                <?php
                // Add empty cells for days before the first day of the month
                for($i = 0; $i < $firstDay; $i++) {
                    echo '<li class="disabled"></li>';
                }
                
                // Add days of the month
                for($day = 1; $day <= $daysInMonth; $day++) {
                    $date = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));
                    $isExamDay = in_array($date, $examDates);
                    $isToday = ($date === date('Y-m-d')) ? 'active-date' : '';
                    $class = $isExamDay ? 'has-exam' : '';
                    if($isToday) $class .= ' active-date';
                    
                    echo "<li class='$class' data-date='$date'>$day</li>";
                }
                ?>
              </ul>
            </div>

            <!-- Second half of Column 8 -->
            <div class="col-lg-6 p-0 px-3">
                <div class="exam-section">
                   
                    <div class="exam-list">
                        <?php 
                        if(!empty($exams)):
                            $examCount = 0;
                            foreach($exams as $exam):
                                if($examCount >= 30) break; // Only show first 30 exams
                                $examCount++;
                                
                                $examtype = $exam['examtype'];
                                $noticeDate = $exam['NoticeDateRaw'];
                                $noticeDateDMY = $exam['NoticeDateDMY'];
                                $examTime = date('h:i A', strtotime($exam['exam_time']));
                                
                                // Calculate days difference from today
                                $today = strtotime(date('Y-m-d'));
                                $examDt = strtotime($noticeDate);
                                $daysRemaining = ceil(($examDt - $today)/86400);
                        ?>
                        <div class="exam-card mb-2">
                            <div class="exam-details">
                                <div class="exam-header d-flex justify-content-between align-items-center">
                                    <p class="exam-name mb-0"><?php echo $examtype; ?></p>
                                    <div class="exam-status">
                                        <img src="assets/img/mingcute_time-line.svg" alt="watch">
                                        <p class="status-text mb-0">
                                            <?php echo $daysRemaining; ?> Days <?php echo $daysRemaining >= 0 ? 'More' : 'Ago'; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="exam-meta d-flex justify-content-between align-items-center">
                                    <div class="exam-time">
                                        <img src="assets/img/mingcute_time-line1.svg" alt="watch">
                                        <p class="time-text mb-0"><?php echo $examTime; ?></p>
                                    </div>
                                    <div class="exam-date">
                                        <img src="assets/img/stash_data-date.svg" alt="calendar">
                                        <p class="date-text mb-0"><?php echo $noticeDateDMY; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                        else: ?>
                            <h5 class="ms-2">No Exams Found for <?php echo $monthName; ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Column with 4 width -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body p-0">
          <!-- Birthday Carousel Section -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
    <?php
    // Get current date for birthday check
    $current_date = date('Y-m-d');
    $birthDayMonth = date('m', strtotime($current_date));
    $birthDayDay = date('d', strtotime($current_date));
    
    // Fetch birthday data directly from database
    $birthday_query = "SELECT `sname`, `DOB`, `sclass`, `ProfilePhoto` 
                      FROM `student_master` 
                      WHERE `sclass` = '$StudentClass' 
                      AND MONTH(`DOB`) = '$birthDayMonth' 
                      AND DAY(`DOB`) = '$birthDayDay'";
    
    $birthday_result = mysqli_query($Con, $birthday_query);
    
    if ($birthday_result && mysqli_num_rows($birthday_result) > 0) {
        ?>
        <div class="carousel-inner">
            <?php
            $first = true;
            while ($row = mysqli_fetch_assoc($birthday_result)) {
                $photo = $row['ProfilePhoto'];
                $path_name = "../Admin/StudentManagement/StudentPhotos/".$photo;
                $image = $path_name;
                ?>
                <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
                    <img src="assets/img/Birthday-bg.png" class="d-block w-100" alt="Birthday" style="height: 300px;">
                    <div class="carousel-caption d-none d-md-block">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile" style="max-width: 80px;">
                        <h6 class="mt-2">Happy Birthday</h6>
                        <p class="text-white"><?php echo htmlspecialchars($row['sname']); ?></p>
                    </div>
                </div>
                <?php
                $first = false;
            }
            ?>
        </div>
        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        <?php
    } else {
        ?>
        <img src="assets/img/Birthday-bg.png" class="d-block w-100" alt="No Birthday" style="height: 298px;">
        <?php
    }
    ?>
</div>


        </div>
      </div>
    </div>
  </div>

  <!-- third row -->
  <div class="row mt-4">
    <!-- Homework and Notice Board -->
    <div class="col-lg-4 mb-sm-3 mb-lg-0">
      <div class="card h-100">
        <div class="card-body p-0 pb-3">
          <div class="header mt-3 d-flex justify-content-between align-items-center  px-3 border-bottom">
            <h5 class="card-title mb-3 p-0">Fee Reminder</h5>
            <a href="MyFees.php" target="_blank"> <img src="assets/img/Frame 1597883679.svg" class="mb-3"
                alt="angle-right"></a>
          </div>
          <div class="header  d-flex justify-content-between align-items-center ">
            <div class="col-12 d-flex flex-column px-0">
              <!-- Tabs Navigation -->
              <div class="nav fee-rimander-nav px-2" id="v-pills-tab" role="tablist">
                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                  data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                  aria-selected="true">Regular Fee</button>
                <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                  data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                  aria-selected="false">Miscellaneous Fee</button>
              </div>

              <!-- Tabs Content -->
              <div class="tab-content" id="v-pills-tabContent">
                  <div class="homework-list-assign">
                <!-- Regular Fee Tab -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <?php
                        // Fetch Regular Fee Reminders dynamically from a web service or database
                        $regular_fee_endpoint = "s-webservies/GetRegularFees.php";
                        $regular_fee_params = [
                            'sadmission' => $StudentId
                        ];
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

                                // Determine the icon and color class based on HeadType
                                switch (strtolower($fee_type)) {
                                    case 'tuition':
                                        $fee_icon = 'tuition_icon.svg';
                                        $fee_color_class = 'bg-primary';
                                        break;
                                    case 'hostel':
                                        $fee_icon = 'hostel_icon.svg';
                                        $fee_color_class = 'bg-success';
                                        break;
                                    // Add more cases as needed
                                    default:
                                        $fee_icon = 'default-icon.svg';
                                        $fee_color_class = 'bg-primary';
                                }
                    ?>
                                <!-- Regular Fee Item -->
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
                        // Fetch miscellaneous fees dynamically from a web service or database
                        $misc_fee_endpoint = "s-webservies/GetMiscellaneousFees.php";
                        $misc_fee_params = [
                            'sadmission' => $StudentId
                        ];
                        $misc_fee_response = getCurlData($misc_fee_endpoint, $misc_fee_params);
                        
                        if (isset($misc_fee_response['error'])) {
                            echo "<p class='text-danger'>Error fetching miscellaneous fees: " . htmlspecialchars($misc_fee_response['error']) . "</p>";
                        } elseif (isset($misc_fee_response['items']) && !empty($misc_fee_response['items'])) {
                            $misc_fees = $misc_fee_response['items'];
                            foreach ($misc_fees as $fee) {
                                $fee_name = htmlspecialchars($fee['fee_name']);
                                $fee_amount = number_format(floatval($fee['amount']), 2);
                                $fee_due_date = htmlspecialchars(date('d M Y', strtotime($fee['due_date'])));
                                $fee_icon = htmlspecialchars($fee['icon']); // Assuming an icon field
                                $fee_color_class = htmlspecialchars($fee['color_class']); // e.g., 'success', 'warning', etc.
                    ?>
                                <!-- Miscellaneous Fee Item -->
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
        </div>
      </div>
    </div>

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
                                    <p class="text-sm text-gray-500">Leave</p>
                                    <p class="text-xl font-semibold text-gray-800"><?php echo $leave_count; ?></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="left_div" style="width: 150px; height: 150px;">
                                    <canvas id="attendanceChart"></canvas>
                                </div>
                                <div class="right_div">
                                    <div class="d-flex align-items-center me-3">
                                        <div class="w-3 h-3 bg-green-500 rounded-circle me-2" style="width:10px;height:10px;"></div>
                                        <p class="text-sm text-gray-800 mb-0">Present</p>
                                    </div>
                                    <div class="d-flex align-items-center me-3">
                                        <div class="w-3 h-3 bg-blue-500 rounded-circle me-2" style="width:10px;height:10px;"></div>
                                        <p class="text-sm text-gray-800 mb-0">Leave</p>
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
                                        $status_class = 'bg-secondary'; // Default color for N/A
                                        if(strtolower($status) === 'present' || strtolower($status) === 'p') {
                                            $status_class = 'bg-success'; // Green for Present
                                        } elseif(strtolower($status) === 'leave' || strtolower($status) === 'l') {
                                            $status_class = 'bg-primary'; // Blue for Leave
                                        } elseif(strtolower($status) === 'absent' || strtolower($status) === 'a') {
                                            $status_class = 'bg-danger'; // Red for Absent
                                        }
                                        echo '<div class="attend-day '.$status_class.' text-white text-center" style="width:25px;height:25px;line-height:25px;border-radius:50%;">'
                                             .htmlspecialchars(substr($day,0,1)).
                                             '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
 

    <div class="col-lg-4">
                            <div class="card h-100">
                                <div class="card-body p-0 pb-3">
                                    <div class="header mt-3 d-flex justify-content-between align-items-center mb-3 px-3 border-bottom">
                                        <h5 class="card-title mb-3 p-0">Assignments</h5>
                                        <a href="Assignment.php" target="_blank">
                                            <img src="assets/img/Frame 1597883679.svg" class="mb-3" alt="arrow">
                                        </a>
                                    </div>
                                    <div class="homework-list-assign">
                                    <?php
                                        // Direct database query for latest 5 assignments
                                        $sql = "SELECT `subject`, `class`, `remark`, 
                                                      DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                                                      DATE_FORMAT(`assignmentcompletiondate`, '%d-%m-%Y') AS `assignmentcompletiondate`, 
                                                      `assignmentURL` 
                                               FROM `assignment` 
                                               WHERE `class` = '$StudentClass' 
                                                 AND `status` = 'Active' 
                                               ORDER BY `assignmentdate` DESC 
                                               LIMIT 25";
                                        
                                        $result = mysqli_query($Con, $sql);
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $key = 0;
                                            while ($assignment = mysqli_fetch_assoc($result)) {
                                                $date = htmlspecialchars($assignment['assignmentdate']);
                                                $assignmentdate = date('d M Y', strtotime($date));
                                                
                                                // Color classes for different positions
                                                if ($key == 0) { $colorClass = 'blue'; }
                                                elseif ($key == 1) { $colorClass = 'dps-light-green'; }
                                                elseif ($key == 2) { $colorClass = 'dps-light-red'; }
                                                elseif ($key == 3) { $colorClass = 'dps-light-blue'; }
                                                elseif ($key == 4) { $colorClass = 'dps-light-yellow'; }
                                                else { $colorClass = 'blue'; }
                                    ?>
                                    <div class="notice px-3 d-flex justify-content-between align-items-center mb-4 noti">
                                        <div class="event">
                                            <div class="date-box d-flex justify-content-center align-items-center <?php echo $colorClass; ?>">
                                                <img src="assets/img/tabler_file-type-zip.svg" alt="zip">
                                            </div>
                                            <div class="event-details ms-2">
                                                <p class="event-title mb-0"><?php echo $assignment['remark']; ?></p>
                                               
                                                   
                                                    <p class="added-date mb-0">Added on :  <img src="assets/img/calendar.svg" alt="calendar">  <?php echo $assignmentdate; ?></p>
                                               
                                            </div>
                                        </div>
                                        <a href="<?php echo $assignment['assignmentURL']; ?>" target="_blank">
                                            <img src="assets/img/material-symbols_download-rounded.svg" alt="download">
                                        </a>
                                    </div>
                                    <?php 
                                                $key++;
                                            }
                                        } else {
                                            echo "<h5>No Assignment Found</h5>";
                                        }
                                    ?>
                                </div>
                                </div>
                            </div>
                        </div>
  </div>

  <!--row 4-->
  <div class="row mt-4">
    <!-- Homework and Notice Board -->
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
                <img src="assets/img/flowbite_angle-right-outline.svg" class="" alt="angle-right">
              </button>
            </div>
          </div>

          <!-- Carousel Start -->
          <div id="facultyCarousel" class="carousel slide" data-bs-ride="carousel">
                <?php
                    // Fetch class teacher and subject teachers
                    $teacher_query = "SELECT 
                                        tcm.`SrNo`, 
                                        tcm.`EmpID`, 
                                        tcm.`EmpName`, 
                                        tcm.`ClassTeacher`, 
                                        tcm.`Class`, 
                                        tcm.`SubjectAssigned`, 
                                        tcm.`teacher_type`,
                                        em.`PhoneNo`, 
                                        em.`Email_Id`, 
                                        em.`ProfilePhoto`
                                    FROM `teacher_class_mapping` tcm
                                    LEFT JOIN `employee_master` em ON tcm.`EmpID` = em.`EmpId`
                                    WHERE tcm.`Class` = '$StudentClass' 
                                    AND tcm.`teacher_type` IN ('classteacher', 'subjectteacher')
                                    ORDER BY 
                                        CASE 
                                            WHEN tcm.`teacher_type` = 'classteacher' THEN 1
                                            ELSE 2
                                        END,
                                        tcm.`SubjectAssigned`";
                    
                    $teacher_result = mysqli_query($Con, $teacher_query);
                    
                    $facultyData = [];
                    
                    if ($teacher_result && mysqli_num_rows($teacher_result) > 0) {
                        while ($row = mysqli_fetch_assoc($teacher_result)) {
                            $facultyData[] = [
                                'name' => $row['EmpName'],
                                'mobile_number' => $row['PhoneNo'],
                                'qualification' => $row['Email_Id'],
                                'profile_image' => $row['ProfilePhoto'] ? $row['ProfilePhoto'] : 'tabs/student/profile.png',
                                'is_class_teacher' => ($row['teacher_type'] == 'classteacher'),
                                'subject' => $row['SubjectAssigned']
                            ];
                        }
                    }
                    
                    // Split faculty data into chunks of 4 for carousel
                    $chunks = array_chunk($facultyData, 4);
                    
                    if (!empty($chunks)) {
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
                                                            <img class="fac-avatar" src="<?= htmlspecialchars($faculty['profile_image']) ?>" alt="Avatar">
                                                            <div class="fac-info">
                                                                <p class="fac-name mb-0"><?= htmlspecialchars($faculty['name']) ?></p>
                                                                <p class="fac-subject">
                                                                    <?= $faculty['is_class_teacher'] ? 'Class Teacher' : ''; ?>
                                                                    <?= !$faculty['is_class_teacher'] && $faculty['subject'] ? ' ' . htmlspecialchars($faculty['subject']) : ''; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="fac-contact">
                                                            <div class="fac-phone">
                                                                <img src="assets/img/solar_phone-linear.svg" alt="phone">
                                                                <a href="tel:<?= htmlspecialchars($faculty['mobile_number']) ?>" class="fac-phone-number"><?= htmlspecialchars($faculty['mobile_number']) ?></a>
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
                        <?php
                    } else {
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
         <!-------------------------------end  new landing page --------------------->
      
        </main>
<!--end page contents-->
</div>
</body>
</html>

   <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- jQuery (if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Custom Scripts -->
    <script>
        // Form Validation Function
        function Validate2() {
            document.getElementById("frmStudentMaster").submit();
        }

        // Sidebar Dropdown Toggle
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

        // Sidebar Show/Hide
        $("#close-sidebar").click(function() {
            $(".page-wrapper").removeClass("toggled");
        });
        $("#show-sidebar").click(function() {
            $(".page-wrapper").addClass("toggled");
        });

        // Auto-toggle sidebar based on screen width
        window.onload = function(){
            var x = screen.width;
            if(x >= 576) {
                $(".page-wrapper").addClass("toggled");
            }
        }
    </script>

    <!-- Chart.js Attendance Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the attendance counts from PHP variables
        var presentCount = <?php echo $present_count; ?>;
        var leaveCount = <?php echo $leave_count; ?>;
        var absentCount = <?php echo $absent_count; ?>;
        
        // Get the context of the canvas
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Create the chart
        var attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Present', 'Leave', 'Absent'],
                datasets: [{
                    data: [presentCount, leaveCount, absentCount],
                    backgroundColor: [
                        '#28a745', // Present - Green
                        '#17a2b8', // Leave - Blue
                        '#dc3545'  // Absent - Red
                    ],
                    borderColor: [
                        '#28a745',
                        '#17a2b8',
                        '#dc3545'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
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

    <!-- Add this JavaScript for month navigation -->
    <script>
    function changeMonth(offset) {
        // Get current month and year from the display
        const currentDisplay = document.querySelector('.calendar-header span').textContent;
        const [month, year] = currentDisplay.split(' ');
        
        // Convert month name to number (1-12)
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const currentMonthIndex = monthNames.indexOf(month);
        
        // Calculate new month and year
        let newMonth = currentMonthIndex + offset;
        let newYear = parseInt(year);
        
        // Handle year change
        if (newMonth < 0) {
            newMonth = 11;
            newYear--;
        } else if (newMonth > 11) {
            newMonth = 0;
            newYear++;
        }
        
        // Validate year range (2000-2100)
        if (newYear < 2000 || newYear > 2100) {
            return; // Don't navigate if year is out of range
        }
        
        // Reload the page with new month/year
        window.location.href = `?month=${newMonth + 1}&year=${newYear}`;
    }
    </script>

</body>
</html>
