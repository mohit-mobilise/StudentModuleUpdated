
<div class="container-fluid">
	<div class="row m-t10">
		<div class="col-12">
			<form name="frmNotice" id="frmNotice" method="post"  action=""  >
                     <input type="hidden" name="isSubmit_notice" id="isSubmit_notice" value="yes">
			<section class="row">
				<div class="col-md-3">
			        <label>From</label>
                    <input type="date" name="txtDateFrom_notice" id="txtDateFrom_notice"  class="form-control">
                </div>
                 <div class="col-md-3">
                     <label>To</label>
                    <input type="date" name="txtDateTo_notice" id="txtDateTo_notice" class="form-control">
                </div>
                 <div class="col-md-3">
                      <label>Select Class</label>
                    <select  name="cboClass_notice" id="cboClass_notice" class="form-control" >
                    
                    		<option  value="All" selected>All</option>
                        <?php
                             
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
	                    <div class="col-sm-3 p-rn">
	                    <input name="Submit1" type="submit" value="Submit" class="btn btn-primary">
	                    </div>
	                    <div class="col-sm-9 p-ln">
	                      
	                    </div>
	                  </div>
	            </div>
	           
			</section>
			 </form>
		
			<?php 
			  $recno=1;
				if($_REQUEST["isSubmit_notice"]=="yes")
				{
				    
				     $tab = '"#tab1"';
                    echo "<script>$('.nav-tabs a[href=$tab]').tab('show');</script>"; 
                    
                   
				  $FromDate=$_REQUEST["txtDateFrom_notice"];
				  $ToDate=$_REQUEST["txtDateTo_notice"];
                  $Class = $_REQUEST["cboClass_notice"];
				
               

                  $ssqlNotice="SELECT distinct `sn`.`notice`, `sn`.`noticetitle`, `sn`.`NoticeDate`, `cl`.`class` FROM `student_notice` as `sn` LEFT JOIN `class_master` as `cl` ON(`sn`.`sclass`=`cl`.`class`) where 1=1 ";
                  if( $Class!='All')
                  {
                    $ssqlNotice=$ssqlNotice." and  `sn`.`sclass`='$Class'";
                  }

                  if( ($FromDate!='') and ($ToDate!=''))
                  {
                    $ssqlNotice=$ssqlNotice." and `sn`.`NoticeDate`>= '$FromDate' and  `sn`.`NoticeDate`<= '$ToDate'";
                  }

                   $ssqlNotice=$ssqlNotice." order by `sn`.`NoticeDate`";
				   
				   
				   
				    $rsStudentNotice= mysqli_query($Con, $ssqlNotice);
                 ?>
			   <div class="table-responsive">
				<table class="table">
				    <thead>
				    <tr class="bg-primary text-white">
				        <th>Srno</th>
				        <th>Date</th>
				        <th>Class</th>
				        <th>Notice title</th>
				        <th>Action</th>
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
  
					       while($row = mysqli_fetch_array($rsStudentNotice))
					            {
					    $notice=$row[0];
					    $noticetitle=$row[1];
					    $NoticeDate=$row[2];
					    $class_name=$row[3];
					   
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $NoticeDate;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $class_name;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $noticetitle;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><button class=" view_notice_dta" data-view_srno="<?php echo $Cnt;?>"><a data-toggle="modal"  class="dropdown-item">View</a></button></font></td>
			
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
				        <th>Date</th>
				        <th>Class</th>
				        <th>Notice title</th>
				        <th>Action</th>
				    </tr>
				    </thead>
				   	<tbody>
				   		<?php 
                  		$Cnt=0;
                  		 if($StudentId=='Admin'){
                  		      $ssqlNotice="SELECT distinct `sn`.`notice`, `sn`.`noticetitle`, `sn`.`NoticeDate`,  `cl`.`class` FROM `student_notice` as `sn` LEFT JOIN `class_master` as `cl` ON(`sn`.`sclass`=`cl`.`class`) where `sn`.`status`='Active' order by `sn`.`NoticeDate` desc Limit 10 ";
                  		      $rsStudentNotice= mysqli_query($Con, $ssqlNotice);
                  		       while($row = mysqli_fetch_array($rsStudentNotice))
					            {
            					    $notice=$row[0];
            					    $noticetitle=$row[1];
            					    $NoticeDate=$row[2];
            					    $class_name=$row[3];
            					   
            					    $Cnt=$Cnt+1;
            					 ?>
            					 
                    			<tr>
                    				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
                    				<td><font face="Cambria" style="font-size: 11pt"><?php echo $NoticeDate;?></font></td>
                    				<td><font face="Cambria" style="font-size: 11pt"><?php echo $class_name;?></font></td>
                    				<td><font face="Cambria" style="font-size: 11pt"><?php echo $noticetitle;?></font></td>
                    				<td ><font face="Cambria" style="font-size: 11pt"><button class="btn btn-sm btn-success view_notice_dta" data-view_srno="<?php echo $Cnt;?>"><a data-toggle="modal"  class="dropdown-item">View</a></button></font></td>
                    		<?php
					            }
                    				
                  		 } 
                  		 else 
                  		 {
                  		   
                             $ssqlNotice="SELECT distinct `sn`.`notice`, `sn`.`noticetitle`, `sn`.`NoticeDate` , `cl`.`class` ,`sn`.`srno` FROM `student_notice` as `sn` LEFT JOIN `class_master` as `cl` ON(`sn`.`sclass`=`cl`.`class`) where `sn`.`sclass`='$StudentClass' and `sn`.`status`='Active'  order by `sn`.`NoticeDate` desc Limit 10 ";
                             $rsStudentNotice= mysqli_query($Con, $ssqlNotice);
                  		 
					       while($row = mysqli_fetch_array($rsStudentNotice))
					            {
					    $notice=$row[0];
					    $noticetitle=$row[1];
					    $NoticeDate=$row[2];
					    $class_name=$row[3];
					     $srno=$row[4];
					   
					    $Cnt=$Cnt+1;
					    ?>

			<tr>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $Cnt;?>&nbsp; </font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $NoticeDate;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $class_name;?></font></td>
				<td><font face="Cambria" style="font-size: 11pt"><?php echo $noticetitle;?></font></td>
					<td class="text-center"><font face="Cambria" style="font-size: 11pt"><button class="btn btn-sm btn-success view_notice_dta" data-view_srno="<?php echo $srno;?>" data-toggle="modal" class="dropdown-item">
                   View</button></font></td>
				<!--<td><font face="Cambria" style="font-size: 11pt"><button class="btn btn-sm btn-success view_notice_dta" data-view_srno="<?php echo $Cnt;?>"><a data-toggle="modal" class="dropdown-item">-->
    <!--               View</a></button></font></td>-->
			
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
	
	
	<div class="modal fade " id="notice_modal"  role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          
                        </div>
                        <!-- BEGIN FORM-->
                        <form action="" id="" class="horizontal-form" name='' method="post" enctype="multipart/form-data">
                           
                        <div class="modal-body">

                            <div class="row">
                            <div class="col-md-12">
                              
                                
                           <p id="notice_dta">
                              
                           
                           </p>
                              
                       

                          
                       </div>
                              </div>
                        </div><!--end of modal-body-->
                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-dark" data-dismiss="modal" >Close</button>
                        </div>
                    </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


