<?php
   // Include security helpers
   require_once __DIR__ . '/includes/security_helpers.php';
   
   // Configure secure session
   configure_secure_session();
   
   require '../connection.php';
   require '../AppConf.php';

   // Create id_card_Consent table if it doesn't exist
   $create_table_query = "CREATE TABLE IF NOT EXISTS id_card_Consent (
       srno INT AUTO_INCREMENT PRIMARY KEY,
       sadmission VARCHAR(50),
       FatherMobileNo VARCHAR(20),
       MotherMobileNo VARCHAR(20),
       Address TEXT,
       BloodGroup VARCHAR(10),
       ProfilePhoto VARCHAR(255),
       status VARCHAR(20),
       routeno VARCHAR(50),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   )";
   mysqli_query($Con, $create_table_query);

   // Check session
   $StudentId = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
   $StudentClass = isset($_SESSION['StudentClass']) ? $_SESSION['StudentClass'] : '';
   
   if($StudentId == "") {
       echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Click <a href='../../Users_new/index.php'>here</a> to login again");
       exit();
   }
   
   // Fetch student data from student_master
   // Use prepared statement to prevent SQL injection
   $StudentId_clean = validate_input($StudentId ?? '', 'string', 50);
   $stmt = mysqli_prepare($Con, "SELECT sm.`sname`, sm.`sadmission`, sm.`sclass`, sm.`DOB`, sm.`BloodGroup`, sm.`Address`, sm.`sfathername`, sm.`FatherMobileNo`, sm.`MotherName`, sm.`MotherMobile`, sm.`routeno`, tbr.`BusRouteName` FROM `student_master` sm LEFT JOIN `Transport_Bus_Routes` tbr ON sm.`routeno` = tbr.`srno` WHERE sm.`sadmission`=?");
   if ($stmt) {
       mysqli_stmt_bind_param($stmt, "s", $StudentId_clean);
       mysqli_stmt_execute($stmt);
       $sql = mysqli_stmt_get_result($stmt);
   } else {
       error_log('ID Card Form query error: ' . mysqli_error($Con));
       $sql = false;
   }
   
   // Initialize variables
   $name = $sadmission = $sclass = $dob = $bloodgroup = $address = $fathername = $fathermobile = $mothername = $mothermobile = $routeno = $bus_route_name = '';
   
   if($sql && mysqli_num_rows($sql) > 0) {
       $row = mysqli_fetch_array($sql);
       $name = $row[0];
       $sadmission = $row[1];
       $sclass = $row[2];
       $dob = $row[3];
       $bloodgroup = $row[4];
       $address = $row[5];
       $fathername = $row[6];
       $fathermobile = $row[7];
       $mothername = $row[8];
       $mothermobile = $row[9];
       $original_routeno = $row[10]; // Store original routeno
       $bus_route_name = $row[11]; // BusRouteName from Transport_Bus_Routes
   }

   // Fetch distinct bus routes for demonstration
   $distinct_bus_routes = [];
   $route_query = mysqli_query($Con, "SELECT DISTINCT BusRouteName FROM Transport_Bus_Routes");
   if ($route_query) {
       while ($route_row = mysqli_fetch_assoc($route_query)) {
           $distinct_bus_routes[] = $route_row['BusRouteName'];
       }
   } else {
       // Log error or display to admin, not directly to user for security
       error_log("Error fetching distinct bus routes: " . mysqli_error($Con));
   }
   // You can then use $distinct_bus_routes array later in your HTML to populate a dropdown, etc.

   // Check consent status
   $consent_status = '';
   $consent_remarks = '';
   // Use prepared statement for consent check
   $stmt_consent = mysqli_prepare($Con, "SELECT Consent_status, Consent_remarks FROM student_id_card WHERE sadmission=?");
   if ($stmt_consent) {
       mysqli_stmt_bind_param($stmt_consent, "s", $StudentId_clean);
       mysqli_stmt_execute($stmt_consent);
       $consent_check = mysqli_stmt_get_result($stmt_consent);
   } else {
       $consent_check = false;
   }
   if($consent_check && mysqli_num_rows($consent_check) > 0) {
       $consent_row = mysqli_fetch_array($consent_check);
       $consent_status = $consent_row['Consent_status'];
       $consent_remarks = $consent_row['Consent_remarks'];
   }
   
   // Handle form submission
   if(isset($_POST['submit'])) {
       $remarks = ''; // Empty remarks for approve
       $consent_status = 'approved';
       
       // Copy existing photo to new path
       $source_photo_path = "../Admin/StudentManagement/idcard_photo/" . $StudentId . ".jpg";
       $source_photo_path_png = "../Admin/StudentManagement/idcard_photo/" . $StudentId . ".png";
       $target_dir = "ID_Card_Consent_Photo/";
       $target_file = $target_dir . $StudentId . ".jpg";
       
       // Create directory if it doesn't exist
       if (!file_exists($target_dir)) {
           if (!mkdir($target_dir, 0777, true)) {
               echo "<script>alert('Error creating upload directory. Please contact administrator.');</script>";
               exit();
           }
       }
       
       // Try to copy the photo
       $photo_copied = false;
       if(file_exists($source_photo_path)) {
           if(copy($source_photo_path, $target_file)) {
               $photo_copied = true;
           }
       } else if(file_exists($source_photo_path_png)) {
           if(copy($source_photo_path_png, $target_file)) {
               $photo_copied = true;
           }
       }
       
       // Check if record exists - Use prepared statement
       $stmt_check = mysqli_prepare($Con, "SELECT * FROM student_id_card WHERE sadmission=?");
       $check_result = false;
       if ($stmt_check) {
           mysqli_stmt_bind_param($stmt_check, "s", $StudentId_clean);
           mysqli_stmt_execute($stmt_check);
           $check_result = mysqli_stmt_get_result($stmt_check);
       }
       
       // Validate and sanitize all inputs
       $name_clean = validate_input($name, 'string', 100);
       $sclass_clean = validate_input($sclass, 'string', 20);
       $dob_clean = validate_input($dob, 'string', 20);
       $bloodgroup_clean = validate_input($bloodgroup, 'string', 10);
       $routeno_clean = validate_input($routeno, 'string', 20);
       $fathername_clean = validate_input($fathername, 'string', 100);
       $fathermobile_clean = validate_input($fathermobile, 'string', 20);
       $mothername_clean = validate_input($mothername, 'string', 100);
       $mothermobile_clean = validate_input($mothermobile, 'string', 20);
       $address_clean = validate_input($address, 'string', 500);
       $consent_status_clean = validate_input($consent_status, 'string', 20);
       $remarks_clean = validate_input($remarks, 'string', 500);
       $profile_photo_clean = validate_input($StudentId_clean . ".jpg", 'string', 100);
       
       if($check_result && mysqli_num_rows($check_result) > 0) {
           // Update existing record - Use prepared statement
           $stmt_update = mysqli_prepare($Con, "UPDATE student_id_card SET sname=?, sclass=?, DOB=?, BloodGroup=?, routeno=?, sfathername=?, FatherMobileNo=?, MotherName=?, MotherMobileNo=?, Address=?, Consent_status=?, Consent_remarks=?, ProfilePhoto=? WHERE sadmission=?");
           if ($stmt_update) {
               mysqli_stmt_bind_param($stmt_update, "sssssssssssss", $name_clean, $sclass_clean, $dob_clean, $bloodgroup_clean, $routeno_clean, $fathername_clean, $fathermobile_clean, $mothername_clean, $mothermobile_clean, $address_clean, $consent_status_clean, $remarks_clean, $profile_photo_clean, $StudentId_clean);
               mysqli_stmt_execute($stmt_update);
               mysqli_stmt_close($stmt_update);
           }
       } else {
           // Insert new record - Use prepared statement
           $stmt_insert = mysqli_prepare($Con, "INSERT INTO student_id_card (sadmission, sname, sclass, DOB, BloodGroup, routeno, sfathername, FatherMobileNo, MotherName, MotherMobileNo, Address, Consent_status, Consent_remarks, ProfilePhoto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
           if ($stmt_insert) {
               mysqli_stmt_bind_param($stmt_insert, "ssssssssssssss", $StudentId_clean, $name_clean, $sclass_clean, $dob_clean, $bloodgroup_clean, $routeno_clean, $fathername_clean, $fathermobile_clean, $mothername_clean, $mothermobile_clean, $address_clean, $consent_status_clean, $remarks_clean, $profile_photo_clean);
               mysqli_stmt_execute($stmt_insert);
               mysqli_stmt_close($stmt_insert);
           }
       }
       
       if(!$photo_copied) {
           echo "<script>alert('Warning: Could not copy the photo. Please contact administrator.');</script>";
       }
       
       echo "<script>alert('Thank you for your consent!'); window.location.href='landing.php';</script>";
       exit();
   }
   
   if(isset($_POST['reject'])) {
       if(empty($_POST['remarks'])) {
           echo "<script>alert('Please provide remarks for rejection');</script>";
       } else {
           $remarks = mysqli_real_escape_string($Con, $_POST['remarks']);
           $consent_status = 'rejected';
           
           // Handle photo upload for rejection
           $profile_photo = $StudentId . ".jpg"; // Default value
           if(isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) {
               $target_dir = "ID_Card_Consent_Photo/";
               $file_extension = strtolower(pathinfo($_FILES["student_photo"]["name"], PATHINFO_EXTENSION));
               $new_filename = $StudentId . "." . $file_extension;
               $target_file = $target_dir . $new_filename;
               
               // Create directory if it doesn't exist
               if (!file_exists($target_dir)) {
                   if (!mkdir($target_dir, 0777, true)) {
                       echo "<script>alert('Error creating upload directory. Please contact administrator.');</script>";
                       exit();
                   }
               }
               
               // Use security helper function for file validation
               $upload_result = validate_file_upload($_FILES["student_photo"], ['image/jpeg', 'image/png', 'image/jpg'], 1048576); // 1MB max
               
               if (!$upload_result['valid']) {
                   echo "<script>alert('" . htmlspecialchars($upload_result['error'], ENT_QUOTES, 'UTF-8') . "');</script>";
                   exit();
               }
               
               // Generate secure filename to prevent path traversal
               $safe_filename = generate_secure_filename($_FILES["student_photo"]["name"], $StudentId . "_");
               $target_file = $target_dir . $safe_filename;
               
               // Validate path doesn't contain directory traversal
               $real_path = realpath(dirname($target_file));
               $base_path = realpath($target_dir);
               if (!$base_path || strpos($real_path, $base_path) !== 0) {
                   echo "<script>alert('Invalid upload path. Please contact administrator.');</script>";
                   exit();
               }
               
               // Try to upload file
               if(move_uploaded_file($_FILES["student_photo"]["tmp_name"], $target_file)) {
                   $profile_photo = $safe_filename;
               } else {
                   echo "<script>alert('Error uploading photo. Please try again.');</script>";
                   exit();
               }
           }
           
           // Get edited values
           $edited_name = mysqli_real_escape_string($Con, $_POST['edited_name']);
           $edited_dob = mysqli_real_escape_string($Con, $_POST['edited_dob']);
           $edited_bloodgroup = mysqli_real_escape_string($Con, $_POST['edited_bloodgroup']);
           $edited_fathername = mysqli_real_escape_string($Con, $_POST['edited_fathername']);
           $edited_fathermobile = mysqli_real_escape_string($Con, $_POST['edited_fathermobile']);
           $edited_mothername = mysqli_real_escape_string($Con, $_POST['edited_mothername']);
           $edited_mothermobile = mysqli_real_escape_string($Con, $_POST['edited_mothermobile']);
           $edited_address = mysqli_real_escape_string($Con, $_POST['edited_address']);
           
           // Check which fields were changed
           $changed_fields = array();
           if($edited_bloodgroup != $bloodgroup) $changed_fields['BloodGroup'] = $edited_bloodgroup;
           if($edited_fathermobile != $fathermobile) $changed_fields['FatherMobileNo'] = $edited_fathermobile;
           if($edited_mothermobile != $mothermobile) $changed_fields['MotherMobileNo'] = $edited_mothermobile;
           if($edited_address != $address) $changed_fields['Address'] = $edited_address;
           if(isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) $changed_fields['ProfilePhoto'] = $profile_photo;
           
           // Insert changed fields into id_card_Consent table
           if(!empty($changed_fields)) {
               $fields = array_keys($changed_fields);
               $values = array_values($changed_fields);
               
               $insert_fields = implode(', ', $fields);
               $insert_values = "'" . implode("', '", $values) . "'";
               
               $insert_query = "INSERT INTO id_card_Consent (sadmission, " . $insert_fields . ", status) 
                              VALUES ('$StudentId', " . $insert_values . ", 'rejected')";
               mysqli_query($Con, $insert_query);
           }
           
           // Update student_id_card table with original student_master data for unchanged fields
           $check_query = "SELECT * FROM student_id_card WHERE sadmission = '$StudentId'";
           $check_result = mysqli_query($Con, $check_query);
           
           if($check_result && mysqli_num_rows($check_result) > 0) {
               // Update existing record - use original student_master data for unchanged fields
               $update_query = "UPDATE student_id_card SET 
                   sname = '".mysqli_real_escape_string($Con, $name)."',
                   sclass = '".mysqli_real_escape_string($Con, $sclass)."',
                   DOB = '".mysqli_real_escape_string($Con, $dob)."',
                   BloodGroup = '".mysqli_real_escape_string($Con, $bloodgroup)."',
                   routeno = '".mysqli_real_escape_string($Con, $routeno)."',
                   sfathername = '".mysqli_real_escape_string($Con, $fathername)."',
                   FatherMobileNo = '".mysqli_real_escape_string($Con, $fathermobile)."',
                   MotherName = '".mysqli_real_escape_string($Con, $mothername)."',
                   MotherMobileNo = '".mysqli_real_escape_string($Con, $mothermobile)."',
                   Address = '".mysqli_real_escape_string($Con, $address)."',
                   Consent_status = '$consent_status',
                   Consent_remarks = '$remarks',
                   ProfilePhoto = '".mysqli_real_escape_string($Con, $StudentId).".jpg'
                   WHERE sadmission = '$StudentId'";
               mysqli_query($Con, $update_query);
           } else {
               // Insert new record with original student_master data
               $insert_query = "INSERT INTO student_id_card 
                   (sadmission, sname, sclass, DOB, BloodGroup, routeno, sfathername, FatherMobileNo, MotherName, MotherMobileNo, Address, Consent_status, Consent_remarks, ProfilePhoto) 
                   VALUES 
                   ('$StudentId', '".mysqli_real_escape_string($Con, $name)."', '".mysqli_real_escape_string($Con, $sclass)."', '".mysqli_real_escape_string($Con, $dob)."', '".mysqli_real_escape_string($Con, $bloodgroup)."', '".mysqli_real_escape_string($Con, $routeno)."', '".mysqli_real_escape_string($Con, $fathername)."', '".mysqli_real_escape_string($Con, $fathermobile)."', '".mysqli_real_escape_string($Con, $mothername)."', '".mysqli_real_escape_string($Con, $mothermobile)."', '".mysqli_real_escape_string($Con, $address)."', '$consent_status', '$remarks', '".mysqli_real_escape_string($Con, $StudentId).".jpg')";
               mysqli_query($Con, $insert_query);
           }
           
           echo "<script>alert('Thank you for your feedback!'); window.location.href='landing.php';</script>";
           exit();
       }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ID Card Consent</title>
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
<?php include 'Header/header_new.php'; ?>

<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php'; ?>

    <main class="page-content" style="margin-top:45px;">
        <div class="container-fluid page-border">
            <section class="row mt-3">
                <div class="col-md-12">
                    <div class="card border-rad">
                        <div class="">
                            <h5 style="padding:10px 0 0 20px;"><i class="fas fa-id-card"></i> ID Card Consent Form</h5>
                            <hr style="margin:0;">
                        </div>
                        <div class="card-body">
                            <form name="consent_form" id="consent_form" method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                    <tr>
                                            <td colspan="4" style="text-align: center; padding: 15px;">
                                                <div style="width: 150px; height: 180px; border: 2px solid #ddd; padding: 5px; text-align: center; background: #f8f9fa; margin: 0 auto;">
                                                    <?php
                                                    // Check both lowercase and uppercase admission numbers
                                                    $photo_path_lower = "../Admin/StudentManagement/idcard_photo/" . strtolower($sadmission) . ".jpg";
                                                    $photo_path_upper = "../Admin/StudentManagement/idcard_photo/" . strtoupper($sadmission) . ".jpg";
                                                    $photo_path_png_lower = "../Admin/StudentManagement/idcard_photo/" . strtolower($sadmission) . ".png";
                                                    $photo_path_png_upper = "../Admin/StudentManagement/idcard_photo/" . strtoupper($sadmission) . ".png";
                                                    
                                                    if(file_exists($photo_path_lower)) {
                                                        echo '<img src="' . $photo_path_lower . '" alt="Student Photo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                                                    } else if(file_exists($photo_path_upper)) {
                                                        echo '<img src="' . $photo_path_upper . '" alt="Student Photo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                                                    } else if(file_exists($photo_path_png_lower)) {
                                                        echo '<img src="' . $photo_path_png_lower . '" alt="Student Photo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                                                    } else if(file_exists($photo_path_png_upper)) {
                                                        echo '<img src="' . $photo_path_png_upper . '" alt="Student Photo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                                                    } else {
                                                        echo '<div style="height: 100%; display: flex; align-items: center; justify-content: center;">';
                                                        echo '<span class="text-muted">Photo not available</span>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="15%">Field</th>
                                            <th width="35%">Value</th>
                                            <th width="15%">Field</th>
                                            <th width="35%">Value</th>
                                        </tr>
                                        <tr>
                                            <td>Admission No</td>
                                            <td><?php echo htmlspecialchars($sadmission); ?></td>
                                            <td>Class</td>
                                            <td><?php echo htmlspecialchars($sclass); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Route</td>
                                            <td>
                                                <span class="view-mode"><?php echo htmlspecialchars($bus_route_name); ?></span>
                                                <select name="edited_routeno" class="form-control edit-mode" style="display:none;">
                                                    <option value="">Select Route</option>
                                                    <?php
                                                    foreach ($distinct_bus_routes as $route) {
                                                        $selected = ($route == $bus_route_name) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($route) . '" ' . $selected . '>' . htmlspecialchars($route) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>Student Name</td>
                                            <td>
                                                <span class="view-mode"><?php echo htmlspecialchars($name); ?></span>
                                                <input type="text" name="edited_name" class="form-control edit-mode" style="display:none;" value="<?php echo htmlspecialchars($name); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date of Birth</td>
                                            <td><?php echo htmlspecialchars($dob); ?></td>
                                            <td>Blood Group</td>
                                            <td>
                                                <span class="view-mode"><?php echo htmlspecialchars($bloodgroup); ?></span>
                                                <input type="text" name="edited_bloodgroup" class="form-control edit-mode" style="display:none;" value="<?php echo htmlspecialchars($bloodgroup); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Father's Name</td>
                                            <td><?php echo htmlspecialchars($fathername); ?></td>
                                            <td>Father's Mobile</td>
                                            <td>
                                                <span class="view-mode"><?php echo htmlspecialchars($fathermobile); ?></span>
                                                <input type="text" name="edited_fathermobile" class="form-control edit-mode" style="display:none;" value="<?php echo htmlspecialchars($fathermobile); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mother's Name</td>
                                            <td><?php echo htmlspecialchars($mothername); ?></td>
                                            <td>Mother's Mobile</td>
                                            <td>
                                                <span class="view-mode"><?php echo htmlspecialchars($mothermobile); ?></span>
                                                <input type="text" name="edited_mothermobile" class="form-control edit-mode" style="display:none;" value="<?php echo htmlspecialchars($mothermobile); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td colspan="3">
                                                <span class="view-mode"><?php echo htmlspecialchars($address); ?></span>
                                                <textarea name="edited_address" class="form-control edit-mode" style="display:none;" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <?php if($consent_status == '') { ?>
                                <div class="row mt-3" id="remarksGroup" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"><b>Remarks </b><span class="required">*</span></label>
                                           <textarea name="remarks" class="" rows="4" style="width:100%;"></textarea>
                                                <span class="help-block">Please provide reason for rejection</span>                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <div class="row">
                                       <div class="col-md-offset-3 col-md-12">
                                                    <button type="submit" name="submit" class="btn btn-primary">Approve</button>
                                                    <button type="submit" name="reject" class="btn btn-secondary">Reject</button>
                                        </div>
                                    </div>
                                </div>
                                <?php } else if($consent_status == 'approved') { ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-success">
                                            <strong>Status: <?php echo ucfirst($consent_status); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <strong>Status: <?php echo ucfirst($consent_status); ?></strong>
                                            <?php if($consent_status == 'rejected' && !empty($consent_remarks)) { ?>
                                                <br>Remarks: <?php echo htmlspecialchars($consent_remarks); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if($consent_status == 'rejected') { 
                                    // Fetch updated data from id_card_Consent
                                    $updated_data = mysqli_query($Con, "SELECT * FROM id_card_Consent WHERE sadmission = '$StudentId' ORDER BY srno DESC LIMIT 1");
                                    if($updated_data && mysqli_num_rows($updated_data) > 0) {
                                        $updated = mysqli_fetch_array($updated_data);
                                ?>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="text-primary">Updated Information:</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <?php if(!empty($updated['ProfilePhoto'])) { ?>
                                                <tr>
                                                    <td colspan="2" style="text-align: center; padding: 15px;">
                                                        <div style="width: 150px; height: 180px; border: 2px solid #ddd; padding: 5px; text-align: center; background: #f8f9fa; margin: 0 auto;">
                                                            <?php
                                                            $uploaded_photo_path = "ID_Card_Consent_Photo/" . $updated['ProfilePhoto'];
                                                            if(file_exists($uploaded_photo_path)) {
                                                                echo '<img src="' . $uploaded_photo_path . '" alt="Updated Student Photo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                                                            } else {
                                                                echo '<div style="height: 100%; display: flex; align-items: center; justify-content: center;">';
                                                                echo '<span class="text-muted">Updated photo not available</span>';
                                                                echo '</div>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th width="30%">Field</th>
                                                    <th width="70%">Value</th>
                                                </tr>
                                                <?php if(!empty($updated['BloodGroup'])) { ?>
                                                <tr>
                                                    <td>Blood Group</td>
                                                    <td><?php echo htmlspecialchars($updated['BloodGroup']); ?></td>
                                                </tr>
                                                <?php } ?>
                                                <?php if(!empty($updated['FatherMobileNo'])) { ?>
                                                <tr>
                                                    <td>Father's Mobile</td>
                                                    <td><?php echo htmlspecialchars($updated['FatherMobileNo']); ?></td>
                                                </tr>
                                                <?php } ?>
                                                <?php if(!empty($updated['MotherMobileNo'])) { ?>
                                                <tr>
                                                    <td>Mother's Mobile</td>
                                                    <td><?php echo htmlspecialchars($updated['MotherMobileNo']); ?></td>
                                                </tr>
                                                <?php } ?>
                                                <?php if(!empty($updated['Address'])) { ?>
                                                <tr>
                                                    <td>Address</td>
                                                    <td><?php echo htmlspecialchars($updated['Address']); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php }
                                    }
                                ?>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<div class="modal fade" id="rejectConfirmModal" tabindex="-1" role="dialog" aria-labelledby="rejectConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectConfirmModalLabel">Confirm Rejection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject the ID card information? You will be able to edit the following information if it is incorrect:</p>
                <ul>
                    <li>ID Card Photo</li>
                    <li>Blood Group</li>
                    <li>Address</li>
                    <li>Father's Mobile Number</li>
                    <li>Mother's Mobile Number</li>
                </ul>
                <p><strong>Note:</strong> Admission Number, Class, Route Number, Student Name, Date of Birth, Father's Name, and Mother's Name cannot be edited.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmReject">Proceed to Edit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="editForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Upload New Photo</label>
                        <input type="file" name="student_photo" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Upload a new photo for ID card (JPG, PNG)</small>
                        <p class="text-danger"><strong>Note:</strong> The photo should be passport-sized, taken in school uniform, with the student's head facing the camera directly, centered, and covering approximately 80% of the image. The file size must be less than 1MB.</p>
                    </div>
                </div>
            </div>


                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                                <label>Blood Group</label>
                                <select name="edited_bloodgroup" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" <?php echo ($bloodgroup == 'A+') ? 'selected' : ''; ?>>A+</option>
                                    <option value="A-" <?php echo ($bloodgroup == 'A-') ? 'selected' : ''; ?>>A-</option>
                                    <option value="B+" <?php echo ($bloodgroup == 'B+') ? 'selected' : ''; ?>>B+</option>
                                    <option value="B-" <?php echo ($bloodgroup == 'B-') ? 'selected' : ''; ?>>B-</option>
                                    <option value="AB+" <?php echo ($bloodgroup == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                    <option value="AB-" <?php echo ($bloodgroup == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                    <option value="O+" <?php echo ($bloodgroup == 'O+') ? 'selected' : ''; ?>>O+</option>
                                    <option value="O-" <?php echo ($bloodgroup == 'O-') ? 'selected' : ''; ?>>O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Father's Mobile</label>
                                <input type="text" name="edited_fathermobile" class="form-control" value="<?php echo htmlspecialchars($fathermobile); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mother's Mobile</label>
                                <input type="text" name="edited_mothermobile" class="form-control" value="<?php echo htmlspecialchars($mothermobile); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="edited_address" class="form-control" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Additional Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Please provide any additional remarks or issues"></textarea required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitEdit">Submit Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Handle reject button click
    $('button[name="reject"]').click(function(e){
        e.preventDefault();
        $('#rejectConfirmModal').modal('show');
    });

    // Handle confirm reject button
    $('#confirmReject').click(function(){
        $('#rejectConfirmModal').modal('hide');
        $('#editModal').modal('show');
    });

    // Handle submit edit button
    $('#submitEdit').click(function(){
        var formData = new FormData($('#editForm')[0]);
        formData.append('reject', '1');
        
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                window.location.href = 'landing.php';
            },
            error: function(xhr, status, error){
                alert('Error submitting form: ' + error);
            }
        });
    });
});
</script>

</body>
</html>
