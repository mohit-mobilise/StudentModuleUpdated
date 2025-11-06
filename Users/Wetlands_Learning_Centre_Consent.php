<?php
session_start();
include '../connection.php';
include '../AppConf.php';
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	
	$sadmission=$_SESSION['userid'];
	$rs=mysqli_query($Con, "select `sname`,`sclass`,`srollno` from `student_master` where `sadmission`='$sadmission'");
	$row=mysqli_fetch_row($rs);
	$sname=$row[0];
	$StudentClass=$row[1];
	$StudentRollNo=$row[2];
	
	if($sadmission == "")
	{
		echo "<br><br><center><b>Session Expired!<br>click <a href='http://dpsfsis.com/'>here</a> to login again!";
		exit();
	}
?>
<?php
if(isset($_POST['submit']))
{
   $Consent=$_POST['D1'];
   $rsChk=mysqli_query($Con, "select * from Wetlands_Learning_Centre_Consent where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="<center><b>Already Submitted!";
   }
   else
   {
	   mysqli_query($Con, "INSERT INTO Wetlands_Learning_Centre_Consent (`sadmission`, `sname`, `sclass`, `Consent`)VALUES('$sadmission','$sname','$StudentClass','$Consent')");
	   $Msg="<center><b>Submitted Successfully!";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip to Pradhanmantri Sanghralaya - Class VIII</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        h2 {
            color: #152A06;
            font-family: "Trebuchet MS";
            font-size: 24px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            margin-bottom: 0px;
        }
        .text-decoration-underline {
            text-decoration: none !important;
            color: #fcfffa;
            font-family: "Trebuchet MS";
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            width: 100%;
            background: #26a69ab5;
            border: 1px solid #FFF;
            padding: 15px;
        }
        body {
            font-family: "Trebuchet MS";
        }
        .btn-primary {
            background-color: #26A69A;
            border-color: #26A69A;
        }
        .btn-primary:hover {
            background-color: #1e8c82;
            border-color: #1e8c82;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="top-header d-flex justify-content-between">
                <div class="left_div">
                    <div class="head-logo">
                        <img src="<?php echo $SchoolLogo; ?>" class="img-fluid" alt="logo" style="height: 100px; width: 400px;" />
                    </div>
                </div>
                <div class="right_div">
                    <div class="address-info">
                        <p>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <?php echo $SchoolAddress; ?>
                            <br>
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <?php echo $SchoolPhoneNo; ?>
                            <br>
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                            <?php echo $SchoolEmailId; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-5 pt-2">
        <?php if($Msg !='') { ?>
            <div class="alert alert-info text-center">
                <?php echo $Msg; ?>
            </div>
        <?php } else { ?>
        <form method="POST" action="">
            <div class="form-section">
                <div class="form-head text-center">
                    <h2>Trip to Pradhanmantri Sanghralaya - Class VIII</h2>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Student Name</label>
                                            <input type="text" class="form-control" value="<?php echo $sname; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Class</label>
                                            <input type="text" class="form-control" value="<?php echo $StudentClass; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Roll No</label>
                                            <input type="text" class="form-control" value="<?php echo $StudentRollNo; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Consent for Trip to Pradhanmantri Sanghralaya <span class="text-danger">*</span></label>
                                            <select class="form-select" name="D1" required style="width: 200px; margin: 0 auto;">
                                                <option value="Yes" selected>Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>