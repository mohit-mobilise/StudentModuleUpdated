<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
session_start(); 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentClass = $_SESSION['StudentClass'] ?? '';
$class = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$sadmission = $_SESSION['userid'] ?? '';

// Fetch Student Name
$sql = mysqli_query($Con, "SELECT `sname` FROM `student_master` WHERE `sadmission`='$sadmission'");
if (!$sql) {
    die('Invalid query: ' . mysqli_error($Con));
}
$row = mysqli_fetch_row($sql);
$name = $row[0];

// Define the array of colors to cycle through (colors include transparency, e.g., last two digits)
$colors = ['#28389726', '#34a86626', '#ef661a26', '#199cda26', '#fcbd4c26', '#da202026'];

// Function to get a darker variant by removing the transparency (last two characters)
function getDarkerColor($color) {
    // Check if the color has 9 characters (including '#' then 8 hex digits)
    if (strlen($color) == 9) {
        return substr($color, 0, 7); // returns "#RRGGBB"
    }
    // Otherwise, return the original color
    return $color;
}

// Handle AJAX Request for Filtering
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    $filter = mysqli_real_escape_string($Con, $_POST['filter']);
    $current_date = date('Y-m-d');
    $query = "";

    if ($filter == '3') { // Past 3 Months
        $start_date = date('Y-m-d', strtotime('-3 months'));
        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                  FROM `student_notice` 
                  WHERE `sclass`='$class' AND `status`='Active' 
                  AND `sname` IN('All', '$name') 
                  AND `NoticeDate` BETWEEN '$start_date' AND '$current_date' 
                  ORDER BY `datetime` DESC";
    } elseif ($filter == '6') { // Past 6 Months
        $start_date = date('Y-m-d', strtotime('-6 months'));
        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                  FROM `student_notice` 
                  WHERE `sclass`='$class' AND `status`='Active' 
                  AND `sname` IN('All', '$name') 
                  AND `NoticeDate` BETWEEN '$start_date' AND '$current_date' 
                  ORDER BY `datetime` DESC";
    } elseif (is_numeric($filter)) { // Specific Year
        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                  FROM `student_notice` 
                  WHERE `sclass`='$class' AND `status`='Active' 
                  AND `sname` IN('All', '$name') 
                  AND YEAR(`NoticeDate`) = '$filter' 
                  ORDER BY `datetime` DESC";
    } else { // Default to Past 3 Months
        $start_date = date('Y-m-d', strtotime('-3 months'));
        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                  FROM `student_notice` 
                  WHERE `sclass`='$class' AND `status`='Active' 
                  AND `sname` IN('All', '$name') 
                  AND `NoticeDate` BETWEEN '$start_date' AND '$current_date' 
                  ORDER BY `datetime` DESC";
    }

    $sql = mysqli_query($Con, $query);
    if (!$sql) {
        die('Invalid query: ' . mysqli_error($Con));
    }

    // Start output buffering to capture the HTML
    ob_start();
    
    // Initialize a counter for cycling through colors
    $counter = 0;
    while ($row = mysqli_fetch_assoc($sql)) {
        $noticetitle    = $row['noticetitle'];
        $NoticeDate     = date('d M Y', strtotime($row['NoticeDate']));
        $noticefilename = $row['noticefilename'];
        $srno           = $row['srno'];
        $Attachment1URL = $row['Attachment1URL'];
        $Attachment2URL = $row['Attachment2URL'];
        $Attachment3URL = $row['Attachment3URL'];

        // Assign a color from the array using the counter modulo number of colors
        $icon_color = $colors[$counter % count($colors)];
        $darker_color = getDarkerColor($icon_color);
        $counter++;

        echo '
            <div class="notice">
                <div class="notice-header" onclick="toggleNotice(' . $srno . ')">
                    <div class="icon-box" style="background-color: ' . $icon_color . ';">
                        <i class="fas fa-bullhorn" style="color: ' . $darker_color . ';"></i>
                    </div>
                    <span class="date">' . htmlspecialchars($NoticeDate) . '</span>
                    <span class="separator-border" style="border-color: ' . $darker_color . ';"></span>
                    <span class="title">' . htmlspecialchars($noticetitle) . '</span>
                </div>
                <div class="notice-content" id="content-' . $srno . '">
                    <p>' . nl2br(strip_tags($noticetitle)) . '</p>
                    <div>';
        // Handle Multiple Attachments
        if (!empty($noticefilename)) {
            echo '<a href="' . htmlspecialchars($noticefilename) . '" class="btn btn-primary btn-sm" download>Download Attachment 1</a> ';
        }
        if (!empty($Attachment1URL)) {
            echo '<a href="' . htmlspecialchars($Attachment1URL) . '" class="btn btn-secondary btn-sm" download>Download Attachment 2</a> ';
        }
        if (!empty($Attachment2URL)) {
            echo '<a href="' . htmlspecialchars($Attachment2URL) . '" class="btn btn-success btn-sm" download>Download Attachment 3</a> ';
        }
        if (!empty($Attachment3URL)) {
            echo '<a href="' . htmlspecialchars($Attachment3URL) . '" class="btn btn-danger btn-sm" download>Download Attachment 4</a> ';
        }

        echo '</div>
                </div>
            </div>
        ';
    }

    // Get the buffered content and send it as response
    $response = ob_get_clean();
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Notice Board</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS and other dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" type="text/css" href="new-style.css">
    <link rel="stylesheet" type="text/css" href="../chart/Chart.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <style>
        /* Ensure Bootstrap row CSS is applied */
        .row > * {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x, 1.5rem) * .5);
            padding-left: calc(var(--bs-gutter-x, 1.5rem) * .5);
            margin-top: var(--bs-gutter-y, 0);
        }
        
        .page-wrapper {
            display: flex;
        }
       
        .header {
            display: flex;
            margin-bottom: 15px;
            justify-content: flex-end;
            align-items: center;
            flex-direction: row;
        }
     
        .header .dropdown {
            position: relative;
            display: inline-block;
        }
        .header .dropdown button {
            background-color: #e0e0e0;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .header .dropdown button i {
            margin-right: 8px;
        }
        .header .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: -4px;
            border-radius: 4px;
        }
        .header .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .header .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .header .dropdown:hover .dropdown-content {
            display: block;
        }
        .notice {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
            position:relative;
        }
        .notice-header {
            display: flex;
            align-items: center;
            padding: 20px;
            cursor: pointer;
            background: #FFF;
        }
        .notice-header .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin-right: 20px;
        }
        .notice-header .icon-box i {
            font-size: 20px;
        }
        .notice-header .date {
            margin-right: 20px;
            color: var(--Color-Secondory, #2A295C);
            text-align: center;
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }
        .notice-header .separator {
            margin-right: 20px;
            color: #333;
        }
        .notice-header .title {
                font-size: 14px;
                font-weight: 500;
                color: #2A295C;
                width: 75%;
                text-align: justify;
        }
        .notice-content {
            padding: 20px;
            display: none;
            border-radius: 6px;
            border: 1px solid #e2e2e2;
            background: var(--Color-White, #FFF);
        }
        .notice-content p {
            margin: 10px 0;
            font-size: 14px;
            color: #333;
        }
        .notice-content .note {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .notice-content .note i {
            margin-right: 5px;
            color: #ff9800;
        }
        .notice-content .note span {
            font-size: 14px;
            color: #333;
        }
        .notice-content .download {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .notice-content .download a {
            background-color: #e0e0e0;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #000;
            margin-left: 5px;
        }
        .notice-content .download a i {
            margin-right: 5px;
        }
        span.separator-border {
            border-width: 3px;
            height: 56px;
            border-style: solid;
            border-radius: 27px;
            margin: 0 25px;
        }
        .notice-header:after {
            content: "┏";
            position: absolute;
            right: 15px;
            rotate: 222deg;
            font-size: 20px;
            color: #2a295c;
        }
        @media (max-width: 600px) {
            .header h1 {
                font-size: 20px;
            }
            .notice-header .date, .notice-header .title {
                font-size: 14px;
            }
            .notice-content p, .notice-content .note span {
                font-size: 12px;
            }
        }
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
        
        <main class="page-content" style="margin-top:45px;">
            <br>
            <div class="row">
                <div class="col-6 text-left">
                    <h4 class="m-t5"><i class="far fa-images"></i><b> Notice Board</b></h4>
                </div>
                <div class="col-6 text-right">
                    <div class="header">
                        <div class="dropdown">
                            <button><i class="fas fa-calendar-alt"></i> Filter</button>
                            <div class="dropdown-content">
                                <a href="#" data-filter="3">Past 3 Months</a>
                                <a href="#" data-filter="6">Past 6 Months</a>
                                <?php
                                // Generate Year Options (e.g., 2025, 2024, etc.)
                                $current_year = date('Y');
                                for ($year = $current_year; $year >= 2020; $year--) {
                                    echo "<a href=\"#\" data-filter=\"$year\">$year</a>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <!-- Notices List -->
                    <div id="noticeList">
                        <?php
                        // Initial Fetch for Past 3 Months
                        $filter = '3';
                        $current_date = date('Y-m-d');
                        $start_date = date('Y-m-d', strtotime('-3 months'));
                        /*$query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                                  FROM `student_notice` 
                                  WHERE `sclass`='$class' AND `status`='Active' 
                                  AND `sname` IN('All', '$name') 
                                  AND `NoticeDate` BETWEEN '$start_date' AND '$current_date' 
                                  ORDER BY `datetime` DESC";*/
                        $CurrentYear = Date('Y');
                        $LastYear = $CurrentYear - 1;
                        $query = "SELECT `noticetitle`, `NoticeDate`, `noticefilename`, `srno`, 
                                  `Attachment1URL`, `Attachment2URL`, `Attachment3URL` 
                                    FROM `student_notice` 
                                    WHERE 
                                      (`NoticeDate` LIKE '$CurrentYear%' OR `NoticeDate` LIKE '$LastYear%')
                                      AND `status` = 'Active'
                                      AND (
                                            `sname` = '$sadmission' AND `sclass` = '$class'
                                            OR (`sname` = 'All' AND `sclass` = '$class')
                                          )
                                    ORDER BY `datetime` DESC";         
                        
                        $sql = mysqli_query($Con, $query);
                        if (!$sql) {
                            die('Invalid query: ' . mysqli_error($Con));
                        }

                        // Initialize a counter for cycling through colors
                        $counter = 0;
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $noticetitle    = $row['noticetitle'];
                            $NoticeDate     = date('d M Y', strtotime($row['NoticeDate']));
                            $noticefilename = $row['noticefilename'];
                            $srno           = $row['srno'];
                            $Attachment1URL = $row['Attachment1URL'];
                            $Attachment2URL = $row['Attachment2URL'];
                            $Attachment3URL = $row['Attachment3URL'];

                            // Use the counter to cycle through the colors
                            $icon_color = $colors[$counter % count($colors)];
                            $darker_color = getDarkerColor($icon_color);
                            $counter++;

                            echo '
                                <div class="notice">
                                    <div class="notice-header" onclick="toggleNotice(' . $srno . ')">
                                        <div class="icon-box" style="background-color: ' . $icon_color . ';">
                                            <i class="fas fa-bullhorn" style="color: ' . $darker_color . ';"></i>
                                        </div>
                                        <span class="date">' . htmlspecialchars($NoticeDate) . '</span>
                                        <span class="separator-border" style="border-color: ' . $darker_color . ';"></span>
                                        <span class="title">' . htmlspecialchars($noticetitle) . '</span>
                                    </div>
                                    <div class="notice-content" id="content-' . $srno . '">
                                        <p>' . nl2br(strip_tags($noticetitle)) . '</p>
                                        <div>';
                            // Handle Multiple Attachments
                            if (!empty($noticefilename)) {
                                echo '<a href="' . htmlspecialchars($noticefilename) . '" class="btn btn-secondary " download><i class="fas fa-download"></i> Download Attachment 1</a> ';
                            }
                            if (!empty($Attachment1URL)) {
                                echo '<a href="' . htmlspecialchars($Attachment1URL) . '" class="btn btn-secondary" download><i class="fas fa-download"></i> Download Attachment 2</a> ';
                            }
                            if (!empty($Attachment2URL)) {
                                echo '<a href="' . htmlspecialchars($Attachment2URL) . '" class="btn btn-secondary" download><i class="fas fa-download"></i> Download Attachment 3</a> ';
                            }
                            if (!empty($Attachment3URL)) {
                                echo '<a href="' . htmlspecialchars($Attachment3URL) . '" class="btn btn-secondary " download><i class="fas fa-download"></i> Download Attachment 4</a> ';
                            }

                            echo '</div>
                                    </div>
                                </div>
                            ';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
        <!--end page contents-->
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Toggle Notice Content
        function toggleNotice(id) {
            var content = document.getElementById("content-" + id);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }

        $(document).ready(function() {
            // Handle Filter Selection
            $('.dropdown-content a').click(function(e) {
                e.preventDefault();
                var selectedFilter = $(this).data('filter');
                var selectedText = $(this).text();

                // Update the dropdown button text
                $('.dropdown button').html('<i class="fas fa-calendar-alt"></i> ' + selectedText);

                // Fetch and display the filtered notices
                $.ajax({
                    url: '', // Current Page
                    type: 'POST',
                    data: {
                        action: 'filter',
                        filter: selectedFilter
                    },
                    success: function(data) {
                        $('#noticeList').html(data);
                        // Optionally, scroll to top of notice list
                        $('html, body').animate({
                            scrollTop: $("#noticeList").offset().top
                        }, 500);
                    },
                    error: function() {
                        toastr.error('An error occurred while fetching notices.', 'Error');
                    }
                });
            });
        });
    </script>
<!-- Toastr JS -->
<script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>
</body>
</html>
