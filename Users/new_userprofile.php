<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
session_start(); 

$StudentId=$_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];

if($StudentId== "")
{
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Click <a href='http://mvn88.com/Users1/index.php'>here</a> to login again");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
</head>
<body>


<?php include 'Header/header_new.php';?>





<div class="wrap border">
<div class="container-fluid">
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-2 col-xl-2 col-p">
             <div class="card dash-widget card-mb">
                <div class="card-body text-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <?php
                        
                          $sql=mysqli_query($Con, "SELECT `ProfilePhoto`, `sname`  FROM `student_master` WHERE `sadmission`='$StudentId'");
                          $rowP=mysqli_fetch_row($sql);
                          $profile_photo=$rowP[0];
                          $sname=$rowP[1];
                          ?>
                     <img src="../Users/upload/<?php echo $profile_photo;?> " alt="Admin" class="rounded-circle" width="150" height="150">
                        <div class="mt-3">
                           <h4><?php echo $sname;?></h4>
                           <h6 class="text-muted font-size-sm">Admission No: <?php echo $StudentId;?> </h6>
                           <h6 class="text-muted font-size-sm">Class : <?php echo $StudentClass;?></h6>
                         
                           
                        </div>
                     </div>
                </div>
             </div>
          </div>
          <div class="col-md-12 col-sm-12 col-lg-10 col-xl-10 col-p">
             <section class="container-fluid">
                    <div class="row body-scroll">
                        <div class="col">
                            <ul class="nav nav-tabs nav-tabs-bottom row">
                                <!--<li class="nav-item"><a class="nav-link active" href="#profile-tab1" data-toggle="tab"><i class="fa fa-user"></i> Profiles</a></li>-->
                                <!--<li class="nav-item"><a class="nav-link" href="" data-toggle="tab"><i class="fas fa-address-card"></i> Parents Details</a></li>-->
                                <!--<li class="nav-item"><a class="nav-link" href="" data-toggle="tab"><i class="fa fa-graduation-cap"></i> Portal and Mobile App Credentials</a></li>-->
                             

                            </ul>
                            <div class="tab-content">
                                <nav class="tab-pane active" id="">
                                    <div class="row">
                                        <div class="col-md-12 d-flex padding-right-2 padding-left-2">
                                           <div class="card flex-fill add-mrf-card">
                                              <div class="card-body card-padding-bottom">
                                   
                                    <h6 class="card-title page-header0" style="color:red"><b>Personal Informations</b> </h6>
                                    
                                        <hr>
                                        
                                    <table class="table">
                                         <tr>
                                             <th>Admission No</th>
                                             <th>Student Name</th>
                                             <th>Class</th>
                                             <th>Roll No</th>
                                            <th>Gender</th>
                                            <th>Date of Birth</th>
                                            <th>Blood Group</th>
                                            <th>Address</th>
                                         </tr>
                                    <?php 
                                    
                                    $sql=mysqli_query($Con, "SELECT  `sname`, `sadmission`, `sclass`, `srollno`, `Sex`, `DOB`, `BloodGroup`, `Address`,`sfathername`,`FatherOccupation`,`FatherEducation`, `FatherEmailId`,`FatherMobileNo`, `MotherName`, `MotherEducation`,`MotherOccupatoin`,`MotherMobile`,`MotherEmail`,`suser`,`spassword`,`smobile`,`email`,`AadharNumber`, `status`  FROM `student_master` WHERE `sadmission`='$StudentId'");
                                    while($row=mysqli_fetch_row($sql))
                                    {
                                        $name=$row[0];
                                        $sadmission=$row[1];
                                        $sclass=$row[2];
                                        $rollno=$row[3];
                                        $sex=$row[4];
                                        $dob=$row[5];
                                        $blooodgroup=$row[6];
                                        $Address=$row[7];
                                        $fathername=$row[8];
                                        $FatherOccupation=$row[9];
                                        $FatherEducation=$row[10];
                                        $FatherEmailId=$row[11];
                                        $FatherMobileNo=$row[12];
                                        $MotherName=$row[13];
                                        $MotherEducation=$row[14];
                                        $MotherOccupatoin=$row[15];
                                        $MotherMobile=$row[16];
                                        $MotherEmail=$row[17];
                                         $suser=$row[18];
                                         $password=$row[19];
                                         $smobile=$row[20];
                                         $email=$row[21];
                                         $aadhar=$row[22];
                                         $status=$row[23];
                                        
                                        
                                        
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td><?php echo $sadmission;?></td>
                                        <td><?php echo $name;?></td>
                                        <td><?php echo $sclass;?></td>
                                        <td><?php echo $rollno;?></td>
                                        <td><?php echo $sex;?></td>
                                        <td><?php echo $dob;?></td>
                                        <td><?php echo $blooodgroup;?></td>
                                        <td><?php echo $Address;?></td>
                                    </tr>
                                </table>
                                                 
                                <h6 class="card-title page-header0" style="color:red"><b>Father</b> </h6>
                                  <hr>         
                                <table class="table">
                                <tr>
                                     <th>Father Name</th>
                                     <th>Father Education</th>
                                     <th>Father Occuption</th>
                                     <th>Father Mob</th>
                                    <th>Father Email</th>
                                </tr>
                                <tr>
                                     <td><?php echo $fathername;?></td>
                                    <td><?php echo $FatherEducation;?></td>
                                    <td><?php echo $FatherOccupation;?></td>
                                    <td><?php echo $FatherMobileNo;?></td>
                                    <td><?php echo $FatherEmailId;?></td>
                                </tr>
                                </table> 
                                 <h6 style="color:red"><b>Mother</b> </h6>
                                   <hr>
                                <table class="table">
                                <tr>
                                    <th>Mother Name</th>
                                    <th>Mother Education</th>
                                    <th>Mother Occuption</th>
                                    <th>Mother Mob</th>
                                    <th>Mother Email</th>
                                </tr>
                                <tr>
                                            
                                     <td><?php echo $MotherName ;?></td>
                                     <td><?php echo $MotherEducation ;?></td>
                                     <td><?php echo $MotherOccupatoin ;?></td>
                                     <td><?php echo $MotherMobile ;?></td>
                                     <td><?php echo $MotherEmail ;?></td>
                                 </tr>
                             </table>
                             
                            <h6 class="card-title page-header0" style="color:red"><b>Portal and Mobile App Credential</b>  
                            </h6>   
                              <hr>
                            <table class="table">
                            <tr>
                                <th>Adm / UserId</th>
                                <th>Password</th>
                                <th>Mobile No</th>
                                <th>Addhar No</th>
                                <th>Email Id</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td><?php echo $suser;?></td>
                                <td><?php echo $password;?></td>
                                <td><?php echo $smobile;?></td>
                                <td><?php echo $aadhar;?></td>
                                <td><?php echo $email;?></td>
                                <td><?php 
                                    if($status=='1'){
                                         echo  "Active";}
                                         else
                                     {echo "InActive";}?>
                                </td>
                           </tr>
                          </table>             
                       </div>
                    </div>
                </div>
            </div>
                                     
        </nav>
                                <nav class="tab-pane" id="profile-tab2">
                                       <div class="row">
                                          <div class="col-md-12 d-flex padding-right-2 padding-left-2">
                                             <div class="card flex-fill add-mrf-card">
                                                <div class="card-body card-padding-bottom">
                                           
                                            
                                                
                                                   
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                      
                                </nav>
                                <nav class="tab-pane" id="profile-tab3">
                                    <div class="row">
                                       <div class="col-md-12 d-flex padding-right-2 padding-left-2">
                                          <div class="card flex-fill add-mrf-card">
                                             <div class="card-body card-padding-bottom">
                                              
                                                <hr>
                                                <div class="experience-box add-p-b10">
                                                
                                               </div>
                                                
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    
                               
        
                              </nav>
                              <nav class="tab-pane" id="profile-tab4">
                                 <div class="row">
                                    <div class="col-md-12 d-flex padding-right-2 padding-left-2">
                                       <div class="card flex-fill add-mrf-card">
                                          <div class="card-body card-padding-bottom">
                                             <h5 class="card-title page-header0"><i class="fa fa-book"></i>Bank information</h5>
                                             <hr>
                                             <p>Data here...</p>
                                             
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </nav>
                              <nav class="tab-pane" id="profile-tab5">
                                 <div class="row staff-grid-row">
                                    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                        <div class="profile-widget">
                                            <div class="profile-img">
                                                <a href="profile" class="avatar"><img src="public/img/profiles/avatar-02.jpg" alt=""></a>
                                            </div>
                                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">John Doe</a></h4>
                                            <div class="small text-muted">Web Designer</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                        <div class="profile-widget">
                                            <div class="profile-img">
                                                <a href="profile" class="avatar"><img src="public/img/profiles/avatar-09.jpg" alt=""></a>
                                            </div>
                                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">Richard Miles</a></h4>
                                            <div class="small text-muted">Web Developer</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                        <div class="profile-widget">
                                            <div class="profile-img">
                                                <a href="profile" class="avatar"><img src="public/img/profiles/avatar-10.jpg" alt=""></a>
                                            </div>
                                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">John Smith</a></h4>
                                            <div class="small text-muted">Android Developer</div>
                                        </div>
                                     </div>
                                     <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                       <div class="profile-widget">
                                           <div class="profile-img">
                                               <a href="profile" class="avatar"><img src="public/img/profiles/avatar-02.jpg" alt=""></a>
                                           </div>
                                           <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">John Doe</a></h4>
                                           <div class="small text-muted">Web Designer</div>
                                       </div>
                                   </div>
                                   <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                       <div class="profile-widget">
                                           <div class="profile-img">
                                               <a href="profile" class="avatar"><img src="public/img/profiles/avatar-09.jpg" alt=""></a>
                                           </div>
                                           <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">Richard Miles</a></h4>
                                           <div class="small text-muted">Web Developer</div>
                                       </div>
                                   </div>
                                   <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-4">
                                       <div class="profile-widget">
                                           <div class="profile-img">
                                               <a href="profile" class="avatar"><img src="public/img/profiles/avatar-10.jpg" alt=""></a>
                                           </div>
                                           <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile">John Smith</a></h4>
                                           <div class="small text-muted">Android Developer</div>
                                       </div>
                                    </div>
                                    
                                </div>

                              </nav>
                              <nav class="tab-pane" id="profile-tab6">
                                   
                              </nav>
                              
                           
                            </div> 
                             
                            <!--end here-->
                        </div>
                    </div>
                </section>
            
            
        </div>
</div>







</div>
</div>
<!--end page contents-->
</body>
</html>