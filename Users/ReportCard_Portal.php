<?php 
// Include necessary files
require '../connection.php'; 
require '../AppConf.php';

// Start the session
session_start(); 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

// Retrieve and sanitize session variables with null coalescing to prevent PHP 8.2 warnings
$StudentName = mysqli_real_escape_string($Con, $_SESSION['StudentName'] ?? '');
$StudentClass = mysqli_real_escape_string($Con, $_SESSION['StudentClass'] ?? '');
$AdmissionId = mysqli_real_escape_string($Con, $_SESSION['userid'] ?? '');
$StudentRollNo = mysqli_real_escape_string($Con, $_SESSION['StudentRollNo'] ?? '');

$currentdate = date("Y-m-d");

// If StudentClass is empty, set from request
if($StudentClass == "") {
    $StudentClass = mysqli_real_escape_string($Con, $_REQUEST["cboClass"] ?? '');
}

// Fetch distinct classes
$ssqlClass = "SELECT DISTINCT `class` FROM `class_master`";
$rsClass = mysqli_query($Con, $ssqlClass);
if(!$rsClass){
    die('Invalid query: ' . mysqli_error($Con));
}

// Handle report card form submission and redirection
if (isset($_POST["isSubmit"]) && $_POST["isSubmit"] == "yes") {
    // Retrieve and sanitize form inputs
    $fyear = isset($_POST['fyear']) ? mysqli_real_escape_string($Con, $_POST['fyear']) : '';
    $master_class = isset($_POST['master_class']) ? mysqli_real_escape_string($Con, $_POST['master_class']) : '';
    $exam_type = isset($_POST['exam_type']) ? mysqli_real_escape_string($Con, $_POST['exam_type']) : '';

    // Validate required fields
    if(empty($fyear) || empty($master_class) || empty($exam_type)) {
        echo "<script>toastr.warning('Please fill in all mandatory fields.', 'Validation Error');</script>";
    }
    else {
        // Fetch current financial year
        $ssqlCFY = "SELECT DISTINCT `year`, `financialyear` FROM `FYmaster` WHERE `Status` = 'Active'";
        $rsCFY = mysqli_query($Con, $ssqlCFY);
        if(!$rsCFY){
            die('Invalid query: ' . mysqli_error($Con));
        }

        $CurrentFinancialYear = '';
        $CurrentFinancialYearName = '';
        while($row = mysqli_fetch_row($rsCFY)) {
            $CurrentFinancialYear = mysqli_real_escape_string($Con, $row[0]);
            $CurrentFinancialYearName = mysqli_real_escape_string($Con, $row[1]);                  
        }

        // Fetch exam report card URL
        $sql = "SELECT `url` FROM `exam_report_card` WHERE `master_class` = '$master_class' AND `exam_type` = '$exam_type' AND `status` = 'Active'";
        $query = mysqli_query($Con, $sql);
        if(!$query){
            die('Invalid query: ' . mysqli_error($Con));
        }
        $queryRS = mysqli_fetch_assoc($query);
        $link = isset($queryRS['url']) ? $queryRS['url'] : '';

        // Encode parameters for URL
        $encoded_AdmissionId = base64_encode($AdmissionId);
        $encoded_exam_type = base64_encode($exam_type);
        $encoded_StudentClass = base64_encode($StudentClass);

        // Check if Current Financial Year matches selected fyear
        if($CurrentFinancialYear != $fyear) {
            include '../connection_2020.php';
        } else {
            include '../connection.php';
        }

        // Redirect to the exam report card link
        if($link != '') {
            header("Location: $link?fymaster=$fyear&txtsadmission=$encoded_AdmissionId&txtTestType=$encoded_exam_type&txtClass=$encoded_StudentClass");
            exit();
        } else {
            // Handle case where URL is not found
            echo "<script>toastr.error('Report card URL not found.', 'Error');</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Report Card</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-toastr/toastr.min.css">
    <!-- Toastr Custom CSS (fixes display issues) -->
    <link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="new-style.css">
</head>
<body>

    <?php include 'Header/header_new.php';?>
    
    <div class="page-wrapper chiller-theme">
        
        <?php include 'new_sidenav.php';?>
        
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
        
        <main class="page-content" style="margin-top:45px;">
            <div class="container-fluid page-border">
                <div class="row">
                    <div class="col-12 text-center bg-primary text-white">
                        <h4 class="m-t5">Report Card</h4>
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border">
                        <section class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <!-- Optional: Add any introductory content here -->
                                </div>
                            </div>
                            <!-- End of first row -->
                            
                            <div class="row m-t10">
                                <div class="col">
                                    <div class="card flex-fill add-mrf-card bg-g-light">
                                        <div class="card-body card-padding-bottom p10">
                                            <form name="frmEMail" id="frmEMail" method="post" action="" class="form-horizontal" role="form">    
                                                <input type="hidden" name="isSubmit" id="isSubmit" value="yes">

                                                <div class="row">
                                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                                        <div class="form-group">
                                                            <label for="fyear">Financial Year</label>
                                                            <select name="fyear" id="fyear" class="form-control" onchange="fnlFillClass();">
                                                                <option value="">Select One</option>
                                                                <?php
                                                                $ssqlCFY = "SELECT DISTINCT `year` FROM `FYmaster` WHERE `Status`='Active'";
                                                                $rsCFY = mysqli_query($Con, $ssqlCFY);
                                                                if($rsCFY){
                                                                    while($row = mysqli_fetch_assoc($rsCFY)){
                                                                        $year = htmlspecialchars($row['year']);
                                                                        echo "<option value=\"$year\">$year</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                                        <div class="form-group">
                                                            <label for="master_class">Class</label>
                                                            <select name="master_class" id="master_class" class="form-control" onchange="fnlFillExam();">
                                                                <option value="">Select One</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    
                                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                                        <div class="form-group">
                                                            <label for="exam_type">Exam Type</label>
                                                            <select name="exam_type" id="exam_type" class="form-control">
                                                                <option value="">Select One</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                                        <div class="form-group">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <button type="button" name="Button1" value="Submit" class="btn btn-primary" onclick="Validate();" style="margin-top:20px">Submit</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row m-t10">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!-- Modal Trigger (if using modal) -->
                                        <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#reportCardModal">View Report Card</button> -->
                                        
                                        <!-- Report Card Content -->
                                        <p id="view_reportcard"></p>  
                                    </div>
                                </div>
                            </div>
                            
                        </section>
                    </div>
                </div>
            </div>
        </main>
        <!-- End of page contents -->
    </div>
    
    <!-- Bootstrap Modal for Report Card Details -->
    <div class="modal fade" id="reportCardModal" tabindex="-1" role="dialog" aria-labelledby="reportCardModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Report Card Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="modal_reportcard_content">Loading...</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

</body>
</html>

<script>
// Ensure the DOM is fully loaded
$(document).ready(function () {
    // Handle Exam Type selection change
    $('#exam_type').change(function(){
        var Admission = "<?php echo $AdmissionId; ?>"; // Admission ID from PHP
        var exam_type = $('#exam_type').val();
        var fyear = $('#fyear').val();
        var master_class = $('#master_class').val();

        // Check if all required fields are selected
        if(exam_type == "" || fyear == "" || master_class == ""){
            $('#view_reportcard').html('<p>Please select Financial Year, Class, and Exam Type.</p>');
            // Optionally, clear modal content
            $('#modal_reportcard_content').html('');
            return;
        }

        // Optionally, open the modal to display loading
        $('#reportCardModal').modal('show');
        $('#modal_reportcard_content').html('<p>Loading report card...</p>');

        // Make AJAX POST request to fetch report card data
        $.ajax({
            type: 'POST',
            url: 'submit_attendance.php', // Consider renaming to submit_report_card.php for clarity
            data: {
                Admission: Admission, 
                exam_type: exam_type, 
                fyear: fyear, 
                master_class: master_class
            },
            dataType: 'html',
            success: function(response){
                // Display the response in the modal
                $('#modal_reportcard_content').html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + ": " + error);
                $('#modal_reportcard_content').html('<p>Error loading report card data.</p>');
            }
        });
    });
});

// Form validation and submission
function Validate() {
    if (document.getElementById('fyear').value == "") {
        toastr.warning("Financial Year is mandatory", "Validation Error");
        return false;
    }
    else if (document.getElementById('exam_type').value == "") { 
        toastr.warning("Exam Type is Mandatory", "Validation Error");
        return false;
    }
    else if (document.getElementById('master_class').value == "") { 
        toastr.warning("Class is Mandatory", "Validation Error");
        return false;
    }
    else {
        document.getElementById('frmEMail').submit();
    }
}

// Function to populate Class dropdown based on Financial Year
function fnlFillClass() {
    var fyear = $('#fyear').val();
    var sadmission = "<?php echo $AdmissionId; ?>";

    if(fyear == ""){
        toastr.warning("Please select a Financial Year.", "Validation Error");
        return;
    }

    $.ajax({
        type: "GET",
        url: "Getclass1.php",
        data: {year: fyear, sadmission: sadmission},
        dataType: "text",
        success: function(response){
            // Assuming response is a comma-separated list of classes
            var classes = response.split(',');
            var master_class_select = $('#master_class');
            // Clear existing options
            master_class_select.empty();
            // Add default option
            master_class_select.append('<option value="">Select One</option>');
            // Add new options
            $.each(classes, function(index, value){
                value = $.trim(value);
                if(value !== ''){
                    master_class_select.append('<option value="' + value + '">' + value + '</option>');
                }
            });
        },
        error: function(xhr, status, error){
            console.error("AJAX Error: " + status + ": " + error);
            toastr.error("Error fetching classes.", "Error");
        }
    });
}

// Function to populate Exam Type dropdown based on Class and Financial Year
function fnlFillExam() {
    var master_class = $('#master_class').val();
    var fyear = $('#fyear').val();

    if(master_class == ""){
        toastr.warning("Please select a Class.", "Validation Error");
        return;
    }

    $.ajax({
        type: "GET",
        url: "Getexam1.php",
        data: {class: master_class, fyear: fyear},
        dataType: "text",
        success: function(response){
            // Assuming response is a comma-separated list of exam types
            var exam_types = response.split(',');
            var exam_type_select = $('#exam_type');
            // Clear existing options
            exam_type_select.empty();
            // Add default option
            exam_type_select.append('<option value="">Select One</option>');
            // Add new options
            $.each(exam_types, function(index, value){
                value = $.trim(value);
                if(value !== ''){
                    exam_type_select.append('<option value="' + value + '">' + value + '</option>');
                }
            });
        },
        error: function(xhr, status, error){
            console.error("AJAX Error: " + status + ": " + error);
            toastr.error("Error fetching exam types.", "Error");
        }
    });
}

// Sidebar toggle functionality
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

// Auto-show sidebar on page load (for screens >= 576px)
// Use both $(document).ready() and window.onload to ensure it works
$(document).ready(function() {
    function showSidebarIfWide() {
        var x = screen.width;
        if (x >= 576) {
            $(".page-wrapper").addClass("toggled");
        }
    }
    
    // Try immediately
    showSidebarIfWide();
    
    // Also try on window.onload as backup (in case jQuery loads after window.onload)
    if (window.addEventListener) {
        window.addEventListener('load', showSidebarIfWide, false);
    } else if (window.attachEvent) {
        window.attachEvent('onload', showSidebarIfWide);
    }
});

// Backup: Also use window.onload directly (in case jQuery isn't ready)
window.onload = function() {
    var x = screen.width;
    if (x >= 576) {
        // Use setTimeout to ensure DOM is ready
        setTimeout(function() {
            if (typeof jQuery !== 'undefined' && jQuery('.page-wrapper').length > 0) {
                jQuery('.page-wrapper').addClass('toggled');
            } else {
                // Fallback: use vanilla JS if jQuery not available yet
                var pageWrapper = document.querySelector('.page-wrapper');
                if (pageWrapper) {
                    pageWrapper.classList.add('toggled');
                }
            }
        }, 100);
    }
};

</script>
<!-- Toastr JS -->
<script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>
