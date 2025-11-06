
<div class="container-fluid">
	<div class="row m-t10">
		<div class="col-12">
			<form name="frmassignment" id="frmassignment" method="post"  action=""  >
                     <input type="hidden" name="isSubmit_assign" id="isSubmit_assign" value="yes">
			<section class="row">
				<div class="col-md-3">
			        <label>From</label>
                    <input type="date" name="txtDateFrom_assign" id="txtDateFrom_assign"  class="form-control">
                </div>
                 <div class="col-md-3">
                     <label>To</label>
                    <input type="date" name="txtDateTo_assign" id="txtDateTo_assign" class="form-control">
                </div>
                 <div class="col-md-3">
                      <label>Select Class </label>
                   

                    	<select name="class_assign" id="class_assign" class="form-control" onchange="fnlFillSubject1();">
						 
						<option selected="" value="">Select One</option>	
						  
                        <?php
                         if($StudentId=="Admin")
                            {
                           
                            
                            $rsemp=mysqli_query($Con, "SELECT distinct `class` FROM `class_master`");
                            
                            }
                            else
                            {
                            
                            $sql="SELECT distinct `class` FROM `class_master` where `class` ='$StudentClass'";
                                $rsemp=mysqli_query($Con, $sql);
                            }

                            while($row1 = mysqli_fetch_row($rsemp))

                                {

                                        $class=$row1[0];
                                    
                                  ?>
                                
                            
                                <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
                            
                                <?php
                            
                                  }
                                  ?>
                   </select>
                </div>
	              <div class="col-md-3">
	                  <label>Select Subject </label>
			         <div class="form-group row">
	                    <div class="col-sm-8 p-rn">
	                        
	                        <select name="subject_name" id="subject_name"  class="form-control">
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
				if($_REQUEST["isSubmit_assign"]=="yes")
				{
				    
				     $tab = '"#tab5"';
                    echo "<script>$('.nav-tabs a[href=$tab]').tab('show');</script>"; 
                    
                   
				  $FromDate=$_REQUEST["txtDateFrom_assign"];
				  $ToDate=$_REQUEST["txtDateTo_assign"];
                  $class_assign = $_REQUEST["class_assign"];
                  $subject_name = $_REQUEST["subject_name"];
                  
				
               

                  $ssqlassign="SELECT  `class`, `subject`, `assignmentdate`, `assignmentcompletiondate`, `remark`, `assignmentURL`, `status`, `datetime` FROM `assignment`  where  1=1 ";
                 
                  if( $class_assign!='All')
                  {
                    $ssqlassign=$ssqlassign." and  `class`='$class_assign'";
                  }

                  if( ($FromDate!='') and ($ToDate!=''))
                  {
                    $ssqlassign=$ssqlassign." and `assignmentdate`>= '$FromDate' and `assignmentcompletiondate`<= '$ToDate'";
                  }

                  if( ($subject_name!=''))
                  {
                    $ssqlassign=$ssqlassign." and `subject`= '$subject_name' ";
                  }
                  
                  $rsassignmnet= mysqli_query($Con, $ssqlassign);
                 ?>
			   <div class="table-responsive">
				<table class="table">
				    <thead>
				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th>Class</th>
				        <th>Subject Name</th>
				        <th>Assignment From</th>
				        <th>Assignment To</th>
				        <th>Remark</th>
				        <th>Assignment URL</th>
				     
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
                         
                        
					       while($row = mysqli_fetch_array($rsassignmnet))
					            {
					    $Class=$row[0];
					    $Subject=$row[1];
					    $Assignment_date=$row[2];
					    $Assignment_completion=$row[3];
					    $Remark=$row[4];
					    $Assignment_url=$row[5];

					    $url_req = trim($Assignment_url,$base_url);
					   
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Class;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Subject;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_date;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_completion;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Remark;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><a href="<?= $url_req;?>"  target="_blank"> View </a></font></td>
			
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
				        <th>Class</th>
				        <th>Subject Name</th>
				        <th>Assignment From</th>
				        <th>Assignment To</th>
				        <th>Remark</th>
				        <th>Assignment URL</th>
				     
				    </tr>
				    </thead>
				   	<tbody>
				    		<?php 
                  		$Cnt=0;
                if($StudentId=='Admin'){
                  	        $ssqlassign="SELECT  `class`, `subject`, `assignmentdate`, `assignmentcompletiondate`, `remark`, `assignmentURL`, `status`, `datetime` FROM `assignment`  where  1=1  order by `assignmentdate` DESC LIMIT 10";
                  	         $rsassignmnet= mysqli_query($Con, $ssqlassign);
                      while($row = mysqli_fetch_array($rsassignmnet))
					    {
					    $Class=$row[0];
					    $Subject=$row[1];
					    $Assignment_date=$row[2];
					    $Assignment_completion=$row[3];
					    $Remark=$row[4];
					    $Assignment_url=$row[5];

					    $url_req = trim($Assignment_url,$base_url);
					   
					    $Cnt=$Cnt+1;
					    ?>
					  <tr>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Class;?></font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Subject;?></font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_date;?></font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_completion;?></font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Remark;?></font></td>
        				<td><font face="Cambria" style="font-size: 11pt"><a href="<?= $url_req;?>"  target="_blank"> View </a></font></td>
        			   <?php
        			    }    
                    }
                    
                    
                else
                {
                    
                  	        $ssqlassign="SELECT  distinct `class`, `subject`, `assignmentdate`, `assignmentcompletiondate`, `remark`, `assignmentURL`, `status`, `datetime` FROM `assignment`  where  `class`='$StudentClass'  order by `assignmentdate` DESC LIMIT 10";
                  	      
                  	          $rsassignmnet= mysqli_query($Con, $ssqlassign);
                  	        
                  	   
                  	   
                         
					    while($row = mysqli_fetch_array($rsassignmnet))
					    {
					    $Class=$row[0];
					    $Subject=$row[1];
					    $Assignment_date=$row[2];
					    $Assignment_completion=$row[3];
					    $Remark=$row[4];
					    $Assignment_url=$row[5];

					    $url_req = trim($Assignment_url,$base_url);
					   
					    $Cnt=$Cnt+1;
					    ?>

			    <tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Class;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Subject;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_date;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Assignment_completion;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Remark;?></font></td>
				<td class="text-center"><font face="Cambria" style="font-size: 11pt"><a href="<?= $url_req;?>"  target="_blank" class="btn btn-success btn-sm text-white"> View </a></font></td>
			
            	     	<?php
            			}
                  	   
                  	
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
	


