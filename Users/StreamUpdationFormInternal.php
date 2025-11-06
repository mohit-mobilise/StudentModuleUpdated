<?php

	session_start();

	include '../connection.php';

	include '../AppConf.php';

?>

<?php

//$sadmission=$_REQUEST["txtUserId"];

$sadmission=$_SESSION['userid'];

$BaordFilled=$_REQUEST["txtBoard"];

	if($sadmission== "")

	{

		echo "<br><br><center><b>You are not logged-in!<br>Please click <a href='http://dpsfsis.com'>here</a> to login into parent portal!";

		exit();

	}



$rsChk=mysqli_query($Con, "select * from `StudentMasterInfo` where `sadmission`='$sadmission'");

if(mysqli_num_rows($rsChk)>0)

{

	echo "<br><br><center><b>Already Submitted!";

	exit();

}

$rsStudentDetail=mysqli_query($Con, "SELECT `sname`,`sclass`,`srollno`,`sfathername`,`smobile`,`email`,`AlternateMobile`,`DOB`,`Address`,`MotherName`,`MasterClass` FROM  `student_master` where `sadmission`='$sadmission'");

while($rowS=mysqli_fetch_row($rsStudentDetail))

{

	                                  

				$sname=$rowS[0];

				//$slastname=$rowS[4];

				$StudentName=$sname." ".$slastname;

				$DOB=$rowS[7];

				$arr=explode('-',$DOB);

				$DOB= $arr[1] . "/" . $arr[2] . "/" . $arr[0];



				$PlaceOfBirth=$rowS[6];

				$Age=$rowS[7];

				$AgeYear=$rowS[8];

				$AgeMonth=$rowS[9];

				$AgeDays=$rowS[10];

				$Sex=$rowS[11];

				$Sibling=$rowS[12];

				$Father_DPS_Alumni=$rowS[13];

				$Mother_DPS_Alumni=$rowS[14];

				$Single_Parent=$rowS[15];

				$Special_Needs=$rowS[16];

				$Staff=$rowS[17];

				$OtherCategory=$rowS[18];

				$MotherTongue=$rowS[19];

				$Nationality=$rowS[20];

				$ResidentialAddress=$rowS[8];

				$Location=$rowS[22];

				$Distance=$rowS[23];

				$PhoneNo=$rowS[6];

				$smobile=$rowS[4];

				$sclass=$rowS[1];

				$MasterClass=$rowS[10];

				$LastSchool=$rowS[28];

				$sfathername=$rowS[3];

				$sfatherage=$rowS[30];

				$FatherEducation=$rowS[31];

				$FatherQualificationDuration=$rowS[32];

				$FatherOccupation=$rowS[33];

				$FatherDesignation=$rowS[34];

				$FatherAnnualIncome=$rowS[35];

				$FatherCompanyName=$rowS[36];

				$FatherOfficeAddress=$rowS[37];

				$FatherOfficePhoneNo=$rowS[38];

				$FatherMobileNo=$rowS[39];

				$FatherEmailId=$rowS[40];

				$MotherName=$rowS[9];

				$MotherAge=$rowS[42];

				$MotherEducation=$rowS[43];

				$MotherQualificationDuration=$rowS[44];

				$MotherOccupatoin=$rowS[45];

				$MotherDesignation=$rowS[46];

				$MontherAnnualIncome=$rowS[47];

				$MotherCompanyName=$rowS[48];

				$MotherOfficeAddress=$rowS[49];

				$MotherOfficePhone=$rowS[50];

				$MotherMobile=$rowS[51];

				$MotherEmail=$rowS[52];

				$GuradianName=$rowS[53];

				$GuradianAge=$rowS[54];

				$GuradinaEducation=$rowS[55];

				$GuradianOccupation=$rowS[56];

				$GuradianDesignation=$rowS[57];

				$GuradianAnnualIncome=$rowS[58];

				$GuradianCompanyName=$rowS[59];

				$GuradianOfficialAddress=$rowS[60];

				$GuradianOfficialPhNo=$rowS[61];

				$GuradianMobileNo=$rowS[62];

				$TransportAvail=$rowS[63];

				$SafeTransport=$rowS[64];

				$SpecialAttentionDetail=$rowS[65];

				$RealBroSisName=$rowS[66];

				$RealBroSisAdmissionNo=$rowS[67];

				$RealBroSisClass=$rowS[68];

				$AlumniFatherName=$rowS[69];

				$AlumniSchoolName=$rowS[70];

				$AlumniPassingYear=$rowS[71];

				$AlumniFatherPassingClass=$rowS[72];

				$AlumniMotherName=$rowS[73];

				$AlumniMotherSchoolName=$rowS[74];

				$AlumniMotherPassingYear=$rowS[75];

				$AlumniMotherPassingClass=$rowS[76];

				$EmergencyContactNo=$rowS[77];

				$RegistrationFormNo=$rowS[78];

				$email=$rowS[5];

				$routeno=$rowS[80];

				$status=$rowS[82];

				$Remarks=$rowS[83];

				$quarter=$rowS[84];

				$FinancialYear=$rowS[85];

				$SchoolId=$rowS[86];

				$TxnAmount=$rowS[87];

				$TxnId=$rowS[88];

				$TxnStatus=$rowS[89];

				$HostelFacility=$rowS[92];

				

			



				break;

				

				}



$ssqlClass="SELECT distinct `MasterClass` FROM `class_master`";

$rsClass= mysqli_query($Con, $ssqlClass);

{

}



$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster`";

$rsFY= mysqli_query($Con, $ssqlFY);

$rsEducation=mysqli_query($Con, "select distinct `Qualification` from `NewStudentRegistrationQualificationMaster` order by `Qualification`");

$rsEducation1=mysqli_query($Con, "select distinct `Qualification` from `NewStudentRegistrationQualificationMaster` order by `Qualification`");



$rsSchooListFather=mysqli_query($Con, "select distinct `SchoolName` from `NewStudentRegistrationSchoolList` order by `SchoolName`");

$rsSchooListMother=mysqli_query($Con, "select distinct `SchoolName` from `NewStudentRegistrationSchoolList` order by `SchoolName`");



$rsLocation=mysqli_query($Con, "select distinct `Sector` from `NewStudentRegistrationDistanceMaster` order by `Sector`");



$currentdate=date("d-m-Y");



	$ssqlRoute="SELECT distinct `routeno` FROM `RouteMaster`";

	$rsRoute= mysqli_query($Con, $ssqlRoute);

	

	



$ssqlDiscount="SELECT distinct `head` FROM `discounttable` where `discounttype`='tuitionfees'";

$rsDiscount= mysqli_query($Con, $ssqlDiscount);



$sstr="SELECT distinct `head` FROM `discounttable` where `discounttype`='admissionfees'";

$rsAdmissionFeeDiscount= mysqli_query($Con, $sstr);



$sstr="SELECT distinct `head` FROM `discounttable` where `discounttype`='annualcharges'";

$rsAnnualFeeDiscount= mysqli_query($Con, $sstr);



$rsSchool = mysqli_query($Con, "select distinct `SchoolId`,`SchoolName` from `SchoolConfig`");

$ssqlClass="SELECT distinct `class` FROM `class_master`";

$rsClass= mysqli_query($Con, $ssqlClass);



?>



<script language="javascript">



// function checkvalue(id)

// {

// 	var name = $('#'+id);

// 	if (!isNaN(name.value)) {

// 		if (parseInt(name.value) >= 80) {



// 		}else{



// 		}

// 	}else{



// 	}

// }

function Validate1()

{

	var sadmission=document.getElementById("txtAdmission").value;

	//alert(sadmission.substring(0,1));

	if(sadmission.substring(0,1)=="N")

	{

		var StudentType="External";

	}

	else

	{

		var StudentType="Internal";

	}



	

if(document.getElementById("cboMathType").value=="" ) 

	{

		alert("Select Math Type are mandatory!");

		return;

	}

	

	if(document.getElementById("txtEnglishMarksPercent").value=="" ) 

	{

		alert("English marks parcent are mandatory!");

		return;

	}



	if(document.getElementById("txtMathsMarksPercent").value=="" )

	{

		alert("Maths marks parcent are mandatory!");

		return;

	}

	if(document.getElementById("txtScienceMarksPercent").value=="" )

	{

		alert("Science percent are mandatory!");

		return;

	}

	if(document.getElementById("txtSocialScienceMarksPercent").value=="" )

	{

		alert("Social Science percent are mandatory!");

		return;

	}

	if(document.getElementById("txtLanguageMarksPercent").value=="" )

	{

		alert("Language percent are mandatory!");

		return;

	}

	if(document.getElementById("cboStream").value=="")

	{

		alert("Stream is mandatory!");

		return;

	}

	

	var averagepercent=parseFloat(document.getElementById("txtEnglishMarksPercent").value) + parseFloat(document.getElementById("txtMathsMarksPercent").value) + parseFloat(document.getElementById("txtScienceMarksPercent").value) + parseFloat(document.getElementById("txtSocialScienceMarksPercent").value)+ parseFloat(document.getElementById("txtLanguageMarksPercent").value);

	averagepercent=averagepercent/5;

	

		var MathsPercentage=parseFloat(document.getElementById("txtMathsMarksPercent").value);



			var SciencePercentage=parseFloat(document.getElementById("txtScienceMarksPercent").value);



	var averagepercentESM=parseFloat(document.getElementById("txtEnglishMarksPercent").value) + parseFloat(document.getElementById("txtMathsMarksPercent").value) + parseFloat(document.getElementById("txtScienceMarksPercent").value);

	averagepercentESM=averagepercentESM/3;



	var averagepercentESMST=parseFloat(document.getElementById("txtEnglishMarksPercent").value) + parseFloat(document.getElementById("txtMathsMarksPercent").value) + parseFloat(document.getElementById("txtScienceMarksPercent").value) + parseFloat(document.getElementById("txtSocialScienceMarksPercent").value);
	averagepercentESMST=averagepercentESMST/3;



if(!isNaN(document.getElementById("txtEnglishMarksPercent").value) && !isNaN(document.getElementById("txtMathsMarksPercent").value) && !isNaN(document.getElementById("txtScienceMarksPercent").value) && !isNaN(document.getElementById("txtSocialScienceMarksPercent").value) && !isNaN(document.getElementById("txtLanguageMarksPercent").value) ) 

{

	
//	if(document.getElementById("cboStream").value!="")

	//	{
			if(averagepercent<40)

			{

				alert("You are not eligible for any stream! ");

				return;

			}

			else

			{

					if(document.getElementById("cboStream").value=="Non-Medical")

					{   



						if(StudentType=="Internal")

						{

                             	if(averagepercent<80)

						           {

							       alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

							           return;

						           }

						           if (MathsPercentage<80)

						            {

                                          alert("You are not eligible for  Non-Medical  stream! Kindly select other stream!");

							           return;

						           }
                                
						          



						           

						           

						}

						else

						{

                             	if(averagepercent<82.5)

						           {

							       alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

							           return;

						           }

                                   if (MathsPercentage<80)

						            {

                                          alert("You are not eligible for  Non-Medical  stream! Kindly select other stream!");

							           return;

						           }

						          



						}



					

					}

					if(document.getElementById("cboStream").value=="Medical")

					{   



						if(StudentType=="Internal")

						{

                             	if(averagepercent<80)

						           {

							       alert("You are not eligible for Medical  stream! Kindly select other stream!");

							           return;

						           }

		                            if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" || document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 80)

						          	 {


                                          alert('You are not eligible for Medical with Math stream! Kindly select other stream!');

                                          

							              return;

						               }

						           

						}

						else

						{

                             	if(averagepercent<82.5)

						           {

							       alert("You are not eligible for Medical stream! Kindly select other stream!");

							           return;

						           }

						           

                                 	if (((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" || document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 80))

						           {

                                          alert('You are not eligible for Medical with Math  stream! Kindly select other stream!');

                                          

							              return;

						               }
                                            
						                



						}



					

					}



					

					if(document.getElementById("cboStream").value=="Commerce With Maths")

					{   



						if(StudentType=="Internal")

						{

                             	if(averagepercent<79)

						           {

							       alert("You are not eligible for  Commerce with Math stream! Kindly select other stream!");

							           return;

						           }

						           if (MathsPercentage<80)

						            {

                                          alert("You are not eligible for  Commerce with Math stream! Kindly select other stream!");

							           return;

						           }

						}

						else

						{

                             	if(averagepercent<82)

						           {

							       alert("You are not eligible for  Commerce with Math stream! Kindly select other stream!");

							           return;

						           }

                                   if (MathsPercentage<80)

						            {

                                          alert("You are not eligible for  Commerce with Math stream! Kindly select other stream!");

							           return;
						           }



						}



					

					}



					if(document.getElementById("cboStream").value=="Commerce")

					{   



						if(StudentType=="Internal")

						{

                             	if(averagepercent<80)

						           {

							       alert("You are not eligible for  Commerce stream! Kindly select other stream!");

							           return;

						           }

						         if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" || document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 80)

						           {

                                          alert('You are not eligible for Commerce with Math stream! Kindly select other stream!');

                                          

							              return;

						               } 

						}

						else

						{

                             	if(averagepercent<82.5)

						           {

							       alert("You are not eligible for  Commerce stream! Kindly select other stream!");

							           return;

						           }

                            if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" ||document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 80)

						           {

                                          alert('You are not eligible for Commerce with Math stream! Kindly select other stream!');

                                          

							              return;

						               } 



						}



					

					}



					

					if(document.getElementById("cboStream").value=="Liberal Arts")

					{
						
			                    var str = document.getElementById("txtOptionalSubject").value;
			                    var n = str.includes("Mathematics");

						if(StudentType=="Internal")

						{

		

						  if(averagepercent<79)

						   {

							alert("You are not eligible for  Liberal Arts stream");

							return;

						   }
									
						  if ((n=true) && MathsPercentage < 80)

						          {

                                                  alert('You are not eligible for Liberal Arts with Math  stream! Kindly select other stream!');

                                          

							              return;

						         } 
                                    
					}

					else

					{

                         if(averagepercent<81.5)

						   {

							alert("You are not eligible for  Liberal Arts stream");

							return;

						   }

						    if ((n=true) && MathsPercentage < 80)


						          {

                                          alert('You are not eligible for Liberal Arts with Math  stream! Kindly select other stream!');

                                          

							              return;

						         }

					}

				}





			if(document.getElementById("cboStream").value=="Liberal Arts without Maths")

					{



						if(StudentType=="Internal")

						{



						  if(averagepercent<79)

						   {

							alert("You are not eligible for  Liberal Arts without Math stream");

							return;

						   }

					 
						}

					}

			if(document.getElementById("cboStream").value=="Medical without Maths")

					{



						if(StudentType=="Internal")

						{



						  if(averagepercent<80)

						   {

							alert("You are not eligible for  Medical without Math stream");

							return;

						   }

					 
					}

					else

					{

                         if(averagepercent<82.5)

						   {

							alert("You are not eligible for  Medical without Math stream");

							return;

						   }

						    
					}

				}





					if(document.getElementById("cboStream").value=="Commerce without Maths")

					{



						if(StudentType=="Internal")

						{



						  if(averagepercent<79)

						   {

							alert("You are not eligible for  Commerce Without Math Stream! Kindly select other stream!");

							return;

						   }

					
					}

					else

					{

                         if(averagepercent<82)

						   {

							alert("You are not eligible for  Commerce without Math Stream");

							return;

						   }

						    
					}

				}

	



					

			}

	//	}

// 		else

// 		{		

// 							if(averagepercent<40)

// 			{

// 				alert("You are not eligible for any stream! ");

// 				return;

// 			}

// 			else

// 			{

// 					if(document.getElementById("cboStream").value=="Non-Medical")

// 					{   



// 						if(StudentType=="Internal")

// 						{

//                              	if(averagepercent<60)

// 						           {

// 							       alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

// 							           return;

// 						           }

// 						           if (MathsPercentage<50)

// 						            {

//                                           alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

// 							           return;

// 						           }

// 						}

// 						else

// 						{

//                              	if(averagepercent<60)

// 						           {

// 							       alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

// 							           return;

// 						           }

//                                   if (MathsPercentage<50)

// 						            {

//                                           alert("You are not eligible for  Non-Medical stream! Kindly select other stream!");

// 							           return;

// 						           }

						            



// 						}



					

// 					}

// 					if(document.getElementById("cboStream").value=="Medical")

// 					{   



// 						if(StudentType=="Internal")

// 						{

//                              	if(averagepercent<60)

// 						           {

// 							       alert("You are not eligible for Medical  stream! Kindly select other stream!");

// 							           return;

// 						           }

// 		                            if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" ||document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 50)

// 						           {

                                    

						           

//                                           alert('You are not eligible for Medical  stream! Kindly select other stream!');

                                          

// 							              return;

// 						               } 



// 						}

// 						else

// 						{

//                              	if(averagepercent<60)

// 						           {

// 							       alert("You are not eligible for Medical stream! Kindly select other stream!");

// 							           return;

// 						           }

//                                  if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" ||document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 50)

// 						           {

//                                           alert('You are not eligible for Medical  stream! Kindly select other stream!');

                                          

// 							              return;

// 						               } 



// 						}



					

// 					}



					

// 					if(document.getElementById("cboStream").value=="Commerce With Maths")

// 					{   



// 						if(StudentType=="Internal")

// 						{

//                              	if(averagepercent<50)

// 						           {

// 							       alert("You are not eligible for  Commerce with Maths stream! Kindly select other stream!");

// 							           return;

// 						           }

// 						           if (MathsPercentage<50)

// 						            {

//                                           alert("You are not eligible for  Commerce with Maths stream! Kindly select other stream!");

// 							           return;

// 						           }

// 						}

// 						else

// 						{

//                              	if(averagepercent<50)

// 						           {

// 							       alert("You are not eligible for  Commerce with Maths stream! Kindly select other stream!");

// 							           return;

// 						           }

//                                   if (MathsPercentage<50)

// 						            {

//                                           alert("You are not eligible for  Commerce with Maths stream! Kindly select other stream!");

// 							           return;

// 						           }



// 						}



					

// 					}



// 					if(document.getElementById("cboStream").value=="Commerce")

// 					{   



// 						if(StudentType=="Internal")

// 						{

//                              	if(averagepercent<50)

// 						           {

// 							       alert("You are not eligible for  Commerce stream! Kindly select other stream!");

// 							           return;

// 						           }

// 						         if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" ||document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 50)

// 						           {

//                                           alert('You are not eligible for Commerce stream! Kindly select other stream!');

                                          

// 							              return;

// 						               } 

// 						}

// 						else

// 						{

//                              	if(averagepercent<50)

// 						           {

// 							       alert("You are not eligible for  Commerce stream! Kindly select other stream!");

// 							           return;

// 						           }

//                             if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" || document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 50)

// 						           {

//                                           alert('You are not eligible for Commerce stream! Kindly select other stream!');

                                          

// 							              return;

// 						               } 



// 						}



					

// 					}



					

// 					if(document.getElementById("cboStream").value=="Liberal Arts")

// 					{



// 						if(StudentType=="Internal")

// 						{



// 						  if(averagepercent<40)

// 						   {

// 							alert("You are not eligible for  Liberal Arts stream");

// 							return;

// 						   }

// 						  if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" || document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage < 50)

// 						          {

//                                           alert('You are not eligible for Liberal Arts with Maths  stream! Kindly select other stream!');

                                          

// 							              return;

// 						         } 

// 					}

// 					else

// 					{

//                          if(averagepercent<40)

// 						   {

// 							alert("You are not eligible for  Liberal Arts stream");

// 							return;

// 						   }

// 						     if ((document.getElementById("cboOptionalSubject").value=="Applied Mathematics" ||document.getElementById("cboOptionalSubject").value=="Maths" || document.getElementById("cboOptionalSubject").value=="Mathematics") && MathsPercentage <50)

// 						          {

//                                           alert('You are not eligible for Liberal Arts with  stream! Kindly select other stream!');

                                          

// 							              return;

// 						         }

// 					}

// 				}


// 			if(document.getElementById("cboStream").value=="Medical without Maths")

// 					{



// 						if(StudentType=="Internal")

// 						{



// 						  if(averagepercent<60)

// 						   {

// 							alert("You are not eligible for  Medical without Maths stream");

// 							return;

// 						   }

						
// 					}

// 					else

// 					{

//                          if(averagepercent<60)

// 						   {

// 							alert("You are not eligible for  Medical without Maths stream");

// 							return;

// 						   }

						 
// 					}

// 				}


// 				if(document.getElementById("cboStream").value=="Liberal Arts without Maths")

// 					{



// 						if(StudentType=="Internal")

// 						{



// 						  if(averagepercent<40)

// 						   {

// 							alert("You are not eligible for  Liberal Arts without Maths stream");

// 							return;

// 						   }

						
// 					}

// 					else

// 					{

//                          if(averagepercent<40)

// 						   {

// 							alert("You are not eligible for  Liberal Arts without Maths stream");

// 							return;

// 						   }

						 
// 					}

// 				}

				

// 				if(document.getElementById("cboStream").value=="Commerce without Maths")

// 					{



// 						if(StudentType=="Internal")

// 						{



// 						  if(averagepercent<50)

// 						   {

// 							alert("You are not eligible for  Commerce without Maths Stream");

// 							return;

// 						   }

						
// 					}

// 					else

// 					{

//                          if(averagepercent<50)

// 						   {

// 							alert("You are not eligible for  Commerce without Maths Stream");

// 							return;

// 						   }

						   
// 					}

// 				}







					



					

// 			}

// 	   }

	  } 

	if(document.getElementById("txtAdmission").value=="A")

	{

	

	}



	

	if (document.getElementById("txtName").value.trim()=="")

	{

		alert("Name is mandatory");

		return;

	}

	

	

	

	if(document.getElementById("txtDOB").value.trim()=="")

	{

		alert ("Date of birth is mandatory!");

		return;

	}

	if(document.getElementById("txtSex").value.trim() == "")

	{

		alert ("Gender is mandatory!");

		return;

	}

	

	if(document.getElementById("cboClass").value=="Select One")

	{

		alert("Class is mandatory!");

		return;

	}

	

	if(document.getElementById("txtOptionalSubject").value=="")

	{

		alert("Optional Subjects are mandatory!");

		return;

	}

	

	if(document.getElementById("txtAddress").value.trim()=="")

	{

		alert("Residential Address is mandatory!");

		return;

	}

	if(document.getElementById("txtCategory").value.trim()=="")

	{

		alert("Category is mandatory!");

		return;

	}



	if (document.getElementById("txtFatherName").value.trim()=="")

	{

		alert("Father's name is mandatory");

		return;

	}

	

	

	

	

	if(document.getElementById("txtFatherMobileNo").value.trim()=="")

	{

		alert("Father's Mobile No is mandatory!");

		return;

	}

	if(document.getElementById("txtFatherEmailId").value.trim()=="")

	{

		alert("Father's Email Id is mandatory!");

		return;

	}

	

	if(document.getElementById("txtMotherName").value.trim()=="")

	{

		alert("Mother's Name is mandatory!");

		return;

	}

	

	

	if(document.getElementById("txtMobile").value.trim()=="")

	{

		alert("Mobile No is mandatory!");

		return;

	}



		if(document.getElementById("txtemail").value.trim()=="")

	{

		alert("Email is mandatory!");

		return;

	}

	

	if(document.frmNewStudent.F1.value=="" && document.getElementById("master_class").value == "10")

	{

		alert("Mark Sheet is mandatory!");

		return;

	}

	

	if(document.frmNewStudent.F2.value=="")

	{

		alert("Child  Photo is mandatory!");

		return;

	}



	if (document.getElementById("txtCategory").value=="OBC" || document.getElementById("txtCategory").value=="SC" || document.getElementById("txtCategory").value=="ST")

	 {

		if(document.frmNewStudent.F3.value=="")

	{



		alert("Category Certificate is mandatory!");

		return;

	}

}

	

	

	document.getElementById("frmNewStudent").submit();

}

function Validate2()

{

	var sadmission=document.getElementById("txtAdmission").value;

	//alert(sadmission.substring(0,1));

	if(sadmission.substring(0,1)=="N")

	{

		var StudentType="External";

	}

	else

	{

		var StudentType="Internal";

	}

if(document.getElementById("cboMathType").value=="" ) 

	{

		alert("Select Math Type are mandatory!");

		return;

	}
	
	if(document.getElementById("cboStream").value=="")

	{

		alert("Stream is mandatory!");

		return;

	}

	if (document.getElementById("txtName").value.trim()=="")

	{

		alert("Name is mandatory");

		return;

	}

	if(document.getElementById("txtDOB").value.trim()=="")

	{

		alert ("Date of birth is mandatory!");

		return;

	}

	if(document.getElementById("txtSex").value.trim() == "")

	{

		alert ("Gender is mandatory!");

		return;

	}

	if(document.getElementById("cboClass").value=="Select One")

	{

		alert("Class is mandatory!");

		return;

	}

	if(document.getElementById("txtOptionalSubject").value=="")

	{

		alert("Optional Subjects are mandatory!");

		return;

	}

	if(document.getElementById("txtAddress").value.trim()=="")

	{

		alert("Residential Address is mandatory!");

		return;

	}

	if(document.getElementById("txtCategory").value.trim()=="")

	{

		alert("Category is mandatory!");

		return;

	}

	if (document.getElementById("txtFatherName").value.trim()=="")

	{

		alert("Father's name is mandatory");

		return;

	}

	if(document.getElementById("txtFatherMobileNo").value.trim()=="")

	{

		alert("Father's Mobile No is mandatory!");

		return;

	}

	if(document.getElementById("txtFatherEmailId").value.trim()=="")

	{

		alert("Father's Email Id is mandatory!");

		return;

	}

	if(document.getElementById("txtMotherName").value.trim()=="")

	{

		alert("Mother's Name is mandatory!");

		return;

	}

	if(document.getElementById("txtMobile").value.trim()=="")

	{

		alert("Mobile No is mandatory!");

		return;

	}

	if(document.getElementById("txtemail").value.trim()=="")

	{

		alert("Email is mandatory!");

		return;

	}

// 	if(document.frmNewStudent.F1.value=="")

// 	{

// 		alert("Mark Sheet is mandatory!");

// 		return;

// 	}

	if(document.frmNewStudent.F2.value=="")

	{

		alert("Child  Photo is mandatory!");

		return;

	}

	if (document.getElementById("txtCategory").value=="OBC" || document.getElementById("txtCategory").value=="SC" || document.getElementById("txtCategory").value=="ST")

	 {

		if(document.frmNewStudent.F3.value=="")

	{



		alert("Category Certificate is mandatory!");

		return;

	}

}

	document.getElementById("frmNewStudent").submit();

}



function CalculateTotalDiscount()

{

	AdmissionFeeDiscount=0;

	AnnualFeeDiscount=0;

	TotalDiscount=0;

	if (!isNaN(document.getElementById("txtAdmissionFeesDiscount").value) == "true" || document.getElementById("txtAdmissionFeesDiscount").value == "")

	{

		AdmissionFeeDiscount=0;

	}

	else

	{

		AdmissionFeeDiscount = parseInt(document.getElementById("txtAdmissionFeesDiscount").value);

	}

	if (!isNaN(document.getElementById("txtAnnualFeeDiscount").value) == "true" || document.getElementById("txtAnnualFeeDiscount").value=="")

	{

		AnnualFeeDiscount=0;

	}

	else

	{

		AnnualFeeDiscount = parseInt(document.getElementById("txtAnnualFeeDiscount").value);

	}



	TotalDiscount = parseInt(AdmissionFeeDiscount) + parseInt(AnnualFeeDiscount) ;

	document.getElementById("txtTotalDiscount").value = TotalDiscount;

}

/*function fnlChkTransport()

{

	if(document.getElementById("cboTransport").value =="No")

	{

		document.getElementById("cboSafeTransport").style.display ="";

		document.getElementById("tdTransport").innerHTML='<font face="Cambria">If No, can you ensure safe commuting of the applicant  to and fro School :</font>';



	}

	else

	{

		document.getElementById("cboSafeTransport").value ="Yes";

		document.getElementById("cboSafeTransport").style.display ="none";

		document.getElementById("tdTransport").innerHTML='';

	}

}*/



function GetMathType()

{

  var type = document.getElementById("cboMathType").value;



  if (type == 'BasicMath') 

  {

     document.getElementById("showhidefld").style.display='none';
     document.getElementById("showhidefld1").style.display='none';
     document.getElementById("showhidefld2").style.display='none';
     document.getElementById("showhidefld3").style.display='none';
     document.getElementById("showhidefld4").style.display='block';
     document.getElementById("showhidefld5").style.display='block';
     document.getElementById("showhidefld6").style.display='block';


  }

  else

  {

 document.getElementById("showhidefld").style.display='block';
     document.getElementById("showhidefld1").style.display='block';
     document.getElementById("showhidefld2").style.display='block';
     document.getElementById("showhidefld3").style.display='block';
      document.getElementById("showhidefld4").style.display='none';
     document.getElementById("showhidefld5").style.display='none';
     document.getElementById("showhidefld6").style.display='none';


  	

  }



}



function GetFeeDetail()

{
    
    
    
	if((document.getElementById("cboClass").value=="11NMEDA")||(document.getElementById("cboClass").value=="11NMEDB")||(document.getElementById("cboClass").value=="11NMEDC")||(document.getElementById("cboClass").value=="11NMEDD")||(document.getElementById("cboClass").value=="11NMEDE")||(document.getElementById("cboClass").value=="11ARTJ")||(document.getElementById("cboClass").value=="11ARTI")||(document.getElementById("cboClass").value=="11COMH")||(document.getElementById("cboClass").value=="11COMG")||(document.getElementById("cboClass").value=="11") ||(document.getElementById("cboClass").value=="11TEMP") ||(document.getElementById("cboClass").value=="10Z") ||(document.getElementById("cboClass").value=="11MEDF"))

	{

		document.getElementById("trOptionalSubject").style.display ="";

		document.getElementById("tdSelectStream").style.display ="";

		document.getElementById("tdStream").style.display ="";

		document.getElementById("trSubjectMarks").style.display ="";

		document.getElementById("trSubjectMarksTitle").style.display ="";

		

	}
	else if( document.getElementById("master_class").value == "10")

	{

		document.getElementById("trOptionalSubject").style.display ="";

		document.getElementById("tdSelectStream").style.display ="";

		document.getElementById("tdStream").style.display ="";

		document.getElementById("trSubjectMarks").style.display ="none";

		document.getElementById("trSubjectMarksTitle").style.display ="none";

		

	}

	else

	{

		var src = document.getElementById("cboOptionalSubject");

		for(var count=0; count < src.options.length; count++) 

			{

				if(src.options[count].selected == true) 

				{

					src.options[count].selected = false;

				}

			}

			SelectedValue="";

			document.getElementById("trOptionalSubject").style.display ="none";

			document.getElementById("tdSelectStream").style.display ="none";

			document.getElementById("tdStream").style.display ="none";

			document.getElementById("trSubjectMarks").style.display ="none";

			document.getElementById("trSubjectMarksTitle").style.display ="none";

			document.getElementById("txtOptionalSubject").value=SelectedValue;

	}

	// try

	// 	    {    

	// 			// Firefox, Opera 8.0+, Safari    

	// 			xmlHttp=new XMLHttpRequest();

	// 		}

	// 	  catch (e)

	// 	    {    // Internet Explorer    

	// 			try

	// 		      {      

	// 				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

	// 			  }

	// 		    catch (e)

	// 		      {      

	// 				  try

	// 			        { 

	// 						xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

	// 					}

	// 			      catch (e)

	// 			        {        

	// 						alert("Your browser does not support AJAX!");        

	// 						return false;        

	// 					}      

	// 			  }    

	// 		 } 

	// 		 xmlHttp.onreadystatechange=function()

	// 	      {

	// 		      if(xmlHttp.readyState==4)

	// 		        {

	// 					var rows="";

	// 		        	rows=new String(xmlHttp.responseText);



	// 		        	arr_row=rows.split(",");



	// 		        	document.getElementById("txtAdmissionFees").value=arr_row[4];

	// 					document.getElementById("txtTotal").value=arr_row[4];

	// 		        	//document.getElementById("txtSecurityFeesAmount").value=arr_row[5];

	// 					//CalculatTotal();

	// 					//alert(rows);														



	// 		        }



	// 	      }







	// 		var submiturl="../Fees/GetAdmissionFeeDetail.php?Class=" + document.getElementById("cboClass").value + "&financialyear=" + document.getElementById("cboFinancialYear").value;



	// 		xmlHttp.open("GET", submiturl,true);



	// 		xmlHttp.send(null);



}

function fnlSelectionCheck()

{

	var src = document.getElementById("cboOptionalSubject");

		SelectedCount=0;

		SelectedValue="";

		for(var count=0; count < src.options.length; count++) 

		{

			if(src.options[count].selected == true) 

			{

				var option = src.options[count];

				

				SelectedCount=SelectedCount + 1;

				SelectedValue=SelectedValue + option.value +",";

			}

		}

	

		//alert(SelectedValue);

	if(document.getElementById("cboStream").value!="Liberal Arts" && document.getElementById("cboStream").value!="Liberal Arts With Maths" && document.getElementById("cboStream").value!="Liberal Arts With Selective Subjects")	

	 {

	 	if(SelectedCount>1)

		{

			for(var count=0; count < src.options.length; count++) 

			{

				if(src.options[count].selected == true) 

				{

					src.options[count].selected = false;

				}

			}

			SelectedValue="";

			alert("Maximum only one subjects can be selected!");

			return;

		}

	  }	



		if(document.getElementById("cboStream").value=="Liberal Arts" || document.getElementById("cboStream").value=="Liberal Arts With Maths" )	

	 

		{

			if(SelectedCount>4)

			{

				for(var count=0; count < src.options.length; count++) 

				{

					if(src.options[count].selected == true) 

					{

						src.options[count].selected = false;

					}

				}

				SelectedValue="";

				alert("Maximum only 4 subjects can be selected!");

				return;

			}

		}

			if(document.getElementById("cboStream").value=="Liberal Arts With Selective Subjects")	

	 

		{

			if(SelectedCount>5 )

			{

				for(var count=0; count < src.options.length; count++) 

				{

					if(src.options[count].selected == true) 

					{

						src.options[count].selected = false;

					}

				}

				SelectedValue="";

				alert("Maximum only 5 subjects can be selected!");

				return;

			}

		}

		document.getElementById("txtOptionalSubject").value=SelectedValue;		

}

function fnlMandatoruShowHide()

{

	if(document.getElementById("cboStream").value=="Non-Medical")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core<br>2. Physics<br>3. Chemistry<br>4. Mathematics</font>";
		document.getElementById("showhidefld11").style.display='block';



	}

	if(document.getElementById("cboStream").value=="Medical")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core<br>2. Physics<br>3. Chemistry<br>4. Biology</font>";
		document.getElementById("showhidefld11").style.display='block';


	}

	if(document.getElementById("cboStream").value=="Commerce")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core<br>2. Accountancy<br>3. Business Studies<br>4. Economics</font>";
		document.getElementById("showhidefld11").style.display='block';


	}

	
	if(document.getElementById("cboStream").value=="Liberal Arts")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core</font>";
		document.getElementById("showhidefld11").style.display='block';


	}
	
	if(document.getElementById("cboStream").value=="Liberal Arts without Maths")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core</font>";
		document.getElementById("showhidefld11").style.display='none';


	}
	if(document.getElementById("cboStream").value=="Medical without Maths")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core<br>2. Physics<br>3. Chemistry<br>4. Biology</font>";
		document.getElementById("showhidefld11").style.display='none';



	}
	if(document.getElementById("cboStream").value=="Commerce without Maths")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="<b><u><font face='Cambria'>Mandatory Subjects :</u></b> <br><br>1. English Core<br>2. Accountancy<br>3. Business Studies<br>4. Economics</font>";
		document.getElementById("showhidefld11").style.display='none';



	}

	


	


	

	if(document.getElementById("cboStream").value=="Select One")

	{

		document.getElementById("tdMandatorySubject").innerHTML ="";

	}

}



</script>

<script language="javascript">

	String.prototype.trim=function()

	{

		return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');

	};

</script>



<SCRIPT language=Javascript>

      <!--

      function isNumberKey(evt)

      {

         var charCode = (evt.which) ? evt.which : event.keyCode

         if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;



         return true;

      }

      //-->

   </SCRIPT>

<html>

<head>

<meta http-equiv="Content-Language" content="en-us">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>Student Registration</title>



<!-- link calendar resources -->



	<link rel="stylesheet" type="text/css" href="../Admin/tcal.css" />

	

	<link rel="stylesheet" type="text/css" href="../Admin/css/style.css" />



	<script type="text/javascript" src="../Admin/tcal.js"></script>

<style type="text/css">

.style7 {

	border-left-style: none;

	border-left-width: medium;

	text-align: center;

}

.style12 {

	border-left-width: 0px;

}

.auto-style1 {



	border-collapse: collapse;



	border: 0px solid #000000;



}



.auto-style6 {



	font-size: small;



}

.auto-style7 {



	border-collapse: collapse;



	border-width: 0px;



}



.auto-style11 {



	border-style: none;



	border-width: medium;



}



.auto-style16 {



	font-size: 12pt;



	color: #000000;



	margin-left: 13px;



	font-family: Calibri;



}



.auto-style18 {



	font-weight: bold;



	font-size: 12pt;



	font-family: Calibri;



}



.auto-style19 {



	border-style: none;



	border-width: medium;



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



}



.auto-style20 {



	font-weight: normal;



	font-size: 12pt;



	font-family: Calibri;



}



.auto-style21 {



	font-family: Calibri;



	font-weight: normal;



	font-size: 12pt;



	color: #000000;



}



.auto-style23 {



	font-size: 12pt;



}



.auto-style25 {



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



}



.auto-style26 {



	border-style: none;



	border-width: medium;



	font-size: 12pt;



	font-family: Calibri;



}

.auto-style30 {



	border: medium solid #FFFFFF;



	color: #000000;



}



.auto-style5 {



	text-align: left;



	font-family: Calibri;



	color: #000000;



	text-decoration: underline;



	font-size: medium;



}



.auto-style3 {



	color: #000000;



}



.auto-style31 {



	font-family: Calibri;



}



.auto-style32 {



	font-size: small;



	font-family: Calibri;



	color: #000000;



}



.auto-style33 {



	font-size: 12pt;



	font-family: Calibri;



}



.auto-style34 {



	border-style: none;



	border-width: medium;



	font-family: Calibri;



}



.auto-style35 {



	text-align: center;



	border-top-style: solid;



	border-top-width: 1px;



	font-family: Calibri;



	font-weight: bold;



	font-size: 18px;



	color: #000000;



}

.auto-style36 {



	border-style: none;



	border-width: medium;



	text-align: right;



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



}



.auto-style38 {



	text-align: center;



	border-top-style: solid;



	border-top-width: 1px;



	font-family: Calibri;



	font-size: medium;



	color: #000000;



}



.auto-style17 {



	font-family: Calibri;



	font-size: 11pt;



	color: #000000;



}



.auto-style39 {



	border-style: none;



	border-width: medium;



	text-align: left;



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



}



.auto-style40 {



	border-style: none;



	border-width: medium;



	font-family: Calibri;



	font-size: 11pt;



	color: #000000;



}



.auto-style41 {



	border-style: none;



	border-width: medium;



	text-align: left;



}

.auto-style47 {



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



	text-decoration: underline;



	background-color: #99CCFF;



}



.auto-style48 {



	border-style: none;



	border-width: medium;



	color: #000000;



	background-color: #99CCFF;



}



.auto-style49 {



	border-style: none;



	border-width: medium;



	font-family: Calibri;



	font-size: 12pt;



	color: #000000;



	text-decoration: underline;



	background-color: #99CCFF;



}

.style14 {



	border-color: #000000;



	border-width: 0px;



	border-collapse: collapse;



}

.footer {



    height:20px; 

    width: 100%; 

    background-image: none;

    background-repeat: repeat;

    background-attachment: scroll;

    background-position: 0% 0%;

    position: fixed;

    bottom: 2pt;

    left: 0pt;



}   



.footer_contents 



{



        height:20px; 

        width: 100%; 

        margin:auto;        

        background-color:Blue;

        font-family: Calibri;

        font-weight:bold;



}

.style15 {

	border-collapse: collapse;

}

.style16 {

	border: 0 solid #FFFFFF;

	color: #000000;

}

.style21 {

	border-style: none;

	border-width: medium;

	font-family: Cambria;

	text-align: center;

}

.style22 {

	text-align: center;

	background-color: #E4E4E4;

}

.style23 {

	text-align: center;

}

.style24 {

	text-align: center;

	background-color: #E4E4E4;

	font-family: Cambria;

}

.style25 {

	font-family: Cambria;

}

.style26 {

	font-family: Calibri;

	font-size: 12pt;

	color: #CC3300;

}

.style28 {

	font-family: Calibri;

	font-size: 12pt;

	color: #000000;

	background-color: #99CCFF;

}

.style29 {

	border-left-style: none;

	border-left-width: medium;

	border-right-style: none;

	border-right-width: medium;

	border-top-style: solid;

	border-top-width: 1px;

	border-bottom-style: none;

	border-bottom-width: medium;

}

.style30 {

	font-family: Cambria;

	font-size: 12pt;

}

.style32 {

	border-style: none;

	border-width: medium;

	text-align: left;

}

.style33 {

	text-decoration: underline;

	font-family: Cambria;

	color: #FF0000;

}

.style34 {

	text-decoration: underline;

}

.style35 {

	border-style: none;

	border-width: medium;

	font-family: Cambria;

	font-size: 12pt;

}

.style36 {

	color: #FF0000;

	text-decoration: underline;

}

</style>

</head>

<body>

<div align="center">

<table width="100%">

<tr>

<td>

<h1 align="center"><b><font face="cambria"><img src="<?php echo $SchoolLogo; ?>" height="100px" width="400px"></font></b></h1>

</td>

</tr>

<tr>

<td align="center">

<font face="cambria"><b><?php echo $SchoolAddress; ?></b></font>

</td>

</tr>

<tr>

<td align="center">

<font face="cambria"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font>

</td>

</tr>

<tr>

<td align="center">

<font face="cambria"><b>Email Id: <?php echo $SchoolEmailId; ?></b></font>

</td>

</tr>

<tr>

<td align="center">

&nbsp;</td>

</tr>

</table>

</div>

<table id="table_10" style="width: 100%" cellspacing="0" cellpadding="0" class="style15">

	<tr>

		<td class="style16">

<p  style="height: 12px" align="center">

<strong><font face="Cambria" style="font-size: 14pt">&nbsp;STREAM&nbsp; OPTION FORM&nbsp; </font></strong></p>

<p  style="height: 12px" align="center">

<strong><font face="Cambria" style="font-size: 14pt">CLASS XI (2022-23)</font></strong></p>

<p  style="height: 12px" align="center">

&nbsp;</p>





<p  style="height: 12px" align="left" class="style25">&nbsp;</p>

</td>

</tr>

		</table>

	<table cellspacing="0" cellpadding="0" class="style12" style="width: 100%">

	<form name="frmNewStudent" id="frmNewStudent" method="post" action="SubmitfrmStudentMasterInfointernal.php" enctype="multipart/form-data">

		<input type="hidden" name="hSibling" id="hSibling" value="No">

		<input type="hidden" name="hFatherAlumni" id="hFatherAlumni" value="No">

		<input type="hidden" name="hMotherAlumni" id="hMotherAlumni" value="No">

		<input type="hidden" name="hSingleParent" id="hSingleParent" value="No">

		<input type="hidden" name="hSpecialNeed" id="hSpecialNeed" value="No">

		<input type="hidden" name="hDPSStaff" id="hDPSStaff" value="No">

		<input type="hidden" name="hEWSCategory" id="hEWSCategory" value="No">

		<input type="hidden" name="hOtherCategory" id="hOtherCategory" value="No">

		<input type="hidden" name="txtOptionalSubject" id="txtOptionalSubject" value="">

		<input type="hidden" name="hsadmission" id="hsadmission" value="<?php echo $sadmission; ?>">

		<tr>

		<td style="height: 10; border-top-style:solid; border-top-width:1px" class="style28">

		<font face="Cambria">

		<strong>Student Details:</strong></font></td>



		<font face="Cambria">

		<br class="auto-style31">



		<br class="auto-style31">

		</font>



		</tr>		<tr>

		<td style="height: 29px;" class="auto-style23">

		<table style="width: 100%" class="style14">

			<tr>



				<td class="auto-style11" colspan="2">



				&nbsp;</td>



				<td style="width: 3%" class="auto-style34">&nbsp;</td>



				<td style="width: 159px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 13%" class="auto-style26">&nbsp;</td>

				<td style="width: 263px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 223px" class="auto-style26">&nbsp;</td>

				<td style="width: 20%" class="auto-style26">&nbsp;</td>



			</tr>

			<tr>


				<td class="auto-style11">



				Registration&nbsp; No:</td>



				<td class="auto-style11">



		<font face="Cambria">



		<input name="txtAdmission" id="txtAdmission" type="text" class="text-box" value="<?php echo $sadmission;?>" readonly></font></td>



				<td style="width: 3%" class="auto-style34">&nbsp;</td>



				<td style="width: 159px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 13%" class="auto-style26">&nbsp;</td>

				<td style="width: 263px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 223px" class="auto-style26">&nbsp;</td>

				<td style="width: 20%" class="auto-style26">&nbsp;</td>



			</tr>

			<tr>



				<td class="auto-style11" colspan="2">



				&nbsp;</td>



				<td style="width: 3%" class="auto-style34">&nbsp;</td>



				<td style="width: 159px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 13%" class="auto-style26">&nbsp;</td>

				<td style="width: 263px" class="auto-style26" colspan="2">&nbsp;</td>

				<td style="width: 223px" class="auto-style26">&nbsp;</td>

				<td style="width: 20%" class="auto-style26">&nbsp;</td>



			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">



		<span class="auto-style21"><font face="Cambria">1. First Name of Applicant</font></span><span style="color: #000000" class="auto-style20"><font face="Cambria">*:</font></span><span class="auto-style18"><font face="Cambria">

		</font>



				</span>

				</td>

				<td style="width: 16%" class="auto-style11">



		<font face="Cambria">



		<input name="txtName" id="txtName" type="text" class="text-box" value="<?php echo $StudentName;?>" readonly></font></td> 

				<td style="width: 3%" class="auto-style11">



				<span class="auto-style31"><font size="3" face="Cambria">



				&nbsp;</font></span></td>

				<td style="width: 159px" class="auto-style11" colspan="2">



		<span class="auto-style21">



		<span class="auto-style25"><font face="Cambria">2. Date of Birth*</font></span><span style="color: #000000" class="auto-style33"><font face="Cambria">:<br>

				(mm/dd/yyyy)</font></span></span></td>

				<td style="width: 13%" class="auto-style11">

		<font face="Cambria">

		<input name="txtDOB" id="txtDOB" type="text" readonly="readonly" value="<?php echo $DOB;?>"></font></td>

				<td style="width: 263px" class="auto-style11" colspan="2">

				&nbsp;</td>

				<td style="width: 223px" class="auto-style19">



		&nbsp;</td>

				<td style="width: 20%" class="auto-style41">

		&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

				<span class="auto-style21"><font face="Cambria">&nbsp;</font></span></td>

				<td style="width: 16%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style11" colspan="2">

				&nbsp;</td>

				<td style="width: 13%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 263px" class="auto-style11" colspan="2">

				&nbsp;</td>

				<td class="auto-style19" colspan="2">

				&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 16%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style11" colspan="2">

				&nbsp;</td>

				<td style="width: 13%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 263px" class="auto-style11" colspan="2">



				&nbsp;</td>

				<td class="auto-style19">



				&nbsp;</td>

				<td class="auto-style19">

				&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

				<span class="auto-style21"><font face="Cambria">3. 



				</font></span><span style="color: #000000" class="auto-style33"><font face="Cambria">

				Gender</font></span><span class="auto-style21"><font face="Cambria">*</font><span style="color: #000000" class="auto-style33"><font face="Cambria">:</font></span></span></td>

				<td style="width: 16%" class="auto-style11">				

		<select size="1" name="txtSex" id="txtSex" class="text-box" readonly>

		<option selected value="">Select One</option>

		<option value="Male" <?php if($Sex=="Male") {?> selected="selected" <?php } ?>>Male</option>

		<option value="Female" <?php if($Sex=="Female") {?> selected="selected" <?php } ?>>Female</option>

		</select></td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style11" >

				<span class="auto-style33"><font face="Cambria"></font></span><span style="color: #000000" class="auto-style33"><font face="Cambria"> 

				4.Select Math Type:<br>( As per CBSE registration in class X)</font></span><span class="auto-style31"><font size="3" face="Cambria"> </font>

				</span>

				</td>

				<td style="width: 13%" class="auto-style11">

				<strong><em style="font-style: normal">

				<font face="Cambria">

		<select name="cboMathType" id="cboMathType" class="text-box" onchange="Javascript:GetMathType();" size="1">

		<option value="">Select One</option>

		<option value="BasicMath">Basic Math</option>

		<option value="StandardMath">Standard Math</option>

	

		</select></font></em></strong></td>

				<td style="width: 159px" class="auto-style11" >

				<span class="auto-style33"><font face="Cambria">5</font></span><span style="color: #000000" class="auto-style33"><font face="Cambria">. 

				Select Class*:</font></span><span class="auto-style31"><font size="3" face="Cambria"> </font>

				</span>

				</td>

				<td style="width: 13%" class="auto-style11">

				<strong><em style="font-style: normal">

				<font face="Cambria">

		<select name="cboClass" id="cboClass" class="text-box" onchange="Javascript:GetFeeDetail();" size="1">

		<option value="Select One">Select One</option>

		<option value="<?php echo $sclass;?>">11</option>





		<!--

		<option value="Nursery">Nursery</option>

		<option value="Prep">Prep</option>
		<option value="1">1st</option>

		<option value="2">2nd</option>

		<option value="3">3rd</option>

		<option value="4">4th</option>

		<option value="5">5th</option>

		<option value="6">6th</option>

		<option value="7">7th</option>

		<option value="8">8th</option>

		<option value="9">9th</option>-->

		<!--<option value="10th">10th</option> -->

		

				

		</select></font></em></strong></td>
        <input type = "hidden" id  ="master_class" class="form_control" value="<?php echo $MasterClass;?>">    
				<td style="width: 263px" class="auto-style11" colspan="2">



				&nbsp;</td>

				<td class="auto-style19">



				<span style="color: #000000" class="auto-style33">



				<font face="Cambria">6. </font></span>

				<span class="auto-style33">

				<font face="Cambria"><span style="color: #000000">Select Stream</span></font></span><span style="color: #000000" class="auto-style33"><font face="Cambria">*

				:</font></span></td>

				<td class="auto-style19">

				<select size="1" name="cboStream" id="cboStream" class="text-box" onchange="javascript:fnlMandatoruShowHide();">

				<option value="">Select One</option>

				<option value="Medical" id="showhidefld">Medical</option>

				<option value="Non-Medical" id="showhidefld1">Non-Medical</option>

				<option value="Commerce" id="showhidefld2">Commerce</option>
				<option value="Liberal Arts" id="showhidefld3">Liberal Arts</option>


				<option value="Medical without Maths" id="showhidefld4">Medical without Maths</option>

					<option value="Commerce without Maths" id="showhidefld5">Commerce without Maths</option>

				<option value="Liberal Arts without Maths" id="showhidefld6">Liberal Arts without Maths</option>

			<!--	<option value="Liberal Arts With Selective Subjects">Liberal Arts With Selective Subjects</option>-->

				

				</select></td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style19" colspan="2">

		&nbsp;</td>

				<td class="auto-style11">

				&nbsp;</td>

				<td class="auto-style11" colspan="2">

				&nbsp;</td>

				<td class="auto-style11" id="tdSelectStream" style="display: none;">

				&nbsp;</td>

				<td class="auto-style11" id="tdStream" style="display: none;">

				&nbsp;</td>

			</tr>



			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style19" colspan="2">

		&nbsp;</td>

				<td class="auto-style11">

				&nbsp;</td>

				<td class="auto-style11" colspan="2">

				&nbsp;</td>

				<td class="auto-style11" id="tdSelectStream" style="display: none;">

				&nbsp;</td>

				<td class="auto-style11" id="tdStream" style="display: none;">

				&nbsp;</td>

			</tr>



			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td style="width: 3%" class="auto-style11">

				&nbsp;</td>

				<td style="width: 159px" class="auto-style19" colspan="2">

		&nbsp;</td>

				<td class="auto-style11">

				&nbsp;</td>

				<td class="auto-style11" colspan="2">

				&nbsp;</td>

				<td class="auto-style11" id="tdSelectStream" style="display: none;">

				&nbsp;</td>

				<td class="auto-style11" id="tdStream" style="display: none;">

				&nbsp;</td>

			</tr>



			<tr id="trOptionalSubject" style="display: none;">

				<td class="auto-style11" colspan="5">

		<p style="text-align: left"><u><b><font face="Cambria">General 

		Instructions:-</font></b></u></p>

		<p style="text-align: left"><b><font face="Cambria">* Only those 

		students who have qualified Mathematics Standard (041) in class X are 

		eligible for the Non Medical stream.</font></b></p>

		<p style="text-align: left"><b>*Students with Mathematics Basic(241) in 

		class X are eligible for Applied Mathematics ( 241) in classes XI and 

		XII in the Medical, the Commerce and the Liberal Arts streams. However 

		they are not eligible to take Mathematics (041) in any of the streams.</b></p>

		<p style="text-align: left"><b>*Students who have qualified Mathematics 

		Standard in class X can take Mathematics (041) in any stream/Applied 

		Mathematics (241) in the Medical, the Commerce and the liberal Arts 
		stream as 

		an optional subject.</b></p>

		<p style="text-align: left"><b>* Students cannot take Mathematics (041) 

		and Applied Mathematics (241) together.</b></p>

		<p style="text-align: left"><b>*In the following subject combinations 

		only one subject in each combination can be opted for:<br>

		a. Library and Information Science/Biotechnology/Banking/Retail.<br>

		b. Taxation/ Insurance<br>

		c. French/German <br>

		d. Kathak-Dance/Bharat Natyam-Dance/Web Application<br>

		e. Painting/Sculpture/Commercial Art<br>

		f. Computer Science/Informatics Practices</b><br>
		<b>g. Typography and Computer Application/ 
		Financial&nbsp; Markets and Management</b><br>
		<b>h. Biology/Biotechnology</b><br>
		<b>i. Legal studies/ Sanskrit Core</b>
		<br>
		<b>j. Yoga/ Artificial Intelligence</b><br>
		<b>k. Agriculture / Mass Media Studies</b></p>


		<p style="text-align: left"><u><b><font face="Cambria">Medical: </font>

		</b></u></p>

		<p style="text-align: left"><font face="Cambria" color="#FF0000">

		<strong>- Student to select only one optional subject from the options 

		given.</strong></font></p>

		<p style="text-align: left"><u><b><font face="Cambria">Non - Medical:

		</font></b></u></p>

		<p style="text-align: left"><font face="Cambria" color="#FF0000">

		<strong>- 

		Mathematics is a compulsory subject for Non-Medical stream and should 

		not be selected as an optional subject.</strong></font></p>

		<p style="text-align: left"><u><b><font face="Cambria">Commerce:</font></b></u></p>

		<p style="text-align: left"><font face="Cambria" color="#FF0000">

		<strong>- Student to select only one optional subject from the options 

		given.</strong></font></p>

		<!--<p style="text-align: left"><u><b><font face="Cambria">Commerce With 

		Mathematics:</font></b></u></p>

		<p style="text-align: left"><font face="Cambria" color="#FF0000">

		<strong>- Mathematics is a compulsory subject for Commerce with 

		Mathematics stream</strong></font></p>

		<p style="text-align: left"><strong>

		<font face="Cambria" color="#FF0000">- Student not to select any 

		optional subject</font></strong></p>-->

		<p style="text-align: left"><u><b><font face="Cambria">Liberal Arts:</font></b></u>&nbsp;

		</p>

		<p style="text-align: left"><font face="Cambria"><span class="style36">

		<strong><span style="text-decoration: none">- Student to select 3 main 

		subjects and&nbsp; one 

		optional subject.</span></strong></span></font></p>

		<p style="text-align: left">&nbsp;</p>

				</td>

				<td class="style32" colspan="2" valign="top" >

		&nbsp;</td>

	<td class="style32" colspan="2" valign="top" id="tdMandatorySubject">

				</td>

				<td style="width: 20%" class="style32">		<span class="style34">

				<font face="Cambria" color="#FF0000"><strong>Optional Subjects 

		(Read Notes On Left Before Selecting Options):</strong></font><strong><br>

				</strong></span>

				<br>

				<select size="19" name="cboOptionalSubject" id="cboOptionalSubject" onclick="fnlSelectionCheck();" multiple>

				<option value="Applied Mathematics" >Applied Mathematics</option>
				<option value="Artificial Intelligence" >Artificial Intelligence</option>
				<option value="Agriculture" >Agriculture</option>
                <option value="Banking">Banking</option>
				<option value="Biotechnology">Biotechnology</option>
				<option value="Beauty and Wellness">Beauty and Wellness</option>
				<option value="Bharatnatyam-Dance">Bharatnatyam-Dance</option>
				<option value="Computer Science">Computer Science</option>
				<option value="Commercial Art">Commercial Art</option>
				<option value="Economics">Economics</option>
				<option value="French">French</option>
				<option value="Fashion Studies">Fashion Studies</option>
				<option value="Financial Markets Management">Financial Markets Management</option>
				<option value="Geography">Geography</option>
				<option value="German">German</option>
				<option value="Hindi Core">Hindi Core</option>
				<option value="History">History</option>
				<option value="Home Science">Home Science</option>
				<option value="Informatics Practices">Informatics Practices</option>
				<option value="Insurance">Insurance</option>
				<option value="Kathak-Dance">Kathak-Dance</option>
				<option value="Legal Studies">Legal Studies</option>
				<option value="Library & Information Science">Library & Information Science</option>
				<option value="Mathematics" id="showhidefld11">Mathematics</option>
				<option value="Mass Media Studies">Mass Media Studies</option>
				<option value="Marketing">Marketing</option>
				<option value="Music(Hindustani Vocal)">Music(Hindustani Vocal)</option>
				<option value="NCC">NCC(Only For Girls)</option>
				<option value="Painting">Painting</option>
				<option value="Psychology">Psychology</option>
				<option value="Political Science">Political Science</option>
				<option value="Physical Education">Physical Education</option>
				<option value="Retail">Retail</option>
				<option value="Sanskrit">Sanskrit</option>
				<option value="Sculpture">Sculpture</option>
				<option value="Sociology">Sociology</option>
				<option value="Taxation">Taxation</option>
				<option value="Typography & Computer Application">Typography & Computer Application</option>
				<option value="Web Application">Web Application</option>
				<option value="Yoga">Yoga</option>
				
				
				
				
				
				
				
				





				<!--<option value="First Aid and Emergency Media Care">First Aid and Emergency Media Care</option>-->



				</select><br>

				<span class="style33"><strong><br>

			Press ctrl button for multiple selection</strong></span></td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

				&nbsp;</td>

				<td class="auto-style11" colspan="9" align="left">

				&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td class="auto-style11" colspan="8">

		&nbsp;</td>

				<td style="width: 20%" class="auto-style11">

				&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 16%" class="auto-style11">

		<span class="auto-style25"><font face="Cambria">7. Residential 

		Address* </font></span>



				</td>

				<td class="auto-style11" colspan="3">

				<font face="Cambria">

				<textarea name="txtAddress" id="txtAddress"class="text-box-address" rows="3" cols="45"><?php echo $ResidentialAddress;?></textarea></font></td>

				<td class="style21" colspan="3">

				8. Select Locality*</td>

				<td class="auto-style11" colspan="3">

		<font face="Cambria">

				<select name="cboLocation" id="cboLocation" class="text-box" size="1">

		<option value="">Select One</option>

		<?php

		while($rowL=mysqli_fetch_row($rsLocation))

		{

			$Sector=$rowL[0];

		?>

		<option value="<?php echo $Sector;?>" <?php if($Location=="$Sector") {?> selected="selected" <?php } ?>><?php echo $Sector;?></option>

		<?php

		}

		?>

		

		</select></font></td>

			</tr>

			

			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td class="auto-style11" colspan="8">

		&nbsp;</td>

				<td style="width: 20%" class="auto-style11">

				&nbsp;</td>

			</tr>

			

			<tr><td style="width: 11%" class="auto-style11">

				<span class="auto-style21"><font face="Cambria">9. </font>
				</span><span style="color: #000000" class="auto-style33"><font face="Cambria">

				Category</font></span><span class="auto-style21"><font face="Cambria">*<span style="color: #000000" class="auto-style33">:</span></font></span></td>

				<td>				

		<select size="1" name="txtCategory" id="txtCategory" class="text-box">

		<option selected value="">Select One</option>

		<option value="General" <?php if($Sex=="General") {?> selected="selected" <?php } ?>>General</option>

			<option value="SC" <?php if($Sex=="SC") {?> selected="selected" <?php } ?>>SC</option>

		<option value="ST" <?php if($Sex=="ST") {?> selected="selected" <?php } ?>>ST</option>

		<option value="OBC" <?php if($Sex=="OBC") {?> selected="selected" <?php } ?>>OBC</option>

	

		

		</select></td>

				<td style="width: 11%" class="auto-style11">

				<span class="auto-style21"><font face="Cambria">10. </font>

				</span><span style="color: #000000" class="auto-style33"><font face="Cambria">

				Aadhar Card </font></span><span class="auto-style21"><font face="Cambria"><span style="color: #000000" class="auto-style33">:</span></font></span></td>

				<td>	

				<input type="text" name="txtAadharCardNo" id="txtAadharCardNo" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  class="text-box">			

		</td>

				<td style="width: 11%" class="auto-style11">

				<span class="auto-style21"><font face="Cambria">11. </font>

				</span><span style="color: #000000" class="auto-style33"><font face="Cambria">

				PWD(Physically Challenged / Dyslexic etc)</font></span><span class="auto-style21"><font face="Cambria">*<span style="color: #000000" class="auto-style33">:</span></font></span></td>

				<td>	

				<select name="pwd" id="pwd"  class="text-box">

					<option value="No">No</option>

					<option value="Yes">Yes</option>



				</select>		

		</td>

				<td style="width: 20%" class="auto-style11">

				&nbsp;</td>

			<tr>

				<td style="width: 16%" class="auto-style11">

		&nbsp;</td>

				<td class="auto-style11" colspan="8">

		&nbsp;</td>

				<td style="width: 20%" class="auto-style11">

				&nbsp;</td>

			</tr>

		<!--	<tr >

				<td style="width: 16%" class="style35">

		12. Hostel Facility required:</td>

				<td class="auto-style11" colspan="8">

		<select name="cboHostelFacility" id="cboHostelFacility">

		<option <?php if($HostelFacility=="") {?> selected="" <?php } ?> value="No">No</option>

		<option <?php if($HostelFacility=="Yes") {?> selected="" <?php } ?> value="Yes">Yes</option>

		</select></td>

				<td style="width: 20%" class="auto-style11">

				&nbsp;</td>

			</tr>--->

			</table>

		</td>

			</tr>

		

		<tr>

		

		<td class="auto-style33" style="border-bottom-style: solid; border-bottom-width: 1px">

		&nbsp;</td>



			</tr>

		

		<tr>

		

		<td style="height: 10; border-top-width:1px" class="style28">

		<strong><font face="Cambria">12</font></strong><font face="Cambria"><strong> 

		. Family Details (Father / Mother / Guardian):</strong></font></td>

			</tr>

			

			

		

			<tr>

			

			

		

		<td style="height: 46px;" class="auto-style23">

		<table style="width: 100%" class="style14">

			<tr>

				<td class="auto-style11" colspan="6">

		&nbsp;</td>

			</tr>

			<tr>

				<td class="auto-style11" colspan="6" height="30">

		<font face="Cambria"><b>(A) Father's Details:</b></font></td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		<span class="auto-style21"><font face="Cambria">&nbsp;Name*</font></span><span style="color: #000000" class="auto-style20"><font face="Cambria">:</font></span></td>

				<td style="width: 172px" class="auto-style11">



		<font face="Cambria">



		<input name="txtFatherName" id="txtFatherName" type="text" class="text-box" value="<?php echo $sfathername;?>" readonly></font></td>

				<td style="width: 157px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



		&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		<font face="Cambria">Mobile No *:</font></td>

				<td style="width: 158px" class="auto-style11">



		<font face="Cambria">



		<input name="txtFatherMobileNo" id="txtFatherMobileNo" class="text-box" type="text" size="20" value="<?php echo $FatherMobileNo;?>"></font></td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 172px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 158px" class="auto-style11">



				&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 172px" class="auto-style11">



		&nbsp;</td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px" class="auto-style11">



		&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		<font face="Cambria">Email Id *:</font></td>

				<td style="width: 172px" class="auto-style11">



		<font face="Cambria">



		<input name="txtFatherEmailId" id="txtFatherEmailId" class="text-box" type="text" size="20" value="<?php echo $email;?>"></font></td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px" class="auto-style11">



		&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 212px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 172px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



		&nbsp;</td>

				<td style="width: 157px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



		&nbsp;</td>

			</tr>

			<tr>

				<td class="auto-style11" colspan="6" style="border-top-style: solid; border-top-width: 1px">

		<b><font face="Cambria">(B)</font></b><font face="Cambria"><b> Mother's 

		Details:</b></font></td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		<span class="auto-style21"><font face="Cambria">&nbsp;Name</font></span><span style="color: #000000" class="auto-style20"><font face="Cambria">*:</font></span></td>

				<td style="width: 172px" class="auto-style11">



		<font face="Cambria">



		<input name="txtMotherName" id="txtMotherName" type="text" class="text-box" value="<?php echo $MotherName;?>" readonly></font></td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		<span class="auto-style21"><font face="Cambria">&nbsp;</font></span><font face="Cambria">Mobile No</font></td>

				<td style="width: 158px" class="auto-style11">



		<font face="Cambria">



		<input name="txtMotherMobileNo" id="txtFatherOfficialPhNo1" class="text-box" type="text" size="20" value="<?php echo $MotherMobile;?>"></font></td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 172px" class="auto-style11">



		&nbsp;</td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px" class="auto-style11">



		&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 212px" class="auto-style11">

		<font face="Cambria">Email id:</font></td>

				<td style="width: 172px" class="auto-style11">



		<font face="Cambria">



		<input name="txtMotherEmailId" id="txtMotherEmailId" class="text-box" type="text" size="20" value="<?php echo $MotherEmail;?>"></font></td>

				<td style="width: 157px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px" class="auto-style11">



		&nbsp;</td>

			</tr>

			<tr>

				<td style="width: 212px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 172px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



		&nbsp;</td>

				<td style="width: 157px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 28px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 217px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">

		&nbsp;</td>

				<td style="width: 158px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style11">



		&nbsp;</td>

			</tr>

			

						</table>

		</td>

</tr>	

			

		

			<tr>

			

			

		

		<td style="height: 46px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style23">

		&nbsp;</td>

</tr>	

<tr>			

		

		<td style="height: 10; border-top-width:1px; background-color:#A9D0F5" class="style28">

		<font face="Cambria">

		<strong>13. Emergency Contact Details:</strong></font></td>

			</tr>

		

		<tr>

		<td style="height: 29px; border-bottom-style:solid; border-bottom-width:1px" class="auto-style23">

		<table style="width: 100%" class="style14">

			<tr>

				<td style="width: 221px" class="auto-style11">

		<span class="auto-style25"><font face="Cambria">Contact No*:</font></span></td>

				<td style="width: 221px" class="auto-style11">

		<font face="Cambria">

		<input name="txtEmergencyNo" id="txtEmergencyNo"  class="text-box" onKeyPress="return isNumberKey(event)"  pattern="[0-9]{10}" type="text" value="<?php echo $smobile;?>"></font></td>

				<td style="width: 221px" class="auto-style11">

		<span class="auto-style21"><font face="Cambria">Mobile No*</font></span><span style="color: #000000" class="auto-style20"><font face="Cambria">:</font></span></td>

				<td style="width: 221px" class="auto-style11">

		<font face="Cambria">

		<input name="txtMobile" id="txtMobile" type="text" class="text-box" onKeyPress="return isNumberKey(event)"  pattern="[0-9]{10}" style="width: 143px" value="<?php echo $smobile;?>"></font></td>

				<td style="width: 221px" class="auto-style26">

		<span class="auto-style25"><font face="Cambria">E-mail Id*</font></span><span style="color: #000000" class="auto-style33"><font face="Cambria">:</font></span></td>

				<td style="width: 222px" class="auto-style26">

		<font face="Cambria">

		<input name="txtemail" id="txtemail" type="text" class="text-box" value="<?php echo $email;?>"></font></td>

			</tr>

			<tr>

				<td style="width: 221px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 221px" class="auto-style11">



				&nbsp;</td>

				<td style="width: 221px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 221px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 221px" class="auto-style11">

				&nbsp;</td>

				<td style="width: 222px" class="auto-style11">

				&nbsp;</td>

			</tr>

			

		</table>

		</td>

		<tr>

		<td style="border-bottom-width: 1px">

		&nbsp;</td>

		

		

		

		

		</tr>

		

		

		

			<tr id="trSubjectMarksTitle" style="display: none;">

		<td style="border-bottom-width: 1px" bgcolor="#A9D0F5">

		<b><font face="Cambria">14</font></b><font face="Cambria"><b>. Marks / Grades Obtained for Class X : </b>

		</font></td>

</tr>







		<tr>







		<td style="border-bottom-width: 1px">

		&nbsp;</td>





</tr>

		<tr id="trSubjectMarks" style="display: none;">

		<td style="border-top-style: solid; border-top-width: 1px; width: 100%;">

		<table border="0" width="100%" style="border-collapse: collapse">

			<tr>

				<td align="center" width="61" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: none; border-top-width: medium; border-bottom-style: solid; border-bottom-width: 1px"><b><font face="Cambria">Sr No</font></b></td>

				<td align="center" width="362" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: none; border-top-width: medium; border-bottom-style: solid; border-bottom-width: 1px"><b><font face="Cambria">Subject</font></b></td>

				<td align="center" width="295" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: none; border-top-width: medium; border-bottom-style: solid; border-bottom-width: 1px">

				<b><font face="Cambria">&nbsp;Marks</font></b></td>

			</tr>
				<?php
			//echo "select  `marks_obt`,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 2') and `master_subject`= 'English'";
				$rsPT3= mysqli_query($Con, "select  `marks_obt`,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 1') and `master_subject`= 'English'  ");
	  				while($row3 = mysqli_fetch_array($rsPT3))
	  				{
	  						$FA1_MarksObtained_eng2=$row3[0];
	  					
	  					
                             
	  				}	

			
			?>
		

			<tr>

				<td width="61" align="center" style="border-style:solid; border-width:1px; height: 27px"><b>

				<font face="Cambria">1</font></b></td>

				<td width="362" style="border-style:solid; border-width:1px; height: 27px"><font face="Cambria"><b>English</b></font></td>

				<td width="295" align="center" style="border-style:solid; border-width:1px; height: 27px">

				<!--<input type="text" name="txtEnglishMarksPercent" id="txtEnglishMarksPercent"  value="<?php //echo $FA1_MarksObtained_eng2;?>" size="20" class="text-box"></td>-->
				
				<input type="text" name="txtEnglishMarksPercent" id="txtEnglishMarksPercent"  value="" size="20" class="text-box"></td>

			</tr>
			<?php
			//echo "select  `marks_obt`,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 2') and `master_subject`= 'Maths'";
			$rsPT3= mysqli_query($Con, "select  `marks_obt`,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 1') and `master_subject`= 'Maths'  ");
	  				while($row3 = mysqli_fetch_array($rsPT3))
	  				{
	  						$FA1_MarksObtained_math2=$row3[0];
	  					
	  					
                             
	  				}	
?>

			<tr>

				<td width="61" align="center" style="border-style: solid; border-width: 1px"><b><font face="Cambria">2</font></b></td>

				<td width="362" style="border-style: solid; border-width: 1px"><font face="Cambria"><b>Maths</b></font></td>

				<td width="295" align="center" style="border-style: solid; border-width: 1px">

				<!--<input type="text" name="txtMathsMarksPercent" id="txtMathsMarksPercent" value="<?php //echo $FA1_MarksObtained_math2;?>"  size="20" class="text-box">-->
				
				<input type="text" name="txtMathsMarksPercent" id="txtMathsMarksPercent" value=""  size="20" class="text-box">
				
				</td>

			</tr>
			<?php
			//echo "select  sum(`marks_obt`) as `marks_obt` ,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 2') and `master_subject`= 'Science'  ";
				$rsPT3= mysqli_query($Con, "select   `marks_obt` ,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 1') and `master_subject`= 'Science'  ");
	  				while($row3 = mysqli_fetch_array($rsPT3))
	  				{
	  						$FA1_MarksObtained_sci2=$row3[0];
	  					
	  					
                             
	  				}
?>

			<tr>

				<td width="61" align="center" style="border-style: solid; border-width: 1px"><b><font face="Cambria">3</font></b></td>

				<td width="362" style="border-style: solid; border-width: 1px"><font face="Cambria"><b>General Science</b></font></td>

				<td width="295" align="center" style="border-style: solid; border-width: 1px">

				<!--<input type="text" name="txtScienceMarksPercent" id="txtScienceMarksPercent" value="<?php //echo $FA1_MarksObtained_sci2;?>"  size="20" class="text-box">-->
				
				<input type="text" name="txtScienceMarksPercent" id="txtScienceMarksPercent" value=""  size="20" class="text-box">
				
				</td>

			</tr>
			<?php
			//echo "select  sum(`marks_obt`) as `marks_obt` ,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 2') and `master_subject`= 'Social Studies'";
				$rsPT3= mysqli_query($Con, "select   `marks_obt` ,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 1') and `subject`= 'Social Studies'  ");
	  				while($row3 = mysqli_fetch_array($rsPT3))
	  				{
	  						$FA1_MarksObtained_ss2=$row3[0];
	  					
	  					
                             
	  				}

?>
			<tr>

				<td width="61" align="center" style="border-style: solid; border-width: 1px"><b><font face="Cambria">4</font></b></td>

				<td width="362" style="border-style: solid; border-width: 1px"><font face="Cambria"><b>Social Science</b></font></td>

				<td width="295" align="center" style="border-style: solid; border-width: 1px">

				<!--<input type="text" name="txtSocialScienceMarksPercent" id="txtSocialScienceMarksPercent" value="<?php //echo $FA1_MarksObtained_ss2;?>"  size="20" class="text-box">-->
				
					<input type="text" name="txtSocialScienceMarksPercent" id="txtSocialScienceMarksPercent" value=""  size="20" class="text-box">
					
				</td>



			</tr>
			<?php
			
			//echo "select  sum(`marks_obt`) as `marks_obt` ,`max_marks` from `exam_mark_entry` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 2') and `master_subject`IN( 'Sanskrit', 'Hindi', 'French','Manipuri','German','Additional Hindi')";
			$rsPT3= mysqli_query($Con, "select  sum(`marks_obt`) as `marks_obt` ,`max_marks` from `exam_mark_entry_2020` as `a` where   `sadmission`='$sadmission'  and `exam_type` in ('Preboard 1') and `master_subject`IN( 'Sanskrit', 'Hindi', 'French','Manipuri','German','Additional Hindi')  ");
	  				while($row3 = mysqli_fetch_array($rsPT3))
	  				{
	  						$FA1_MarksObtained_OP2=$row3[0];
	  					
	  					
                             
	  				}

			?>

			<tr>

				<td width="61" align="center" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-style: none; border-bottom-width: medium"><b><font face="Cambria">5</font></b></td>

				<td width="362" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-style: none; border-bottom-width: medium"><font face="Cambria"><b>Hindi / Sanskrit / 

				French / Other Language</b></font></td>

				<td width="295" align="center" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-style: none; border-bottom-width: medium">

				<!--<input type="text" name="txtLanguageMarksPercent" id="txtLanguageMarksPercent" value="<?php //echo $FA1_MarksObtained_OP2;?>"  size="20" class="text-box">-->
				
					<input type="text" name="txtLanguageMarksPercent" id="txtLanguageMarksPercent" value=""  size="20" class="text-box">
					
					
				</td>

			</tr>

		</table>

		</td>





</tr>







		<tr>

		<td style="border-top-style: solid; border-top-width: 1px; width: 100%;">

		&nbsp;</td>





</tr>



<tr>

		<td style="border-bottom-width: 1px">

		<font face="Cambria">Photograph of Applicant* :<input type="file" name="F2" size="20" accept="image/gif, image/jpeg, image/png, image/tiff, image/bmp,image/tif,image/gif"></font></td></tr>

		<tr>

		<td style="border-bottom-width: 1px">

		&nbsp;</td></tr>

		<tr>

		<td style="border-bottom-width: 1px">

		<font face="Cambria">OBC/SC/ST Certificate:<input type="file" name="F3" size="20" accept="image/gif, image/jpeg, image/png, image/tiff, image/bmp,image/tif,image/gif"></font></td></tr>

		<tr>

		<td style="border-bottom-width: 1px">

		&nbsp;</td></tr>

		

		<tr>

		<td style="border-bottom-width: 1px">

		<font face="Cambria">Self Certified Mark Sheet of Class X as downloaded 

		from CBSE Website : <input type="file" name="F1" size="20" accept="image/gif, image/jpeg, image/png, image/tiff, image/bmp,image/tif,image/gif"></font></td></tr>

		

		</table>

		</td></tr>

		

		<tr>

		<td style="border-top-style: solid; border-top-width: 1px; width: 100%;">

		&nbsp;</td></tr>

		<tr>

		<td style="border-top-style: solid; border-top-width: 1px; width: 100%;">

		<p><font face="Cambria"><b>Place</b></font> :

		<input type="text" name="T1" size="20"><p><b><font face="Cambria">Date :<?php echo $currentdate;?>

		</font></b>

		<p>&nbsp;</td></tr>

		<tr>

		<td height="30">

		<p align="center">		<font face="Cambria">
		    
		<?php if($MasterClass != '10') {
		    ?>
		<input name="BtnSubmit" type="button" value="I Agree &amp; Submit" onclick="Validate1();" style="font-weight: 700" class="text-box">
        <?php } else {?>
        <input name="BtnSubmit" type="button" value="I Agree &amp; Submit" onclick="Validate2();" style="font-weight: 700" class="text-box">
        <?php } ?>
        
		</font>

		</td></tr>

<tr>

		<td style="height: 29px" class="style7">



		&nbsp;</td>	</tr>



<tr>

		<td style="height: 29px" class="style7">		&nbsp;</td>



	</tr>	</form></table><!--

<div class="footer" align="center">



    <div class="footer_contents" align="center">



		<font color="#FFFFFF" face="Cambria">Powered by Eduworld Technologies LLP</font></div>



</div>



--></body>

</html>