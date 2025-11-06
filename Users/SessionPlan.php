<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

require '../connection.php';
require '../AppConf.php';

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");

if (empty($StudentClass)) {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
    
    // If still empty, get from database
    if (empty($StudentClass) && !empty($StudentId)) {
        // Use prepared statement to prevent SQL injection
        $StudentId_clean = validate_input($StudentId, 'string', 50);
        $stmt = mysqli_prepare($Con, "SELECT `sclass` FROM `student_master` WHERE `sadmission`=? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $StudentId_clean);
            mysqli_stmt_execute($stmt);
            $classResult = mysqli_stmt_get_result($stmt);
            if ($classResult && mysqli_num_rows($classResult) > 0) {
                $classRow = mysqli_fetch_assoc($classResult);
                $StudentClass = $classRow['sclass'] ?? '';
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Final fallback to '10' if still empty
    if (empty($StudentClass)) {
        $StudentClass = '10';
    }
}

$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$ssqlClass = "SELECT DISTINCT `class` FROM `class_master`";
$rsClass = mysqli_query($Con, $ssqlClass);

// Initialize variables for filtering
$date_from = isset($_REQUEST["date_from"]) ? $_REQUEST["date_from"] : '';
$date_to = isset($_REQUEST["date_to"]) ? $_REQUEST["date_to"] : '';

// Initialize result variables
$reslt = null;
$reslt1 = null;

$ssql = "SELECT `class`, `remark`, 
                DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                `assignmentURL` 
         FROM `course_curriculam` WHERE 1=1";

// Apply filtering based on the provided dates
if ($date_from != '' && $date_to != '') {
    // Use prepared statement to prevent SQL injection
    $date_from_clean = validate_input($date_from, 'string', 20);
    $date_to_clean = validate_input($date_to, 'string', 20);
    $class_clean = validate_input($StudentClass, 'string', 20);
    
    $stmt = mysqli_prepare($Con, "SELECT `class`, `remark`, 
                                         DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                                         `assignmentURL` 
                                  FROM `course_curriculam` 
                                  WHERE `assignmentdate` >= ? 
                                    AND `assignmentdate` <= ? 
                                    AND `class` = ? 
                                  ORDER BY `datetime` DESC");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $date_from_clean, $date_to_clean, $class_clean);
        mysqli_stmt_execute($stmt);
        $reslt = mysqli_stmt_get_result($stmt);
        if (!$reslt) {
            error_log("SessionPlan Query Error: " . mysqli_error($Con));
        }
    } else {
        error_log("SessionPlan Prepared Statement Error: " . mysqli_error($Con));
        $reslt = false;
    }
} else {
    $ssql1 = "SELECT `class`, `remark`, 
                      DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                      `assignmentURL` 
               FROM `course_curriculam` WHERE `class` = '$StudentClass' ORDER BY `datetime` DESC";

    $reslt1 = mysqli_query($Con, $ssql1);
    if (!$reslt1) {
        error_log("SessionPlan Query Error: " . mysqli_error($Con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Session Plan</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="new-style.css">
</head>
<body>

<?php include 'Header/header_new.php';?>

<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php';?>

    <main class="page-content" style="margin-top:50px;">
        <div class="container-fluid page-border">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                    <h4 class="m-t5"><i class="fas fa-file-alt"></i> Session Plan</h4>
                </div>
                <div class="col">
                    <div class="card-body card-padding-bottom p10">
                        <form name="frmStudentMaster" id="frmStudentMaster" method="post" action="SessionPlan.php">
                            <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-12">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="date_from" value="<?php echo $date_from; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="date_to" value="<?php echo $date_to; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12 mt-1">
                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary" type="submit" onclick="Javascript:Validate2();">Search</button>
                                        <button type="button" class="btn btn-secondary" onclick="resetFilters();">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row m-t10">
                <div class="col-md-4">
                    <div class="form-inline">
                        <label for="itemperpage">Items Per Page &nbsp;</label>
                        <select name="itemperpage" id="itemperpage" class="form-control ml-10">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
    <thead>
        <tr class="bg-primary text-white">
            <th width="5%">Srno</th>
            <th width="15%">Uploaded Date</th>
            <th width="50%">Remark</th>
            <th width="10%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $srno = 1;
        $isSubmit = isset($_REQUEST["isSubmit"]) ? $_REQUEST["isSubmit"] : '';
        
        // Determine which result set to use based on whether dates are provided
        $result_to_use = null;
        if ($date_from != '' && $date_to != '') {
            $result_to_use = $reslt;
        } else {
            $result_to_use = $reslt1;
        }
        
        // Only process if we have a valid result set
        if ($result_to_use && mysqli_num_rows($result_to_use) > 0) {
            while ($rowa = mysqli_fetch_assoc($result_to_use)) {
                $class = $rowa['class'] ?? '';
                $remark = $rowa['remark'] ?? '';
                $assignmentdate = $rowa['assignmentdate'] ?? '';
                $assignmentURL = $rowa['assignmentURL'] ?? '';
        ?>
            <tr>
                <td><?php echo $srno++; ?></td>
                <td><?php echo htmlspecialchars($assignmentdate); ?></td>
                <td><?php echo htmlspecialchars($remark); ?></td>
                <td class="text-center">
                    <?php
                    if ($assignmentURL != '') {
                    ?>
                        <a href="<?php echo htmlspecialchars($assignmentURL); ?>" target="_blank" title="Download Assignment">
                            <button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button>
                        </a>
                    <?php
                    }
                    ?>
                </td>
            </tr>
        <?php
            }
        } else {
            // Show message if no data found
        ?>
            <tr>
                <td colspan="4" class="text-center">No session plan data found.</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function Validate2() {
        document.getElementById("frmStudentMaster").submit();
    }

    function resetFilters() {
        document.querySelector('input[name="date_from"]').value = '';
        document.querySelector('input[name="date_to"]').value = '';
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
        if (x >= 576) {
            $(".page-wrapper").addClass("toggled");
        }
    }
    
    // Sidebar visibility fix - ensure sidebar is visible on page load
    (function() {
        function showSidebar() {
            var pageWrapper = document.querySelector('.page-wrapper');
            if (pageWrapper && screen.width >= 576) {
                pageWrapper.classList.add('toggled');
            }
        }
        
        // Run immediately
        showSidebar();
        
        // Also run on DOMContentLoaded and window.load as fallbacks
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSidebar);
        }
        window.addEventListener('load', showSidebar);
    })();
</script>

</body>
</html>
