<?php 
require '../connection.php';
require '../AppConf.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
?>
<?php
if($StudentId==''){
    echo "<br><br><center><b>Session has been expired<br>Click <a href='index.php'>here</a> to re-login!</b></center>";
	exit();
}
?>
<style>
.mov{
    position: relative !important;
    top: 0px !important;
    margin-right: -50px;
    padding: 6px 15px !important;
    border-radius: 3px !important;
    border: 1px solid #e0dcdc !important;
}
.mov>.fas
{
     
    font-size: 22px;
    color:#6f6f6f;
}
.dropdown-menu
{
    left:-87px !important;
}

@media (min-width: 768px)
{
.navbar-expand-md {
    justify-content: space-between !important;
}
}
</style>
    <link rel="stylesheet" href="../../assets/loader/loader.css" />


<nav class="navbar navbar-expand-md navbar-light shadow fixed-top" style="background-color:white;">
    <a href="#" class="navbar-brand"><img src="../assets/image/logo_new.svg" width="250"  height="50px" alt="logo"></a>
            <div class="nav-item dropdown">
               <?php
                $rsphoto=mysqli_query($Con, "select `ProfilePhoto`, `sname` from `student_master` where `sadmission`='$StudentId' ");
               $row=mysqli_fetch_row($rsphoto);
               $photo=$row[0];
               $Name=$row[1];
              
               ?>
<!--if($photo!='') {echo '../../Admin/StudentManagement/StudentPhotos/'.$photo;} else { echo '../../Admin/StudentManagement/StudentPhotos/profile.png' ;}-->
                <a href="#" class="nav-link dropdown-toggle mydrop" data-toggle="dropdown" title="<?php echo $Name;?>"><img src="<?php echo 'tabs/student/profile.png' ?>" width="30" class="m-r-10"> </a>
                <div class="dropdown-menu dropdown-menu11">
                    
                   <!--<a data-toggle="modal" href="#change_password" class="dropdown-item"><i class="fas fa-key edit_exam_btn_data" data-edit_srno="<?php echo $srno ;?>" value="<?php echo $srno ;?>"></i>-->
                   
                    <!--<a href="Javascript:fnlChangePw();" class="dropdown-item"><i class="fas fa-key"></i> -->
                   <!-- Change Password</a>-->
                    
                    
                    <a href="http://dpscam.mobilisepro.com/ssoGates/saml3/index.php?slo" class="dropdown-item"><i class="fas fa-power-off"></i> Log Out </a>
                    
                </div>
            </div>

</nav>

<div id="openMenu" class="openmenu">

    <div class="container-fluid">
        <div class="row">
            <div class="col-6"><h4>&nbsp;</h4></div>
            <div class="col-6"><div class="close1">X</div></div>
        </div>
    </div>
    <section class="container">
        <div class="row">
             <?php
                    $ssqlAppName="SELECT distinct `um`.`ApplicationName`,`um`.`BaseURL` ,`um`.`imageurl`,`mm`.`icon_name` FROM `user_menu_master` AS `um` LEFT JOIN `menu_master` as `mm`ON (`um`.`ApplicationName`=`mm`.`ApplicationName`) where `um`.`EmpId`='$EmployeeId' group by `um`.`ApplicationName`  ";
                 
                        $rsModuleName= mysqli_query($Con, $ssqlAppName);
                            while($rowA = mysqli_fetch_row($rsModuleName))
                                {
                                    $AppName=$rowA[0];
                                    $AppNameBaseURL=$rowA[1];
                                     $icon_name=$rowA[3];
                                ?>
                               
                                <div class="col-md-2 col-4 app-col text-center">
                                     <a href="Javascript:ValidateHeader3('<?php echo $AppName;?>');">
                        	        <section class="bg-change shadow m-t-16 main-menu">
                                        <img alt="" height="35" class="make-link img-opcity" src="App_icon/<?php echo $icon_name;?>">
                                        <h6 class="text-white"><?php echo $AppName;?></h6>
                        	        </section>
                        	        </a>
                                </div>
                                
                            <?php
                            }
                            ?>
            
            
            
        </div>
        
    </section>
</div>
<!--<div class="position-relative loading" style="height: 100vh">-->
<!--    <div class="position-absolute top-50 start-50 translate-middle">-->
<!--        <div class="dual-circle-loader">-->
<!--            <div class="inner-circle"></div>-->
<!--            <div class="outer-circle"></div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="modal fade " id="change_password"  role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h5 class="modal-title">Change Password </h5>
                        </div>
                        <!-- BEGIN FORM-->
                        <form action="" id="" class="horizontal-form" name='' method="post" enctype="multipart/form-data">
                           
                        <div class="modal-body">

                            <div class="row">
                            <div class="col-md-12">
           
                                
                           <p>
                              
                           <input class="form-control" type="hidden" id="edit_change_srno" name="edit_change_srno">
                           </p>
                              
                           <p>
                          <h5>Old Password </h5>
        
                            <input type="password" name="old_password" id="old_password" class="form-control">
                          </p>
                           <p>
                          <h5>New Password </h5>
        
                            <input type="password" name="new_password" id="new_password" class="form-control">
                          </p>
                           <p>
                          <h5>Confirm Password </h5>
        
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                          </p>
                          

                          
                       </div>
                              </div>
                        </div><!--end of modal-body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"  name="submit_change_password" id="submit_change_password">Submit</button>
                            <button type="button" class="btn btn-dark" data-dismiss="modal" >Close</button>
                        </div>
                    </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            
<script>

            $('.loading').hide();



    function openmenu()
    {
        $('#openMenu').toggle('slow',function(){
            $(this).css({marginTop:'0'});
        });
        
    }
    $(document).ready(function(){
        $('.close1').click(function(){
            $("#openMenu").hide('slow');
        });
    });
    
</script>

<script>


$(document).ready(function(){
$('#submit_change_password').click(function(){
   var old_password=$('#old_password').val();
   var new_password=$('#new_password').val();
   var confirm_password=$('#confirm_password').val();
   
   if(old_password=='')
   {
       toastr.warning("Old Password is mandatory", "Validation Error");
   }
   else if(new_password=='')
   {
       toastr.warning("New Password is mandatory", "Validation Error");
   }
   else if(confirm_password=='')
   {
       toastr.warning("Confirm Password is mandatory", "Validation Error");
   }
   
   else
   {
   $.ajax({
               type:'POST',
               url:'submit_forget_password_users.php',
               data:{
                        
                        old_password:old_password,
                        new_password:new_password,
                        confirm_password:confirm_password
                   
               },
               success:function(response){
                  toastr.info(response, "Information");
                  setTimeout(function() { location.reload(true); }, 1500);
       

               }
        });
   }    

 });
 }); 
 
</script>  

<script>
    $(document).ready(function(){
        $(".mydrop").click(function(){
            $('.dropdown-menu11').toggle();
        });
        $("body").click(function(){
            $('.dropdown-menu11').hide();
        });
    });
</script>
<!-- Toastr CSS and JS - Common includes for all pages using this header -->
<!-- Note: Individual pages should include toastr.min.css and toastr.min.js before this header -->
<!-- Toastr Custom CSS (fixes display issues) - load after toastr.min.css -->
<link rel="stylesheet" type="text/css" href="../assets/css/toastr-custom.css" id="toastr-custom-css">
<!-- Toastr Configuration JS - load after toastr.min.js -->
<script src="../assets/js/toastr-config.js" id="toastr-config-js"></script>

