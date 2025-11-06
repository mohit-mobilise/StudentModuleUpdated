

<div class="container-fluid">
	<div class="row m-t10">
		<div class="col-12">
			<form name="frmleave" id="frmleave" method="post"  action=""  >
                     <input type="hidden" name="isSubmit_leave" id="isSubmit_leave" value="yes">
			<section class="row">
				<div class="col-md-3">
			        <label>From</label>
                    <input type="date" name="txtDateFrom_leave" id="txtDateFrom_leave"  class="form-control">
                </div>
                 <div class="col-md-3">
                     <label>To</label>
                    <input type="date" name="txtDateTo_leave" id="txtDateTo_leave" class="form-control">
                </div>
                 
	              <div class="col-md-3">
	                  <label>Leave type </label>
			         <div class="form-group row">
	                    <div class="col-sm-8 p-rn">
	                    	<select name="leave_type" id="leave_type" class="form-control">
	                    		 <option value="">Select One</option>
	                    		
	                    		<?php
	                    		 
                            $rsleave=mysqli_query($Con, "select distinct `LeaveType` from `Student_Leave_Transaction`");
                        
                            while($row1 = mysqli_fetch_row($rsleave))

                                {

                                        $LeaveType=$row1[0];
                                        

                                  ?>
                                
                            
                                <option value="<?php echo $LeaveType; ?>"><?php echo $LeaveType; ?></option>
                            
                                <?php
                            
                                  }
                                  ?>
	                    	</select>

	                   
	                    </div>
	                    <div class="col-sm-3 p-ln">
	                       <input name="Submit1" type="submit" value="Submit" class="btn btn-primary" style="margin-left:10px">
	                    </div>
	                  </div>
	            </div>
	           
			</section>
			 </form>
		
			<?php 
			  $recno=1;
				if($_REQUEST["isSubmit_leave"]=="yes")
				{
				    
				     $tab = '"#tab3"';
                    echo "<script>$('.nav-tabs a[href=$tab]').tab('show');</script>"; 
                    
                   
				  $FromDate=$_REQUEST["txtDateFrom_leave"];
				  $ToDate=$_REQUEST["txtDateTo_leave"];
                
                  $leave_type = $_REQUEST["leave_type"];
                  
				 $ssqlleave="SELECT distinct  `sadmission`, `sclass`, `LeaveFrom`, `LeaveTo`, `LeaveType`, `remark`, `EntryDate`, `MedicalCertificate`, `sys_date_time`, `status`, `teacher_remark`, `emp_id`, `response_date` FROM `Student_Leave_Transaction` where  1=1 ";
                 
                  if( $leave_type!='')
                  {
                    $ssqlleave=$ssqlleave." and  `LeaveType`='$leave_type'";
                  }

                  if( ($FromDate!='') and ($ToDate!=''))
                  {
                    $ssqlleave=$ssqlleave." and `LeaveFrom`>= '$FromDate' and `LeaveTo`<= '$ToDate'";
                  }

                  


                   $ssqlleave=$ssqlleave." order by `LeaveFrom`";
				   
				   
				  

				    $rsleave_emp= mysqli_query($Con, $ssqlleave);
                 ?>
			   <div class="table-responsive">
				<table class="table">
				    <thead>

				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th>Admission</th>
				        <th>Class</th>
				        <th>Leave From</th>
				        <th>Leave To</th>
				        <th>Leave Type</th>
				        <th>Remark</th>
				        <th>Medical Certificate</th>
				        <th>Status</th>
				        <th>Teacher Remark</th>
				               
				        
				       
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
  
					       while($row = mysqli_fetch_array($rsleave_emp))
					            {
					                
					              
					    $sadmission=$row[0];
					    $sclass=$row[1];
					    $leave_from=$row[2];
					    $leave_to=$row[3];
					    $leave_type=$row[4];
					    $remark=$row[5];
					    $MedicalCertificate=$row[7];
					    $status=$row[9];
					    $teacher_remark=$row[10];
					    
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $sadmission;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $sclass;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_from;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_to?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_type?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $remark?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><a href="<?php echo $MedicalCertificate;?>" target="_blank">View</a></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $status?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $teacher_remark?></font></td>
			
			
				<?php
			    }
			    ?>
			</tr>


				      

				 </tbody>	
				</table>
				</div>
				<?php
			}
			else
			{
			?>
			 <div class="table-responsive">
				<table class="table">
				    <thead>
				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th>Admission</th>
				        <th>Class</th>
				        <th>Leave From</th>
				        <th>Leave To</th>
				        <th>Leave Type</th>
				        <th>Remark</th>
				        <th>Medical Certificate</th>
				        <th>Status</th>
				        <th>Teacher Remark</th>
				      
				       
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
                     if($StudentId=='Admin'){
                       
                        $ssqlleave="SELECT distinct  `sadmission`, `sclass`, `LeaveFrom`, `LeaveTo`, `LeaveType`, `remark`, `EntryDate`, `MedicalCertificate`, `sys_date_time`, `status`, `teacher_remark`, `emp_id`, `response_date` FROM `Student_Leave_Transaction` where  1=1 order by `LeaveFrom` desc LIMIT 10 ";
                 
                   
                     }
                     else
                     {
                          $ssqlleave="SELECT distinct  `sadmission`, `sclass`, `LeaveFrom`, `LeaveTo`, `LeaveType`, `remark`, `EntryDate`, `MedicalCertificate`, `sys_date_time`, `status`, `teacher_remark`, `emp_id`, `response_date` FROM `Student_Leave_Transaction` where  `sadmission`='$StudentId'  order by `LeaveFrom` desc LIMIT 10 ";
                 
                         
                     }
                        $rsleave_emp= mysqli_query($Con, $ssqlleave);
                         
					       while($row = mysqli_fetch_array($rsleave_emp))
					            {
					                 $sadmission=$row[0];
            					    $sclass=$row[1];
            					    $leave_from=$row[2];
            					    $leave_to=$row[3];
            					    $leave_type=$row[4];
            					    $remark=$row[5];
            					    $MedicalCertificate=$row[7];
            					    $status=$row[9];
            					    $teacher_remark=$row[10];
    					    $Cnt=$Cnt+1;
					   
				
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $sadmission;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $sclass;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_from;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_to?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $leave_type?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $remark?></font></td>
			    <td><font face="Cambria" style="font-size: 11pt"><a href="<?php echo $MedicalCertificate;?>" target="_blank">View</a></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $status?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $teacher_remark?></font></td>
			
			
				<?php
			    }
			    ?>
			</tr>

			</tbody>	
				</table>
				</div>
			
			<?php
			}
			?>
			</div>
		</div>
	</div>
	


