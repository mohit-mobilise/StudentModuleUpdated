<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php
session_start();
$class=$_SESSION['StudentClass'];
$sadmission=$_SESSION['userid'];
?>

<?php  include "student_id_van_card_fetch.php";

$INFO = fetch_data($sadmission);
$data = json_decode($INFO, true);
$data = $data[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Escort Card</title>
    <link rel="stylesheet" href="style.css">

    <style>
.container
{
font-size:13px;
}
</style>
</head>
<body>

  
  

 <div id="main_body">
    <div id="student_card">
        <div class="header">
         <div class="school_logo">
             <img src="../Admin/images/logo.png" alt="school logo">
         </div>
          <div class="school_content">
           <p> <span class="school_name"><?= $SchoolName; ?></span><br>
<span class="school_add"><?= $SchoolAddress; ?></span><br>

<span class="affiliate">EMAIL : <?= $SchoolEmailId; ?></span><br>
<span class="email">WEBSITE : <?= $SchoolWebsite; ?> / </span>
      <span class="email">Phones :<?= $SchoolPhoneNo; ?></span></p>
       </div><!--school_content-->
        </div><!--header-->
        
        <div class="line"></div>
        <div class="student_detail">
           <h3 align="center">Parent/Escort ID Card</h3>
           <div class="bar" style="width: 100%;height: 3px;background-color: gray; margin-bottom: 5px"></div>
            <div class="std_detail">
                <table align="center">
                <tr>
                    <td class="data">Name</td>
                    <td><?php echo $data['sname'];?></td>
                </tr>
                 <tr>
                    <td class="data">Admission</td>
                    <td><?php echo $data['sadmission'];?></td>
                </tr>
                <tr>
                    <td class="data">class</td>
                    <td><?php echo $data['sclass'];?></td>
                </tr>
                <tr>
                    <td class="data">DOB<!--Addhar--></td>
                    <td><?php $dob = $data['DOB']; echo date('d-m-Y', strtotime($dob)); ?><!--<?php echo $data['AadharNumber'];?>--></td>
                </tr>
                <tr>
                    <td class="data">Address</td>
                    <td><?php echo $data['Address'];?></td>
                </tr>
            </table>
            </div><!--std_detail-->
            <div class="std_img"><!--student img-->
              <img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['ProfilePhoto'];?>" alt="student photo" width="100%" height="160px">  
            </div><!--std_img-->
        </div><!--student_detail-->
        
        <div id="parent_detail">
            <div class="card"><!---mother card img-->
  <div class="card_img"><img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['MotherPhoto'];?>" alt="mother photo" width="100%" height="130px"></div>
  <div class="container">
    <p><b>Mother </b>:<?php echo $data['MotherName'];?></p> 
    <p><?php echo $data['MotherMobile'];?></p> 
  </div><!--container-->
</div><!--card-->
        
<!--        ///////////////////////////////////////////////////////////////////////////////////    -->
        
         <div class="card"><!--father card img-->
   <div class="card_img"><img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['FatherPhoto'];?>" alt="father photo" width="100%" height="130px"></div>
  <div class="container">
    <p><b>Father </b>: <?php echo $data['sfathername'];?></p> 
    <p><?php echo $data['FatherMobileNo'];?></p> 
  </div><!--container-->
</div><!--card-->
        
 
<!--        //////////////////////////////////////////////////////////////////////////////////////////   -->
         <div class="card"><!--gaurdian card img-->
         
  <div class="card_img">
<img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['GuardianPhoto'];?>" alt="gaurdian photo" width="100%" height="128">
</div>

    <div class="container">
    <p><b>Guardian </b>: <?php echo $data['GuradianName'];?></p> 
    <p><?php if($data['GuradianOfficialPhNo'] == '')
{ echo $data['GuradianMobileNo']; }else{ echo $data['GuradianOfficialPhNo'];};?></p> 
  </div>
  <!--container-->
<!--container-->
</div>
<!--card-->

        </div><!--parent_detail-->
    </div><!--div student_card-->
    <div class="clearfix"></div>
    
    <?php   $mode= $data['TransportAvail'];
    if($mode=="Van" || $mode=="Bus" )
    {
     ?>
    
     <div id="van_card">
        <div class="header">
         <div class="school_logo">
             <img src="../Admin/images/logo.png" alt="school logo">
         </div>
          <div class="school_content">
           <p> <span class="school_name"><?= $SchoolName; ?></span><br>
<span class="school_add"><?= $SchoolAddress; ?></span><br>

<span class="affiliate">EMAIL : <?= $SchoolEmailId; ?></span><br>
<span class="email">WEBSITE : <?= $SchoolWebsite; ?> / </span>
      <span class="email">Phones :<?= $SchoolPhoneNo; ?></span></p>
       </div><!--school_content-->
        </div><!--header-->
        
        <div class="line"></div>
        
<!--        /////////////////////////////////////////////////////////////////////////////////////////////////   -->
    
    <div class="student_detail">
           <h3 align="center">STUDENT DETAIL</h3>
            <div class="std_detail">
                <table align="center">
                <tr>
                    <td class="data">Name</td>
                    <td><?php echo $data['sname'];?></td>
                </tr>
                 <tr>
                    <td class="data">Addmission</td>
                    <td><?php echo $data['sadmission'];?></td>
                </tr>
                <tr>
                    <td class="data">class</td>
                    <td><?php echo $data['sclass'];?></td>
                </tr>
                <tr>
                    <td class="data">DOB<!--Addhar--></td>
                    <td><?php $dob = $data['DOB']; echo date('d-m-Y', strtotime($dob)); ?><!--<?php echo $data['AadharNumber'];?>--></td>
                </tr>
                <tr>
                    <td class="data">Address</td>
                    <td><?php echo $data['Address'];?></td>
                </tr>
               <tr>
                    <td >Father</td>
                    <td><?php echo $data['sfathername']."/".$data['FatherMobileNo'];?></td>
                    
                </tr>
                <tr>
                    <td >Mother</td>
                    <td><?php echo $data['MotherName']."/".$data['MotherMobile'];?></td></td>
                    
                </tr>
                 
            </table>
            </div><!--std_detail-->
            <div class="std_img">
                <img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['ProfilePhoto'];?>" alt="student photo" width="100%" height="160px"><!--student img--> 
            </div><!--std_img-->
        </div><!--student_detail-->
        
        
        <div class="line"></div>
        
<!--        //van driver detail ///////////////////  -->
    <div class="student_detail">
           <h3 align="center">VAN DETAIL</h3>
            <div class="std_detail">
                <table align="center">
                <tr>
                    <td class="data">Driver name</td>
                    <td><?php echo $data['driver_name'];?></td>
                </tr>
                 <tr>
                    <td class="data">licence No</td>
                    <td><?php echo $data['license_no'];?></td>
                </tr>
                <tr>
                    <td class="data">Van No</td>
                    <td><?php echo $data['van_no'];?></td>
                </tr>
                <tr>
                    <td class="data">Aadhar</td>
                    <td><?php echo $data['driver_aadhar_no'];?></td>
                </tr>
                <tr>
                    <td class="data">Address</td>
                    <td><?php echo $data['driver_address'];?></td>
                </tr>
                
            </table>
            </div><!--std_detail-->
            <div class="std_img">
                <img class="img" src="../Admin/StudentManagement/StudentDocuments/<?php echo $data['DriverPhoto'];?>" alt="driver photo" width="100%" height="160px"><!--van driver img-->
            </div><!--std_img-->
        </div><!--student_detail-->
     </div>
     <?php
     }
     ?><!-- div van_card-->
 </div><!--div-main_body-->
</body>
</html>