<?php 
require '../connection.php';
require '../AppConf.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");
if($StudentClass == "")
	{
		$StudentClass = $_REQUEST["cboClass"] ?? '';
	}
	
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
	$ssqlClass="SELECT distinct `class` FROM `class_master`";
	$rsClass= mysqli_query($Con, $ssqlClass);
	
if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"]=="yes")
{	

    $ssql="select  `subject`, `homework`,date_format(`homeworkdate`,'%d-%m-%Y') as `homeworkdate`,`homeworkimage` from  `homework_master` where `status`='Active'";

    
    
    $Cdate=date("Y-m-d");
  
	if (isset($_REQUEST["date_from"]) && $_REQUEST["date_from"] != "")
	{
	   // if($Cdate>=$_REQUEST["date_from"] )
	   // {
	        
			$date_from=$_REQUEST["date_from"] ?? '';
			$date_to=$_REQUEST["date_to"] ?? '';
			
		
			$ssql = $ssql . " and `homeworkdate`>='$date_from' and `homeworkdate` <='$date_to' and `sclass`='$StudentClass' order by `datetime` desc ";
				//echo $ssql;
				//exit();
				$reslt = mysqli_query($Con, $ssql);
				
             
           


		
	   // }		
	}
}


 $ssql1="select  `subject`, `homework`,date_format(`homeworkdate`,'%d-%m-%Y') as `homeworkdate`,`homeworkimage` from  `homework_master` where `sclass`='$StudentClass'AND `status`='Active' limit 10";
	$reslt1 = mysqli_query($Con, $ssql1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> ||Home Work</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
  
 <link rel="stylesheet" type="text/css" href="assets/css/dps-users-style.css">
 <link rel="stylesheet" type="text/css" href="assets/css/open-sans.css">
 
</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme ">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:50px;">
          <div class="container-fluid page-border"> 
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4><i class="fas fa-file-alt"></i> Home Work</h4>
                </div>
               <div class="col-12 col-pn">
                  <!-- search panal -->
                  <div class="flex-fill add-mrf-card">
                     <div class="card-body card-padding-bottom p10">
                    <form name="frmStudentMaster" id="frmStudentMaster" method="post" action="Homework.php"> 
                         
                         <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
	
                        <div class="row">
                           <div class="col-xl-3 col-lg-3 col-md-3">
                              <div class="form-group">
                                 <label>Date From</label>
                                  <input type="date"  name="date_from" class="form-control">
                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-3">
                              <div class="form-group">
                                 <label>Date To</label>
                                 <input type="date" name="date_to" class="form-control">
                              </div>
                           </div>
                           
                           <div class="col-xl-3 col-lg-3 col-md-3 mt-1">
                              <div class="form-group mt-4">
                                 <button class="btn btn-primary btn-sm" type="submit" onclick ="Javascript:Validate2();"> Search</button>
                                 <button type="button" class="btn btn-primary btn-sm"> Reset</button>
                              </div>
                           </div>
                        </div>
                    </form>    
                     </div>
                  </div>
               </div>
            </div>
            <!--end first row -->
            <div class="row m-t10">
                <div class="col-md-4 col-pn mb-3">
                    <div class="form-inline">
                    <label for="itemperpage">Items Per Page &nbsp;</label>
                    <select name="itemperpage" id="itemperpage" class="form-control ml-10">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    </select>
                    </div>
                    </div>
                <div class="col-12 col-pn">
                    <div class="table-responsive">
                        <table class="table table-row-fixed">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>
                                        Srno</th>

                                    <th>
										 Uploaded Date</th>
								

                                     <th>
                                         Subject</th>

                                    <th>
                                         Homework</th> 
                                         
                                     <th>
                                         Classwork</th>       
										 									
									 <th>
										Homework Attached File
									</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                	<?php
                            if (isset($_REQUEST["date_from"]) && $_REQUEST["date_from"] != "")
	                       {
                               $srno = 0; // Initialize serial number counter
                            		while($rowa = mysqli_fetch_assoc($reslt))
                            	   {    
                                        $subject=$rowa['subject'];                       	   		
                            	   		$homework=$rowa['homework'];
                            	   		$homeworkdate=$rowa['homeworkdate'];
                            	   	
                            	   		$homeworkimage=$rowa['homeworkimage'];
                            	   		$srno=$srno+1;
                            	   		
                            	
                            	?>
                                <tr>
                                    
                                    	<td>
										 <?php echo $srno; ?></td>		 
                                    	
									<td>
										 <?php echo $homeworkdate; ?></td>

                                    
                                    <td>
                                         <?php echo $subject; ?></td>
                                         
                                         
                                    <td>
                                         <?php echo $homework; ?></td>
                                             
								   <?php
								   
								   //echo "SELECT `classwork` FROM `classwork_master` WHERE `classworkdate`>='$date_from' and `classworkdate` <='$date_to' and `sclass`='$StudentClass'";
								   $classwork = ''; // Initialize classwork variable
								   $sql_r=mysqli_query($Con, "SELECT distinct `classwork` FROM `classwork_master` WHERE `classworkdate`>='$date_from' and `classworkdate` <='$date_to' and `sclass`='$StudentClass' and `subject`='$subject' ");		
                            	   if ($sql_r) {
                            	       while($row=mysqli_fetch_row($sql_r))
                            	       {
                            	           $classwork=$row[0];
                            	       }
                            	   }    
                            	   ?>								
								   <td>
								       <?php echo htmlspecialchars($classwork);?> 
								   </td>
								  
								    <td class="text-center">
                                        <?php
                                        if($homeworkimage!='')
                                        {
                                        ?>    
										  	<a href="<?php echo $homeworkimage; ?>" target="_blank" ><button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button></a>
                                        <?php
                                        }
                                        ?>    

                                          </td>
                                      
										   
										
								
                                </tr>
                                	<?php
								}
                             
	                        }
	                        else
	                        {
								$srno = 0; // Initialize serial number counter
									while($rowa = mysqli_fetch_assoc($reslt1))
                            	   {    
                                        $subject=$rowa['subject'];                       	   		
                            	   		$homework=$rowa['homework'];
                            	   		$homeworkdate=$rowa['homeworkdate'];
                            	   	
                            	   		$homeworkimage=$rowa['homeworkimage'];
                            	   		$srno=$srno+1;
                            	   		
                            	   		$newDate = date("Y-m-d", strtotime($homeworkdate));
                            	   	
                            	   	
                            	  
                                    
                            	?>
                                <tr>
                                    
                                    	<td>
										 <?php echo $srno; ?></td>		 
                                    	
									<td>
										 <?php echo $homeworkdate; ?></td>

                                    
                                    <td>
                                         <?php echo $subject; ?></td>
                                         
                                         
                                    <td>
                                         <?php echo $homework; ?></td>
                                             
									  <?php
								  // echo "SELECT distinct `classwork` FROM `classwork_master` WHERE `classworkdate`>='$homeworkdate' and `classworkdate` <='$homeworkdate' and `sclass`='$StudentClass' and `subject`='$subject'";
								  $classwork = ''; // Initialize classwork variable
								  $sql_r=mysqli_query($Con, "SELECT distinct `classwork` FROM `classwork_master` WHERE `classworkdate`>='$newDate' and `classworkdate` <='$newDate' and `sclass`='$StudentClass' and `subject`='$subject' ");		
                            	   if ($sql_r) {
                            	       while($row=mysqli_fetch_row($sql_r))
                            	       {
                            	           $classwork=$row[0];
                            	       }
                            	   }    
                            	   ?>								
								   <td>
								       
								       <?php echo htmlspecialchars($classwork);?> 
								   </td>								
										 	     
								    <td class="text-center">
                                        <?php
                                            if($homeworkimage!='')
                                            {
                                        ?>    
										  	<a href="<?php echo $homeworkimage; ?>" target="_blank" ><button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button></a>
                                        <?php
                                        }
                                        ?>    

                                          </td>
                                      
										   
										
								
                                </tr>
                               
                            <?php
	                        }
	                        }
	                        ?>
                            </tbody>
                        </table>
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