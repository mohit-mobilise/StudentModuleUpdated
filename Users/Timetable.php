<?php 
// Include database connection and configuration
require '../connection.php'; 
require '../AppConf.php';

// Start the session
session_start(); 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

// Retrieve user information from session with null coalescing to prevent PHP 8.2 warnings
$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");

// If class is not set in session, get it from request
if($StudentClass == "") {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
}

$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';

// Fetch distinct classes (optional, for dropdown or other purposes)
$ssqlClass = "SELECT DISTINCT `class` FROM `class_master`";
$rsClass = mysqli_query($Con, $ssqlClass);

if(!$rsClass) {
    die("Error fetching classes: " . mysqli_error($Con));
}

// Check if the form was submitted
if (($_REQUEST["isSubmit"] ?? '') == "yes") {	
    $ssql = "SELECT `notice`, DATE_FORMAT(`NoticeDate`, '%d-%m-%Y') AS `NoticeDate`, DATE_FORMAT(`NoticeEndDate`, '%d-%m-%Y') AS `NoticeEndDate`, `noticefilename` 
             FROM `student_Timetable` 
             WHERE `status` = 'Active'";

    $Cdate = date("Y-m-d");

    if (($_REQUEST["date_from"] ?? '') != "") {
        $date_from = $_REQUEST["date_from"] ?? '';
        $date_to = $_REQUEST["date_to"] ?? '';
        
        $ssql .= " AND `NoticeDate` >= '$date_from' AND `NoticeDate` <= '$date_to' AND `sclass` = '$StudentClass' ORDER BY `datetime` DESC ";
        
        $reslt = mysqli_query($Con, $ssql);
    }
}

// Fetch timetable data with teacher information
$StudentClassEscaped = mysqli_real_escape_string($Con, $StudentClass);

$ssqlTimetable = "
    SELECT 
        tt.subject, 
        tt.weekday, 
        tt.daytime, 
        tt.datetime, 
        ttc.Emp_Name,
        ttc.image_url
    FROM 
        `time_table` tt
    LEFT JOIN 
        `time_table_teacher_class_mapping` ttc 
        ON tt.sclass = ttc.class AND tt.subject = ttc.subject
    WHERE 
        tt.sclass = '$StudentClassEscaped'
    ORDER BY 
        FIELD(tt.weekday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
        tt.daytime
";

$resltTimetable = mysqli_query($Con, $ssqlTimetable);

if(!$resltTimetable) {
    die("Error fetching time table: " . mysqli_error($Con));
}

// Organize the timetable data by weekdays
$timeTable = array();
while ($row = mysqli_fetch_assoc($resltTimetable)) {
    $day = $row['weekday'];
    if (!isset($timeTable[$day])) {
        $timeTable[$day] = array();
    }
    $timeTable[$day][] = array(
        'daytime' => $row['daytime'],
        'subject' => $row['subject'],
        'teacher' => $row['Emp_Name'] ? $row['Emp_Name'] : 'Not Assigned',
        'image_url' => $row['image_url'] ? $row['image_url'] : 'https://placehold.co/40x40' // Default image if not set
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($SchoolName); ?> - Time Table</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS and other necessary styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <!-- Custom Styles -->
    <link rel="stylesheet" type="text/css" href="new-style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-top:20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .header .print-button {
            font-size: 18px;
            color: #333;
            cursor: pointer;
        }
        .timetable {
              display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 10px;
                border-radius: 6px;
                border: 1px solid var(--Color-Stroke-Color, #E0E0E0);
                background: #FFF;
                padding: 16px;
        }
       
        .day h2 {
            margin-bottom: 10px;
            color: var(--Color-Secondory, #2A295C);
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .class {
            background-color: #e0f7fa;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .class:nth-child(2) {
            background-color: #ffebee;
        }
        .class:nth-child(3) {
            background-color: #e3f2fd;
        }
        .class:nth-child(4) {
            background-color: #fff3e0;
        }
        .class:nth-child(5) {
            background-color: #e8f5e9;
        }
        .class:nth-child(6) {
            background-color: #f3e5f5;
        }
        .class .time {
              color: rgba(42, 41, 92, 0.80);
                font-size: 14px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
        }
        .class .subject {
              margin: 10px 0;
            color:  #2A295C;
            font-size: 15px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .class .teacher {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 5px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .class .teacher img {
              width: 30px;
                height: 30px;
                border-radius: 5px;
                margin-right: 10px;
                object-fit: cover;
        }
       .class .teacher span {
                color: #2A295C;
                font-size: 13px;
                font-style: normal;
                font-weight: 600;
                line-height: normal;
            }
        
        .class {
            background-color: #e0f7fa;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .class:nth-child(4n+1) {
            background-color: #e0f7fa; /* Light Blue */
        }
        
        .class:nth-child(4n+2) {
            background-color: #ffebee; /* Light Red */
        }
        
        .class:nth-child(4n+3) {
            background-color: #e3f2fd; /* Light Blue */
        }
        
        .class:nth-child(4n+4) {
            background-color: #fff3e0; /* Light Orange */
        }
        
        
        @media print {
            body * {
                visibility: hidden;
            }
            .container, .container * {
                visibility: visible;
            }
            .container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .header .print-button {
                display: none;
            }
            .timetable {
                grid-template-columns: repeat(6, 1fr);
                gap: 10px;
            }
            .day {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>


    <?php include 'Header/header_new.php'; ?>

    <div class="page-wrapper chiller-theme">
        
        <?php include 'new_sidenav.php'; ?>
        
        <main class="page-content" style="margin-top:45px;">
        <div class="">
            <div class="header">
                <h2 class="page-title">Time Table</h2>
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> &nbsp; Print
                </button>
            </div>
            <div class="timetable">
                <?php
                // Define the order of weekdays
                $weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                foreach ($weekDays as $day) {
                    echo '<div class="day">';
                    echo '<h2>' . htmlspecialchars($day) . '</h2>';

                    if (isset($timeTable[$day])) {
                        foreach ($timeTable[$day] as $slot) {
                            echo '<div class="class">';
                            echo '<div>';
                            echo '<div class="time"><i class="far fa-clock"></i> ' . htmlspecialchars(date("h:i A", strtotime($slot['daytime']))) . '</div>';
                            echo '<div class="subject">Subject: ' . htmlspecialchars($slot['subject']) . '</div>';
                            echo '</div>';
                            echo '<div class="teacher">';
                            echo '<img src="' . htmlspecialchars($slot['image_url']) . '" alt="Teacher\'s photo">';
                            echo '<span>' . htmlspecialchars($slot['teacher']) . '</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // If no classes on this day
                        echo '<div class="class">';
                        echo '<div class="time">No classes scheduled.</div>';
                        echo '</div>';
                    }

                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>
    <!--end page contents-->
</div>
</body>
<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

    window.onload = function() {
        var x = screen.width;
        if(x >= 576) {
            $(".page-wrapper").addClass("toggled");
        }
    }
</script>


</html>