<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
session_start(); 

$StudentId=$_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];
 
if($StudentId== "")
{
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Click <a href='https://dpsfsis.com/Users_new/index.php'>here</a> to login again");
	exit();
}

$sql=mysqli_query($Con, "SELECT  `sname`, `sadmission`, `sclass`, `srollno`, `Sex`, date_format(`DOB`,'%d-%m-%Y') as `dob`, `BloodGroup`, `Address`,`sfathername`,`FatherOccupation`,`FatherEducation`, `FatherEmailId`,`FatherMobileNo`, `MotherName`, `MotherEducation`,`MotherOccupatoin`,`MotherMobile`,`MotherEmail`,`suser`,`spassword`,`smobile`,`email`,`AadharNumber`, `status`,`ProfilePhoto`,`routeno`,`GuradianName`,`GuradianOccupation`,`GuradinaEducation`,`GuradianMobileNo`,`GuradianEmailId`, `FatherPhoto`, `MotherPhoto` ,`GuradianPhoto`,`DriverPhoto`,`vehicle_type`, `vehicle_no`, `driver_name`, `driver_contact_no` FROM `student_master` WHERE `sadmission`='$StudentId'");
while($row=mysqli_fetch_array($sql))
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
        $profile_photo=$row[24];
        $routeno=$row[25];
        $GuradianName=$row[26];
        $GuradianOccupation=$row[27];
        $GuradinaEducation=$row[28];
        $GuradianMobileNo=$row[29];
        $GuradianEmailId=$row[30];
        $FatherPhoto=$row[31];
        $MotherPhoto=$row[32];
        $GuradianPhoto=$row[33];
        $DriverPhoto=$row[34];
        
        
         $vehicle_type = $row['vehicle_type'];
         $vehicle_no = $row['vehicle_no'];
         $driver_name = $row['driver_name'];
         $driver_contact_no =$row['driver_contact_no'];
        
    }
    
    
//     if($profile_photo==''){
//       $profile_photo = 'user.png';
//   }
    
//   if($FatherPhoto==''){
//       $FatherPhoto = 'user.png';
//   }
//   if($MotherPhoto==''){
//       $MotherPhoto = 'user.png';
//   }
//      if($GuradianPhoto==''){
//       $GuradianPhoto = 'user.png';
//   }
//     if($DriverPhoto==''){
//       $DriverPhoto = 'user.png';
//   }
   
   
   
  if(isset($_REQUEST['submit_profile_photo'])){
   
      $admission_no  = $_REQUEST['ModalInput3'];
       $documents    =  $_FILES['image']['name']; 
       $imageFileType = strtolower(pathinfo($documents,PATHINFO_EXTENSION));
       $target_dir = "../Admin/StudentManagement/StudentPhotos/";
       
       $img_name = $admission_no."-S.".$imageFileType;
       $target_file = $target_dir .$img_name;

     // $target_file = $target_dir.$admission_no.$documents;    // path + name


        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);    // function for upload file in                                    
      
      
  //    $insert_photo_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`) values($admission_no, $documents, '', '') ";
      
         $sql_d1=mysqli_query($Con, "select `ProfilePhoto` from `student_id_card`  where `sadmission`='$admission_no' and `ProfilePhoto`!='' ");
		if(mysqli_num_rows($sql_d1)==0)
		{
		    
		     $inserted_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`,`status`) values('$admission_no', '$img_name', '', '','Pending') ";
                $update_query = "Update `student_master` SET   `image_status`='Pending'  where `sadmission`='$admission_no'" ;
               $query=mysqli_query($Con, $update_query);
			 
		} 
		 else
		 {
		     $inserted_query = "Update `student_id_card` SET  `ProfilePhoto`='$img_name' ,`status`='Pending' where `sadmission`='$admission_no' AND  `MotherPhoto`='' AND `FatherPhoto`='' AND `guardian_photo`='' AND `driver_photo`=''";
   
		    $update_query = "Update `student_master` SET  `ProfilePhoto`=''  , `image_status`='Pending'  where `sadmission`='$admission_no'" ;
		 
             $query=mysqli_query($Con, $update_query);
		 }
		 
       
      
      $insert_photo_query = mysqli_query($Con, $inserted_query);
      
      if($insert_photo_query){
          ?>
          
          <script>
            alert('Dear user, photo updated successfully in the system. it will be reflect next 24hours.')
                        
          </script>

      
     <?php }
     else{
         ?>
         
      <script>
            alert('something went wrong !')
                        
          </script>
         
<?php         
     }
       
      
  } 
    
    
    // vehichle details
    
   
    
    
    
    //father photo
     if(isset($_REQUEST['submit_father_photo'])){
       $Saddmission  = $_REQUEST['ModalInput1'];
       $documents    =  $_FILES['father_photo']['name']; 
       $imageFileType = strtolower(pathinfo($documents,PATHINFO_EXTENSION));
       $target_dir = "../Admin/StudentManagement/StudentParentPhoto/";
       
       $img_name = $Saddmission."-F.".$imageFileType;
       $target_file = $target_dir .$img_name;

     // $target_file = $target_dir.$admission_no.$documents;    // path + name


        move_uploaded_file($_FILES["father_photo"]["tmp_name"], $target_file);    // function for upload file in  
   
        $sql_d1=mysqli_query($Con, "select `FatherPhoto` from `student_id_card`  where `sadmission`='$Saddmission' and `FatherPhoto`!='' ");
		if(mysqli_num_rows($sql_d1)==0)
		{
		    
		    $inserted_query = "INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`, `status`) values('$Saddmission', '', '$img_name', '' ,'Pending') ";
            $update_query = "Update `student_master` SET   `image_status`='Pending'  where `sadmission`='$Saddmission'" ;
               $query=mysqli_query($Con, $update_query);
			 
		} 
		 else
		 {
		     $inserted_query = "Update `student_id_card` SET  `FatherPhoto`='$img_name' , `status`='Pending'   where `sadmission`='$Saddmission' AND  ProfilePhoto='' AND MotherPhoto='' AND `ProfilePhoto`='' AND `guardian_photo`='' AND driver_photo=''";
             $update_query = "Update `student_master` SET  `FatherPhoto`='' , `image_status`='Pending'  where `sadmission`='$Saddmission'" ;
               $query=mysqli_query($Con, $update_query);
		    
		 }

  
      
  //    $insert_photo_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`) values($admission_no, $documents, '', '') ";
      
     
    
       
        $update_details = mysqli_query($Con, $inserted_query);
         
        if($update_details){
            ?>
            
        <script>
            alert('Dear user, photo updated successfully in the system. it will be reflect next 24hours.');
        </script>
    <?php    
        }
        else{
            ?>
            
            <script>
            alert('something wents wrong!');
        </script>
    
    <?php        
        }
        
        
    }
    
    
    //mother photo
      if(isset($_REQUEST['submit_mother_photo'])){
        
       $Saddmission  = $_REQUEST['ModalInput2'];
       $documents    =  $_FILES['mother_photo']['name']; 
       $imageFileType = strtolower(pathinfo($documents,PATHINFO_EXTENSION));
       $target_dir = "../Admin/StudentManagement/StudentParentPhoto/";
       
       $img_name = $Saddmission."-M.".$imageFileType;
       $target_file = $target_dir .$img_name;

     // $target_file = $target_dir.$admission_no.$documents;    // path + name


        move_uploaded_file($_FILES["mother_photo"]["tmp_name"], $target_file);    // function for upload file in  
   
     
     
     
       $sql_d1=mysqli_query($Con, "select `MotherPhoto` from `student_id_card`  where `sadmission`='$Saddmission' and `MotherPhoto`!='' ");
		if(mysqli_num_rows($sql_d1)==0)
		{
		    
		     $inserted_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`, `status`) values('$Saddmission', '', '', '$img_name','Pending') ";
              $update_query = "Update `student_master` SET   `image_status`='Pending'  where `sadmission`='$Saddmission'" ;
               $query=mysqli_query($Con, $update_query);
			 
		} 
		 else
		 {
		     $inserted_query = "Update `student_id_card` SET  `MotherPhoto`='$img_name' , `status`='Pending'  where `sadmission`='$Saddmission' AND  `ProfilePhoto`='' AND `FatherPhoto`='' AND `guardian_photo`='' AND `driver_photo`=''";
              $update_query = "Update `student_master` SET  `MotherPhoto`='' ,`image_status`='Pending'  where `sadmission`='$Saddmission'" ;
               $query=mysqli_query($Con, $update_query);
		    
		 }
		 
      
  //    $insert_photo_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`) values($admission_no, $documents, '', '') ";
      
        
        $update_details = mysqli_query($Con, $inserted_query);
         
        
        if($update_details){
            ?>
            
        <script>
            alert('Dear user, photo updated successfully in the system. it will be reflect next 24hours.');
        </script>
    <?php    
        }
        else{
            ?>
            
            <script>
            alert('something wents wrong!');
        </script>
    
    <?php        
        }
        
        
    }
     
  
if(isset($_REQUEST['submit_guardian_photo'])){
   
      
        $admission_no  = $_REQUEST['ModalInput4'];
       $documents    =  $_FILES['guardian_photo']['name']; 
       $imageFileType = strtolower(pathinfo($documents,PATHINFO_EXTENSION));
       $target_dir = "../Admin/StudentManagement/StudentParentPhoto/";
       
       $img_name = $admission_no."-G.".$imageFileType;
       $target_file = $target_dir .$img_name;

     // $target_file = $target_dir.$admission_no.$documents;    // path + name


        move_uploaded_file($_FILES["guardian_photo"]["tmp_name"], $target_file); 
        
       $sql_d1=mysqli_query($Con, "select `guardian_photo` from `student_id_card`  where `sadmission`='$admission_no' and `guardian_photo`!='' ");
	   
       if(mysqli_num_rows($sql_d1)==0)
	  {
		    
		 $inserted_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`,`guardian_photo`,`status`) values('$admission_no', '', '', '','$img_name','Pending') ";
  	      $update_query = "Update `student_master` SET   `image_status`='Pending'  where `sadmission`='$admission_no'" ;
               $query=mysqli_query($Con, $update_query);
		} 
		 else
		 {
		     $inserted_query = "Update `student_id_card` SET  `guardian_photo`='$img_name', `status`='Pending'  where `sadmission`='$admission_no' AND  `ProfilePhoto`='' AND `FatherPhoto`='' AND `MotherPhoto`='' AND `driver_photo`=''";
               $update_query = "Update `student_master` SET  `GuradianPhoto`='', `image_status`='Pending'  where `sadmission`='$admission_no'" ;
               $query=mysqli_query($Con, $update_query);
		    
		 }
		 
        
    
      $insert_photo_query = mysqli_query($Con, $inserted_query);
      
      if($insert_photo_query){
          ?>
          
          <script>
            alert('Dear user, photo updated successfully in the system. it will be reflect next 24hours.')
                        
          </script>

      
     <?php }
     else{
         ?>
         
      <script>
            alert('something went wrong !')
                        
          </script>
         
<?php         
     }
       
      
  }   


if(isset($_REQUEST['submit_driver_photo'])){
   
      
       
       $admission_no  = $_REQUEST['ModalInput5'];
       $documents    =  $_FILES['driver_photo']['name']; 
       $imageFileType = strtolower(pathinfo($documents,PATHINFO_EXTENSION));
       $target_dir = "../Admin/StudentManagement/StudentParentPhoto/";
       
       $img_name = $admission_no."-D.".$imageFileType;
       $target_file = $target_dir .$img_name;

     // $target_file = $target_dir.$admission_no.$documents;    // path + name


        move_uploaded_file($_FILES["driver_photo"]["tmp_name"], $target_file); 
        
        
      $sql_d1=mysqli_query($Con, "select `driver_photo` from `student_id_card`  where `sadmission`='$admission_no' and `driver_photo`!='' ");
	   
       if(mysqli_num_rows($sql_d1)==0)
	  {
		    
		$inserted_query = " INSERT INTO `student_id_card`(`sadmission`, `ProfilePhoto`, `FatherPhoto`, `MotherPhoto`,`guardian_photo`,`driver_photo`,`status`) values('$admission_no', '', '', '','','$img_name','Pending') ";
            $update_query = "Update `student_master` SET  `image_status`='Pending'  where `sadmission`='$admission_no'" ;
               $query=mysqli_query($Con, $update_query);
			 
		} 
		 else
		 {
		     $inserted_query = "Update `student_id_card` SET  `driver_photo`='$img_name' , `status`='Pending' where `sadmission`='$admission_no' AND  `ProfilePhoto`='' AND `FatherPhoto`='' AND `MotherPhoto`='' AND `guardian_photo`=''";
            $update_query = "Update `student_master` SET  `DriverPhoto`='' , `image_status`='Pending'  where `sadmission`='$admission_no'" ;
               $query=mysqli_query($Con, $update_query);
		    
		 }
		 
		 
     
      $insert_photo_query = mysqli_query($Con, $inserted_query);
      
      if($insert_photo_query){
          ?>
          
          <script>
            alert('Dear user, photo updated successfully in the system. it will be reflect next 24hours.')
                        
          </script>

      
     <?php }
     else{
         ?>
         
      <script>
            alert('something went wrong !')
                        
          </script>
         
<?php         
     }
       
      
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



	<style>
	.xy
	{
		width: 133px;
		height: 133px;
		border: 1px solid black;
		border-radius: 100%;
		/*background-image: url("images/SPD.jpg");*/
		background-size: 100% 100%;
		margin:0 auto;
	}
	.xy img
	{
	    border-radius:100%;
	}
	.fo1
	{
	    margin: 20px 0 0 0px;
	}
	.pad{
	    padding: 25px 5px;
	}
	.pad1
	{
	    padding: 8px 10px;
	}

#yourBtn {
  width: 40px;
  height: 40px;
  line-height: 40px;
  border: 1px solid #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
  border-radius: 50px;
}
#yourBtn-change {
  width: 30px;
  height: 30px;
  line-height: 30px;
  border: 1px solid #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
  border-radius: 50px;
}
.xy-change
{
    width:80px !important;
    height:80px !important;
    overflow:hidden;
}
.m-t
	{
	    margin: 60px 0 0 0px;
	}
	
	.icon-po
	{
  
    background-color: #97cfd7;
    padding: 8px;
    display: inline-block;
    width: 30px;
    border-radius: 50px;
    cursor:pointer;
    box-shadow: 0px 2px 5px black;
    position: absolute;
    margin-top: -20px;
    margin-left: -13%;
	}
</style>

</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme">  

    <?php include 'new_sidenav.php';?>



<main class="page-content" style="margin-top:45px;">
<div class="container-fluid page-border">
    <section class="row" style="box-shadow:0 2px 5px black;">
        <div class="col-md-3 col-pn">
            <div class="card pad">
            <?php
         
            $sql=mysqli_query($Con, "select `ProfilePhoto` from `student_master` where `sadmission`='$StudentId' and `ProfilePhoto` !=''");
       
            while($row=mysqli_fetch_row($sql))
            {
            
                
              $ProfilePhoto1=$row[0]; 
               $ProfilePhoto="../Admin/StudentManagement/StudentPhotos/".$ProfilePhoto1;
             $blank='../Admin/StudentManagement/StudentPhotos/profile.png';
              
            }
            
            ?>
          
                <center class="xy"><img src="<?php if($ProfilePhoto!=''){ echo $ProfilePhoto;} else {echo '../Admin/StudentManagement/StudentPhotos/profile.png'; }?>" height="100%" width="100%">
            	<form action="#type your action here" method="POST" enctype="multipart/form-data" name="myForm" class="fo1">
            	  <!--<div id="yourBtn" onclick="getFile()"><i class="fa fa-pencil"></i></div>-->
            	  <div style='height: 0px;width: 0px; overflow:hidden;'>
            	    <!--<input id="upfile" type="file"  name="file_data" value="upload"  />-->
            	  	<!--<button type="hidden" class="btn btn-success btn-xs" name="submit_profile_photo1">Submit</button>-->
            	  </div>
            	
            	</form>	
            	</center>
            <i class="fa fa-edit showModal3 icon-po" aria-hidden="true"  data-device3="<?php echo $StudentId; ?>" style="margin-left:46%;margin-top:112px"></i>
            	   
            </div>
            
            	  	
            	  	
        </div>
        <div class="col-md-9 col-pn">
            <div class="card border-rad">
                <div class="bg-primary text-white border-rad">
                        <h5 style="padding:5px 0 0 20px;"><i class="fas fa-user"></i> Personal Informations</h5>
                        <hr style="margin:0;">
                        </div>
              <div class="row pad1">
                    <div class="col-sm-2 col-5 font-weight-bold">Adm. No:</div>
                    <div class="col-sm-3 col-7"><?php echo $StudentId;?></div>
                    <div class="col-sm-2 col-5 font-weight-bold">Class:</div>
                    <div class="col-sm-5 col-7"><?php echo $StudentClass;?></div>
                </div>
                <div class="row pad1">
                    <div class="col-sm-2 col-5 font-weight-bold">DOB:</div>
                    <div class="col-sm-3 col-7"><?php echo $dob;?></div>
                    <div class="col-sm-2 col-5 font-weight-bold">Blood Group:</div>
                    <div class="col-sm-5 col-7"><?php echo $blooodgroup;?></div>
                </div>
                <div class="row pad1">
                    <div class="col-sm-2 col-5 font-weight-bold">Mobile No:</div>
                    <div class="col-sm-3 col-7"><?php echo $smobile;?></div>
                    <div class="col-sm-2 col-5 font-weight-bold">Email ID:</div>
                    <div class="col-sm-5 col-7"><?php echo $email;?></div>
                </div>
                <div class="row pad1">
                    <div class="col-sm-2 col-5 font-weight-bold">Address:</div>
                    <div class="col-sm-10 col-7"><?php echo $Address;?></div>
                </div>
            </div>
        </div>
    </section>
    
                             
    
<div class="row">
    
          <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-p">
             <section class="container-fluid">
                <div class="row">
                    <div class="col-md-6 border border-rad m-t10">
                        <div class="bg-primary border-rad text-white row p-cus">
                        <h5><i class="fas fa-male"></i> Fathers</h5>
                        </div>
                        <div class="container-fluid m-t10">
                          <div class="row">
                            <div class="col-md-2 col-pn text-center">
                                <?php
                                    $sql=mysqli_query($Con, "select `FatherPhoto` from `student_master` where `sadmission`='$StudentId' and `FatherPhoto` !=''");
                                        while($row=mysqli_fetch_row($sql))
                                        {
                                          $FatherPhoto1=$row[0]; 
                                          $FatherPhoto="../Admin/StudentManagement/StudentParentPhoto/".$FatherPhoto1;
                                          $blank="../Admin/StudentManagement/StudentPhotos/profile.png";
                                        }   
                                        
                                        ?>
                               <center class="xy xy-change"><img src="<?php if($FatherPhoto!=''){ echo $FatherPhoto;} else { echo '../Admin/StudentManagement/StudentPhotos/profile.png';} ?>" height="100%" width="100%" >		
                            	<form action="#" method="POST" enctype="multipart/form-data" name="myForm" class="m-t">
                            	  <!--<div id="yourBtn-change" onclick="getFile()"><i class="fa fa-pencil"></i></div>-->
                            	  <div style='height: 0px;width: 0px; overflow:hidden;'>
                            	  	<!--<input id="upfile" type="file" value="upload" onchange="sub(this)" />-->
                            	  	
                            	  </div>
                            	</form>	
                            	</center>
                            	 
                                  <i class="fa fa-edit showModal1 icon-po" aria-hidden="true"  data-device1="<?php echo $StudentId; ?>" ></i>
                               
                            
                            </div>
                            <div class="col-md-10 col-pn">
                                <ul class="row list_chan">
                                    <li class="col-5">Name</li>
                                    <li class="col-7"><?php echo $fathername;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Occupation</li>
                                    <li class="col-7"><?php echo $FatherOccupation;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Education</li>
                                    <li class="col-7"><?php echo $FatherEducation;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Mobile No.</li>
                                    <li class="col-7"><?php echo $FatherMobileNo;?></li>
                                </ul>
                                 <ul class="row list_chan">
                                    <li class="col-5">Email Id</li>
                                    <li class="col-7"><?php echo $FatherEmailId;?></li>
                                </ul>
                                
                            </div>
                            </div>
                        </div>
                    </div>
                    <!--end third block-->
                    <div class="col-md-6 border border-rad m-t10">
                        <div class="bg-primary border-rad text-white row p-cus">
                        <h5><i class="fas fa-female"></i> Mothers</h5>
                        </div>
                        <div class="container-fluid m-t10">
                          <div class="row">
                            <?php
                            
                                $mother_photo='../Admin/StudentManagement/StudentPhotos/'.$MotherPhoto;
                            ?>
                            <div class="col-md-2 col-pn text-center">
                                  <?php
                                    $sql=mysqli_query($Con, "select `MotherPhoto` from `student_master` where `sadmission`='$StudentId' and `MotherPhoto` !=''");
                                        while($row=mysqli_fetch_row($sql))
                                        {
                                          $MotherPhoto1=$row[0];
                                          
                                            $MotherPhoto="../Admin/StudentManagement/StudentParentPhoto/".$MotherPhoto1;
                                             $blank="../Admin/StudentManagement/StudentPhotos/profile.png";
                                        }   
                                        
                                        ?>
                                        
                               <center class="xy xy-change"><img src="<?php if($MotherPhoto!=''){ echo $MotherPhoto;} else {echo '../Admin/StudentManagement/StudentPhotos/profile.png'; } ?>" height="100%" width="100%" >		
                            	<form action="#" method="POST" enctype="multipart/form-data" name="myForm" class="m-t">
                            	  <!--<div id="yourBtn-change" onclick="getFile()"><i class="fa fa-pencil"></i></div>-->
                            	  <div style='height: 0px;width: 0px; overflow:hidden;'>
                            	  	<!--<input id="upfile" type="file" value="upload" onchange="sub(this)" />-->
                            	  </div>
                            	</form>	
                            	</center>
                            	
                            	 <i class="fa fa-edit showModal2 icon-po" aria-hidden="true"  data-device2="<?php echo $StudentId; ?>" ></i>
                            	 
                            
                            </div>
                            <div class="col-md-10 col-pn">
                                <ul class="row list_chan">
                                    <li class="col-5">Name</li>
                                    <li class="col-7"><?php echo $MotherName;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Occupation</li>
                                    <li class="col-7"><?php echo $MotherOccupatoin;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Education</li>
                                    <li class="col-7"><?php echo $MotherEducation;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Mobile No.</li>
                                    <li class="col-7"><?php echo $MotherMobile;?></li>
                                </ul>
                                 <ul class="row list_chan">
                                    <li class="col-5">Email Id</li>
                                    <li class="col-7"><?php echo $MotherEmail;?></li>
                                </ul>
                                
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 border border-rad m-t10">
                        <div class="bg-primary border-rad text-white row p-cus">
                        <h5><i class="fas fa-male"></i> Guardian</h5>
                        </div>
                        <div class="container-fluid m-t10">
                          <div class="row">
                            <?php
                            
                                $mother_photo='../Admin/StudentManagement/StudentPhotos/'.$MotherPhoto;
                            ?>
                            <div class="col-md-2 col-pn text-center">
                                <?php
                                    $sql=mysqli_query($Con, "select `GuradianPhoto` from `student_master` where `sadmission`='$StudentId' and `GuradianPhoto` !=''");
                                        while($row=mysqli_fetch_row($sql))
                                        {
                                          $guardian_photo1=$row[0]; 
                                           $guardian_photo="../Admin/StudentManagement/StudentParentPhoto/".$guardian_photo1;
                                           $blank="../Admin/StudentManagement/StudentPhotos/profile.png";
                                        }   
                                        
                                        ?>
                                        
                               <center class="xy xy-change"> <img src="<?php if($guardian_photo!=''){  echo $guardian_photo; } else {echo '../Admin/StudentManagement/StudentPhotos/profile.png';}?>" height="100%" width="100%" >
                               
                                        
                            	<form action="#" method="POST" enctype="multipart/form-data" name="myForm" class="m-t">
                            	  <!--<div id="yourBtn-change" onclick="getFile()"><i class="fa fa-pencil"></i></div>-->
                            	  <div style='height: 0px;width: 0px; overflow:hidden;'>
                            	  	<input id="upfile" type="file" value="upload" onchange="sub(this)" />
                            	  </div>
                            	</form>	
                            	</center>
                             <i class="fa fa-edit showModal4 icon-po" aria-hidden="true"  data-device4="<?php echo $StudentId; ?>" ></i>

                            </div>
                            
                            <div class="col-md-10 col-pn">
                                <ul class="row list_chan">
                                    <li class="col-5">Name</li>
                                    <li class="col-7"><?php echo $GuradianName;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Occupation</li>
                                    <li class="col-7"><?php echo $GuradianOccupation;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Education</li>
                                    <li class="col-7"><?php echo $GuradinaEducation;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Mobile No.</li>
                                    <li class="col-7"><?php echo $GuradianMobileNo;?></li>
                                </ul>
                                 <ul class="row list_chan">
                                    <li class="col-5">Email Id</li>
                                    <li class="col-7"><?php echo $GuradianEmailId;?></li>
                                </ul>
                                
                            </div>
                            </div>
                        </div>
                    </div>
                    <!--end fourth block -->
                    
                    <!-- end fifth block -->
                    <div class="col-md-6 border border-rad m-t10">
                        <div class="bg-primary border-rad text-white row p-cus">
                        <h5><i class="fas fa-bus-alt"></i> Driver Details  
                        
                        <i class="fa fa-pencil btn btn-info btn-sm edit_driver_details" aria-hidden="true"   data-device="<?php echo $sadmission; ?>" ></i>
                        <!--<button type="button"  >Open Modal</button> -->
                        
                        </h5>
                        </div>
                       <div class="container-fluid m-t10">
                           
                           
                           
                          <div class="row">
                              <div class="col-md-2 col-pn text-center">
                                   <?php
                                    $sql=mysqli_query($Con, "select `DriverPhoto` from `student_master` where `sadmission`='$StudentId' and `DriverPhoto` !=''");
                                        while($row=mysqli_fetch_row($sql))
                                        {
                                          $driver_photo1=$row[0];  
                                          $driver_photo="../Admin/StudentManagement/StudentParentPhoto/".$driver_photo1;
                                          $blank="../Admin/StudentManagement/StudentPhotos/profile.png";
                                        }   
                                        
                                        ?>
                                        
                               <center class="xy xy-change"><img src="<?php  if($driver_photo!=''){  echo $driver_photo; } else {echo '../Admin/StudentManagement/StudentPhotos/profile.png'; } ?>" height="100%" width="100%" >		
                            	<form action="#" method="POST" enctype="multipart/form-data" name="myForm" class="m-t">
                            	  <!--<div id="yourBtn-change" onclick="getFile()"><i class="fa fa-pencil"></i></div>-->
                            	  <div style='height: 0px;width: 0px; overflow:hidden;'>
                            	  	<input id="upfile" type="file" value="upload" onchange="sub(this)" />
                            	  </div>
                            	</form>	
                            	</center>
                            	 <i class="fa fa-edit showModal5 icon-po" aria-hidden="true"  data-device5="<?php echo $StudentId; ?>" ></i>
                            </div>
                            <div class="col-md-10 col-pn">
                                  <ul class="row list_chan">
                                    <li class="col-5">Vehicle Type:</li>
                                    <li class="col-7" ><?php echo $vehicle_type; ?> </li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Vehicle No:</li>
                                    <li class="col-7"><?php echo $vehicle_no; ?> </li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Driver Name</li>
                                    <li class="col-7"><?php echo $driver_name; ?> </li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Driver Mob#</li>
                                    <li class="col-7"><?php echo $driver_contact_no; ?> </li>
                                </ul>
                              
                            </div>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-md-2 col-pn text-center">
                                 <?php
                            
                                $Driver_Photo='../Admin/StudentManagement/StudentPhotos/'.$DriverPhoto;
                                
                                $rsStRoute=mysqli_query($Con, "select routeno from student_master where sadmission='$sadmission'");
                                    $rowStRoute=mysqli_fetch_row($rsStRoute);
                                    $StBusNo=$rowStRoute[0];
                                    $ArrBusNo=explode(",",$StBusNo);
                                    $SizeOfArrBusNo=sizeof($ArrBusNo);
                                    
                             

                                
                               
                            ?>
                            
                                <!--<img src="<?php echo $Driver_Photo;?>" style="width:50px;height:50px;border-radius:50px;">-->
                            </div>
                            <div class="col-md-10 col-pn">
                                <?php
                                
                                   foreach ($ArrBusNo as $value)
                                //{
                                
                                    $ssql="SELECT `routeno`,`bus_no`  ,`driver_name` ,`driver_mobile` FROM `RouteMaster`  where routeno='$value'";
                                
                                    $rs= mysqli_query($Con, $ssql);
                                    while($row=mysqli_fetch_row($rs))
                                    //{
                                        $route_no=$row[0];
                                        $bus_no=$row[1];
                                        $driver_name=$row[2];
                                        $driver_mobile=$row[3];
                                        
                                    
                                ?>
                               
                                
                                <!--<ul class="row list_chan">-->
                                <!--    <li class="col-5">Name</li>-->
                                <!--    <li class="col-7"><?php //echo $driver_name;?></li>-->
                                <!--</ul>-->
                                <!--<ul class="row list_chan">-->
                                <!--    <li class="col-5">Mobile No</li>-->
                                <!--    <li class="col-7"><?php //echo $driver_mobile;?></li>-->
                                <!--</ul>-->
                                <!--<ul class="row list_chan">-->
                                <!--    <li class="col-5">Route No.</li>-->
                                <!--    <li class="col-7"><?php //echo $route_no;?></li>-->
                                <!--</ul>-->
                                <!--<ul class="row list_chan">-->
                                <!--    <li class="col-5">Bus No . </li>-->
                                <!--    <li class="col-7"><?php //echo $bus_no;?></li>-->
                                <!--</ul>-->
                                
                                <?php
                              //  }
                                //}
                                ?>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6 border border-rad m-t10">
                        <div class="bg-primary border-rad text-white row p-cus">
                        <h5><i class="fas fa-user"></i> Portal and Mobile App Credential</h5>
                        </div>
                        <div class="container-fluid m-t10">
                          <div class="row">
                            <div class="col-md-10 col-pn">
                                  <ul class="row list_chan">
                                    <li class="col-5">Adm / UserId</li>
                                    <li class="col-7"><?php echo $suser;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Password</li>
                                    <li class="col-7"><?php echo $password;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Mobile No</li>
                                    <li class="col-7"><?php echo $smobile;?></li>
                                </ul>
                                <ul class="row list_chan">
                                    <li class="col-5">Email Id</li>
                                    <li class="col-7"><?php echo $email;?></li>
                                </ul>
                              
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- end sixth block-->
                </div>
                </section>
            
            
        </div>
</div>







</div>
</main>
</div>


<!-- Vehicle Start Modal -->

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Vehicle Details </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post">
            
            <input type="hidden" name="ModalInput" id="ModalInput" value="">
             <input type="hidden" name="vehicle_adm" id="vehicle_adm" value="">
            
            <div class="form-group">
              <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" placeholder="Vehicle Type">
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control" id="vehicle_no"  name="vehicle_no" placeholder="Vehicle No">
            </div>
            
          <div class="form-group">
              <input type="text" class="form-control" id="driver_name" placeholder="Driver Name" name="driver_name">
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control" id="driver_contact_no" placeholder="Driver Mob#" name="driver_contact_no"  maxlength="10">
            </div>
            
            
            
             <div class="form-group">
                 <center>
            <button type="button" class="btn btn-success btn-xs" name="submit_vehicle_detail" id="submit_vehicle_detail">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- End Modal -->




<!-- Father pic Start Modal -->

<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Father Profile </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="ModalInput1" id="ModalInput1" value="">
            
            <div class="form-group">
              <input type="file" class="form-control input-h" id="father_photo" name="father_photo" >
            </div>
            
            
            
             <div class="form-group">
                 <center>
            <button type="submit" class="btn btn-success btn-xs" name="submit_father_photo">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- End Modal -->


<!-- Mother pic Start Modal -->

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Mother Profile </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="ModalInput2" id="ModalInput2" value="">
            
            <div class="form-group">
              <input type="file" class="form-control input-h" id="mother_photo" name="mother_photo" >
            </div>
            
           
            
             <div class="form-group">
                 <center>
            <button type="submit" class="btn btn-success btn-xs" name="submit_mother_photo">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- End Modal -->



<!-- Guradian pic Start Modal -->

<div class="modal fade" id="myModal4" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Guardian Profile </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="ModalInput4" id="ModalInput4" value="">
            
            <div class="form-group">
              <input type="file" class="form-control input-h" id="guardian_photo" name="guardian_photo" >
            </div>
            
           
            
             <div class="form-group">
                 <center>
            <button type="submit" class="btn btn-success btn-xs" name="submit_guardian_photo">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- Guradian End Modal -->




<!-- Driver pic Start Modal -->

<div class="modal fade" id="myModal5" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Driver Profile </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="ModalInput5" id="ModalInput5" value="">
            
            <div class="form-group">
              <input type="file" class="form-control input-h" id="driver_photo" name="driver_photo" >
            </div>
            
           
            
             <div class="form-group">
                 <center>
            <button type="submit" class="btn btn-success btn-xs" name="submit_driver_photo">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- DRIVER End Modal -->


<!-- Profile pic Start Modal -->

<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4> <b> Student Profile </b></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
          
             <form action = " "  method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="ModalInput3" id="ModalInput3" value="">
            
            <div class="form-group">
           
              <input type="file" class="form-control input-h" id= "image" name="image" >
           
            </div>
            
           
            
             <div class="form-group">
                 <center>
            <button type="submit" class="btn btn-success btn-xs" name="submit_profile_photo">Submit</button>
             </center>
             </div>
          
          </form>
        
        </div>
        <!--<div class="modal-footer">-->
        <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <!--</div>-->
      </div>
      
    </div>
  </div>

<!-- End Modal -->



<!--end page contents-->

<script>
function getFile() {
  document.getElementById("upfile").click();
}


// img_profile


function sub(obj) {
	
  var file = obj.value;
  var fileName = file.split("\\");
  document.getElementById("yourBtn").innerHTML = fileName[fileName.length - 1];
  document.myForm.submit();
  event.preventDefault();
}

   
   
       // modal show function
  
    $('.showModal').click(function(){  
    
    
    var data_department = $(this).attr('data-device');
    $('#ModalInput').val(data_department );
    $('#myModal').modal('show');
        
        
    });
    
    
    
    
     $('.showModal1').click(function(){  
    
    
    var data_department = $(this).attr('data-device1');
    $('#ModalInput1').val(data_department );
    $('#myModal1').modal('show');
        
        
    });
    
    
    
     $('.showModal2').click(function(){  
    
    
    var data_department = $(this).attr('data-device2');
    $('#ModalInput2').val(data_department );
    $('#myModal2').modal('show');
        
        
    });
    
    
    
    
      $('.showModal3').click(function(){  
    
    
    var data_department = $(this).attr('data-device3');
    $('#ModalInput3').val(data_department );
    $('#myModal3').modal('show');
        
        
    });
    
       $('.showModal4').click(function(){  
        var data_department = $(this).attr('data-device4');
        $('#ModalInput4').val(data_department );
        $('#myModal4').modal('show');
        
        
    });
    
      $('.showModal5').click(function(){  
        var data_department = $(this).attr('data-device5');
        $('#ModalInput5').val(data_department );
        $('#myModal5').modal('show');
        
        
    })
    
    
</script>

<script>
function getFile() {
  document.getElementById("upfile").click();
}

function sub(obj) {
  var file = obj.value;
  var fileName = file.split("\\");
  document.myForm.submit();
  event.preventDefault();
}

$('.edit_driver_details').click(function(){

   
      var edit_admission = $(this).attr('data-device');
     
      if (edit_admission=="")
       {
            alert(" Admission No. is not found!");
       }
       else
       {
           $('#myModal').modal('show');
        $.ajax({
            type:'POST',
            url:'submit_driver_detail.php',
            data:{edit_admission:edit_admission},
            dataType:'JSON',
            success:function(response){
                $('#ModalInput').val(response.srno);
                $('#vehicle_type').val(response.vehicle_type);
                $('#vehicle_no').val(response.vehicle_no);
                $('#driver_name').val(response.driver_name);
                $('#driver_contact_no').val(response.driver_contact_no);
                $('#vehicle_adm').val(response.sadmission);
                
                
              
          
                }
        });

       }

   });


$('#submit_vehicle_detail').click(function(){

      var ModalInput=$('#ModalInput').val();
      var vehicle_type=$('#vehicle_type').val();
      var vehicle_no=$('#vehicle_no').val();
      var driver_name=$('#driver_name').val();
      var driver_contact_no=$('#driver_contact_no').val();
      var vehicle_adm=$('#vehicle_adm').val();
      if (ModalInput=="")
        {
            alert(" Id is not found!");
        }
      else
       {
        $.ajax({
            type:'POST',
            url:'submit_driver_detail.php',
            data:{
                    ModalInput:ModalInput,
                    vehicle_type:vehicle_type,
                    vehicle_no:vehicle_no,
                    driver_name:driver_name,
                    driver_contact_no:driver_contact_no,
                    driver_admission:vehicle_adm
                },
            dataType:'JSON',
            success:function(response){
                alert(response.info)
                location.reload(true);
          
                }
        });

      }

  });
   
</script>
</body>
</html>