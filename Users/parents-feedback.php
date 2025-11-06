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

if (empty($AdmissionId)) {
	echo ("<br><br><center><b>Due to security reason or network issues your session has expired!<br>Please login from your respected ERP");
	exit();
}

$getsql = mysqli_query($Con, "select * from student_master where sadmission='$AdmissionId'");
$getstudentdata = mysqli_fetch_array($getsql);
extract($getstudentdata);

$datasql = mysqli_query($Con, "select * from hcp_parentfeedback where sadmission='$AdmissionId'");
if (mysqli_num_rows($datasql) > 0) {
	$alreadyfilled = 'Yes';
	$filled_data = mysqli_fetch_array($datasql);
    extract($filled_data);
}


	$class_array = ['I', 'II'];

	$ch_class = explode("-", $sclass);
	$stu_ch_class = $ch_class[0];

	if (!in_array($stu_ch_class, $class_array)) {
		echo ("<br><br><center><b>You are trying with wrong URL!");
		exit();
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript" src="../mysweetalert/sweetalert.js"></script>    
    <style>
        /*.form-control{*/
        /*    width:50%;*/
        /*}*/
        
    .sticky-button {
    position: fixed;
    top: 10px; 
    right: 10px; 
    padding: 6px 19px;
    background-color: #20b2aa; /*lightseagreen */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    }
    
    .sticky-button:hover {
    background-color: darkcyan;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
    }
    
    textarea{
        resize:none;
    }
    
    @media print{
    .sticky-button{
        display:none;
    }
    
    .form-control{
          border: none;
          outline: none; 
          background: transparent; 
    }
    }
    </style>    
</head>

<body>
    <button class="sticky-button" onclick='savedata()'>save</button>
    <form name='submitparentfeedback' id='submitparentfeedback'>
        <input type='hidden' name='already_filled' id='already_filled' value='<?php echo $alreadyfilled; ?>'>
		<input type='hidden' name='sadmission' id='sadmission' value='<?php echo $AdmissionId; ?>'>
		<input type='hidden' name='parentfeedback' id='parentfeedback' value='Yes'>
        <div class="containerr">
            <div class="logo text-center">
                <img src="hcp-images/log.JPG" alt="">
            </div>

            <table class="progress-indicators mt-3">
                <tr class="green">
                    <th>S.No</th>
                    <th class="col-5">QUESTIONNAIRE</th>
                    <th>Experience 1</th>
                </tr>
                
                <tr>
                    <th class="col1">1</th>
                    <td >Do you discuss your child’s emotional and academic needs regularly?
                    </td>
                    <td><select name='pf1' id='emotionalacademic' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf1=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf1=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf1=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>2</th>
                    <td >Do you cooperate in your child’s academic and
                        extracurricular achievements?</td>
                    <td><select name='pf2' id='academicextracurricular' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf2=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf2=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf2=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>3</th>
                    <td >Do you always answer your child's inquisitive queries?</td>
                    <td><select name='pf3' id='inquiqueries' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf3=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf3=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf3=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>4</th>
                    <td >How often do you go for outings with your child?</td>
                   <td><select name='pf4' id='outings' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf4=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf4=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf4=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>5</th>
                    <td >How often do you have meals together with your child?</td>
                    <td><select name='pf5' id='mealstogether' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf5=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf5=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf5=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>6</th>
                    <td >Are you satisfied with the academic progress of your child ?</td>
                    <td><select name='pf6' id='academicprogress' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf6=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf6=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf6=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>7</th>
                    <td >How often do you spend quality ( face to face ) time with your child?</td>
                    <td><select name='pf7' id='spendquality' class='form-control'>
                        <option value=''>Select one</option>
                        <option value='Always' <?php if($pf7=='Always'){ ?> selected<?php } ?>>Always</option>
                        <option value='Sometimes' <?php if($pf7=='Sometimes'){ ?> selected<?php } ?>>Sometimes</option>
                        <option value='Rarely' <?php if($pf7=='Rarely'){ ?> selected<?php } ?>>Rarely</option>
                    </select></td>
                </tr>
                <tr>
                    <th>8</th>
                    <td >How do you show affection and positive reinforcement to your child?</td>
                    <td><textarea class='form-control' name='pf8' id='positiveaffect'  rows='4'><?php if(!empty($pf8)){ echo $pf8; } ?></textarea></td>
                </tr>
                <tr>
                    <th>9</th>
                    <td >Would you like to support the school in any activity with your talent?</td>
                    <td><textarea class='form-control' name='pf9' id='activitytalent'  rows='4'><?php if(!empty($pf9)){ echo $pf9; } ?></textarea></td>
                </tr>
                <tr>
                    <th>10</th>
                    <td >Any other suggestion you would like to make ?</td>
                    <td><textarea class='form-control' name='pf10' id='othersuggestion'  rows='4'><?php if(!empty($pf10)){ echo $pf10; } ?></textarea></td>
                </tr>
                <tr>
                    <th>11</th>
                    <td >Signature / Contact No.</td>
                    <td><textarea class='form-control' name='pf11' id='contact'  rows='4'><?php if(!empty($pf11)){ echo $pf11; } ?></textarea></td>
                </tr>
            </table>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script>  
    document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });
	
	   $(window).on('keydown',function(event)
            {
            if(event.keyCode==123) {
                // alert('Entered F12');
                return false;
            }
            else if(event.ctrlKey && event.shiftKey && event.keyCode==67) {
                return false;  //Prevent from ctrl+shift+c
            }
            else if(event.ctrlKey && event.shiftKey && event.keyCode==73) {
                return false;  //Prevent from ctrl+shift+i
            }
            else if(event.ctrlKey && event.keyCode==73) {
                return false;  //Prevent from ctrl+shift+i
            }
        });

    function savedata() {
			var formdata = new FormData($('form#submitparentfeedback')[0]);

			$.ajax({
				url: 'savestudentdata.php',
				type: 'POST',
				processData: false,
				contentType: false,
				data: formdata,
				dataType: 'JSON',
				success: function(res) {

					if (res.status == 'success') {
						$('#already_filled').val(res.alreadyfilled);
						Swal.fire({
							title: "Success...",
							text: res.info,
							icon: "success"
						});
					} else {
						Swal.fire({
							icon: "error",
							title: "Oops...",
							text: res.info,
							//   footer: '<a href="#">Why do I have this issue?</a>'
						});
					}

				}
			});
		}
</script>		
</body>

</html>