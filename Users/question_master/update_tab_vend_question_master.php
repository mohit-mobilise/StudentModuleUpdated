

<form name="frmvendor_ques_update" id="frmvendor_ques_update" method="post">
    <input type="hidden" id="user_id1" name="user_id1" value="<?php echo $loggedin_user;?>" >
     <input type="hidden" name="app_id1" id="app_id1" value="<?php echo $app_Id;?>">
    

<div class="row">
    <input type="hidden" id="class_name" name="class_name" value="<?php echo $sclass;?>" >  
    <div class="col-sm-6">
        	<div class="form-group">
                <label class="control-label">Class Teacher</label>
                <select name="teacher_name" id="teacher_name" class="form-control">
                    <option value="">Please Select One </option>
                   
                    <?php
            

                $chr = substr($loggedin_user,0,3);
              
                if($chr=='MVN'){
                    
                 $rsStudentDetail=mysqli_query($Con, "SELECT distinct `EmpId`, `Name` FROM  `employee_master`  ");   
                }
                else
                {
                $rsStudentDetail=mysqli_query($Con, "SELECT distinct `EmpID`,`EmpName` FROM `feedback_form_mapping` WHERE `Class`='$sclass' ");
                    
                }
                  
                   while($rowS=mysqli_fetch_row($rsStudentDetail))
                   {
                    	$EmpID=$rowS[0];
                    	$ClassTeacher=$rowS[1];
                    
                    
                ?>    	

                    <option value="<?php echo $EmpID;?>"><?php echo $ClassTeacher;?></option>
                <?php
                   }
                   ?>
                </select>
				

				
			</div>

    </div>
     <div class="col-sm-6">
         	<div class="form-group">
                <label class="control-label">Subject Name</label>
                <select name="subject_name" id="subject_name" class="form-control">
					</select>

				
			</div>

        
    </div>
    
</div>    
    
    

<table class="table table-bordered" id="show_hide_table">
                                        
    <thead>
     <tr> 
      <th width="60%">Question</th>
      <th width="40%"></th>
      </tr>
    </thead>
    <tbody id="tbody_ques">
        
     
    </tbody>
     
</table>
<?php 

// $sql=mysqli_query($Con, "SELECT `vendor_id`, `EmpId`, `Emp_Subject`, `app_id`  FROM `vendor_ques_response` WHERE `vendor_id`='$EmpID' and  `app_id`='$app_Id' and `EmpId`='$loggedin_user'");
//if(mysqli_num_rows($sql)==0){
?>
<div class="row">
	<div class="col-md-12" align="center">
        <input type="button" name="btnUpdateQuestion" id="btnUpdateQuestion" class="btn btn-primary" value="Update">
	</div>  
</div>
<?php 
//}
?>
</form>