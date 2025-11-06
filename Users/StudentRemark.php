<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
session_start(); 
$StudentId=$_SESSION['userid'];
$StudentClass = $_SESSION['StudentClass'];
$currentdate=date("Y-m-d");
$StudentRollNo = $_SESSION['StudentRollNo'];
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
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="new-style.css">
</head>
<script>
function Validate1()
{
    document.getElementById("frmStudentMaster").action="submit_remark.php";
    document.getElementById("frmStudentMaster").submit();
}
</script>    
<body>
<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme ">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:50px;">
          <div class="container-fluid page-border">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4 class="m-t5"><i class="fas fa-file-alt"></i> Remark </h4>
                </div>
               <div class="col">
                  <!-- search panal -->
                  <div class="card flex-fill add-mrf-card bg-g-light">
                     <div class="card-body card-padding-bottom p10">
                      
                     </div>
                  </div>
               </div>
            </div>
            <!--end first row -->
            <div class="row m-t10">
                 <form name="frmStudentMaster" id="frmStudentMaster" method="post" action="StudentRemark.php"> 
                   <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-row-fixed">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%">
                                         Srno</th>
                                    <th width="10%">
                                         Remark 1</th>
                                    <th width="10%">
                                        Remark 2</th>
                                         
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                $sqlds=mysqli_query($Con, "select  `remarks`, `remark2` from `exam_remark` where `sadmission`='$StudentId' and `exam_type`='SEM2'  and `type`='student'");
                                $rows=mysqli_fetch_row($sqlds);
                                $remarks1=$rows[0];
                                $remarks2=$rows[1];

                                ?> 
                                <tr>
                                    
                                    <td width="33">
                                       1</td>
                                         
                                    <td>
                                        <select name="remark1" id="remark1" class="form-control">
                                             <option value="">Please select One</option>
                                         <?php    
                                        $sqld=mysqli_query($Con, "select distinct `remark_id`,`Remark` from `exam_remark_mapping` where `sclass`='$StudentClass' and `type`='student'  ");
                                        while($row1=mysqli_fetch_row($sqld))
                                        {
                                            $remark_id=$row1[0];
                                            $remark=$row1[1];
                                        ?>    
                                            <option value="<?php echo $remark_id;?>" <?php if($remarks1==$remark_id){ echo 'selected';} ?>><?php echo $remark;?></option>
                                       <?php
                                       }
                                       ?>

                                       </select>     
 

                                    </td>

                                    <td>
                                        <select name="remark2" id="remark2" class="form-control">
                                            <option value="">Please select One</option>
                                            <?php    
                                            $sql2=mysqli_query($Con, "select distinct `remark_id`,`Remark` from `exam_remark_mapping` where `sclass`='$StudentClass' and `type`='student' ");
                                            while($row2=mysqli_fetch_row($sql2))
                                            {
                                                $remark_id2=$row2[0];
                                                $remark2=$row2[1];
                                            ?>    
                                                <option value="<?php echo $remark_id2;?>" <?php if($remarks2==$remark_id2){ echo 'selected';} ?>><?php echo $remark2;?></option>
                                           <?php
                                           }
                                           ?>
                                        </select>
                                    </td>   

                                         
                                  
                                </tr>
                                    
                              <tr>
                                <td colspan="3"> <input name="Submit" type="button" value="Submit" onclick="Javascript:Validate1();" class="btn btn-danger" ></td>
                              </tr>  
                                     
                                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>       
                    
                </form> 
               
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