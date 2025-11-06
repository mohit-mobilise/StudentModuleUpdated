<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

include '../connection.php';

// Check if connection is available
if (!isset($Con) || !$Con) {
    die('Database connection error. Please try again later.');
}

// Get session variables with null checks
$StudentName = $_SESSION['StudentName'] ?? '';
$class1 = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$AdmissionId = $_SESSION['userid'] ?? '';

// Get parameters from POST or GET - Use prepared statements
$fyear = '';
if (isset($_POST['fyear'])) {
    $fyear = validate_input($_POST['fyear'], 'string', 20);
} elseif (isset($_GET['fymaster'])) {
    $fyear = validate_input($_GET['fymaster'], 'string', 20);
}

// Handle class - decode from base64 if coming from GET
$class = '';
if (isset($_POST['master_class'])) {
    $class = validate_input($_POST['master_class'], 'string', 20);
} elseif (isset($_GET['txtClass'])) {
    $decodedClass = base64_decode($_GET['txtClass'], true);
    // If base64_decode fails, try without strict mode
    if ($decodedClass === false || empty($decodedClass)) {
        $decodedClass = base64_decode($_GET['txtClass']);
    }
    $class = validate_input(trim($decodedClass), 'string', 20);
}

// Handle exam_type - decode from base64 if coming from GET
$exam_type = '';
if (isset($_POST['exam_type'])) {
    $exam_type = validate_input($_POST['exam_type'], 'string', 50);
} elseif (isset($_GET['txtTestType'])) {
    $decodedExamType = base64_decode($_GET['txtTestType'], true);
    // If base64_decode fails, try without strict mode
    if ($decodedExamType === false || empty($decodedExamType)) {
        $decodedExamType = base64_decode($_GET['txtTestType']);
    }
    $exam_type = validate_input(trim($decodedExamType), 'string', 50);
}

// Validate required parameters
if (empty($fyear) || empty($class) || empty($exam_type)) {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Report Card - Error</title>
</head>
<body>
    <h2>Error: Missing Required Parameters</h2>
    <p>Please ensure all required fields are filled:</p>
    <ul>
        <li>Financial Year: ' . htmlspecialchars($fyear ?: 'Missing') . '</li>
        <li>Class: ' . htmlspecialchars($class ?: 'Missing') . '</li>
        <li>Exam Type: ' . htmlspecialchars($exam_type ?: 'Missing') . '</li>
    </ul>
    <p><a href="ReportCard_Portal.php">Go back to Report Card Portal</a></p>
</body>
</html>';
    exit();
}

// Get MasterClass from class_master - Use prepared statement
$master_class = '';
$stmt = mysqli_prepare($Con, "SELECT `MasterClass` FROM `class_master` WHERE `class`=? LIMIT 1");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $class);
    mysqli_stmt_execute($stmt);
    $sqlMasterClass = mysqli_stmt_get_result($stmt);
    
    $rsMasterClass = mysqli_fetch_row($sqlMasterClass);
    if (!$rsMasterClass || empty($rsMasterClass[0])) {
        // If not found, check if we need to use class as MasterClass directly
        mysqli_stmt_close($stmt);
        $stmt2 = mysqli_prepare($Con, "SELECT COUNT(*) as cnt FROM `class_master` WHERE `MasterClass`=? LIMIT 1");
        if ($stmt2) {
            mysqli_stmt_bind_param($stmt2, "s", $class);
            mysqli_stmt_execute($stmt2);
            $checkDirect = mysqli_stmt_get_result($stmt2);
            if ($checkDirect && ($row = mysqli_fetch_assoc($checkDirect)) && $row['cnt'] > 0) {
                $master_class = $class;
            } else {
                $master_class = $class;
            }
            mysqli_stmt_close($stmt2);
        } else {
            $master_class = $class;
        }
    } else {
        $master_class = $rsMasterClass[0];
    }
    if ($stmt) mysqli_stmt_close($stmt);
} else {
    error_log('MasterClass query error: ' . mysqli_error($Con));
    $master_class = $class; // Fallback
}

// Validate master_class
$master_class = validate_input($master_class, 'string', 20);

// Get report card URL - Use prepared statement
$link = '';
$stmt = mysqli_prepare($Con, "SELECT `url` FROM `exam_report_card` WHERE `master_class`=? AND `exam_type`=? AND `status`='Active' AND `fyear`=? LIMIT 1");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $master_class, $exam_type, $fyear);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    
    if ($query) {
        $queryRS = mysqli_fetch_assoc($query);
        $link = $queryRS['url'] ?? '';
    }
    mysqli_stmt_close($stmt);
} else {
    error_log('Report card URL query error: ' . mysqli_error($Con));
}

// If no URL found, show error
if (empty($link)) {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Report Card - Error</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Error: Report Card URL Not Found</h2>
        <p>No report card URL found for the selected criteria:</p>
        <ul>
            <li>Master Class: ' . htmlspecialchars($master_class) . '</li>
            <li>Exam Type: ' . htmlspecialchars($exam_type) . '</li>
            <li>Financial Year: ' . htmlspecialchars($fyear) . '</li>
        </ul>
        <p><a href="ReportCard_Portal.php" class="btn btn-primary">Go back to Report Card Portal</a></p>
    </div>
</body>
</html>';
    exit();
}

// Check if URL points back to this file (prevents infinite loop)
$currentScript = basename($_SERVER['PHP_SELF']);
if (strpos($link, $currentScript) !== false || $link == 'show_reportcard.php') {
    // Display report card directly using submit_attendance.php
    // Include necessary files
    require '../connection.php';
    
    // Get report card data via submit_attendance.php logic
    $html = '';
    
    // Use prepared statement for subject detail query
    $AdmissionId_clean = validate_input($AdmissionId, 'string', 50);
    $stmt = mysqli_prepare($Con, "SELECT DISTINCT `master_subject` FROM `exam_subject_master` WHERE `sclass`=? AND `exam_type`=? AND `master_subject` IN (SELECT DISTINCT master_subject FROM `exam_mark_entry` WHERE `exam_type`=? AND sclass=? AND `sadmission`=?) ORDER BY CAST(`subject_pri` AS UNSIGNED INT)");
    
    $SubjectDetail = false;
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $class, $exam_type, $exam_type, $class, $AdmissionId_clean);
        mysqli_stmt_execute($stmt);
        $SubjectDetail = mysqli_stmt_get_result($stmt);
    }
    
    if ($SubjectDetail && mysqli_num_rows($SubjectDetail) > 0) {
        $html .= '<table class="table table-striped table-bordered table-hover">
<thead>
    <tr class="bg-primary text-white">
        <th>Subject</th>
        <th>Max Marks</th>
        <th>Marks Obtained</th>
    </tr>
</thead>
<tbody>';
        
        while($rowS = mysqli_fetch_row($SubjectDetail)) {
            $Subjectname = $rowS[0];
            $Subjectname_clean = validate_input($Subjectname, 'string', 100);
            
            // Use prepared statement for mark detail query
            $stmt_mark = mysqli_prepare($Con, "SELECT DISTINCT `marks_obt`,`max_marks` FROM `exam_mark_entry` WHERE `sadmission`=? AND `exam_type`=? AND `subject`=?");
            $markDetail = false;
            if ($stmt_mark) {
                mysqli_stmt_bind_param($stmt_mark, "sss", $AdmissionId_clean, $exam_type, $Subjectname_clean);
                mysqli_stmt_execute($stmt_mark);
                $markDetail = mysqli_stmt_get_result($stmt_mark);
            }
            
            if ($markDetail && mysqli_num_rows($markDetail) > 0) {
                while($rowP = mysqli_fetch_row($markDetail)) {
                    $MarkObtained = $rowP[0];
                    $MaxMark = $rowP[1];
                    
                    $html .= '<tr>
                        <td>' . htmlspecialchars($Subjectname) . '</td>
                        <td>' . htmlspecialchars($MaxMark) . '</td>
                        <td>' . htmlspecialchars($MarkObtained) . '</td>
                    </tr>';
                }
            }
        }
        
        $html .= '</tbody></table>';
    } else {
        $html = '<p>No marks found for this exam.</p>';
    }
    
    // Display the report card
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Report Card</h2>
        <p><strong>Student:</strong> ' . htmlspecialchars($StudentName) . '</p>
        <p><strong>Class:</strong> ' . htmlspecialchars($class) . '</p>
        <p><strong>Exam Type:</strong> ' . htmlspecialchars($exam_type) . '</p>
        <p><strong>Financial Year:</strong> ' . htmlspecialchars($fyear) . '</p>
        <hr>
        ' . $html . '
        <hr>
        <p><a href="ReportCard_Portal.php" class="btn btn-secondary">Back to Report Card Portal</a></p>
    </div>
</body>
</html>';
    exit();
}

// If URL is external, redirect to it
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
</head>
<body onload="load()">
    <form method="post" action="<?php echo htmlspecialchars($link); ?>?exam_type=<?php echo htmlspecialchars($exam_type); ?>" name="frm1" id="frm1">
        <input type="hidden" name="txtsadmission" id="txtsadmission" value="<?php echo htmlspecialchars($AdmissionId); ?>">
        <input type="hidden" name="txtClass" id="txtClass" value="<?php echo htmlspecialchars($class1); ?>">
    </form>

</body>
</html>
<script type="text/javascript">
function load()
{
    // Only submit if form action is valid and not pointing to this script
    var form = document.frm1;
    var currentScript = 'show_reportcard.php';
    if (form && form.action && form.action !== '' && form.action.indexOf(currentScript) === -1) {
        form.submit();
    } else {
        alert('Error: Invalid report card URL. Please contact administrator.');
        window.location.href = 'ReportCard_Portal.php';
    }
}
</script>
