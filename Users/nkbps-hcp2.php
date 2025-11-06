<?php
session_start();
require '../connection.php';
require '../AppConf.php';

$StudentName = $_SESSION['StudentName'];
$class = $_SESSION['StudentClass'];
$AdmissionId = $_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];
$currentdate = date("Y-m-d");

// variables used
$Exp1='Experience1';
$Exp2='Experience2';

$cg1='CG-1';
$cg2='CG-2';
$cg3='CG-3';
$cg4='CG-4';
$cg5='CG-5';
$cg6='CG-6';
$cg7='CG-7';
$cg8='CG-8';
$cg9='CG-9';
$cg10='CG-10';
$cg11='CG-11';
$cg12='CG-12';
$cg13='CG-13';

$phy='PHYSICAL AND MOTOR DEVELOPMENT';
$social='SOCIAL EMOTIONAL AND ETHICAL DEVELOPMENT';
$cognitive='COGNITIVE DEVELOPMENT';
$language='LANGUAGE AND LITERACY DEVELOPMENT';
$creative='CREATIVE AESTHETIC AND CULTURAL DEVELOPMENT';
$positive='POSITIVE LERANING HABITS';

$query = mysqli_query($Con, "SELECT `financialyear`,year FROM `FYmaster` where `status`='Active' ");
$rowS = mysqli_fetch_row($query);
$financialyear = $rowS[0];
$CurrentFinancialYear = $rowS[1];

if (empty($AdmissionId)) {
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Please login from your respected ERP");
	exit();
}

$getsql = mysqli_query($Con, "select * from student_master where sadmission='$AdmissionId'");
$getstudentdata = mysqli_fetch_array($getsql);
extract($getstudentdata);

if ($emptype != 'employee') {
	$class_array = ['I', 'II'];

	$ch_class = explode("-", $sclass);
	$stu_ch_class = $ch_class[0];

	if (!in_array($stu_ch_class, $class_array)) {
		echo ("<br><br><center><b>You are trying with wrong URL!");
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Card</title>
    <link rel="stylesheet" href="hcp-images/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
        <div class="containerr">
            <h2 class="underline">Key Competencies with Curricular Goals</h2>
            <h3 class="underline">Class-<?php echo $stu_ch_class;?></h3>
            <h3>Session: <?php echo $financialyear;?></h3>
            <?php
                $healthsql=mysqli_query($Con, "select * from student_his_health_wellness where FY='$CurrentFinancialYear' and sclass='$StudentClass' and sadmission='$AdmissionId'");
                $fetchhealthrow=mysqli_fetch_assoc($healthsql);
            
				$rsAttendanceDetail = mysqli_query($Con, "SELECT `attendance`,`total_days` FROM `exam_attendance` WHERE `sadmission`='$AdmissionId'  and `exam_code`='E8' and financialyear='$CurrentFinancialYear'");
				$rsAttendanceDetail1 = mysqli_query($Con, "SELECT `attendance`,`total_days` FROM `exam_attendance` WHERE `sadmission`='$AdmissionId'  and `exam_code`='E9' and financialyear='$CurrentFinancialYear'");
				$rowAt = mysqli_fetch_row($rsAttendanceDetail);
				$Attendanceterm1 = $rowAt[0];
				$TotalAttendanceDaysterm1 = $rowAt[1];

				// $att_percentterm1 = 0;
				// $att_percentterm1 = number_format(($Attendanceterm1 / $TotalAttendanceDaysterm1) * 100, 2);

				$rowAt1 = mysqli_fetch_row($rsAttendanceDetail1);
				$Attendanceterm2 = $rowAt1[0];
				$TotalAttendanceDaysterm2 = $rowAt1[1];

				// $att_percentterm2 = 0;
				// $att_percentterm2 = number_format(($Attendanceterm2 / $TotalAttendanceDaysterm2) * 100, 2);

			?>
            <table class="header-table">
                <tr>
                    <th colspan="2">Personal Domain</th>
                    <th>Experience 1</th>
                    <th>Experience 2</th>
                </tr>
                <tr>
                    <td colspan="2">General Health</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Height</td>
                    <td class='text-center'><?php if(!empty($fetchhealthrow['height'])){echo (stristr($fetchhealthrow['height'],'cm'))?$fetchhealthrow['height']:$fetchhealthrow['height'].' cm';}?></td>
                    <td class='text-center'><?php if(!empty($fetchhealthrow['height'])){echo (stristr($fetchhealthrow['height'],'cm'))?$fetchhealthrow['height']:$fetchhealthrow['height'].' cm';}?></td>
                </tr>
                <tr>
                    <td colspan="2">Weight</td>
                    <td class='text-center'><?php if(!empty($fetchhealthrow['weight'])){echo (stristr($fetchhealthrow['weight'],'kg'))?$fetchhealthrow['weight']:$fetchhealthrow['weight'].' kg';}?></td>
                    <td class='text-center'><?php if(!empty($fetchhealthrow['weight'])){echo (stristr($fetchhealthrow['weight'],'kg'))?$fetchhealthrow['weight']:$fetchhealthrow['weight'].' kg';}?></td>
                </tr>
                <tr>
                    <th colspan="2">Attendance</th>
                    <td class='text-center'><?php echo (!empty($Attendanceterm1))?$Attendanceterm1."/".$TotalAttendanceDaysterm1:'/';?></td>
                    <td class='text-center'><?php echo (!empty($Attendanceterm2))?$Attendanceterm2."/".$TotalAttendanceDaysterm2:'/';?></td>
                </tr>
                <tr>
                    <td class="text-center col-3">Punctual to school</td>
                    <td class="text-center">Yes</td>
                    <td class='text-center'></td>
                    <td class='text-center'></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">No</td>
                    <td class='text-center'></td>
                    <td class='text-center'></td>
                </tr>
            </table>

            <div class="heading">
                <h3 class="fs-5">CG-1 Children develop habits that keep them healthy and safe.</h3>
            </div>
            <?php
                $cg1sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$phy' and `hie`.`indicatortype`='CG-1' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg1sql);
            ?>
            <table class="progress-indicators">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 light-blue">
                        <p class="verticle-text underline m-0">DOMAIN: PHYSICAL AND MOTOR DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg1sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg1' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg1' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG-2 Children develop sharpness in sensorial perceptions</h3>
            </div>
            <?php
                $cg2sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$phy' and `hie`.`indicatortype`='$cg2' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg2sql);
            ?>
            <table class="progress-indicators mt-3 mb-5">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 light-blue">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: PHYSICAL AND<br><br> MOTOR DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg2sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg2' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg2' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
                
            </table>

            <div class="heading">
                <h3 class="fs-5">CG-3 Children develop a fit and flexible body</h3>
            </div>
            <?php
                $cg3sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$phy' and `hie`.`indicatortype`='$cg3' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg3sql);
            ?>
            <table class="progress-indicators mt-3">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 light-blue">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: PHYSICAL AND<br><br> MOTOR DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg3sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg3' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$phy' and indicatortype='$cg3' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG-4: Children develop emotional intelligence, i.e, the ability to
                    understand and manage their own emotions, and respond positively
                    to social norms</h3>
            </div>
            <?php
                $cg4sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$social' and `hie`.`indicatortype`='$cg4' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg4sql);
            ?>
            <table class="progress-indicators mt-4 mb-5">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 pink">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: SOCIAL EMOTIONAL AND<br><br>ETHICAL
                            DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg4sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg4' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg4' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>

            <div class="heading">
                <h3 class="fs-5">CG-5 Children develop a positive attitude towards productive work and
                    service or 'Seva'.</h3>
            </div>
            <?php
                $cg5sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$social' and `hie`.`indicatortype`='$cg5' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg5sql);
            ?>
            <table class="progress-indicators mt-4">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 pink">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: SOCIAL EMOTIONAL AND<br><br>ETHICAL
                            DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg5sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg5' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg5' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG-6 Children develop a positive regard for the natural environment
                    around them.</h3>
            </div>
            <?php
                $cg6sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$social' and `hie`.`indicatortype`='$cg6' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg6sql);
            ?>
            <table class="progress-indicators mt-4 mb-5">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 pink">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: SOCIAL EMOTIONAL AND<br><br>ETHICAL
                            DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg6sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg6' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$social' and indicatortype='$cg6' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>

            <div class="heading">
                <h3 class="fs-5">CG-7 Children make sense of world around through observation and logical
                    thinking .
                </h3>
            </div>
            <?php
                $cg7sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$cognitive' and `hie`.`indicatortype`='$cg7' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg7sql);
            ?>
            <table class="progress-indicators mt-4">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 grey">
                        <p class="verticle-text underline m-0">DOMAIN: COGNITIVE DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg7sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$cognitive' and indicatortype='$cg7' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$cognitive' and indicatortype='$cg7' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG-8 Children develop mathematical understanding and abilitiesto
                    recognize the world through quantities, shapes, and measures.
                </h3>
            </div>
            <?php
                $cg8sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$cognitive' and `hie`.`indicatortype`='$cg8' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg8sql);
            ?>
            <table class="progress-indicators mt-4">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 grey">
                        <p class="verticle-text underline m-0">DOMAIN: COGNITIVE DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg8sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$cognitive' and indicatortype='$cg8' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$cognitive' and indicatortype='$cg8' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG-9 Children develop effective communication skills for day-to-day interactions
                </h3>
            </div>
            <?php
                $cg9sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$language' and `hie`.`indicatortype`='$cg9' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg9sql);
            ?>
            <table class="progress-indicators mb-2">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 violet">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: LANGUAGE AND LITERACY<br><br> DEVELOPMENT
                        </p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg9sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg9' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg9' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>

            <div class="heading">
                <h3 class="fs-5">CG- 10 Children develop fluency in reading and writing in Language 1 ( L-1)</h3>
            </div>
            <?php
                $cg10sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$language' and `hie`.`indicatortype`='$cg10' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg10sql);
            ?>
            <table class="progress-indicators">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 violet">
                        <p class="verticle-text underline m-0">DOMAIN: LANGUAGE AND LITERACY<br><br> DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg10sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg10' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg10' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG- 11 Children begin to read and write, and listen in Language-2 ( L-2)</h3>
            </div>
            <?php
                $cg11sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$language' and `hie`.`indicatortype`='$cg11' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg11sql);
            ?>
            <table class="progress-indicators mt-3">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 violet">
                        <p class="verticle-text underline m-0">DOMAIN: LANGUAGE AND LITERACY<br><br> DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg11sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg11' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$language' and indicatortype='$cg11' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="heading">
                <h3 class="fs-5">CG- 12 Children develop abilities and sensibilities in visual and
                    performing arts and express their emotions through art in meaningful and joyful ways.</h3>
            </div>
            <?php
                $cg12sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$creative' and `hie`.`indicatortype`='$cg12' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg12sql);
            ?>
            <table class="progress-indicators mt-3 mb-5">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 pink">
                        <p class="verticle-text underline m-0 pe-0">DOMAIN: CREATIVE, AESTHETIC AND <br><br>CULTURAL
                            DEVELOPMENT</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg12sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$creative' and indicatortype='$cg12' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$creative' and indicatortype='$cg12' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>

            <div class="heading">
                <h3 class="fs-5">CG-13 Children develop habits of learning that allow them to engage
                    actively in formal learning environments like a school classroom.</h3>
            </div>
            <?php
                $cg13sql=mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`eim`.`indicator_priority` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`subindicator`='$positive' and `hie`.`indicatortype`='$cg13' order by cast(`eim`.`indicator_priority` as unsigned)");
                $rowspan=mysqli_num_rows($cg13sql);
            ?>
            <table class="progress-indicators mt-3 mb-4">
                <tr>
                    <th rowspan="<?php echo ($rowspan+3);?>" class="col-1 violet">
                        <p class="verticle-text underline m-0">DOMAIN: POSITIVE LERANING HABITS</p>
                    </th>
                </tr>
                <tr class="green">
                    <th rowspan="2" colspan="2" class="col-5 ">PROGRESS INDICATORS</th>
                    <th colspan="3">Experience 1</th>
                    <th colspan="3">Experience 2</th>
                </tr>
                <tr class="green textcenter">
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                    <td><img src="hcp-images/emoji.svg"></td>
                    <td><img src="hcp-images/flower.svg"></td>
                    <td><img src="hcp-images/tree.svg"></td>
                </tr>
                <?php
                    $srno=1;
                    while($fetchCG1=mysqli_fetch_array($cg13sql)){
                    $indicator=$fetchCG1[0];
                ?>
                <tr>
                    <th class="col1"><?php echo $srno;?></th>
                    <td><?php echo $indicator;?></td>
                <?php
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$positive' and indicatortype='$cg13' and exam_type='$Exp1' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php 
                    } 
                    
                    $gradesql=mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$positive' and indicatortype='$cg13' and exam_type='$Exp2' and indicator_desc='$indicator'");
                    $graderow=mysqli_fetch_array($gradesql);
                    for($i=0;$i<=2;$i++){
                ?>
                    <td class='textcenter'><?php if($i==0){if($graderow[0]=='B'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==1){if($graderow[0]=='P'){?><img src="hcp-images/Tick.png"><?php }}elseif($i==2){if($graderow[0]=='A'){?><img src="hcp-images/Tick.png"><?php }}?></td>
                <?php } ?> 
                </tr>
               <?php $srno++;} ?>
            </table>

            <div class="block violet mt-3">
                <div class="box1 text-center">
                    <img src="hcp-images/emoji.svg" alt="">
                    <p>Beginner</p>
                </div>
                <div class="box2 text-center">
                    <img src="hcp-images/flower.svg" alt="">
                    <p>Progressing</p>
                </div>
                <div class="box3 text-center">
                    <img src="hcp-images/tree.svg" alt="">
                    <p>Proficient</p>
                </div>
            </div>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <div class="logo text-center">
                <img src="hcp-images/log.JPG" alt="">
            </div>
            <?php
                $pfeedsql=mysqli_query($Con, "SELECT * FROM `hcp_parentfeedback` where sadmission='$AdmissionId'");
                $pfeedrow=mysqli_fetch_array($pfeedsql);
            ?>
            <table class="progress-indicators mt-3">
                <tr class="green">
                    <th rowspan="2">S.No</th>
                    <th rowspan="2" colspan="2" class="col-5">QUESTIONNAIRE</th>
                    <th colspan="3">Experience 1</th>
                </tr>
                <tr class="green">
                    <th class="text-center">Always</th>
                    <th class="text-center">Sometimes</th>
                    <th class="text-center">Rarely</th>
                </tr>
                <tr>
                    <th class="col1">1</th>
                    <td colspan="2">Do you discuss your child’s emotional and academic needs regularly?
                    </td>
                    <td class='textcenter'><?php if($pfeedrow[2]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[2]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[2]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>2</th>
                    <td colspan="2">Do you cooperate in your child’s academic and
                        extracurricular achievements?</td>
                    <td class='textcenter'><?php if($pfeedrow[3]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[3]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[3]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>3</th>
                    <td colspan="2">Do you always answer your child's inquisitive queries?</td> 
                    <td class='textcenter'><?php if($pfeedrow[4]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[4]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[4]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>4</th>
                    <td colspan="2">How often do you go for outings with your child?</td>
                    <td class='textcenter'><?php if($pfeedrow[5]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[5]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[5]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>5</th>
                    <td colspan="2">How often do you have meals together with your child?</td>
                    <td class='textcenter'><?php if($pfeedrow[6]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[6]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[6]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>6</th>
                    <td colspan="2">Are you satisfied with the academic progress of your child ?</td>
                    <td class='textcenter'><?php if($pfeedrow[7]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[7]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[7]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>7</th>
                    <td colspan="2">How often do you spend quality ( face to face ) time with your child?</td>
                    <td class='textcenter'><?php if($pfeedrow[8]=='Always'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[8]=='Sometimes'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                    <td class='textcenter'><?php if($pfeedrow[8]=='Rarely'){?><img src="hcp-images/Tick.png"><?php } ?></td>
                </tr>
                <tr>
                    <th>8</th>
                    <td colspan="2">How do you show affection and positive reinforcement to your child?</td>
                    <td colspan="3"><?php if(!empty($pfeedrow[9])){echo $pfeedrow[9]; } ?></td>
                </tr>
                <tr>
                    <th>9</th>
                    <td colspan="2">Would you like to support the school in any activity with your talent?</td>
                    <td colspan="3"><?php if(!empty($pfeedrow[10])){echo $pfeedrow[10]; } ?></td>
                </tr>
                <tr>
                    <th>10</th>
                    <td colspan="2">Any other suggestion you would like to make ?</td>
                    <td colspan="3"><?php if(!empty($pfeedrow[11])){echo $pfeedrow[11]; } ?></td>
                </tr>
                <tr>
                    <th>11</th>
                    <td colspan="2">Signature / Contact No.<br></td>
                    <td colspan="3"><?php if(!empty($pfeedrow[12])){echo $pfeedrow[12]; } ?></td>
                </tr>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="containerr">
            <?php 
			    $tfeedsql1=mysqli_query($Con, "SELECT * FROM `hcp_teacherfeedback` where sadmission='$AdmissionId' and sclass='$StudentClass' and financial_year='$CurrentFinancialYear' and exam_type='Experience 1'");
			    $tfeedsql2=mysqli_query($Con, "SELECT * FROM `hcp_teacherfeedback` where sadmission='$AdmissionId' and sclass='$StudentClass' and financial_year='$CurrentFinancialYear' and exam_type='Term 2'");
			    $fetchfeedrow1=mysqli_fetch_assoc($tfeedsql1);
			    $fetchfeedrow2=mysqli_fetch_assoc($tfeedsql2);
			?>
            <h3 class="yellow mb-4">Teacher's feedback</h3>
            <h4 class="mb-5">EXPERIENCE-1</h4>
            <div class="border-bott"><?php echo $fetchfeedrow1['feedback'];?></div>
            <div class="border-bott"></div>
            <div class="border-bott"></div>
            <div class="border-bott mb-5"></div>
            <h4 class="mb-4">CONGRATULATIONS!</h4>
            <div class="box grey">
                <p>Miss / Master___________ moves a</p>
                <p>step forward to class____________</p>
                <p>w.e.f. _________</p>
            </div>
            <div class="d-flex align-item-center justify-content-between fs-3">
                <div class="1">
                    <div class="small-line"></div>
                    <p>Class Teacher</p>
                </div>
                <div class="2">
                    <div class="small-line"></div>
                    <p>Incharge</p>
                </div>
                <div class="3">
                    <div class="small-line"></div>
                    <p>Principal</p>
                </div>
                <div class="4">
                    <div class="small-line"></div>
                    <p>Parent</p>
                </div>
            </div>


        </div>


    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>