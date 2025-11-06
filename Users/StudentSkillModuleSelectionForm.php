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
   $Club=$_POST['D2'];
   $rsChk=mysqli_query($Con, "select * from StudentSkillModule where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="<center><b>Already Submitted!";
   }
   else
   {
	   mysqli_query($Con, "INSERT INTO StudentSkillModule (`sadmission`, `sname`, `sclass`, `Stream`, `Club`)VALUES('$sadmission','$sname','$StudentClass','$Consent','$Club')");
	   $Msg="<center><b>Submitted Successfully!";
	}
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title> StudentSkillModule Selection Form </title>
<script type="text/javascript">
function toggleDropdowns() {
    var skillModule = document.getElementsByName("D1")[0];
    var club = document.getElementsByName("D2")[0];
    
    if(skillModule.value != "") {
        club.disabled = true;
        club.value = "";
    } else if(club.value != "") {
        skillModule.disabled = true;
        skillModule.value = "";
    } else {
        skillModule.disabled = false;
        club.disabled = false;
    }
}
</script>
</head>

<body>
<font face="Cambria">
<?php
if($Msg !='')
{
	echo "<p>".$Msg."</p>";
	exit();
}
?>
</font>
<form method="POST" method ="post" action="">
<table border="1" width="100%" style="border-collapse: collapse" height="305">
	<tr>
		<td colspan="2" height="19" style="border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><img src="<?php echo $SchoolLogo; ?>" height="100px" width="400px"></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b><?php echo $SchoolAddress; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Email Id: <?php echo $SchoolEmailId; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium">
		<p align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" height="57">
		<p align="center"><u><b>
			<font face="Cambria" size="4">
			Skill Module Selection Form<br>2025-26</font></b></u></td>
	
	</tr>
	<tr>
		<td colspan="2" height="133">&nbsp;<p><font face="Cambria"><b>Dear 
		Parent</b></font></p>
		<div style="padding-top: 0px; border-top: 0px; color: rgb(51, 51, 51); font-family: &quot;Lucida Grande&quot;, Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">
			<font face="Cambria" size="4">
			<br>
			Kindly indicate the Skill Module(s) to be opted for by your ward 
			this year&nbsp; by selecting the right option.</font></div>
		<p class="MsoNormal">
		<font face="Cambria"><br>
		<b>PRINCIPAL</b></font></p>
		<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="21" width="516">&nbsp;</td>
		<td height="21" width="517">&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="516"><font face="Cambria"><b>I Select Skill 
		Module</b></font></td>
		<td height="22" width="517">
		
			<p><font face="Cambria">
			<select size="1" name="D1" style="font-weight: 700" onchange="toggleDropdowns()">
				<option value="">Select One</option>
			    
				<option value="Beauty & Wellness">Beauty & Wellness</option>
								
				<option value="Design Thinking and Innovation">Design Thinking and Innovation</option>
				<option value="Financial Literacy">Financial Literacy</option>
				<option value="Handicrafts">Handicrafts</option>
				<option value="Marketing/ Commercial Application">Marketing/ Commercial Application</option>
				<option value="Mass Media">Mass Media</option>
				<option value="Travel & Tourism">Travel & Tourism</option>
				<option value="Coding">Coding</option>
				<option value="Life Cycle of Medicine and Vaccine">Life Cycle of Medicine and Vaccine</option>
				<option value="Embroidery">Embroidery</option>
				<option value="What to do when Doctor is not around">What to do when Doctor is not around</option>
				<option value="Application of Satellites">Application of Satellites</option>
				<option value="Culinary and Baking">Culinary and Baking</option>
				<option value="Block Printing">Block Printing</option>
				<option value="Food Preservation">Food Preservation</option>
				
				</select></font></p>
		
		</td>
	</tr>
	<tr>
		<td height="22" width="516"><font face="Cambria"><b>I Select Club (Classes VI to XI)</b></font></td>
		<td height="22" width="517">
		
			<p><font face="Cambria">
			<select size="1" name="D2" style="font-weight: 700" onchange="toggleDropdowns()">
				<option value="">Select One</option>
				<option value="Orbitals - Chemistry Club">Orbitals - Chemistry Club</option>
				<option value="Helicase - Biology Club">Helicase - Biology Club</option>
				<option value="Quantum - Physics Club">Quantum - Physics Club</option>
				<option value="Logical Reasoning and Mental Ability - Maths Club">Logical Reasoning and Mental Ability - Maths Club</option>
				<option value="Lamhe - Photography Club">Lamhe - Photography Club</option>
				<option value="MUN - Model United Nations Club">MUN - Model United Nations Club</option>
				<option value="Abhivyakti / TED Talk - Discussion/Debate/Creative Writing">Abhivyakti / TED Talk - Discussion/Debate/Creative Writing</option>
				<option value="Reflections - Drafting and Compiling School Magazine / Newsletter">Reflections - Drafting and Compiling School Magazine / Newsletter</option>
				<option value="Nrityanjali - Dance (Western / Indian)">Nrityanjali - Dance (Western / Indian)</option>
				<option value="Acoustics - Instrumental Music">Acoustics - Instrumental Music</option>
				<option value="Chords - Vocal Music (Western)">Chords - Vocal Music (Western)</option>
				<option value="Expressions - Art and Craft">Expressions - Art and Craft</option>
				<option value="Robo Sapiens - Robotics Club">Robo Sapiens - Robotics Club</option>
				<option value="Nurture Nature - Gardening Club">Nurture Nature - Gardening Club</option>
				<option value="Masterchef - Cookery Club">Masterchef - Cookery Club</option>
				<option value="SEWAM - Society Empowered for the Welfare of Animals & Mankind">SEWAM - Society Empowered for the Welfare of Animals & Mankind</option>
				<option value="Pehchaan - Community Service Movement">Pehchaan - Community Service Movement</option>
				<option value="Drop Everything & Read - Library Club">Drop Everything & Read - Library Club</option>
				<option value="Swachh Bharat - Cleanliness Club">Swachh Bharat - Cleanliness Club</option>
				<option value="Pranayam - Yoga & Meditation">Pranayam - Yoga & Meditation</option>
				<option value="Laughter Club">Laughter Club</option>
				<option value="Chess Club/Rubic Cube Club">Chess Club/Rubic Cube Club</option>
				<option value="NCC (Classes 6-8)">NCC (Classes 6-8)</option>
				<option value="Quiz">Quiz</option>
				<option value="Crochet Club">Crochet Club</option>
				<option value="Road Safety Club">Road Safety Club</option>
				<option value="SOAR Astronomy Club (It is a paid activity.)">SOAR Astronomy Club (It is a paid activity.)</option>
			</select></font></p>
		
		</td>
	</tr>
</table>
	<p align="center">
	<font face="Cambria">
	<input name="submit" type="submit" value="Submit" style="font-weight: 700" class="text-box" ></font></p>
</form>
<p align="center">&nbsp;</p>

</body>

</html>