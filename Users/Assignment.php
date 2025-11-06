<?php 
require '../connection.php';
require '../AppConf.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");

if($StudentClass == "") {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
}

$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$ssqlClass = "SELECT DISTINCT `class` FROM `class_master`";
$rsClass = mysqli_query($Con, $ssqlClass);

// Initialize variables for filtering
$date_from = isset($_REQUEST["date_from"]) ? $_REQUEST["date_from"] : '';
$date_to = isset($_REQUEST["date_to"]) ? $_REQUEST["date_to"] : '';

$ssql = "SELECT `subject`, `class`, `remark`, 
                DATE_FORMAT(`assignmentdate`, '%d-%m-%Y') AS `assignmentdate`, 
                DATE_FORMAT(`assignmentcompletiondate`, '%d-%m-%Y') AS `assignmentcompletiondate`, 
                `assignmentURL` 
         FROM `assignment` WHERE `status` = 'Active'";

// Apply filtering based on the provided dates
if ($date_from != '' && $date_to != '') {
    $ssql .= " AND `assignmentdate` >= '$date_from' AND `assignmentdate` <= '$date_to'";
}

$ssql .= " AND `class` = '$StudentClass' ORDER BY `datetime` DESC";
$reslt = mysqli_query($Con, $ssql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Assignment</title>
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
                    <h4 class="m-t5"><i class="fas fa-file-alt"></i> Assignment</h4>
                </div>
                <div class="col col-pn">
                    <!-- search panel -->
                    <div class="card flex-fill add-mrf-card bg-g-light">
                        <div class="card-body card-padding-bottom p10">
                            <form name="frmStudentMaster" id="frmStudentMaster" method="post" action="Assignment.php">
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
            </div>

            <div class="row m-t10">
                <div class="col-md-4 col-pn">
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
                
                <div class="col-12 col-pn">
                    <div class="table-responsive">
                        <table class="table table-row-fixed">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>Srno</th>
                                    <th>Start Date</th>
                                    <th>Finish Date</th>
                                    <th>Subject</th>
                                    <th>Remark</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $srno = 1;
                                while ($rowa = mysqli_fetch_assoc($reslt)) {
                                    $subject = $rowa['subject'];
                                    $remark = $rowa['remark'];
                                    $assignmentdate = $rowa['assignmentdate'];
                                    $assignmentcompletiondate = $rowa['assignmentcompletiondate'];
                                    $assignmentURL = $rowa['assignmentURL'];
                                ?>
                                    <tr>
                                        <td><?php echo $srno++; ?></td>
                                        <td><?php echo $assignmentdate; ?></td>
                                        <td><?php echo $assignmentcompletiondate; ?></td>
                                        <td><?php echo $subject; ?></td>
                                        <td><?php echo $remark; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($assignmentURL != '') {
                                            ?>
                                                <a href="<?php echo $assignmentURL; ?>" target="_blank">
                                                    <button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button>
                                                </a>
                                            <?php
                                            }
                                            ?>
                                        </td>
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
</script>

</body>
</html>
