
<div class="container-fluid">
	<div class="row m-t10">
		<div class="col-12">
			<form name="frmAttendance" id="frmAttendance" method="post"  action="" onsubmit="return validate1();" >
                     <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
			<section class="row">
				<div class="col-md-3">
			        <label>From</label>
                    <input type="date" name="txtDateFrom" id="txtDateFrom"  class="form-control">
                </div>
                 <div class="col-md-3">
                     <label>To</label>
                    <input type="date" name="txtDateTo" id="txtDateTo" class="form-control">
                </div>
                 <div class="col-md-3">
                      <label>Select Class</label>
                    <select  name="cboClass" id="cboClass" class="form-control">
                    	<option selected="" value="">Select One</option>
                        <?php
                            if($EmployeeId=="Admin")
                            {
                           
                            
                            $rsClass=mysqli_query($Con, "SELECT distinct `class` FROM `class_master`");
                            
                            }
                            else
                            {
                            
                            $sql="SELECT distinct `class` FROM `class_master` where `class` in (select distinct `Class` from `teacher_class_mapping` where `EmpID`='$EmployeeId')";
                          
                            
                            $rsClass=mysqli_query($Con, $sql);
                            
                            }
                 
                            while($row1 = mysqli_fetch_row($rsClass))

                                {

                                        $Class=$row1[0];
                                        

                                  ?>
                                
                            
                                <option value="<?php echo $Class; ?>" <?php if ($Class==$SelectedClass) { echo "selected"; } ?> ><?php echo $Class; ?></option>
                            
                                <?php
                            
                                  }
                                  ?>
                    </select>
                </div>
	             <div class="col-md-3">
	                  <label>&nbsp;</label>
			         <div class="form-group row">
	                    <div class="col-sm-1 p-rn">
	                    <!-- <select name="cboExamType" id="cboExamType"  class="form-control">-->
	                    <!-- </select>	-->
	                    </div>
	                    <div class="col-sm-9 p-ln">
	                     <input name="Submit1" type="submit" value="Submit" class="btn btn-primary" style="margin-left:10px"> 
	                    </div>
	                  </div>
	            </div>
	           
			</section>
			 </form>
		
			<?php 
			  $recno=1;
				if($_REQUEST["isSubmit"]=="yes")
				{
				    $tab = '"#tab2"';
                    echo "<script>$('.nav-tabs a[href=$tab]').tab('show');</script>"; 
				  $FromDate=$_REQUEST["txtDateFrom"];
				  $ToDate=$_REQUEST["txtDateTo"];
				  $ToDate1=$_REQUEST["txtDateTo"];

				  $Class = $_REQUEST["cboClass"];
				  $subject_code = $_REQUEST["cboExamType"];
                
                  $ssqlStudent="SELECT distinct `sname`,`sclass`,`srollno`,`sadmission`,`sfathername` FROM `student_master` where `sclass`='$Class'  ORDER BY `srollno`";
				  
				    $rsStudent= mysqli_query($Con, $ssqlStudent);
                 ?>
			   <div class="table-responsive">
				<table class="table">
				    <thead>
				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th> Rollno #</th>
				        <th>Student Adm #</th>
				        <th>Student Name</th>
				        <th>Father Name</th>
				        <?php
							$ToDate=strftime("%Y-%m-%d", strtotime("$ToDate +1 day"));

							$period = new DatePeriod(
							     new DateTime($FromDate),
							     new DateInterval('P1D'),
							     new DateTime($ToDate)
							);

							foreach ($period as $key => $i) {
							    $i=$i->format('Y-m-d');       
							?>
							<td><?=date('d-m-Y',strtotime($i));?>.</td>
							<?php } ?>
						<td>Total Attendance</td>
						<td>Present Days</td>
						<td>Percentage</td>

				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;

					       while($row = mysqli_fetch_array($rsStudent))
					            {
					    $StudentName=$row[0];
					    $StudetnClass=$row[1];
					    $StudetnRollNo=$row[2];
					    $sadmission=$row[3];
					    $FatherName=$row[4];
					    $Cnt=$Cnt+1;
					    ?>

				<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $StudetnRollNo;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $sadmission;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $StudentName;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $FatherName;?></font></td>
				<?php


				foreach ($period as $key => $i) {
				    $i=$i->format('Y-m-d');       

				      $rsAttenadance= mysqli_query($Con, "select `attendance` from `attendance` where   `sadmission`='$sadmission' and `attendancedate`='$i'  and `sclass`='$Class'");
				          
				          $Attendance='';
				          while($rowMTE1= mysqli_fetch_array($rsAttenadance))
				            {
				              $Attendance=$rowMTE1[0];
				              break;
				            }
				        ?>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Attendance;?></font></td>
				<?php
				 } 

				         $rsPresent= mysqli_query($Con, "select count(*) from `attendance` where   `sadmission`='$sadmission' and `attendancedate`>='$FromDate' and `attendancedate`<='$ToDate1' and `attendance`='P'  and `sclass`='$Class'");      
				          $Present='';
				          $rowPresent= mysqli_fetch_array($rsPresent);
				          $Present=$rowPresent[0];

				           $rsAbsent= mysqli_query($Con, "select count(*) from `attendance` where   `sadmission`='$sadmission' and `attendancedate`>='$FromDate' and `attendancedate`<='$ToDate1' and `attendance`='A'  and `sclass`='$Class'");      
				          $Absent='';
				          $rowAbsent= mysqli_fetch_array($rsAbsent);
				          $Absent=$rowAbsent[0];
				           $TotalAttendance='';
				          $TotalAttendance=$Present+ $Absent;
				          $AttendancePercentage='';
				         $AttendancePercentage=number_format(($Present/$TotalAttendance)*100,2);

				?>
				<td><?php echo $TotalAttendance;?></td>
				<td><?php echo $Present;?></td>
				<td><?php echo $AttendancePercentage;?></td>

				  </tr>
				  <?php 
                }
                ?> 
				      

				 </tbody>	
				</table>
				</div>
				<?php
			}
			?>
			</div>
		</div>
	</div>
	


