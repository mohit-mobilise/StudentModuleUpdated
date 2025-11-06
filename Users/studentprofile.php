<?php 
session_start(); 
require '../connection.php';
require '../AppConf.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';

$sql=mysqli_query($Con, "SELECT  `sname`, `sadmission`, `sclass`, `srollno`, `Sex`, date_format(`DOB`,'%d-%m-%Y') as `dob`, `BloodGroup`, `Address`,`sfathername`,`FatherOccupation`,`FatherEducation`, `FatherEmailId`,`FatherMobileNo`, `MotherName`, `MotherEducation`,`MotherOccupatoin`,`MotherMobile`,`MotherEmail`,`suser`,`spassword`,`smobile`,`email`,`AadharNumber`, `status`,`ProfilePhoto`,`routeno`,`GuradianName`,`GuradianOccupation`,`GuradinaEducation`,`GuradianMobileNo`,`GuradianEmailId`, `FatherPhoto`, `MotherPhoto` ,`GuradianPhoto`,`DriverPhoto`,`vehicle_type`, `vehicle_no`, `driver_name`, `driver_contact_no`, `DateOfAdmission`, `FinancialYear`, `FatherOfficePhoneNo`, `sfatherage`, `FatherCompanyName`, `FatherOfficeAddress`, `MotherOfficePhone`, `MotherOfficeAddress`, `MotherAge`, `MotherCompanyName`,`DiscontType`,`Special_Needs` FROM `student_master` WHERE `sadmission`='$StudentId'");
while($row=mysqli_fetch_array($sql))
    {
        
        // echo '<pre>';
        // print_r($row);
        // exit;
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
        
        $finyear=$row['FinancialYear'];
        $DOA=$row['DateOfAdmission'];
        $fatherofficeaddress=$row['FatherOfficeAddress'];
        $fathercompany=$row['FatherCompanyName'];
        $fatherage=$row['sfatherage'];
        $fatherofficeno=$row['FatherOfficePhoneNo'];
        $mothercompany=$row['MotherCompanyName'];
        $motherofficeno=$row['MotherOfficePhone'];
        $motherage=$row['MotherAge'];
        $motherofficeaddress=$row['MotherOfficeAddress'];
        $discountType = $row['DiscontType'];
        $cwsn = $row['Special_Needs'];
        //get discount type
           
        $discountSql  = mysqli_query($Con, "SELECT * FROM fees_discountmaster WHERE discount_id = '$discountType' LIMIT 1");
        $numRows = mysqli_num_rows($discountSql);
           if($numRows > 0) {
               $discountData = mysqli_fetch_array($discountSql);
               $discountREc = $discountData['discounttype'];
            } else {
               $discountREc = ""; 
        }
           
        //end
        
    }

?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> ||Student Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style_1.css">



	<style>
        .border-up {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }

        .border-down,
        .border-up-down {
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
        .comment {
            background:none !important;
            width:100% !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .border-up-down {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }

        .emp-profile2 {
            /*width: 120px;*/
            position: relative;
            top: -30px;
            border-radius: 50%;
        }

        .text-center {
            text-align: center !important;
        }

        .profilepic__image {
            width: 80px;
            margin-top: 44px;
            opacity: 1;
            transition: opacity .2s ease-in-out;
            background-color: #fff;
            border: 2px solid #f3f3f3;
            border-radius: 5px !important;
        }

        .profilepic__content {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity .2s ease-in-out;
        }
/*.table td, .table th {*/
/*    width: 33%;*/
/*}*/
        h4 {
            font-size: 17px;
        }

        .portlet.box.green-haze>.portlet-title {
            background-color: #2a2b5b;
        }

        .portlet.box.green-ad>.portlet-title {
            background-color: #0071bc;
            padding-top: 0;
        }

        .green-ad{
            margin-top:20px;
        }

        .portlet.box.green-haze {
            border: 1px solid #2a2b5b;
            border-top: 0;
        }


        .portlet>.portlet-body.green-haze,
        .portlet.green-haze {
            background-color: #2a2b5b;
        }

        .portlet.box {
            padding: 0px !important;
        }

        .green.btn:active:hover,
        .green.btn.active:hover {
            background-color: #2a2b5b;
        }

        .green.btn:active,
        .green.btn.active {
            background-image: none;
            background-color: #2a2b5b;
        }

        .green.btn:hover,
        .green.btn:focus,
        .green.btn:active,
        .green.btn.active {
            color: #FFFFFF;
            background-color: #2a2b5b;
        }

        .green.btn {
            color: #FFFFFF;
            background-color: #393a8d;
        }

        .card {
            /*border-radius: 10px !important;*/
            box-shadow: 0 0px 8px 0 #cdc7c7;
            padding: 15px;
        }

        .text-right {
            text-align: right !important;
        }
        .w_6 {
            width: 1250px !important;
            left: 25% !important;
            height: 700px !important;
        }

        .table td.fit,
        .table th.fit {
            white-space: nowrap;
            width: 40%;
        }
        
        .tab-pane{
            margin:0;
        }
        
        /*.nav-item:hover{*/
        /*    border-bottom:3px solid red;*/
        /*}*/
        
        .information1{
            padding:0;
        }
        
        .information2{
            padding:4px;
        }
        
        #btnachievement, #btnremark, #btnhealth, #btnbehave{
            display:block;
        }
        
        #btnbehaveless, #btnachievementless, #btnremarkless, #btnhealthless{
            display:none;
        }
        .studentprofile h5.card-header {
    border-radius: 6px 6px 0px 0px;
    border-bottom: 1px solid rgba(42, 41, 92, 0.15);
    background: linear-gradient(0deg, rgba(25, 156, 218, 0.07) 0%, rgba(25, 156, 218, 0.07) 100%), #FFF;
    color: var(--Color-Secondory, #2A295C);
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}
.studentprofile li.information2 {
    color: var(--Color-Secondory, #2A295C);
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}
 .col-md-12.bg-white.border-down.stuent-profile {
    border: 2px solid #19227840;
    border-radius: 4px;
    margin: 10px 0px;
}       
       
    </style>
    
</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper ">  

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

<!--<div class="page-content-wrapper" style="background-color: #f7f7f7 !important;">-->
            <div class="page-content">

                <div class="container-fluid" style="padding:8px; margin-top:50px;">

                    <div class="row">
                        <div class="col-12 text-center bg-primary text-white">
                            <h4>Student Profile</h4>
                        </div>
                    </div>

                    <section class="">
                        <div class="col-md-12 m-t-1">
                            <div class="row m-0">
                                <!--<div class="col-md-12 p-0">-->
                                <!--    <img src="img/emp-banner.png" width="100%"-->
                                <!--        class="border-up img-fluid"></div>-->
                                <div class="col-md-12 bg-white border-down stuent-profile">
                                    <div class="row border-up-down">
                                        <div class="col-md-2 text-center emp-profile2">
            <?php
                               $sql=mysqli_query($Con, "select `ProfilePhoto` from `student_master` where `sadmission`='$StudentId' and `ProfilePhoto` !=''");
   
                                while($row=mysqli_fetch_row($sql))
                                {
                                  $ProfilePhoto1=$row[0]; 
                                   $ProfilePhoto="../Admin/StudentManagement/StudentPhotos/".$ProfilePhoto1;
                                }
            ?>
                                            <img class="profilepic__image img-fluid"
                                                src="<?php if($ProfilePhoto!=''){ echo $ProfilePhoto;} else {echo 'tabs/student/profile.png'; }?>">
                                            <div class="profilepic__content"><span><i aria-hidden="true"
                                                        class="fa fa-camera fa-lg"></i></span><input type="file"
                                                    accept="image/*" class="d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <table class="font-weight-normal mt-3 mb-3 w-100 border-r-desk">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Name: <?php echo $name;?>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>DOB: <?php echo $dob;?></td>
                                                    </tr>
                                                    <!--<tr>-->
                                                    <!--   <td>05/02/2017</td>-->
                                                    <!--</tr>-->
                                                    <tr>
                                                        <td>Class: <?php echo $sclass;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6" style="margin-top:8px;">
                                            <table class="mt-3 mb-3">
                                                <!--<tr>-->
                                                <!--   <th>Profit Center :</th>-->
                                                <!--   <th class="font-weight-normal width55"> - </th>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <th>Phone :</th>
                                                    <td class="font-weight-normal"><?php echo $smobile;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Gender : </th>
                                                    <td class="font-weight-normal"><?php if($sex=='M'){ echo'Male';}elseif($sex=='F'){ echo 'Female';}else{ echo $sex;} ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Email address :</th>
                                                    <td class="font-weight-normal"><?php echo $email;?></td>
                                                </tr>
                                                <tr>
                                                    <th>Admission No :</th>
                                                    <td class="font-weight-normal"><?php echo $sadmission;?></td>
                                                </tr>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="container-fluid studentprofile">
                            <div class="card" style="padding:0">
                                <h5 class="card-header" style="color:white;background-color:#191970">
                                    <i class="fa fa-globe"></i>&nbsp;Student Management
                                </h5>
                                <div class="card-body" style="padding:10px">
                                    <div class="margin-bottom-5">
                                    </div>
                                    <div id="loading-image" style="display: none;">
                                        <img src="images/loading.gif"
                                            style="position: absolute; top:300px;left:815px;height:75px;width:75px;z-index: 100;"
                                            alt="Loading...">
                                    </div>
                                    
                            
                            <ul class="nav nav-tabs">
                              <li class="nav-item" id="tab1" onclick="changeborder(this.id)">
                                <a class="nav-link active"   data-toggle="tab" href="#tab_15_1"><b>Basic Info</b></a>
                              </li>
                              
                              <li class="nav-item" id="tab3" onclick="changeborder(this.id)">
                                <a class="nav-link"   data-toggle="tab" href="#tab_15_3"><b>Academics</b></a>
                              </li>
                              
                              <li class="nav-item" id="tab2" onclick="changeborder(this.id)">
                                <a class="nav-link"   data-toggle="tab" href="#tab_15_2"><b>Achievement</b></a>
                              </li>
                              
                              <li class="nav-item" id="tab4" onclick="changeborder(this.id)">
                                <a class="nav-link"   data-toggle="tab" href="#tab_15_4"><b>Health</b></a>
                              </li>
                              <li class="nav-item" id="tab5" onclick="changeborder(this.id)">
                                <a class="nav-link"   data-toggle="tab" href="#tab_15_5"><b>Behaviour</b></a>
                              </li>
                            </ul>
                            
                            <!-- Tab panes -->
                            <div class="tab-content">
                              <div class="tab-pane  active" id="tab_15_1"><?php include('tabs/student/student_basic_info.php') ?></div>
                              <div class="tab-pane" id="tab_15_3"><?php include('tabs/student/student_remarks.php') ?></div>
                              <div class="tab-pane" id="tab_15_2"><?php include('tabs/student/student_achievments.php') ?></div>
                              <div class="tab-pane" id="tab_15_4"><?php include('tabs/student/student_health.php') ?></div>
                              <div class="tab-pane" id="tab_15_5"><?php include('tabs/student/student_behaviour.php') ?></div>
                            </div>
                            
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            </section>


        </div><!-- container //  -->

    </div>
    </div>

<!--end page contents-->

<script>
// $(document).ready(function(){
//     document.getElementById('tab1').style.borderBottom='3px solid red';
// });

//     function changeborder(tabs){
//         document.getElementById('tab1').style.borderBottom='';
//         document.getElementById('tab2').style.borderBottom='';
//         document.getElementById('tab3').style.borderBottom='';
//         document.getElementById('tab4').style.borderBottom='';
//         document.getElementById('tab5').style.borderBottom='';
//         document.getElementById(tabs).style.borderBottom='3px solid red';
//     }
    
    function showmoredata(adm){
        $('#btnachievement').css('display','none');
        $('#show_admdata').css('display','block');
        $.ajax({
         url:'showstudentdata.php',
         type:'POST',
         data:{adm_no:adm},
         dataType:'html',
         success:function(res){
             $('#show_admdata').html(res);
             $('#btnachievementless').css('display','block');
         }
        });
    }
    
    function showmoredataremark(adm){
        $('#btnremark').css('display','none');
        $('#show_admdataremark').css('display','block');
        $.ajax({
         url:'showstudentdata.php',
         type:'POST',
         data:{admno:adm,
               remarks:"remarks"
         },
         dataType:'html',
         success:function(res){
             $('#show_admdataremark').html(res);
             $('#btnremarkless').css('display','block');
         }
        });
    }
    
    function showmoredatahealth(adm){
        $('#btnhealth').css('display','none');
        $('#show_admdatahealth').css('display','block');
        $.ajax({
         url:'showstudentdata.php',
         type:'POST',
         data:{admission:adm,
               health:"health"
         },
         dataType:'html',
         success:function(res){
             $('#show_admdatahealth').html(res);
             $('#btnhealthless').css('display','block');
         }
        });
    }
    
    function showmoredatabehave(adm){
        $('#btnbehave').css('display','none');
        $('#show_admdatabehave').css('display','block');
        $.ajax({
         url:'showstudentdata.php',
         type:'POST',
         data:{admission_no:adm,
               behave:"behave"
         },
         dataType:'html',
         success:function(res){
             $('#show_admdatabehave').html(res);
             $('#btnbehaveless').css('display','block');
         }
        });
    }
    
    function showlessdata(val){
        if(val=='behave'){
            $('#btnbehaveless').css('display','none');
            $('#show_admdatabehave').css('display','none');
            $('#btnbehave').css('display','block');
            
        }else if(val=='achievement'){
            
            $('#btnachievementless').css('display','none');
            $('#show_admdata').css('display','none');
            $('#btnachievement').css('display','block');
            
        }else if(val=='remark'){
            
            $('#btnremarkless').css('display','none');
            $('#show_admdataremark').css('display','none');
            $('#btnremark').css('display','block');
            
        }else if(val=='health'){
            
            $('#btnhealthless').css('display','none');
            $('#show_admdatahealth').css('display','none');
            $('#btnhealth').css('display','block');
        }
    }
</script>


</body>
</html>