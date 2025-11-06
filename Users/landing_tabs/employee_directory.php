
<div class="container-fluid">
	<div class="row m-t10">
		<div class="col-12">
			<form name="frmdir" id="frmdir" method="post"  action=""  >
                     <input type="hidden" name="isSubmit_directory" id="isSubmit_directory" value="yes">
			<section class="row">
				
                 <div class="col-md-3">
                      <label>Select Section</label>
                        <input list="dir" name="employee_dir" id="employee_dir" class="form-control">
				    <datalist id="dir">
						<?php
                       

                             $rsemp=mysqli_query($Con, "select distinct `srno`, `section`, `phoneno`, `email_id`, `datetime` from `school_directory`");
                             while($row1 = mysqli_fetch_row($rsemp))
                                {
                                    $section=$row1[1];
                                   
                                ?>
                            <option value="<?php echo $section; ?>"><?php echo $section; ?></option>
                        <?php
                        }
                        ?>
                   </datalist>
                </div>
	            
	           <div class="col-md-3">
	                  <label>&nbsp; </label>
			        <div class="form-group row">
	                    <div class="col-sm-8 p-rn">
	                    	  <input name="Submit1" type="submit" value="Submit" class="btn btn-primary" style="margin-left:10px">
	                    </div>
	                    <div class="col-sm-3 p-ln">
	                     
	                    </div>
	                </div>
	            </div>
	           
			</section>
			 </form>
		
			<?php 
			  $recno=1;
				if($_REQUEST["isSubmit_directory"]=="yes")
				{
				    
				     $tab = '"#tab4"';
                    echo "<script>$('.nav-tabs a[href=$tab]').tab('show');</script>"; 
                    
                  $employee_dir = $_REQUEST["employee_dir"];
                  
                
                  $ssqlemp="select distinct `srno`, `section`, `phoneno`, `email_id`, `datetime` from `school_directory` WHERE 1  ";
                 
                  
                           
                             
                 if( ($employee_dir!=''))
                  {
                    $ssqlemp=$ssqlemp." and `section`= '$employee_dir' ";
                  }
                  
               
                     $rs_emp_master= mysqli_query($Con, $ssqlemp);
                 ?>
                 
			   <div class="table-responsive">
				<table class="table">
				    <thead>
				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th>Section</th>
				        <th>Phone No</th>
				        <th>Email Id</th>
				      
				      
				       
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
  
					       while($row = mysqli_fetch_array($rs_emp_master))
					            {
					                
					    $srno=$row[0];
					    $section=$row[1];
					    $phoneno=$row[2];
					    $email_id=$row[3];
					    $datetime=$row[4];
					   
					   
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $section;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $phoneno;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $email_id;?></font></td>
			
			
			
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
				        <th>Section</th>
				        <th>Phone No</th>
				        <th>Email Id</th>
				    
				       
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
                     $ssqlemp="select distinct `srno`, `section`, `phoneno`, `email_id`, `datetime` from `school_directory`  WHERE 1  order by `srno` ASC LIMIT 10  ";
                     $rs_emp_master= mysqli_query($Con, $ssqlemp);
					       while($row = mysqli_fetch_array($rs_emp_master))
					            {
					   $srno=$row[0];
					    $section=$row[1];
					    $phoneno=$row[2];
					    $email_id=$row[3];
					    $datetime=$row[4];
					   
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $section;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $phoneno;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $email_id;?></font></td>
		
			
			
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
	


