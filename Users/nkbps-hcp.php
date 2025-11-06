<?php
session_start();
require '../connection.php';
require '../AppConf.php';

$StudentName = $_SESSION['StudentName'];
$class = $_SESSION['StudentClass'];
$AdmissionId = $_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];
$currentdate = date("Y-m-d");

$query = mysqli_query($Con, "SELECT `financialyear`,year FROM `FYmaster` where `status`='Active' ");
$rowS = mysqli_fetch_row($query);
$financialyear = $rowS[0];
$CurrentFinancialYear = $rowS[1];


$sqlemp = mysqli_query($Con, "select EmpId from employee_master where EmpId='$AdmissionId'");
$sqlstu = mysqli_query($Con, "select sadmission from student_master where sadmission='$AdmissionId'");
if (!empty(mysqli_num_rows($sqlemp))) {
	$emptype = 'employee';
} else if (!empty(mysqli_num_rows($sqlstu))) {
	$emptype = 'student';
}

if (empty($AdmissionId)) {
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Please login from your respected ERP");
	exit();
}

$datasql = mysqli_query($Con, "select * from hcpreport_data where sadmission='$AdmissionId'");
if (mysqli_num_rows($datasql) > 0) {
	$alreadyfilled = 'Yes';
	$filled_data = mysqli_fetch_array($datasql);
	extract($filled_data);
}

$getsql = mysqli_query($Con, "select * from student_master where sadmission='$AdmissionId'");
$getstudentdata = mysqli_fetch_array($getsql);
extract($getstudentdata);

if ($emptype != 'employee') {
	$class_array = ['VI', 'VII', 'VIII'];

	$ch_class = explode("-", $sclass);
	$stu_ch_class = $ch_class[0];

	if (!in_array($stu_ch_class, $class_array)) {
		echo ("<br><br><center><b>You are trying with wrong URL!");
		exit();
	}
}

$keyperform = 'Key Performer Descriptors';
$teacherfeed = 'Teacher Feedback';
$areastrength = 'Areas of strength';
$barrier = 'Barrier';
$term1 = 'Term 1';
$term2 = 'Term 2';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>N K BAGRODIA PUBLIC SCHOOL</title>
	<link rel="stylesheet" href="nkbpshcp.css">
	<link rel="icon" type="image/png/jpg/jpeg" href="PRFIMGUSR17201664426806.png">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript" src="../mysweetalert/sweetalert.js"></script>
	<style>
		<?php
		if ($emptype == 'student') {
		?>.pdatadiv,
		.partA4 {
			display: block;
		}

		<?php
		} else if ($emptype == 'employee') {
		?>.partc {
			display: block;
		}

		<?php }
		?>table#performertable tr td img,
		table.feedbacktable tr td img {
			height: 30px;
		}

		.textcenter {
			text-align: center;
		}

		#termI,
		#termII {
			width: 15%;
			background-color: #fcece8;
			text-align: center;
			color: black;
		}

		#space {
			width: 40%;
			background-color: #fcece8;
			text-align: center;
			color: black;
		}

		#based {
			font-size: large;
		}

		@media print {

			#performertable th,
			td {
				padding: 7px;
			}

			table#performertable tr td img,
			table.feedbacktable tr td img {
				height: 20px;
			}

			.partA4 {
				zoom: 110%;
			}

			.pdatadiv {
				zoom: 102%;
			}
	</style>

</head>

<body>
	<button class="sticky-button" onclick='savedata()'>Save</button>
	<button class="sticky-button-print" id='printbtn' onclick='handlePrint()'>Print</button>
	<div>
		<header>
			<h2>N K BAGRODIA PUBLIC SCHOOL<br>
				SECTOR-9, ROHINI </h2>
		</header>
		<hr>
		<form name='studenthcpdata' id='studenthcpdata' action="">
			<input type='hidden' name='already_filled' id='already_filled' value='<?php echo $alreadyfilled; ?>'>
			<input type='hidden' name='sadmission' id='sadmission' value='<?php echo $AdmissionId; ?>'>
			<input type='hidden' name='stu_class' id='stu_class' value='6to8'>

			<div class='pdatadiv'>
				<h3>GENERAL INFORMATION </h3>

				<div class="flex-container"></div>
				<div class="flex-box"></div>
				<div class="section1" style='margin-top:35px'>
				</div>
				<div class="question">
					<label for="studentName">Student Name:</label>
					<input type="text" id="studentName" name="hcp1" value='<?php echo $sname; ?>' class='hcpreadonly'>
				</div>
				<div class="question">
					<label for="rollNo">Roll No :</label>
					<input type="number" id="rollNo" name="hcp2" value='<?php echo $srollno; ?>' class='hcpreadonly'>
					<label for="regNo">Admission No :</label>
					<input type="text" id="regNo" name="hcp3" value='<?php echo $sadmission; ?>' class='hcpreadonly'>
				</div>
				<div class="question">
					<div class="classdiv">
						<label for="class">Class:</label>
						<div class="label-group">
							<span>Grade 6</span>
							<label for="Grade 6">
								<?php
								$class_str = explode("-", $sclass);
								$stuclass = $class_str[0];
								$stusection = $class_str[1];
								?>
								<input type="checkbox" class='gradecheckbox' value="Grade 6" name="hcp4" <?php if ($stuclass == 'VI') { ?> checked<?php } ?>>
							</label>
							<span class='gradespan'>Grade 7</span>
							<label for="Grade 7">
								<input type="checkbox" class='gradecheckbox' value="Grade 7" name="hcp4" <?php if ($stuclass == 'VII') { ?> checked<?php } ?>>
							</label>
							<span class='gradespan'>Grade 8</span>
							<label for="Grade 8">
								<input type="checkbox" class='gradecheckbox' value="Grade 8" name="hcp4" <?php if ($stuclass == 'VIII') { ?> checked<?php } ?>>
							</label>
						</div>
					</div>
					<br>

					<label for="section">Section:</label>
					<input type="text" id="section" name="hcp5" class='inputstyle hcpreadonly' value='<?php echo $stusection; ?>'>

					<label for="date">Date of Birth:</label>
					<input type="text" id="dob" name="hcp6" class='inputstyle hcpreadonly' value='<?php echo $DOB; ?>'>

					<br>
					<label for="Address">Address:</label>
					<input type="text" id="Address" name="hcp7" value='<?php echo $Address; ?>' class='hcpreadonly'>

					<br>
					<input type="text" id="Address1" name="hcp7_1" class='hcpreadonly'>
					<label for="tel">Phone No.</label>
					<input type="text" id="PhoneNo" name="hcp8" class='inputstyle hcpreadonly' value='<?php echo $smobile; ?>'>
				</div>

				<div class="question">
					<label for="motherName">Mother/Guardian Name:</label>
					<input type="text" id="motherName" name="hcp9" value='<?php echo $MotherName; ?>' class='hcpreadonly'>
				</div>

				<div class="question">
					<label for="motherEducation">Mother/Guardian Education:</label>
					<input type="text" id="motherEducation" name="hcp10" value='<?php echo $MotherEducation; ?>' class='hcpreadonly'>
					<label for="motherOccupation">Mother/Guardian Occupation:</label>
					<input type="text" id="motherOccupation" name="hcp11" value='<?php echo $MotherOccupatoin; ?>' class='hcpreadonly'>
				</div>

				<div class="question">
					<label for="fatherName">Father/Guardian Name:</label>
					<input type="text" id="fatherName" name="hcp12" value='<?php echo $sfathername; ?>' class='hcpreadonly'>
				</div>

				<div class="question">
					<label for="fatherEducation">Father/Guardian Education:</label>
					<input type="text" id="fatrherEducation" name="hcp13" value='<?php echo $FatherEducation; ?>' class='hcpreadonly'>
					<label for="fatherOccupation">Father/Guardian Occupation:</label>
					<input type="text" id="fatherOccupation" name="hcp14" value='<?php echo $FatherOccupation; ?>' class='hcpreadonly'>
				</div>

				<div class="question">
					<label for="Number Of Siblings:">Number Of Siblings:</label>
					<input type="text" id="NumberOfSiblings" name="hcp15" value='' class='hcpreadonly'>
					<label for="Sibling’s Age">Sibling’s Age:</label>
					<input type="text" id="SiblingsAge" name="hcp16" class='inputstyle hcpreadonly'>
				</div>

				<div class="question">
					<label for="Mother Tongue:">Mother Tongue:</label>
					<input type="text" id="MotherTongue" name="hcp17" value='<?php echo $MotherTongue; ?>' class='hcpreadonly'>
					<label for="Medium of Instruction">Medium of Instruction:</label>
					<input type="text" id="Mediumofnstruction" name="hcp18" class='inputstyle hcpreadonly' <?php if (!empty($hcp18)) { ?>value='<?php echo $hcp18; ?>' <?php } else { ?>value='English' <?php } ?>>
				</div>

				<!-- Attendance Section -->
				<div class="section">
					<h4>Attendance:</h4>
				</div>
				<div>
					<?php
					$rsAttendanceDetail = mysqli_query($Con, "SELECT `attendance`,`total_days` FROM `exam_attendance` WHERE `sadmission`='$AdmissionId'  and `exam_code`='E8' and financialyear='$CurrentFinancialYear'");
					$rsAttendanceDetail1 = mysqli_query($Con, "SELECT `attendance`,`total_days` FROM `exam_attendance` WHERE `sadmission`='$AdmissionId'  and `exam_code`='E9' and financialyear='$CurrentFinancialYear'");
					$rowAt = mysqli_fetch_row($rsAttendanceDetail);
					$Attendanceterm1 = $rowAt[0];
					$TotalAttendanceDaysterm1 = $rowAt[1];

					$att_percentterm1 = 0;
					$att_percentterm1 = number_format(($Attendanceterm1 / $TotalAttendanceDaysterm1) * 100, 2);

					$rowAt1 = mysqli_fetch_row($rsAttendanceDetail1);
					$Attendanceterm2 = $rowAt1[0];
					$TotalAttendanceDaysterm2 = $rowAt1[1];

					$att_percentterm2 = 0;
					$att_percentterm2 = number_format(($Attendanceterm2 / $TotalAttendanceDaysterm2) * 100, 2);

					?>
					<table>
						<thead>
							<tr>
								<th>MONTHS</th>
								<th>Term I</th>
								<th>Term II</th>

							</tr>
						</thead>
						<tbody>
							<tr>
								<td class='addendancetable'>No. of Working Days</td>
								<td class='addendancetable tdincenter'><?php echo $TotalAttendanceDaysterm1; ?></td>
								<td class='addendancetable tdincenter'><?php echo $TotalAttendanceDaysterm2; ?></td>
							</tr>
							<tr>
								<td class='addendancetable'>No. of Days Attended</td>
								<td class='addendancetable tdincenter'><?php echo $Attendanceterm1; ?></td>
								<td class='addendancetable tdincenter'><?php echo $Attendanceterm2; ?></td>
							</tr>
							<tr>
								<td class='addendancetable'>% of Attendance</td>
								<td class='addendancetable tdincenter'><?php echo $att_percentterm1; ?></td>
								<td class='addendancetable tdincenter'><?php echo $att_percentterm2; ?></td>
							</tr>
							<tr>
								<td class='addendancetable'>If attendance is low then reasons thereof</td>
								<td class='addendancetable tdincenter'>---</td>
								<td class='addendancetable tdincenter'>---</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="page-break-after: always;"></div>

			<div class='partA4'>
				<div>
					<h2 style='color: orangered;margin-bottom:3px;margin-top:;'>PART A</h2>
					<h2 style='color: orangered;margin-bottom:10px;margin-top:4px;'>Parent-teacher partnership card</h2>
					<label for="Tick the resources available to your child at home.">Tick the resources available to your child at home.</label>
				</div>

				<div class="question resourcesathome">
					<div class="classdiv">
						<div class="label-group1">
							<span>Books</span>
							<label for="Books">
								<input type="checkbox" id="Books" value='Books' name="hcp91" <?php if (!empty($hcp91)) { ?>checked<?php } ?>>
							</label>

							<span class='gradespan1'>Magazines</span>
							<label for="Magazines">
								<input type="checkbox" id="Magazines" value="Magazines" name="hcp92" <?php if (!empty($hcp92)) { ?>checked<?php } ?>>
							</label>

							<span class='gradespan1'>Toys and Game</span>
							<label for="Toys and Game">
								<input type="checkbox" id="Toys and Game" value="Toys and Game" name="hcp93" <?php if (!empty($hcp93)) { ?>checked<?php } ?>>
							</label>

							<span class='gradespan1'>Mobile Phone</span>
							<label for="Mobile Phone">
								<input type="checkbox" id="Mobile Phone" value="Mobile Phone" name="hcp94" <?php if (!empty($hcp94)) { ?>checked<?php } ?>>
							</label>

							<span class='gradespan1'>Computer</span>
							<label for="Computer">
								<input type="checkbox" id="Computer" value="Computer" name="hcp95" <?php if (!empty($hcp95)) { ?>checked<?php } ?>>
							</label>

							<span class='gradespan1'>Internet</span>
							<label for="Internet">
								<input type="checkbox" id="Internet" value="Internet" name="hcp96" <?php if (!empty($hcp96)) { ?>checked<?php } ?>>
							</label>

						</div>
					</div>
				</div>

				<div class="fulltable">
					<table id="datatable">
						<thead>
							<tr>
								<th colspan="5">Understanding of my Child</th>
							</tr>
							<tr>
								<th colspan="5" id="colspanth">Circle the most appropriate option for your child at home.</th>
							</tr>
							<tr>
								<th id="space"></th>
								<th id="termI">Yes</th>
								<th id="termII">No</th>
								<th id="termI">Sometimes</th>
								<th id="termII">Not Sure</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td valign="top" class='childtd'>1. My child seems motivated to learn and engage with new concepts learnt at school.</td>

								<td class='textcenter'>
									<input type="checkbox" name="hcp97_1" id='learntatschool1_yes' value="Yes" <?php if ($hcp97_1 == 'Yes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp97_1" id='learntatschool1_no' value="No" <?php if ($hcp97_1 == 'No') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp97_1" id='learntatschool1_sometime' value="Sometimes" <?php if ($hcp97_1 == 'Sometimes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp97_1" id='learntatschool1_notsure' value="Not Sure" <?php if ($hcp97_1 == 'Not Sure') { ?>checked<?php } ?>>
								</td>
							</tr>

							<tr>
								<td valign="top" class='childtd'>2. My child follows a schedule at home that includes curricular & extra-curricular activities, social connectivity and screen time.</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp98_1" id='extracurricular1_yes' value="Yes" <?php if ($hcp98_1 == 'Yes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp98_1" id='extracurricular1_no' value="No" <?php if ($hcp98_1 == 'No') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp98_1" id='extracurricular1_sometime' value="Sometimes" <?php if ($hcp98_1 == 'Sometimes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp98_1" id='extracurricular1_notsure' value="Not Sure" <?php if ($hcp98_1 == 'Not Sure') { ?>checked<?php } ?>>
								</td>
							</tr>
							<tr>
								<td valign="top" class='childtd'>3. My child finds the grade-level curriculum difficult and needs additional support.</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp99_1" id='curriculamadditional1_yes' value="Yes" <?php if ($hcp99_1 == 'Yes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp99_1" id='curriculamadditional1_no' value="No" <?php if ($hcp99_1 == 'No') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp99_1" id='curriculamadditional1_sometime' value="Sometimes" <?php if ($hcp99_1 == 'Sometimes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp99_1" id='curriculamadditional1_notsure' value="Not Sure" <?php if ($hcp99_1 == 'Not Sure') { ?>checked<?php } ?>>
								</td>
							</tr>

							<tr>
								<td valign="top" class='childtd'>4. My child is making good progress as per his/her grade.</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp100_1" id='goodprogress1_yes' value="Yes" <?php if ($hcp100_1 == 'Yes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp100_1" id='goodprogress1_no' value="No" <?php if ($hcp100_1 == 'No') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp100_1" id='goodprogress1_sometime' value="Sometimes" <?php if ($hcp100_1 == 'Sometimes') { ?>checked<?php } ?>>
								</td>
								<td class='textcenter'>
									<input type="checkbox" name="hcp100_1" id='goodprogress1_notsure' value="Not Sure" <?php if ($hcp100_1 == 'Not Sure') { ?>checked<?php } ?>>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="fulltable2">
					<table>
						<thead>
							<tr>
								<th colspan="3" style="text-align: left;">At school, my child needs support with</th>

							</tr>
						</thead>
						<thead>
							<tr>
								<th colspan="3" id="colspan34">
									<div class="classdiv">
										<div class="label-group1">
											<span>Language(R1, R2, R3)</span>
											<label for="Language(R1, R2, R3)">
												<input type="checkbox" value="Language(R1, R2, R3)" id="LanguageR1R2R3" name="hcp101" <?php if (!empty($hcp101)) { ?>checked<?php } ?>>
											</label>

											<span class='gradespan1'>Mathematics</span>
											<label for="Mathematics">
												<input type="checkbox" value="Mathematics" id="Mathematics" name="hcp102" <?php if (!empty($hcp102)) { ?>checked<?php } ?>>
											</label>

											<span class='gradespan1'>Social Science</span>
											<label for="Social Science">
												<input type="checkbox" value="Social Science" id="SocialScience" name="hcp103" <?php if (!empty($hcp103)) { ?>checked<?php } ?>>
											</label>

											<span class='gradespan1'>Science</span>
											<label for="Science">
												<input type="checkbox" value="Science" id="Science" name="hcp104" <?php if (!empty($hcp104)) { ?>checked<?php } ?>>
											</label>
										</div>
									</div>

								</th>

							</tr>
						</thead>
					</table>
					<table>
						<thead>
							<tr>
								<th id="Building">Building Self-belief and Self-reliance</th>
								<th id="Building2"><input type="checkbox" id="buildselfbelief" name="hcp105" <?php if (!empty($hcp105)) { ?>checked<?php } ?>></th>
								<th id="Developing">Developing social skills & conflict resolution</th>
								<th id="Developing2"><input type="checkbox" id="devlopsocialskill" name="hcp106" <?php if (!empty($hcp106)) { ?>checked<?php } ?>></th>
							</tr>
							<tr>
								<th id="Managing">Managing difficult emotions like anger</th>
								<th id="Managing2">
									<input type="checkbox" id="emotionlikeanger" name="hcp107" <?php if (!empty($hcp107)) { ?>checked<?php } ?>>
								</th>
								<th id="skills">Developing effective study skills like time management</th>
								<th id="skills2">
									<input type="checkbox" id="studyskillslike" name="hcp108" <?php if (!empty($hcp108)) { ?>checked<?php } ?>>
								</th>
							</tr>
							<tr>
								<th id="Vocational">Vocational Guidance/Digital Literacy</th>
								<th style="background-color: #FFEBEE; color: black; width: 5%;">
									<input type="checkbox" id="vocationalguidance" name="hcp109" <?php if (!empty($hcp109)) { ?>checked<?php } ?>>
								</th>
								<th id="anyother">Any other <input type='text' name='hcp110' id='childneed_anyother' class='childneedanyother' <?php if (!empty($hcp110)) { ?>value='<?php echo $hcp110; ?>' <?php } ?>></th>
								<th id="check">
									<input type="checkbox" id='childneed_anyother_check' name="hcp111" <?php if (!empty($hcp111)) { ?>checked<?php } ?>>

								</th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="fulltable4">
					<table>
						<thead>
							<tr>
								<th colspan="3" id="based">Based on my discussion with the teacher, I will support my child at home by:</th>

							</tr>
						</thead>
						<thead>
							<tr>
								<th colspan="3" id="height">
									<textarea name='hcp112' id='teacherparentdiscuss' rows='9'><?php echo $hcp112; ?></textarea>
								</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div style="page-break-after: always;"></div>

			<div class='partc'>
				<div>
					<h2 style="color: orangered; text-align:center;margin-bottom:3px;margin-top:;">PART B and C</h2>
					<H1 style="color: orangered; text-align:center;margin-bottom:10px;margin-top:4px;">SUMMARY FOR THE ACADEMIC YEAR</H1>
					<h3 style="color: black;">Key Performer Descriptors</h3>
				</div>

				<table id="performertable">
					<thead>
						<tr>
							<th rowspan="3">Subjects</th>
							<th rowspan="3">Abilities</th>
							<th colspan="3">Term I</th>
							<th colspan="3">Term II</th>
						</tr>
						<tr>
							<td colspan="6" id="performance" style='text-align:center'>Performance level Descriptors</td>
						</tr>
						<tr>
							<th id="B">B</th>
							<th id="P">P</th>
							<th id="A">A</th>
							<th id="B">B</th>
							<th id="P">P</th>
							<th id="A">A</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cg1sql = mysqli_query($Con, "select distinct `hie`.`subindicator` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`indicatortype`='$keyperform' order by case when subindicator='ENGLISH' then 1 when subindicator='HINDI' then 2 when subindicator='SANSKRIT' then 3 when subindicator='FRENCH' then 4 when subindicator='MATHS' then 5 when subindicator='SCIENCE' then 6 when subindicator='SOCIAL SCIENCE' then 7 when subindicator='ART' then 8 when subindicator='PERFORMING ARTS' then 9 else 10 End");
						$srno = 1;
						while ($fetchCG1 = mysqli_fetch_array($cg1sql)) {
							$indicator = $fetchCG1[0];
						?>
							<tr>
								<td rowspan="3"><?php echo $indicator; ?></td>
								<td>AWARENESS</td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term1' and indicator_desc='AWARENESS'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php
								}

								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term2' and indicator_desc='AWARENESS'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php } ?>
							</tr>
							<tr>
								<td>SENSITIVITY</td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term1' and indicator_desc='SENSITIVITY'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php
								}

								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term2' and indicator_desc='SENSITIVITY'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php } ?>
							</tr>
							<tr>
								<td>CREATIVITY</td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term1' and indicator_desc='CREATIVITY'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php
								}

								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and subindicator='$indicator' and indicatortype='$keyperform' and exam_type='$term2' and indicator_desc='CREATIVITY'");
								$graderow = mysqli_fetch_array($gradesql);
								for ($i = 0; $i <= 2; $i++) {
								?>
									<td class='textcenter'><?php if ($i == 0) {
																if ($graderow[0] == 'B') { ?><img src="hcp-images/Tick.png"><?php }
																													} elseif ($i == 1) {
																														if ($graderow[0] == 'P') { ?><img src="hcp-images/Tick.png"><?php }
																																													} elseif ($i == 2) {
																																														if ($graderow[0] == 'A') { ?><img src="hcp-images/Tick.png"><?php }
																																																																} ?></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<div style="page-break-after: always;"></div>

				<div class="fulltable3">
					<table id="datatable" class='feedbacktable'>
						<thead>
							<tr>
								<th colspan="3" id="teachersfeedback">TEACHER’S FEEDBACK</th>
							</tr>
							<tr>
								<th id="Area">Areas of strength(✓ all that apply)</th>
								<th id="term">Term I</th>
								<th id="Term">Term II</th>
							</tr>
						</thead>
						<?php
						$cg1sql = mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`hie`.`anyother` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`indicatortype`='$teacherfeed' and `hie`.`subindicator`='$areastrength' order by case when subindicator='Follows instructions' then 1 when subindicator='Independent work' then 2 when subindicator='Communication' then 3 when subindicator='Solution-focused thinking' then 4 when subindicator='Empathy' then 5 when subindicator='Organization and Prioritization' then 6 when subindicator='Collaboration' then 7 when subindicator='Responsibility' then 8 when subindicator='Creativity' then 9 when subindicator='Concentration' then 10 else 11 End");
						$srno = 1;
						while ($fetchCG1 = mysqli_fetch_array($cg1sql)) {
							$indicator = $fetchCG1[0];
							if ($indicator == 'Any Other' && empty($fetchCG1[1])) {
								continue;
							}
						?>
							<tr>
								<td><b><?php echo ($indicator == 'Any Other') ? $fetchCG1[1] : $indicator; ?></b></td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and `indicatortype`='$teacherfeed' and `subindicator`='$areastrength' and exam_type='$term1'");
								$graderow = mysqli_fetch_array($gradesql);
								?>
								<td class='textcenter'><?php if ($graderow[0] == 'true') { ?><img src="hcp-images/Tick.png"><?php } ?></td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and `indicatortype`='$teacherfeed' and `subindicator`='$areastrength' and exam_type='$term2'");
								$graderow = mysqli_fetch_array($gradesql);
								?>
								<td class='textcenter'><?php if ($graderow[0] == 'true') { ?><img src="hcp-images/Tick.png"><?php } ?></td>

							</tr>
						<?php } ?>


						<tr>
							<th id="barrier">Barrier(s) to success(✓ all that apply)</th>
							<th id="tone"></th>
							<th id="ttwo"></th>
						</tr>

						<?php
						$cg1sql = mysqli_query($Con, "select distinct `hie`.`indicator_desc`,`hie`.`anyother` from `hcp_indicator_entry` as `hie` left join `exam_class_indicator_mapping` as `eim` on(`eim`.`indicator_type`=`hie`.`indicatortype` and `eim`.`indicator_subcat`=`hie`.`subindicator` and `eim`.`indicator_desc`=`hie`.`indicator_desc` and `eim`.`sclass`=`hie`.`sclass` and `eim`.`exam_type`=`hie`.`exam_type`) where `hie`.`sadmission`='$AdmissionId' and `hie`.`sclass`='$StudentClass' and `hie`.`FinancialYear`='$CurrentFinancialYear' and `hie`.`indicatortype`='$teacherfeed' and `hie`.`subindicator`='$barrier' order by case when subindicator='Lack of attention' then 1 when subindicator='Lack of motivation' then 2 when subindicator='Lack of preparation' then 3 when subindicator='Peer pressure' then 4 when subindicator='Undefined Goals' then 5 when subindicator='Domestic issues' then 6 when subindicator='Inappropriate behaviour in class' then 7 when subindicator='Severe illness or injury' then 8 when subindicator='None' then 9 else 10 End");
						$srno = 1;
						while ($fetchCG1 = mysqli_fetch_array($cg1sql)) {
							$indicator = $fetchCG1[0];
							if ($indicator == 'Any Other' && empty($fetchCG1[1])) {
								continue;
							}
						?>
							<tr>
								<td><b><?php echo ($indicator == 'Any Other') ? $fetchCG1[1] : $indicator; ?></b></td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and `indicatortype`='$teacherfeed' and `subindicator`='$barrier' and exam_type='$term1'");
								$graderow = mysqli_fetch_array($gradesql);
								?>
								<td class='textcenter'><?php if ($graderow[0] == 'true') { ?><img src="hcp-images/Tick.png"><?php } ?></td>
								<?php
								$gradesql = mysqli_query($Con, "select grade from hcp_indicator_entry where `sadmission`='$AdmissionId' and `sclass`='$StudentClass' and `FinancialYear`='$CurrentFinancialYear' and `indicatortype`='$teacherfeed' and `subindicator`='$barrier' and exam_type='$term2'");
								$graderow = mysqli_fetch_array($gradesql);
								?>
								<td class='textcenter'><?php if ($graderow[0] == 'true') { ?><img src="hcp-images/Tick.png"><?php } ?></td>

							</tr>
						<?php } ?>

					</table>
				</div>

				<div>
					<table>
						<thead>
							<tr>
								<th colspan="3" id="colbottam">Teacher’s Observations and Recommendations</th>
							</tr>
							<tr>
								<th id="ttterm1">Term 1</th>
								<th id="ttterm2">Term 2</th>
							</tr>
						</thead>
						<?php
						$tfeedsql1 = mysqli_query($Con, "SELECT * FROM `hcp_teacherfeedback` where sadmission='$AdmissionId' and sclass='$StudentClass' and financial_year='$CurrentFinancialYear' and exam_type='Term 1'");
						$tfeedsql2 = mysqli_query($Con, "SELECT * FROM `hcp_teacherfeedback` where sadmission='$AdmissionId' and sclass='$StudentClass' and financial_year='$CurrentFinancialYear' and exam_type='Term 2'");
						$fetchfeedrow1 = mysqli_fetch_assoc($tfeedsql1);
						$fetchfeedrow2 = mysqli_fetch_assoc($tfeedsql2);
						?>
						<tbody>
							<tr>
								<td id="ttterm3" valign='top'><?php echo $fetchfeedrow1['feedback']; ?></td>
								<td id="ttterm4" valign='top'><?php echo $fetchfeedrow2['feedback']; ?></td>
							</tr>
							</thead>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="nkbpshcp.js"></script>
</body>

</html>