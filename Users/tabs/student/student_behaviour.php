<style>
    #listings-container-behave div {
    display:none;
</style>
<script>
    function showMoreDataBehave() {
        //alert('hello');
        $("#listings-container-behave").find("div:hidden:lt(6)").show();
    
}
</script>

<div id="addBehaviour" class="modal fade" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Behaviour</h5>
        <!--<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>-->
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        <form name="addBehaviourfrm" id="addBehaviourfrm">
        
          
          <div class="row">
              <div class="col-md-12">
                <label for="message-text" class="col-form-label">Remark:</label>
                <textarea class="form-control" name="remark" id="remarks"></textarea>
              </div>
         </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="return submitBehaviour();">Submit</button>
      </div>
    </div>
  </div>
</div>

<!--<div class="row" id="btnachievement">-->
<!--	<div class="col-md-12">-->
<!--		<div class="text-right mt-1">-->
		
<!--		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#addBehaviour">Add Behaviour</button>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<?php
        $fyquery = mysqli_query($Con, "SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrow=mysqli_fetch_array($fyquery)){
            $finyear=$fyrow['year'];
            
        $chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_behaviour` where `sadmission`='$sadmission' and `FY`='$finyear'");
        if(mysqli_num_rows($chcekquery)==0){
            continue;
        }
        
?>	
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Remarks  (<?php echo $finyear;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-responsive table-striped table-bordered table-hover text-center" style="width:100%">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center" style="width:100%;!important">Remarks</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     $querybehave = mysqli_query($Con, "SELECT * FROM `student_his_behaviour` where `sadmission`='$sadmission' and `FY`='$finyear' order by `systemdatetime` desc");
                                     $srno=1;
                                     $fycount=mysqli_num_rows($querybehave);
                                     while($rowSbehave=mysqli_fetch_array($querybehave)){
                                     $FY=$rowSbehave['FY'];
                                     $remark=$rowSbehave['remark'];
                                     $class=$rowSbehave['sclass'];
                            ?>                
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $class;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                              <!--<td><?php echo wordwrap($remark,50,"<br>\n");?></td>-->
                                              
                                            </tr>
                             <?php
                                            $srno++;
                                                } 
                                    ?>               
                                          </tbody>
                                        </table>
    						       
                                </div>
                            </div>
						
                    </div>

<?php 
}
?>

<div id="listings-container-behave">
<!-- show more function start --->

<?php
        /*$fyquery = mysql_query("SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrow=mysql_fetch_array($fyquery)){
            $finyear=$fyrow['year'];
            
        $chcekquery=mysql_query("SELECT distinct`FY` FROM `student_his_behaviour` where `sadmission`='$sadmission' and `FY`='$finyear'");
        if(mysql_num_rows($chcekquery)==0){
            continue;
        }*/
        $finyearmore = date('Y');
        //echo "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`!='$finyearmore'";
        $fyquery = mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_behaviour` where `sadmission`='$sadmission' and `FY`!='$finyearmore'");
        while($fyrow=mysqli_fetch_array($fyquery)){
        $finyearShowMOre=$fyrow['FY'];
        
?>	
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Remarks  (<?php echo $finyearShowMOre;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-responsive table-striped table-bordered table-hover text-center" style="width:100%">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center" style="width:100%;!important">Remarks</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     $querybehave = mysqli_query($Con, "SELECT * FROM `student_his_behaviour` where `sadmission`='$sadmission' and `FY`='$finyearShowMOre' order by `FY` desc");
                                     $srno=1;
                                     $fycount=mysqli_num_rows($querybehave);
                                     while($rowSbehave=mysqli_fetch_array($querybehave)){
                                     $FY=$rowSbehave['FY'];
                                     $remark=$rowSbehave['remark'];
                                     $class=$rowSbehave['sclass'];
                            ?>                
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $class;?></td>
                                              <td><div class="comment more" style="width:100% !important"><?php echo $remark;?></div></td>
                                              <!--<td><?php echo wordwrap($remark,50,"<br>\n");?></td>-->
                                              
                                            </tr>
                             <?php
                                            $srno++;
                                                } 
                                    ?>               
                                          </tbody>
                                        </table>
    						       
                                </div>
                            </div>
						
                    </div>

<?php 
}
?>
</div>

<?php
$chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_behaviour` where `sadmission`='$sadmission'");
$fycount=mysqli_num_rows($chcekquery);
if($fycount>0){
?> 
                    <div class="row" id="btnbehave">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <!--<button type="button" class="btn btn-info" onclick="showmoredatabehave('<?php //echo $sadmission;?>')">More</button>-->
					            <button type="button" class="btn btn-info" onclick="showMoreDataBehave()">More</button>
					        </div>
					    </div>
					</div>
<?php } ?>

<div id="show_admdatabehave"></div>

                    <div class="row" id="btnbehaveless">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <button type="button" class="btn btn-primary" onclick="showlessdata('behave')">Less</button>
					        </div>
					    </div>
					</div>
<script>
function submitBehaviour() {
            
            var remark = $("#remarks").val();
            
            if(remark == "") {
                toastr.warning('Please enter remark', 'Validation Error');
                $("#remarks").focus();
                return false;
            }
            
            
            else {
            
            var formData = new FormData(document.getElementById("addBehaviourfrm"));
            $.ajax({
                type: "POST",
                url: "add_behaviour.php",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    toastr.success("Behaviour added successfully!", "Success");
                    $("#addBehaviourfrm")[0].reset();
                }
            });
            }
        }
</script>