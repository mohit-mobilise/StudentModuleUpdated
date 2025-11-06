<?php 
session_start(); 
require '../connection.php';
require '../AppConf.php';
$StudentId=$_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];
$currentdate=date("Y-m-d");

$ssqlStudent = mysqli_query($Con, "SELECT `sadmission`,`sname`,`MasterClass` FROM `student_master` a  where  `sadmission`='$StudentId' order by `sname`");
	$rsStudentDetail = mysqli_fetch_array($ssqlStudent);
	
	if($rsStudentDetail[2]=='I' || $rsStudentDetail[2]=='II'){
        $cbotesttype='Experience1';
    }else{
        $cbotesttype='Term 1';
    }

if($StudentClass=="")
	{
		$StudentClass = $_REQUEST["cboClass"];
	}
	
if($StudentId == ""  )
    {
        echo "<br><br><center><b>Session Expired!<br>click <a href='index.php'>here</a> to login again!";
        exit();
    }
    
    if ($_REQUEST["SubmitType"] == "ReloadWithSubject") {
	$SelectedIndicator = $_REQUEST["cbocategory"];
	$Selectedsubcategory=$_REQUEST["cbosubcategory"];
	
    $selectexamcode=mysqli_query($Con, "select examtype from exam_type where exam_code='$cbotesttype' and status='Active'");
    $fetchrow=mysqli_fetch_array($selectexamcode);
    $exa_code=$fetchrow[0];
    }
    $query = mysqli_query($Con, "SELECT `financialyear`,`year` FROM `FYmaster` where Status='Active' ");
    $rowS=mysqli_fetch_row($query);
    $financialyear=$rowS[0];
    $CurrentFinancialYear=$rowS[1];
    
    $array=['At school my child needs support with','select the resources available to your child at home'];
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
  <!-- Toastr CSS -->
  <link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-toastr/toastr.min.css">
  <!-- Toastr Custom CSS (fixes display issues) -->
  <link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
  <link rel="stylesheet" type="text/css" href="new-style.css">

<script>
    function ReloadWithSubject() {
		if (document.getElementById("cbocategory").value == "") {
			toastr.warning("Category is mandatory!", "Validation Error");
			return;
		}
		
		document.getElementById("SubmitType").value = "ReloadWithSubject";
		document.getElementById("frmIndicatorEntryNur").submit();
	}
	
	function savedata(rows){
	 var formdata = new FormData($('form#indicatorform')[0]); 
	 var input_desc;
	 var input;
	 var inputype;
	 var inputname;
	    for(var i=1;i<=rows;i++){
	         input_desc=document.getElementById("Indicator"+i).value;
	         input=document.getElementById("cboIndicatorGrade"+i);
	         inputype=document.getElementById("cboIndicatorGrade"+i+i).value;
	         inputname="cboIndicatorGrade"+i;
	          
	        if(input.value.trim()==''){
	            toastr.warning(input_desc+' field is mandatory!', "Validation Error");
	            input.style.border='1px solid red';
	            return false;
	        }
	        
	        if(inputype=='checkbox'){
    	        if(!$('#cboIndicatorGrade'+i).is(':checked')){
    	            formdata.set(inputname, '');
    	        }
	        }
	    }

	$.ajax({
		url: 'submithcpdata.php',
		type: 'POST',
		processData: false,
		contentType: false,
		data: formdata,
		dataType: 'JSON',
		success: function(res) {
        if(res.status=='success'){
            toastr.success(res.msg, "Success");
            setTimeout(function() { window.location.href = 'hcp-feedback.php'; }, 1500);
        }else{
            toastr.error(res.msg, "Error");
        }

		}
	});
		
	}
	
$(document).ready(function() {
    $('.inputdata').on('input', function() {
        var data = $(this).val(); // Get the input value
        if (data.trim() !== '') {
            $(this).css('border', '1px solid black'); // Set border style
        } else {
            $(this).css('border', ''); // Reset border if input is empty
        }
    });
});

function fnlFillIndicator() {
		try {
			// Firefox, Opera 8.0+, Safari    
			xmlHttp = new XMLHttpRequest();
		} catch (e) { // Internet Explorer    
			try {
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {
					toastr.error("Your browser does not support AJAX!", "Error");
					return false;
				}
			}
		}
		xmlHttp.onreadystatechange = function() {
			if (xmlHttp.readyState == 4) {
				var rows = "";
				rows = new String(xmlHttp.responseText);
				removeAllOptions(document.frmIndicatorEntryNur.cbosubcategory);
				addOption(document.frmIndicatorEntryNur.cbosubcategory, "Select One", "Select One")
				arr_row = rows.split(',');
				var leng = arr_row.length;
				var row_count = 0
				while (row_count < leng) {
					var rowcount1 = row_count + 1;
					addOption(document.frmIndicatorEntryNur.cbosubcategory, arr_row[rowcount1], arr_row[row_count])
					row_count = row_count + 2;
				}
			}
		}
		
		var submiturl = "getsubject-feed.php?Class=" + document.getElementById("cboClass").value + "&TestType="+ escape(document.getElementById("cbotesttype").value) +"&category=" + escape(document.getElementById("cbocategory").value) + "";
		
		xmlHttp.open("GET", submiturl, true);
		xmlHttp.send(null);
	}	
	
	function removeAllOptions(selectbox) {
	var i;
	for (i = selectbox.options.length - 1; i >= 0; i--) {
		selectbox.remove(i);
	}
	}

	function addOption(selectbox, text, value) {
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		selectbox.options.add(optn);
	}
</script>   
</head>
<body>
<?php include 'Header/header_new.php';?>
<div class="page-wrapper chiller-theme ">
    <?php include 'new_sidenav.php';?>
<main class="page-content" style="margin-top:50px;">
          <div class="container-fluid page-border">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4 class="m-t5"><i class="fas fa-file-alt"></i> Report Card Entry</h4>
                </div>
              
            </div>
            <!--end first row -->
            <div class="row m-t10">
                
                <div class="col-12 col-pn">
                    <div class="">
                        <form name="frmIndicatorEntryNur" id="frmIndicatorEntryNur" method="post" action="" class="horizontal-form">
                            <input type="hidden" name="SubmitType" id="SubmitType" value="" >
                            <input type="hidden" name="cboClass" id="cboClass" value="<?php echo $StudentClass;?>" >
                            <input type="hidden" name="cbotesttype" id="cbotesttype" value="<?php echo $cbotesttype;?>" >
							<div class="form-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												Select Category</label>
											<?php
											if ($_REQUEST["SubmitType"] == "ReloadWithSubject") {
											?>
												<input type="text" name="Selectedcategory" id="Selectedcategory" value="<?php echo $SelectedIndicator; ?>" readonly="readonly" class="form-control" data-cls="<?php echo $SelectedIndicator; ?>">
											<?php
											} else {
											?>
												<select name="cbocategory" id="cbocategory"  class="form-control" onchange='fnlFillIndicator()'>
													<option selected="" value="">Select One</option>
													<?php
													    $getfsql=mysqli_query($Con, "select distinct `indicator_type` from hcp_class_indicator_mapping where indicator_type in('Parent teacher partnership card','Feedback') and sclass='$StudentClass'");
													    while($getmapping=mysqli_fetch_array($getfsql)){
													?>
												 		<option value="<?=$getmapping[0];?>" <?php if ($SelectedIndicator == $getmapping[0]) {echo "selected";} ?>><?=$getmapping[0];?></option>
												 	<?php } ?>	
												</select>
											<?php
											}
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												Select Sub Category</label>
											<?php
											if ($_REQUEST["SubmitType"] == "ReloadWithSubject") {
											?>
												<input type="text" class="form-control" name="Selectedsubcategory" id="Selectedsubcategory" value="<?php echo $Selectedsubcategory; ?>" readonly="readonly" data-exm="<?php echo $cbotesttype; ?>">
											<?php
											} else {
											?>
												<select name="cbosubcategory" class="form-control" id="cbosubcategory">
												    <option value=''>Select One</option>
												</select>
											<?php
											}
											?>
										</div>
									</div>
								</div>
								
								<div class="row">

									<div class="col-md-12 text-center">
										<div class="form-group">
											<input name="btnShow" type="button" value="submit" onclick="Javascript:ReloadWithSubject();" class="btn btn-primary" <?php if ($_REQUEST["SubmitType"] == "ReloadWithSubject") {?>style='display:none'<?php } ?>>
										</div>
									</div>
								</div>
							</div>
						</form>	
							<?php
							if ($_REQUEST["SubmitType"] == "ReloadWithSubject") {
							?>
							<form name="indicatorform" id="indicatorform" method="post" action="" class="horizontal-form">
							    <input type="hidden" name="txtsadmission" id="txtsadmission" value="<?php echo $StudentId; ?>">
							    <input type="hidden" name="txtsname" id="txtsname" value="<?php echo $rsStudentDetail[1]; ?>">
    							<input type="hidden" name="txtSelectedClass" id="txtSelectedClass" value="<?php echo $StudentClass; ?>">
    							<input type="hidden" name="txtSelectedIndicator" id="txtSelectedIndicator" value="<?php echo $SelectedIndicator; ?>">
    							<input type="hidden" name="txtSelectedTestType" id="txtSelectedTestType" value="<?php echo $cbotesttype; ?>">
    							<input type="hidden" name="txtSubIndicator" id="txtSubIndicator" value="<?php echo $Selectedsubcategory; ?>">
								<div class="table-responsive mt-3">
									<table class="table table-striped table-bordered table-hover">
									    <thead>
									        <tr>
									            <!--<th style='width:10px'>Sr No</th>-->
									            <th style='width:30%' class=''> indicators</th>
									            <th class=''> Input fields</th>
									        </tr>
									   </thead>     
									    <?php
									        $Cnt = 0;
										    $Snt = 0;
											$ssqlIndicatorDESC = mysqli_query($Con, "SELECT distinct indicator_desc,indicator_grade,`indicator_subcat` FROM `hcp_class_indicator_mapping` where exam_type='$cbotesttype' and sclass='$StudentClass' and indicator_type='$SelectedIndicator' and indicator_subcat='$Selectedsubcategory'");
											$numrows=mysqli_num_rows($ssqlIndicatorDESC);
											while ($rowIndIDESC = mysqli_fetch_array($ssqlIndicatorDESC)) {
												$Cnt = $Cnt + 1;
											
											$ssqlFilledIndicator = "SELECT distinct `grade`,`anyother` FROM `exam_indicator_entry` WHERE `exam_type`='$cbotesttype' and `sadmission`='$StudentId' and indicator_desc='$rowIndIDESC[0]' and indicatortype='$SelectedIndicator' and subindicator ='$Selectedsubcategory' and sclass='$StudentClass' and FinancialYear ='$CurrentFinancialYear'";
											$rsFilledValue = mysqli_query($Con, $ssqlFilledIndicator);
											$FilledGrade = "";
											$row2 = mysqli_fetch_row($rsFilledValue);
											$FilledGrade = $row2[0];
											$other_specify = $row2[1];
											?>
											<tr style="border: 1px solid #ddd;">
												<!--<th rowspan=""><?php echo $Cnt; ?>&nbsp;</th>-->
												<th colspan="" class=''><?php echo $rowIndIDESC['indicator_desc']; if($rowIndIDESC['indicator_desc']=='Other subject areas:specify:' || $rowIndIDESC['indicator_desc']=='Any other'){?><input type='text' name='otherspeify' class='form-control' id='otherspeify' value='<?php echo $other_specify;?>'><?php } ?><input type='hidden' value='<?php echo $rowIndIDESC['indicator_grade'];?>' id='cboIndicatorGrade<?php echo $Cnt; ?><?php echo $Cnt; ?>' name='cboIndicatorGrade<?php echo $Cnt; ?><?php echo $Cnt; ?>'></th>
											    <td colspan=''>
											    <input type='hidden' name='Indicator<?php echo $Cnt; ?>' id='Indicator<?php echo $Cnt; ?>' value='<?php echo $rowIndIDESC['indicator_desc'];?>'>
											    <?php 
											        if($rowIndIDESC['indicator_grade']=='select'){
											            if($rsStudentDetail[2]=='I' || $rsStudentDetail[2]=='II'){
											     ?>
											     <select name='cboIndicatorGrade<?php echo $Cnt; ?>' id='cboIndicatorGrade<?php echo $Cnt; ?>' class='form-control inputdata'>
											         <option value=''>Select One</option>
											         <option value='Always' <?php if($FilledGrade=='Always'){?>selected<?php } ?>>Always</option>
											         <option value='Sometimes' <?php if($FilledGrade=='Sometimes'){?>selected<?php } ?>>Sometimes</option>
											         <option value='Rarely' <?php if($FilledGrade=='Rarely'){?>selected<?php } ?>>Rarely</option>
											     </select>
                                                <?php											                
											            }else{
											     ?>
											     <select name='cboIndicatorGrade<?php echo $Cnt; ?>' id='cboIndicatorGrade<?php echo $Cnt; ?>' class='form-control inputdata'>
											         <option value=''>Select One</option>
											         <option value='Yes' <?php if($FilledGrade=='Yes'){?>selected<?php } ?>>Yes</option>
											         <option value='No' <?php if($FilledGrade=='No'){?>selected<?php } ?>>No</option>
											         <option value='Sometimes' <?php if($FilledGrade=='Sometimes'){?>selected<?php } ?>>Sometimes</option>
											         <option value='Not Sure' <?php if($FilledGrade=='Not Sure'){?>selected<?php } ?>>Not Sure</option>
											     </select>
											 <?php    
											    }
										        }else if($rowIndIDESC['indicator_desc']=='I will support my child at home by' || $rowIndIDESC['indicator_grade']=='Textarea'){
										     ?>                
										        <textarea name='cboIndicatorGrade<?php echo $Cnt; ?>' id='cboIndicatorGrade<?php echo $Cnt; ?>' class='form-control inputdata' style='height:100px'><?php echo $FilledGrade;?></textarea>
										    <?php          
										        }else{
											    ?>
											    <input type='<?php echo $rowIndIDESC['indicator_grade'];?>' name='cboIndicatorGrade<?php echo $Cnt; ?>' id='cboIndicatorGrade<?php echo $Cnt; ?>' class='form-control inputdata' value='<?php echo $rowIndIDESC['indicator_desc'];?>' <? if($FilledGrade==$rowIndIDESC['indicator_desc']){?>checked<?php } ?>>
												<?php } ?>
												</td>
											</tr>
									<?php
										}
										
										?>
										<input type='hidden' name='totalrow' id='totalrow' value='<?php echo $numrows;?>'>
									</table>
									
								</div>
								<div class="col-md-12 text-center mt-3">
										<div class="form-group">
											<input name="btnShow" type="button" value="submit" onclick="Javascript:savedata(<?php echo $numrows;?>);" class="btn btn-primary">
										</div>
									</div>
									</form>
                            <?php } ?>
						
                    </div>
                </div>
            </div>
          </div>
        </main>
<!--end page contents-->
</div>
</body>
</html>
<script>
    function Validate2()
    {
    	document.getElementById("frmStudentMaster").submit();
    }
    $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
    if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });
  $("#close-sidebar").click(function() {
    $(".page-wrapper").removeClass("toggled");
  });
  $("#show-sidebar").click(function() {
    $(".page-wrapper").addClass("toggled");
  });
   window.onload=function(){
      var x=screen.width;
      if(x>=576)
      {
         $(".page-wrapper").addClass("toggled");
      }
  }
</script>
<!-- Toastr JS -->
<script src="../../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>