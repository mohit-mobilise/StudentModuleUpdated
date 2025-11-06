<?php include '../connection.php';?>
<?php
session_start();
  $StudentClass = $_SESSION['StudentClass'];
  $class = $_SESSION['StudentClass'];
  $StudentRollNo = $_SESSION['StudentRollNo'];
  $sadmission=$_SESSION['userid'];
?>

<?php
function show_attendance(){
    global $Con;
    
if(isset($_POST["month_name"]))

{

$month_name=$_POST['month_name'];
$adm=$_POST['adm_name'];

$html = ''; // Initialize html variable

if (!$Con) {
    echo '<p style="color: red;">Database connection error. Please try again later.</p>';
    return;
}

 $rsattendance="SELECT  DISTINCT `attendancedate`, (CASE WHEN `attendance` IN ('P')THEN 1 END ) as `present` ,(CASE WHEN `attendance` IN ('A')THEN 1 END ) as `absent`  FROM `attendance` WHERE `sadmission`='$adm' and  `attendancedate` LIKE '$month_name%'  ORDER BY `attendancedate`" ;

 
 $rsattendance_m= mysqli_query($Con, $rsattendance);
 
 if (!$rsattendance_m) {
     echo '<p style="color: red;">Error fetching attendance: ' . mysqli_error($Con) . '</p>';
     return;
 }
 
 $html .='<table class="table table-striped table-bordered table-hover" id="sample_1">
<thead>
 <tr class="bg-primary text-white">
  <th> S.No</th>
  <th>Date</th>
  <th>Present</th>
  <th>Absent</th>
</tr>
</thead>
 <tbody>'; 
                
$recno=1;
while($row=mysqli_fetch_row($rsattendance_m))
{

  $Date=$row[0];
  $Present=$row[1];
  $Absent=$row[2];
  if($Present=="1")
  {
     $present_att= "P";   
  }
  
  if($Absent=="1")
  {
     $absent_att= "A";   
  }
  
  
          
    $html .=' <tr>
    <td>'.$recno.'</td>
    <td class="center">'.$Date.'</td>
     <td class="center">'.$present_att.'</td>
      <td class="center">'.$absent_att.'</td>
    </tr>';


  $recno=$recno+1;
  } 

  $html .=' </tbody>
  </table>';
   echo $html;

}
}


function view_reportcard(){
    global $Con;
    
if(isset($_POST["Admission"]))
{
    
$Admission=$_POST['Admission'];
$exam_type=$_POST['exam_type'];
$fyear=$_POST['fyear'];
$Class=$_POST['master_class'];

$html = ''; // Initialize html variable

if (!$Con) {
    echo '<p style="color: red;">Database connection error. Please try again later.</p>';
    return;
}

	$SubjectDetail=mysqli_query($Con, "SELECT distinct `master_subject` FROM `exam_subject_master` WHERE `sclass`='$Class' and `exam_type`='$exam_type' and `master_subject` in (select distinct master_subject from `exam_mark_entry` where `exam_type`='$exam_type' and sclass='$Class' and `sadmission`='$Admission') ORDER BY CAST(`subject_pri` AS UNSIGNED INT) ");

if (!$SubjectDetail) {
    echo '<p style="color: red;">Error fetching subjects: ' . mysqli_error($Con) . '</p>';
    return;
}

 
 $html .='<table class="table table-striped table-bordered table-hover" id="sample_1">
<thead>
    <tr class="bg-primary text-white">
        <th>Subject </th>
        <th>Max Marks</th>
        <th>Marks Obtained </th>
                                    
    </tr>
</thead>
 <tbody>'; 
                
$recno=1;
while($rowS=mysqli_fetch_row($SubjectDetail))   
{
    $Subjectname=$rowS[0];
    
  
        $markDetail=mysqli_query($Con, "SELECT   distinct `marks_obt`,`max_marks` FROM `exam_mark_entry` WHERE `sadmission`='$Admission' and `exam_type`='$exam_type' and `subject`='$Subjectname' ");
    $MarkObtained='';
    $MaxMark='';
    
	if (!$markDetail) {
	    continue; // Skip if query fails for this subject
	}
    
	while($rowP=mysqli_fetch_row($markDetail))
	{
		$MarkObtained=$rowP[0];
		$MaxMark=$rowP[1];
		
		
			 
    $html .=' <tr>
    <td class="center">'.$Subjectname.'</td>
     <td class="center">'.$MaxMark.'</td>
      <td class="center">'.$MarkObtained.'</td>
    </tr>';


    $recno=$recno+1;
    } 
}
  $html .=' </tbody>
  </table>';
   echo $html;

}
}


view_reportcard();

show_attendance();
?>
