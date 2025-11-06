<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
session_start(); 



$StudentClass = $_SESSION['StudentClass'];
$class = $_SESSION['StudentClass'];
$StudentRollNo = $_SESSION['StudentRollNo'];
$sadmission = $_SESSION['userid'];

$ssql = "SELECT `srno`, `section`, `phoneno`, `email_id`, `datetime` FROM `school_directory` WHERE `status` = 'Active'";
$rs = mysqli_query($Con, $ssql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||School Directory</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="new-style.css">
    <link rel="stylesheet" type="text/css" href="../chart/Chart.min.css">
</head>
<body>

<?php include 'Header/header_new.php';?>

<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php';?>

    <main class="page-content" style="margin-top:45px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                    <h4 class="m-t5"><i class="fas fa-phone-alt"></i> School Directory</h4>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border"></div>
            </div>

            <!-- Filter Section -->
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
                        <table class="table table-row-fixed">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="30%">Designation</th>
                                    <th width="30%">Email</th>
                                    <th width="30%">Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = mysqli_fetch_row($rs)) {
                                    $srno = $row[0];
                                    $section = $row[1];
                                    $phoneno = $row[2];
                                    $email = $row[3];
                                ?> 
                                    <tr>
                                        <td><?php echo $section;?></td>
                                        <td><i class="fas fa-envelope"></i> <?php echo $email;?></td>
                                        <td><i class="fas fa-phone-alt"></i> <?php echo $phoneno;?></td>
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
    // Reset the filter values and reload the page to fetch the default data
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
