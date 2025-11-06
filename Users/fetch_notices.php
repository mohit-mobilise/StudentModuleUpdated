<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

// Include the database connection
include '../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('Please log in to access the Fee Management System.'); window.location.href='login.php';</script>";
    exit();
}

// Retrieve session variables
$sadmission = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';

// Fetch student details - Use prepared statement
$sadmission_clean = validate_input($sadmission ?? '', 'string', 50);
$stmt = mysqli_prepare($Con, "SELECT `sname`, `sclass`, `sfathername`, `MotherName`, `smobile`, `DiscontType`, `RouteForFees`, `MasterClass`, `FinancialYear`, `FeeSubmissionType` FROM `student_master` WHERE `sadmission`=?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $sadmission_clean);
    mysqli_stmt_execute($stmt);
    $rsStDetail = mysqli_stmt_get_result($stmt);
} else {
    error_log('Fetch notices query error: ' . mysqli_error($Con));
    $rsStDetail = false;
}
$rowSt = mysqli_fetch_assoc($rsStDetail);

// Assign student details to variables with null coalescing to prevent PHP 8.2 warnings
$sname = $rowSt['sname'] ?? '';
$sclass = $rowSt['sclass'] ?? '';
$masterclass = $rowSt['MasterClass'] ?? '';
$sfathername = $rowSt['sfathername'] ?? '';
$MotherName = $rowSt['MotherName'] ?? '';
$smobile = $rowSt['smobile'] ?? '';
$DiscontType = $rowSt['DiscontType'] ?? '';
$RouteForFees = $rowSt['RouteForFees'] ?? '';
$strAdmissionIdFYYear = $rowSt['FinancialYear'] ?? '';
$FeeSubmissionType = $rowSt['FeeSubmissionType'] ?? '';

// Fetch current financial year
$rsCurrent = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status`='Active'");
$rowCurrent = mysqli_fetch_assoc($rsCurrent);
$CurrentYear = $rowCurrent['year'] ?? '';
$previousY = intval(substr($CurrentYear, 0, 4)) - 1; // Assuming 'year' is like '2024'
$previousYConnection = "../connection_" . $previousY . ".php"; // Adjust if necessary
include "$previousYConnection";

// Determine Fee Collection Financial Year
$rsCFY = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status`='Active'");
$rowCurrentFy = mysqli_fetch_assoc($rsCFY);
$CurrentFY = $rowCurrentFy['year'];

if ($CurrentFY == $strAdmissionIdFYYear) {
    $FeeCollectionFY = $CurrentFY;
} elseif ($CurrentFY < $strAdmissionIdFYYear) {
    $FeeCollectionFY = $strAdmissionIdFYYear;
} else {
    $FeeCollectionFY = $CurrentFY;
}

// Fetch fee month display status
$sqlCalChk = mysqli_query($Con, "SELECT `fee_month_show_monthly` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' AND `fee_pay_status`='1' ORDER BY `Priority` LIMIT 1");
$rsCalChk = mysqli_fetch_assoc($sqlCalChk);
$fee_month_show_monthly = $rsCalChk['fee_month_show_monthly'];

// Fetch all fee months and quarters
$sqlMonth = mysqli_query($Con, "SELECT `Month`, `Quarter` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' AND `fee_pay_status`='1' ORDER BY `Priority`");

// Fetch distinct fee heads
$sqlfeehead = mysqli_query($Con, "SELECT DISTINCT `a`.`head_id`, `b`.`FeesHeadName` FROM `fees_student` AS `a` LEFT JOIN `Fees_Head` AS `b` ON (`a`.`head_id`=`b`.`head_id`) WHERE `a`.`sadmission`='$sadmission' AND `a`.`fy`='$CurrentFY' AND `a`.`status`='1' AND `b`.`head_id`!='101' ORDER BY `b`.`head_priority`");

// Fetch fee payment history
$sqlfeehistory = mysqli_query($Con, "SELECT `receipt_no`, `cr_amnt`, DATE_FORMAT(`datetime`, '%d-%m-%Y') AS `date`, `cheque_status`, `dr_amnt`, `ChequeBounceRemark` FROM `fees` WHERE `sadmission`='$sadmission' AND `status`='1' AND `FinancialYear`='$CurrentFY' AND `cheque_status` IN ('Clear', 'Bounce', 'Online')");

// Function to calculate bounce fees
function getBounceFee($class, $adm, $year)
{
    $sqlbouncefee = mysqli_query($Con, "SELECT `Bounce_charge` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$class'");
    $rsbouncefee = mysqli_fetch_assoc($sqlbouncefee);
    $Bounce_charge = $rsbouncefee['Bounce_charge'];

    $bounce_count = 0;
    $sqlchkstatus = mysqli_query($Con, "SELECT `cheque_status` FROM `fees` WHERE `sadmission`='$adm' ORDER BY `created_at`");
    while ($rschkstatus = mysqli_fetch_assoc($sqlchkstatus)) {
        $cheque_status = $rschkstatus['cheque_status'];

        if ($cheque_status == 'Bounce') {
            $bounce_count++;
        }

        if ($bounce_count > 0 && $cheque_status == 'Clear') {
            $bounce_count = 0;
        }
    }

    $bounce_amt = $Bounce_charge * $bounce_count;

    return $bounce_amt;
}

// Function to calculate late fees
function fnlLateFee($month, $class, $adm, $year)
{
    $sqllatefee = mysqli_query($Con, "SELECT `last_fees_start_date`, `LastFee_date_1`, `LastFee_date_2`, `LastFee_date_3`, `Late_fees`, `Late_fees_1`, `Late_fees_2`, `Late_fees_3` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$class' AND `Month`='$month' AND `status`='1'");
    $rslatefee = mysqli_fetch_assoc($sqllatefee);

    $Dt1 = $rslatefee['last_fees_start_date'];
    $LastFee_date_1 = $rslatefee['LastFee_date_1'];
    $LastFee_date_2 = $rslatefee['LastFee_date_2'];
    $LastFee_date_3 = $rslatefee['LastFee_date_3'];
    $Late_fees = $rslatefee['Late_fees'];
    $LateFee_1 = $rslatefee['Late_fees_1'];
    $LateFee_2 = $rslatefee['Late_fees_2'];
    $LateFee_3 = $rslatefee['Late_fees_3'];

    $LateFee = 0;

    $now = strtotime(date('Y-m-d'));
    $your_date = strtotime($Dt1);
    $datediff = $now - $your_date;
    if ($datediff > 0)
        $TotalLateDays = floor($datediff / (60 * 60 * 24));
    else
        $TotalLateDays = 0;

    $LateFee += $TotalLateDays * $Late_fees;

    $todaydate = date('Y-m-d');

    $sqlfeechk = mysqli_query($Con, "SELECT `amount` FROM `fees_latefee_adjust` WHERE `sadmission`='$adm' AND `financialyear`='$year' AND `date`='$todaydate' AND `Month`='$month'");
    $rsfeechk = mysqli_fetch_assoc($sqlfeechk);
    $fee_late = $rsfeechk['amount'];

    if ($LateFee > 0) {
        if ($LateFee > $fee_late) {
            $LateFee = $LateFee - $fee_late;
        } else {
            $LateFee = 0;
        }
    }

    return $LateFee;
}

// Calculate bounce fees
$bounce_fee = getBounceFee($masterclass, $sadmission, $CurrentFY);

// Handle Fee Payment Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay_fees'])) {
    $selected_months = $_POST['selected_months']; // Array of selected months

    if (empty($selected_months)) {
        echo "<script>alert('Please select at least one month to pay.'); window.location.href='';</script>";
        exit();
    }

    // Here, integrate with a payment gateway (e.g., PayU)
    // For simplicity, we'll assume payment is successful

    // Process each selected month
    foreach ($selected_months as $month) {
        // Fetch fee details for the month
        $sqlFee = mysqli_query($Con, "SELECT * FROM `fees_student` WHERE `sadmission`='$sadmission' AND `Month`='$month' AND `fy`='$CurrentFY' AND `status`='1'");
        while ($rsFee = mysqli_fetch_assoc($sqlFee)) {
            $head_id = $rsFee['head_id'];
            $dr_amnt = $rsFee['dr_amnt'];
            $cr_amnt = $rsFee['cr_amnt'];
            $pre_dr_amnt = $rsFee['pre_dr_amnt'];
            $pre_cr_amnt = $rsFee['pre_cr_amnt'];

            // Calculate balance
            $balance = ($dr_amnt + $pre_dr_amnt) - ($cr_amnt + $pre_cr_amnt);

            if ($balance > 0) {
                // Update fees_student to mark as paid
                mysqli_query($Con, "UPDATE `fees_student` SET `cr_amnt`=`cr_amnt` + '$balance' WHERE `sadmission`='$sadmission' AND `Month`='$month' AND `fy`='$CurrentFY' AND `head_id`='$head_id' AND `status`='1'");

                // Insert into payments_history
                $receipt_id = 'REC' . time() . rand(1000, 9999);
                $payment_date = date('Y-m-d');
                mysqli_query($Con, "INSERT INTO `payments_history` (`month_range`, `amount`, `date`, `receipt_id`) VALUES ('$month', '$balance', '$payment_date', '$receipt_id')");

                // Insert into fees table for tracking
                $payment_mode = 'Online'; // This should be fetched from payment gateway
                mysqli_query($Con, "INSERT INTO `fees` (`sadmission`, `cr_amnt`, `dr_amnt`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `datetime`, `status`) VALUES ('$sadmission', '$balance', '0', '$CurrentFY', 'Q1', '$month', '$payment_date', '$receipt_id', '$payment_mode', NOW(), '1')");
            }
        }
    }

    // Redirect or notify user
    echo "<script>alert('Payment successful.'); window.location.href='';</script>";
    exit();
}

// Handle Challan Generation Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_challan'])) {
    $selected_year = $_POST['challan_year'];
    $selected_months = $_POST['selected_months']; // Array of selected months

    // Validate inputs
    if (empty($selected_months)) {
        echo "<script>alert('Please select at least one month to generate challan.'); window.location.href='';</script>";
        exit();
    }

    // Insert into challan table (Assuming a new table 'challan' exists)
    foreach ($selected_months as $month) {
        // Generate a unique challan ID
        $challan_id = 'CH' . time() . rand(1000, 9999);

        // Insert challan details
        $insert_challan = mysqli_query($Con, "INSERT INTO `challan` (`challan_id`, `sadmission`, `month`, `year`, `created_at`) VALUES ('$challan_id', '$sadmission', '$month', '$selected_year', NOW())");

        // Optionally, update fee status or perform other actions
    }

    // Redirect or notify user
    echo "<script>alert('Challan generated successfully.'); window.location.href='';</script>";
    exit();
}

// Fetch miscellaneous fees
$miscFees = [];
$sqlMiscFees = mysqli_query($Con, "SELECT `id`, `fee_head`, `amount`, `fine_excess`, `quarter`, `date`, `status` FROM `miscellaneous_fees` WHERE `sadmission`='$sadmission' AND `status`='Due'");
while ($row = mysqli_fetch_assoc($sqlMiscFees)) {
    $miscFees[] = $row;
}

// Function to fetch fee status counts
function getFeeStatusCounts($sadmission, $CurrentFY, $masterclass)
{
    $paid = 0;
    $due = 0;
    $balance = 0;

    // Calculate Paid fees
    $sqlPaid = mysqli_query($Con, "SELECT COUNT(DISTINCT `FeeMonth`) AS `paid` FROM `fees` WHERE `sadmission`='$sadmission' AND `FinancialYear`='$CurrentFY' AND `cheque_status`='Clear'");
    $rsPaid = mysqli_fetch_assoc($sqlPaid);
    $paid = $rsPaid['paid'];

    // Calculate Total fees
    $sqlTotal = mysqli_query($Con, "SELECT COUNT(DISTINCT `Month`) AS `total` FROM `fees_structure` WHERE `Class`='$masterclass'");
    $rsTotal = mysqli_fetch_assoc($sqlTotal);
    $total_fees = $rsTotal['total'];

    $due = $total_fees - $paid;

    // For simplicity, balance is considered same as due
    $balance = $due;

    return ['paid' => $paid, 'due' => $due, 'balance' => $balance];
}

// Fetch fee status counts
$feeStatusCounts = getFeeStatusCounts($sadmission, $CurrentFY, $masterclass);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 4.5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .text-algn {
            text-align: right;
        }
        .checkbox-custom {
            transform: scale(1.5);
            margin-right: 10px;
        }
        .btn-custom {
            min-width: 100px;
        }
        /* Additional custom styles */
        .table th, .table td {
            vertical-align: middle;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header Section -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Fee Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Add more navigation items if needed -->
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">My Fees</a>
                    <!-- Add more sidebar items if needed -->
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Fee Structure and Payment History -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-wallet"></i> My Fees</h4>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#challan_modal">
                            <i class="fas fa-print"></i> Generate Challan
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Fee Structure Section -->
                            <div class="col-md-6 mb-4">
                                <h5>Fee Structure</h5>
                                <form id="feeForm" method="post" action="">
                                    <input type="hidden" name="admission_no" value="<?= $sadmission; ?>">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Month</th>
                                                    <th>Amount</th>
                                                    <th>Late Fee</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_payable = 0;
                                                $cnt = 1;

                                                mysql_data_seek($sqlMonth, 0);
                                                while ($rsMonth = mysqli_fetch_assoc($sqlMonth)) {
                                                    $Month = $rsMonth['Month'];
                                                    $Quarter = $rsMonth['Quarter'];

                                                    // Fetch fee details for the month
                                                    $sqlFee = mysqli_query($Con, "SELECT SUM(`dr_amnt`) AS `total_dr`, SUM(`cr_amnt`) AS `total_cr` FROM `fees_structure` WHERE `sadmission`='$sadmission' AND `month`='$Month'");
                                                    $rsFee = mysqli_fetch_assoc($sqlFee);
                                                    $total_dr = $rsFee['total_dr'] ? $rsFee['total_dr'] : 0;
                                                    $total_cr = $rsFee['total_cr'] ? $rsFee['total_cr'] : 0;
                                                    $balance = $total_dr - $total_cr;

                                                    // Calculate late fee
                                                    $lateFee = fnlLateFee($Month, $masterclass, $sadmission, $CurrentFY);

                                                    // If balance is zero, no fee due
                                                    $is_due = $balance > 0 ? true : false;

                                                    // Update total payable
                                                    if ($is_due) {
                                                        $total_payable += ($balance + $lateFee);
                                                    }
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php if ($is_due) { ?>
                                                                <input type="checkbox" class="select-month checkbox-custom" name="selected_months[]" value="<?= $Month; ?>" checked>
                                                            <?php } else { ?>
                                                                <input type="checkbox" class="select-month checkbox-custom" name="selected_months[]" value="<?= $Month; ?>" disabled>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?= $Month; ?>
                                                            &nbsp;<i class="fas fa-file-pdf text-primary cursor-pointer" title="View <?= $Month; ?> Challan" onclick='monthwisechallan("<?= $sadmission; ?>","<?= $masterclass; ?>","<?= $CurrentFY; ?>","<?= $Month; ?>")'></i>
                                                        </td>
                                                        <td>₹<?= number_format($total_dr, 2); ?></td>
                                                        <td>₹<?= number_format($lateFee, 2); ?></td>
                                                        <td>₹<?= number_format(($is_due ? ($balance + $lateFee) : 0), 2); ?></td>
                                                    </tr>
                                                <?php
                                                    $cnt++;
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                                    <td><strong>₹<span id="total_payable"><?= number_format($total_payable, 2); ?></span></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>Bounce Charge: ₹<?= number_format($bounce_fee, 2); ?></h5>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button type="submit" name="pay_fees" class="btn btn-success btn-custom">
                                                Pay ₹<?= number_format($total_payable, 2); ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Payment History Section -->
                            <div class="col-md-6 mb-4">
                                <h5>Payment History</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Month</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Receipt ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($sqlfeehistory) > 0) {
                                                while ($rsfeehistory = mysqli_fetch_assoc($sqlfeehistory)) {
                                                    $receipt_no = $rsfeehistory['receipt_no'];
                                                    $paid_amount = $rsfeehistory['cr_amnt'] ? $rsfeehistory['cr_amnt'] : $rsfeehistory['dr_amnt'];
                                                    $feedate = $rsfeehistory['date'];
                                                    $cheque_status = $rsfeehistory['cheque_status'];
                                                    $remark = $rsfeehistory['ChequeBounceRemark'];

                                                    // Fetch associated months for the receipt
                                                    $sqlmonth_his = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `payments_history` WHERE `receipt_id`='$receipt_no' ORDER BY `id` LIMIT 1");
                                                    $rsmonth_his = mysqli_fetch_assoc($sqlmonth_his);
                                                    $month_h = $rsmonth_his['Month'];

                                                    $sqlmonth_his1 = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `payments_history` WHERE `receipt_id`='$receipt_no' ORDER BY `id` DESC LIMIT 1");
                                                    $rsmonth_his1 = mysqli_fetch_assoc($sqlmonth_his1);
                                                    $month_h1 = $rsmonth_his1['Month'];

                                                    if ($month_h == $month_h1) {
                                                        $month_his = $month_h;
                                                    } else {
                                                        $month_his = $month_h . " to " . $month_h1;
                                                    }

                                                    // Format amount
                                                    $amt = number_format($paid_amount, 2);
                                            ?>
                                                    <tr>
                                                        <td><?= $month_his; ?></td>
                                                        <td>₹<?= $amt; ?></td>
                                                        <td><?= $feedate; ?></td>
                                                        <td>
                                                            <?= $receipt_no; ?>
                                                            <i class="fas fa-print text-primary ml-2 cursor-pointer" title="Print Receipt" onclick="open_fee_receipt('<?= $sadmission; ?>','<?= $receipt_no; ?>')"></i>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No payment history found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Status Chart -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Fee Status</h5>
                                <canvas id="feeStatusChart" width="400" height="150"></canvas>
                            </div>
                        </div>

                        <!-- Miscellaneous Fee Payment Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Miscellaneous Fees</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Fee Head</th>
                                                <th>Amount</th>
                                                <th>Fine/Excess</th>
                                                <th>Quarter</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($miscFees)) {
                                                foreach ($miscFees as $misc) {
                                                    $misc_head = $misc['fee_head'];
                                                    $misc_amount = number_format($misc['amount'], 2);
                                                    $misc_fine = number_format($misc['fine_excess'], 2);
                                                    $misc_quarter = $misc['quarter'];
                                                    $misc_date = $misc['date'];
                                                    $misc_status = ($misc['status'] == 'Due') ? 'Due' : 'Complete';
                                            ?>
                                                    <tr>
                                                        <td><?= $misc_head; ?></td>
                                                        <td>₹<?= $misc_amount; ?></td>
                                                        <td>₹<?= $misc_fine; ?></td>
                                                        <td><?= $misc_quarter; ?></td>
                                                        <td><?= $misc_date; ?></td>
                                                        <td><?= $misc_status; ?></td>
                                                        <td>
                                                            <?php if ($misc_status == 'Due') { ?>
                                                                <button class="btn btn-success btn-sm" onclick="payMiscFee('<?= $misc['id']; ?>')">Pay Now</button>
                                                            <?php } ?>
                                                            <?php if ($misc_status == 'Complete') { ?>
                                                                <i class="fas fa-print text-primary ml-2 cursor-pointer" title="Print Receipt" onclick="printMiscReceipt('<?= $sadmission; ?>','<?= $misc['id']; ?>')"></i>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No miscellaneous fees found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div> <!-- End of Card Body -->
                </div> <!-- End of Card -->

                <!-- Important Notices or Additional Information -->
                <div class="alert alert-warning">
                    <strong>Note:</strong> Please wait for 48 hrs before making the next transaction if the fee amount is already deducted from your Credit Card/Debit Card/Net Banking or any other payment mode.
                </div>
                <div class="mb-4">
                    <p><a href="privacy.php">Click here to read Terms & Conditions and Privacy Policy</a> for online fees payment.</p>
                    <p>- Please call the School Reception for further details.</p>
                </div>
            </div> <!-- End of Main Content -->
        </div> <!-- End of Row -->
    </div> <!-- End of Container -->

    <!-- Challan Generation Modal -->
    <div class="modal fade" id="challan_modal" tabindex="-1" aria-labelledby="challanModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <form method="post" id="challanForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="challanModalLabel">Generate Challan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <input type="hidden" name="challan_adm_no" value="<?= $sadmission; ?>">
                        <input type="hidden" name="challan_class" value="<?= $masterclass; ?>">

                        <div class="form-group">
                            <label for="challan_year">Year</label>
                            <select class="form-control" id="challan_year" name="challan_year" required>
                                <option value="<?= $CurrentFY; ?>"><?= $CurrentFY; ?></option>
                                <!-- Add more options if necessary -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="challan_months">Select Months</label>
                            <div id="challan_months">
                                <?php
                                mysql_data_seek($sqlMonth, 0);
                                while ($row1 = mysqli_fetch_assoc($sqlMonth)) {
                                    $Month1 = $row1['Month'];
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input selectmonthchallan" type="checkbox" value="<?= $Month1; ?>" id="challan_month_<?= $Month1; ?>" name="selected_months[]">
                                        <label class="form-check-label" for="challan_month_<?= $Month1; ?>">
                                            <?= $Month1; ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="generate_challan" class="btn btn-primary">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipt & Challan Forms (Hidden) -->
    <form method="post" name="frmfeereceipt" id="frmfeereceipt" target="_blank" action="">
        <input type="hidden" name="receipt_no" id="receipt_no">
        <input type="hidden" name="adm_no" id="adm_no">
    </form>

    <form method="post" name="frmdisplaychallan" id="frmdisplaychallan" target="_blank" action="">
        <input type="hidden" name="master_class" id="master_class">
        <input type="hidden" name="sadm_no" id="sadm_no">
        <input type="hidden" name="year" id="year">
        <input type="hidden" name="month" id="month">
    </form>

    <form method="post" name="frmmiscreceipt" id="frmmiscreceipt" target="_blank" action="">
        <input type="hidden" name="misc_receipt_no" id="misc_receipt_no">
        <input type="hidden" name="misc_adm_no" id="misc_adm_no">
    </form>

    <!-- Footer Section -->
    <footer class="bg-primary text-white text-center py-3 mt-4">
        &copy; <?= date('Y'); ?> Your School Name. All Rights Reserved.
    </footer>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Chart.js for Pie Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Function to calculate total payable based on selected months
            $('.select-month').change(function() {
                let total = 0;
                $('.select-month:checked').each(function() {
                    let row = $(this).closest('tr');
                    let totalCol = row.find('td:eq(4)').text().replace('₹', '');
                    total += parseFloat(totalCol);
                });
                $('#total_payable').text(total.toFixed(2));
            });

            // Initialize total payable on page load
            let initial_total = 0;
            $('.select-month:checked').each(function() {
                let row = $(this).closest('tr');
                let totalCol = row.find('td:eq(4)').text().replace('₹', '');
                initial_total += parseFloat(totalCol);
            });
            $('#total_payable').text(initial_total.toFixed(2));

            // Handle Challan Form Submission via AJAX
            $('#challanForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "", // Current page
                    data: $(this).serialize(),
                    success: function(response) {
                        alert('Challan generated successfully.');
                        $('#challan_modal').modal('hide');
                        location.reload();
                    },
                    error: function() {
                        alert('Error generating challan. Please try again.');
                    }
                });
            });
        });

        // Function to open fee receipt
        function open_fee_receipt(adm_no, receipt_no) {
            $('#receipt_no').val(receipt_no);
            $('#adm_no').val(adm_no);
            $('#frmfeereceipt').attr('action', 'fee_receipt.php'); // Change to your receipt generation script
            $('#frmfeereceipt').submit();
        }

        // Function to open challan
        function monthwisechallan(adm_no, master_class, year, month) {
            $('#master_class').val(master_class);
            $('#sadm_no').val(adm_no);
            $('#year').val(year);
            $('#month').val(month);
            $('#frmdisplaychallan').attr('action', 'display_challan.php'); // Change to your challan display script
            $('#frmdisplaychallan').submit();
        }

        // Function to pay miscellaneous fee
        function payMiscFee(misc_id) {
            // Open a modal or redirect to payment page
            // For simplicity, we'll redirect to a payment page with misc_id
            window.location.href = 'pay_misc_fee.php?misc_id=' + misc_id;
        }

        // Function to print miscellaneous receipt
        function printMiscReceipt(adm_no, misc_id) {
            $('#misc_receipt_no').val(misc_id);
            $('#misc_adm_no').val(adm_no);
            $('#frmmiscreceipt').attr('action', 'misc_receipt.php'); // Change to your misc receipt script
            $('#frmmiscreceipt').submit();
        }

        // Initialize Fee Status Chart
        <?php
        // Calculate Paid, Due, Balance fees
        $paid_fees = $feeStatusCounts['paid'];
        $due_fees = $feeStatusCounts['due'];
        $balance_fees = $feeStatusCounts['balance'];
        ?>
        var ctx = document.getElementById('feeStatusChart').getContext('2d');
        var feeStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Paid', 'Due', 'Balance'],
                datasets: [{
                    data: [<?= $paid_fees; ?>, <?= $due_fees; ?>, <?= $balance_fees; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)', // Green for Paid
                        'rgba(220, 53, 69, 0.7)', // Red for Due
                        'rgba(23, 162, 184, 0.7)'  // Blue for Balance
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
