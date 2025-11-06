<?php
session_start();
require '../connection.php';
require '../AppConf.php';

$StudentId = $_SESSION['userid'] ?? '';

if (empty($StudentId)) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");
if ($StudentClass == "") {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
}

$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$ssqlClass = "SELECT distinct `class` FROM `class_master`";
$rsClass = mysqli_query($Con, $ssqlClass);

$ssqlFY = mysqli_query($Con, "SELECT distinct `financialyear`,`year` FROM `FYmaster` where `Status`='Active'");
$row4 = mysqli_fetch_row($ssqlFY);
$SelectedFinancialYear = $row4[0];
$CurrentFinancialYear = $row4[1];

// Fetch Issued Books - Use prepared statement to prevent SQL injection
$issuedBooksQuery = "
    SELECT 
        lbt.bookname, 
        lbt.bookid, 
        lbt.bookauthor, 
        lbt.issue_date, 
        lbt.returndate, 
        lbt.status,
        lbt.fine
    FROM 
        library_book_transaction lbt
    WHERE 
        TRIM(lbt.sadmission) = ?
    ORDER BY 
        lbt.issue_date DESC
";

$stmt = mysqli_prepare($Con, $issuedBooksQuery);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $StudentId);
    mysqli_stmt_execute($stmt);
    $issuedBooksResult = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    error_log('Issued books query error: ' . mysqli_error($Con));
    $issuedBooksResult = false;
}

// Fetch Available Books - Use prepared statement to prevent SQL injection
$availableBooksQuery = "
    SELECT 
        lbm.BookName, 
        lbm.BookCode AS bookid, 
        lbm.Author AS bookauthor, 
        lbm.Subject AS subject, 
        lbm.Class AS class, 
        lbm.PurchasingDate AS publish_date,
        ls.status1_name AS status
    FROM 
        library_book_master lbm
    JOIN 
        library_status1 ls ON lbm.status1 = ls.status1_code
    WHERE 
        ls.status1_name = 'Activated'
        AND lbm.Class = ?
    ORDER BY 
        lbm.BookName ASC
";
$stmt2 = mysqli_prepare($Con, $availableBooksQuery);
if ($stmt2) {
    mysqli_stmt_bind_param($stmt2, "s", $StudentClass);
    mysqli_stmt_execute($stmt2);
    $availableBooksResult = mysqli_stmt_get_result($stmt2);
    mysqli_stmt_close($stmt2);
} else {
    error_log('Available books query error: ' . mysqli_error($Con));
    $availableBooksResult = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Library</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="new-style.css"> 
    <style>
    
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #2c3e50;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        .search-box i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #ccc;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 20px;
        }
        .tabs div {
                padding: 10px 20px;
                cursor: pointer;
                color: #2A295C;
                font-size: 16px;
                margin: 0 5px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
        }
        .tabs .active {
            border-bottom: 3px solid #283897;
            
        }
       td {
        color: var(--Color-Secondory, #2A295C);
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        padding: 6px 14px !important;
        height: 38px;
        font-size: 14px !important;
        vertical-align: middle !important;
        min-width: 100px;
        text-align: left !important;
        border: 0;
        border-bottom: 1px solid #DFDFDF;
    }
        @media (max-width: 768px) {
            .header h1 {
                font-size: 20px;
            }
            .search-box input {
                width: 150px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<?php include 'Header/header_new.php'; ?>
<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php'; ?>
    <main class="page-content" style="margin-top:50px;">
        <div class="container-fluid page-border">
            <div class="">
                <div class="header">
                    <h2 class="page-title"> Library</h2>
                    <div class="search-box">
                        <input type="text" placeholder="Search">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="portlet-body">
                <div class="tabs">
                    <div class="active" onclick="showTab('issued')">Issued</div>
                    <div onclick="showTab('available')">Available</div>
                </div>
                <div id="issued" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Book Name</th>
                                <th>Book ID</th>
                                <th>Writer</th>
                                <th>Book Issue Date</th>
                                <th>Book Return Date</th>
                                <th>Status</th>
                                <th>Fine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($issuedBooksResult && mysqli_num_rows($issuedBooksResult) > 0): 
                                while ($row = mysqli_fetch_assoc($issuedBooksResult)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['bookname'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookid'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookauthor'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['issue_date'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['returndate'] ?? 'Not returned'); ?></td>
                                        <td><span class="status <?php echo strtolower($row['status'] ?? ''); ?>"><?php echo htmlspecialchars($row['status'] ?? ''); ?></span></td>
                                        <td><?php echo htmlspecialchars($row['fine'] ?? '0'); ?></td>
                                    </tr>
                                <?php endwhile; 
                            else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:20px;">
                                        <p>No issued books found.</p>
                                        <p><a href="../create_library_data.php" style="color:#007bff;text-decoration:underline;">Click here to create library entries</a></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="available" class="tab-content" style="display: none;">
                    <table>
                        <thead>
                            <tr>
                                <th>Book Name</th>
                                <th>Book ID</th>
                                <th>Writer</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Publish Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($availableBooksResult && mysqli_num_rows($availableBooksResult) > 0): 
                                while ($row = mysqli_fetch_assoc($availableBooksResult)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['BookName'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookid'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookauthor'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['subject'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['class'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['publish_date'] ?? ''); ?></td>
                                        <td><span class="status available"><?php echo htmlspecialchars($row['status'] ?? ''); ?></span></td>
                                    </tr>
                                <?php endwhile; 
                            else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:20px;">
                                        <p>No available books found for your class.</p>
                                        <p><a href="../create_library_data.php" style="color:#007bff;text-decoration:underline;">Click here to create library entries</a></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    function showTab(tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tabs")[0].children;
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        event.currentTarget.className += " active";
    }
</script>
</body>
</html>