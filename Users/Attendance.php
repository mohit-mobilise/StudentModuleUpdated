<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

require '../connection.php';
require 'commonApiFile.php';
require '../AppConf.php';

// If user is not logged in, redirect
if(!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Retrieve session variables
$StudentId      = $_SESSION['userid'] ?? '';
$StudentClass   = $_SESSION['StudentClass'] ?? '';
$StudentRollNo  = $_SESSION['StudentRollNo'] ?? '';

// If class is not set, handle
if($StudentClass == "") {
    $StudentClass = validate_input($_REQUEST["cboClass"] ?? '', 'string', 20);
}

// --------------------------------------------------------
// 1. TOP DASHBOARD: PRESENT, ABSENT, ON LEAVES COUNTS
//    (Assume 'on leaves' means counting how many rows
//     in Student_Leave_Transaction overlap with today.)
// --------------------------------------------------------

// Example date range for attendance
$currentdate = date("Y-m-d");
$end_date    = $currentdate; // today
$start_date  = date('Y-m-d', strtotime('-30 days')); // last 30 days

// Present / Absent from 'attendance' table - Use prepared statement
$StudentId_clean = validate_input($StudentId ?? '', 'string', 50);
$StudentClass_clean = validate_input($StudentClass ?? '', 'string', 20);
$start_date_clean = validate_input($start_date ?? '', 'string', 20);
$end_date_clean = validate_input($end_date ?? '', 'string', 20);

$stmt = mysqli_prepare($Con, "SELECT 
        SUM(CASE WHEN attendance = 'P' THEN 1 ELSE 0 END) AS present_cnt,
        SUM(CASE WHEN attendance = 'A' THEN 1 ELSE 0 END) AS absent_cnt,
        SUM(CASE WHEN attendance = 'L' THEN 1 ELSE 0 END) AS leave_cnt
    FROM attendance
    WHERE sadmission = ?
      AND sclass = ?
      AND attendancedate BETWEEN ? AND ?");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssss", $StudentId_clean, $StudentClass_clean, $start_date_clean, $end_date_clean);
    mysqli_stmt_execute($stmt);
    $attendance_counts_result = mysqli_stmt_get_result($stmt);
    if(!$attendance_counts_result) {
        error_log('Attendance query error: ' . mysqli_error($Con));
        die('An error occurred while fetching attendance data.');
    }
} else {
    error_log('Attendance prepared statement error: ' . mysqli_error($Con));
    die('An error occurred while fetching attendance data.');
}
$attendance_counts = mysqli_fetch_assoc($attendance_counts_result);

$present_count = isset($attendance_counts['present_cnt']) ? $attendance_counts['present_cnt'] : 0;
$absent_count  = isset($attendance_counts['absent_cnt'])   ? $attendance_counts['absent_cnt']  : 0;
$leave_count   = isset($attendance_counts['leave_cnt'])    ? $attendance_counts['leave_cnt']   : 0;

// "On Leaves" from `Student_Leave_Transaction` table
$on_leaves_count = 0;
$on_leaves_query = "
    SELECT COUNT(*) AS leave_cnt
    FROM Student_Leave_Transaction
    WHERE sadmission = '$StudentId'
      AND LeaveFrom <= CURDATE()
      AND LeaveTo   >= CURDATE()
";
$on_leaves_result = mysqli_query($Con, $on_leaves_query);
if($on_leaves_result) {
    $row_leaves      = mysqli_fetch_assoc($on_leaves_result);
    $on_leaves_count = $row_leaves['leave_cnt'];
}

// --------------------------------------------------------
// 2. HANDLE APPLY LEAVE FORM SUBMISSION => INSERT INTO
//    Student_Leave_Transaction
// --------------------------------------------------------
if(isset($_POST['apply_leave_submit']))
{
    // Convert posted fields to match columns in Student_Leave_Transaction
    $leave_type = mysqli_real_escape_string($Con, $_POST['leave_type']);
    $from_date  = mysqli_real_escape_string($Con, $_POST['from_date']);
    $to_date    = mysqli_real_escape_string($Con, $_POST['to_date']);
    $reason     = mysqli_real_escape_string($Con, $_POST['leave_reason']);
    
    // Calculate # of days
    $diff = (strtotime($to_date) - strtotime($from_date)) / (60*60*24) + 1;
    $no_of_days = max($diff, 0);
    
    // (Optional) file attachment => `MedicalCertificate` column
    $attachment_name = "";
    if(!empty($_FILES['leave_attachment']['name'])) {
        $attachment_name  = basename($_FILES["leave_attachment"]["name"]);
        $target_directory = "uploads/leaves/";  // ensure folder is writable
        $target_file      = $target_directory . $attachment_name;
        move_uploaded_file($_FILES["leave_attachment"]["tmp_name"], $target_file);
    }

    // Insert into Student_Leave_Transaction
    // srno is auto_incr presumably, so not specified
    $insert_leave_query = "
        INSERT INTO Student_Leave_Transaction
        (sadmission, LeaveFrom, LeaveTo, LeaveType, remark, EntryDate, MedicalCertificate, source)
        VALUES
        (
            '$StudentId',
            '$from_date',
            '$to_date',
            '$leave_type',
            '$reason',
            CURDATE(),
            '$attachment_name',
            'Portal'  -- or 'mobile app', etc.
        )
    ";
    mysqli_query($Con, $insert_leave_query) or die("Error inserting into Student_Leave_Transaction: " . mysqli_error($Con));
    
    // Optionally redirect or show success
    // header("Location: Attendance.php?tab=leave&msg=LeaveApplied");
    // exit();
}

// --------------------------------------------------------
// 3. HANDLE ATTENDANCE FILTER (Year / Month)
// --------------------------------------------------------
$filter_year  = isset($_GET['filter_year'])  ? (int)$_GET['filter_year']  : date('Y');
$filter_month = isset($_GET['filter_month']) ? (int)$_GET['filter_month'] : date('m');

$attendance_query = "
    SELECT
        srno,
        attendancedate,
        DAYNAME(attendancedate) AS day_name,
        attendance
    FROM attendance
    WHERE sadmission = '$StudentId'
      AND sclass     = '$StudentClass'
      AND YEAR(attendancedate)  = '$filter_year'
      AND MONTH(attendancedate) = '$filter_month'
    ORDER BY attendancedate ASC
";
$attendance_result = mysqli_query($Con, $attendance_query) or die('Invalid attendance query: ' . mysqli_error($Con));

// --------------------------------------------------------
// 4. HANDLE LEAVE FILTER (Start Date / End Date) for
//    Student_Leave_Transaction
// --------------------------------------------------------
$leave_start_date = isset($_GET['leave_start_date']) ? $_GET['leave_start_date'] : '';
$leave_end_date   = isset($_GET['leave_end_date'])   ? $_GET['leave_end_date']   : '';

$leave_query = "
    SELECT
        srno,
        LeaveType,
        DATE_FORMAT(EntryDate, '%d-%m-%Y') AS applied_on,
        remark,
        LeaveFrom,
        LeaveTo,
        -- If you want to calculate no_of_days on the fly:
        (DATEDIFF(LeaveTo, LeaveFrom) + 1) AS no_of_days,
        MedicalCertificate,
        source
    FROM Student_Leave_Transaction
    WHERE sadmission = '$StudentId'
";
if(!empty($leave_start_date) && !empty($leave_end_date)) {
    $leave_query .= "
      AND LeaveFrom >= '$leave_start_date'
      AND LeaveTo   <= '$leave_end_date'
    ";
}
$leave_query .= " ORDER BY srno DESC";

$leave_result = mysqli_query($Con, $leave_query) or die('Invalid leave query: ' . mysqli_error($Con));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Attendance & Leave</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Font Awesome (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
     <link rel="stylesheet" type="text/css" href="/new-style.css">
    <style>

    </style>
</head>
<body>

<?php include 'Header/header_new.php'; ?>
<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php'; ?>
    
    <!-- Inline script to ensure sidebar is visible immediately (runs as soon as DOM element exists) -->
    <script>
    // Immediately set sidebar visible on wide screens (runs before jQuery)
    (function() {
        function showSidebar() {
            var pageWrapper = document.querySelector('.page-wrapper');
            if (pageWrapper && screen.width >= 576) {
                pageWrapper.classList.add('toggled');
            }
        }
        
        // Try immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSidebar);
        } else {
            showSidebar();
        }
        
        // Also try on window load as backup
        window.addEventListener('load', showSidebar);
    })();
    </script>
    
    <main class="page-content" style="margin-top:50px;">
        <div class="container-fluid">
              <div class="row mb-3">
                 <div class="col-12 text-center bg-primary text-white">
                    <h4 class="m-t5"><i class="far fa-images"></i> Attendance & Leave </h4>
                 </div>
                 </div>

            <!-- ============================= -->
            <!-- TOP DASHBOARD SECTION        -->
            <!-- ============================= -->
            <div class="row">
                <div class="col-md-3">
                    <div class="count-box text-primary">
                        <h4>Present</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="left_div">
                                <img src="assets/img/present.svg" alt="icon">
                            </div>
                             <div class="right_div">
                        <div class="count-number"><?php echo $present_count; ?></div>
                        </div>
                </div>
                </div>
                </div>
                <div class="col-md-3">
                    <div class="count-box text-danger">
                        <h4>Absent</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="left_div">
                                <div class="on-absent">
                                <img src="assets/img/absent.svg" alt="icon">
                                </div>
                            </div>
                             <div class="right_div">
                        
                        <div class="count-number"><?php echo $absent_count; ?></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="count-box text-warning">
                        <h4>On Leaves</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="left_div">
                                <div class="on-leave">
                                <img src="assets/img/on-leave.svg" alt="icon">
                                </div>
                            </div>
                             <div class="right_div">
                        <div class="count-number"><?php echo $leave_count; ?></div>
                        </div>
                        </div>
                    </div>
                </div>
                
                <!-- "Apply Leave" Button (opens modal) -->
                <div class="col-md-3 text-right">
                    <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#applyLeaveModal">
                        <i class="fa fa-plus"></i> Apply Leave
                    </button>
                </div>
            </div><!-- /.row -->
        <div class="card-attendance-boady bg-white">
            <!-- TABS: ATTENDANCE / LEAVE -->
            <ul class="nav nav-tabs mt-4" id="attendanceLeaveTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="attendance-tab" data-toggle="tab"
                       href="#attendance-content" role="tab"
                       aria-controls="attendance-content" aria-selected="true">
                       Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="leave-tab" data-toggle="tab"
                       href="#leave-content" role="tab"
                       aria-controls="leave-content" aria-selected="false">
                       Leave
                    </a>
                </li>
            </ul>
 
            <div class="tab-content" id="attendanceLeaveTabContent">
                <!-- ATTENDANCE TAB -->
                <div class="tab-pane fade show active" id="attendance-content" role="tabpanel" aria-labelledby="attendance-tab">
                    
                    <!-- FILTERS: Year / Month -->
                    <form method="GET" class="form-inline mb-3 p-3">
                        <label class="mr-2" for="filter_year">Year:</label>
                        <select name="filter_year" id="filter_year" class="form-control mr-3 w-25">
                            <?php
                            $current_year = date('Y');
                            for($y = $current_year; $y >= $current_year - 5; $y--) {
                                $selected = ($y == $filter_year) ? 'selected' : '';
                                echo "<option value='{$y}' {$selected}>{$y}</option>";
                            }
                            ?>
                        </select>
                        
                        <label class="mr-2" for="filter_month">Month:</label>
                        <select name="filter_month" id="filter_month" class="form-control mr-3 w-25">
                            <?php
                            for($m = 1; $m <= 12; $m++) {
                                $selected = ($m == $filter_month) ? 'selected' : '';
                                $month_label = date('F', mktime(0, 0, 0, $m, 10)); 
                                echo "<option value='{$m}' {$selected}>{$month_label}</option>";
                            }
                            ?>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <!-- ATTENDANCE TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <!--<th>Attendance Status</th>-->
                                    <th>Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sr_no = 1;
                            while($row_att = mysqli_fetch_assoc($attendance_result)) {
                                $att_date  = $row_att['attendancedate'];
                                $day_name  = $row_att['day_name'];
                                $status    = $row_att['attendance'];
                                
                                $action_display = ($status == 'P')
                                    ? '<span class="">Present</span>'
                                    : (($status == 'A') 
                                        ? '<span class="">Absent</span>'
                                        : '<span class="">Leave</span>');
                                
                                echo "<tr>";
                                echo "<td>{$sr_no}</td>";
                                echo "<td>" . date('d-m-Y', strtotime($att_date)) . "</td>";
                                echo "<td>{$day_name}</td>";
                                // echo "<td>{$status}</td>";
                                echo "<td>{$action_display}</td>";
                                echo "</tr>";
                                
                                $sr_no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end ATTENDANCE TAB -->

                <!-- LEAVE TAB -->
                <div class="tab-pane fade" id="leave-content" role="tabpanel" aria-labelledby="leave-tab">
                    
                    <!-- FILTERS: Start/End Date -->
                    <form method="GET" class="form-inline mb-3 p-3">
                        <input type="hidden" name="tab" value="leave" />
                        
                        <label class="mr-2" for="leave_start_date">Start Date:</label>
                        <input type="date" name="leave_start_date" id="leave_start_date"
                               class="form-control mr-3 w-25"
                               value="<?php echo htmlspecialchars($leave_start_date); ?>" />
                               
                        <label class="mr-2" for="leave_end_date">End Date:</label>
                        <input type="date" name="leave_end_date" id="leave_end_date"
                               class="form-control mr-3 w-25"
                               value="<?php echo htmlspecialchars($leave_end_date); ?>" />
                        
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <!-- LEAVE RECORDS TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Leave Type</th>
                                    <th>Applied On</th>
                                    <th>Reason</th>
                                    <th>Applied For</th>
                                    <th>No. of Days</th>
                                    <th>Status</th> <!-- Not in DB; we can mark 'N/A' -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $leave_sr_no = 1;
                            while($row_leave = mysqli_fetch_assoc($leave_result)) {
                                $srno           = $row_leave['srno'];
                                $LeaveType      = $row_leave['LeaveType'];
                                $applied_on     = $row_leave['applied_on']; // d-m-Y format
                                $remark         = $row_leave['remark'];
                                $LeaveFrom      = $row_leave['LeaveFrom'];
                                $LeaveTo        = $row_leave['LeaveTo'];
                                $no_of_days     = $row_leave['no_of_days'];
                                $MedicalCert    = $row_leave['MedicalCertificate'];
                                $source         = $row_leave['source'];

                                // Show "Applied For" as date range
                                $applied_for_str = date('d-m-Y', strtotime($LeaveFrom));
                                if($LeaveFrom != $LeaveTo) {
                                    $applied_for_str .= ' to ' . date('d-m-Y', strtotime($LeaveTo));
                                }

                                // We have no explicit status column -> show "N/A"
                                $status = 'N/A';

                                echo "<tr>";
                                echo "<td>{$leave_sr_no}</td>";
                                echo "<td>{$LeaveType}</td>";
                                echo "<td>{$applied_on}</td>";
                                echo "<td>{$remark}</td>";
                                echo "<td>{$applied_for_str}</td>";
                                echo "<td>{$no_of_days}</td>";
                                echo "<td>{$status}</td>";
                                echo "</tr>";
                                
                                $leave_sr_no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end LEAVE TAB -->

            </div> <!-- end tab-content -->
            
            </div>
            

        </div> <!-- end container-fluid -->
    </main>
</div>

<!-- APPLY LEAVE MODAL -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1" role="dialog" aria-labelledby="applyLeaveModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" enctype="multipart/form-data">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="applyLeaveModalLabel">Apply Leave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        
        <div class="modal-body">
          <!-- Leave Type -->
          <div class="form-group">
            <label for="leave_type">Leave Type</label>
            <select class="form-control" id="leave_type" name="leave_type" required>
              <option value="">--Select--</option>
              <option value="Sick Leave">Sick Leave</option>
              <option value="Casual Leave">Casual Leave</option>
              <option value="Other">Other</option>
            </select>
          </div>
          
          <!-- From Date -->
          <div class="form-group">
            <label for="from_date">From Date</label>
            <input type="date" class="form-control" id="from_date" name="from_date" required>
          </div>

          <!-- To Date -->
          <div class="form-group">
            <label for="to_date">To Date</label>
            <input type="date" class="form-control" id="to_date" name="to_date" required>
          </div>
          
          <!-- Reason (remark) -->
          <div class="form-group">
            <label for="leave_reason">Reason</label>
            <textarea class="form-control" id="leave_reason" name="leave_reason" rows="3"></textarea>
          </div>
          
          <!-- Attach File => MedicalCertificate -->
          <div class="form-group">
            <label for="leave_attachment">Attach File (optional)</label>
            <input type="file" class="form-control-file" id="leave_attachment" name="leave_attachment" />
          </div>

        </div> <!-- end modal-body -->
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="apply_leave_submit">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
// Sidebar Show/Hide functionality
$(document).ready(function(){
    // Close sidebar button
    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    
    // Show sidebar button
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });
    
    // Auto-toggle sidebar based on screen width (show by default on larger screens)
    window.onload = function(){
        var x = screen.width;
        if(x >= 576) {
            $(".page-wrapper").addClass("toggled");
        }
    }
    
    // Auto-select "Leave" tab if ?tab=leave is in the URL
    var urlParams = new URLSearchParams(window.location.search);
    var activeTab = urlParams.get('tab');
    if(activeTab === 'leave') {
        $('#attendanceLeaveTab a[href="#leave-content"]').tab('show');
    }
});
</script>

</body>
</html>
