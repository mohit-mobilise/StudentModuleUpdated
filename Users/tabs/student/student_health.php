<style>
    .table td, .table th {
        /*min-width:max-content !important;*/
    }
    #listings-container-health div {
    display:none;
}
</style>
<script>
function showMoreDataHealth() {
        //alert('hello');
        $("#listings-container-health").find("div:hidden:lt(6)").show();
    
}
</script>

<div id="healthmodal" class="modal fade" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Health Detail</h5>
        <!--<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>-->
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        <form name="addhealthfrm" id="addhealthfrm">
        <div class="row">

              <div class="col-md-6">
                <label for="recipient-name" class="col-form-label">Height:</label>
                <input name="height" id="height" class="form-control">
                    
              </div>
              
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Weight:</label>
                <input type="text" class="form-control" name="weight" id="weight">
              </div>
          </div>
          
          <div class="row">
              
              
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Blood Group:</label>
                <input type="text" class="form-control" name="blood_group" id="blood_group">
              </div>
              
              <div class="col-md-6">
                <label for="recipient-name" class="col-form-label">Throat:</label>
                <input type="text" name="throat" id="throat" class="form-control">
                    
              </div>
          </div>
          <div class="row">
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Dental Health:</label>
                <input type="text" class="form-control" name="dental_health" id="dental_health">
              </div>
              
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Any Allergies:</label>
                <input type="text" class="form-control" name="any_allergies" id="any_allergies">
              </div>
              
           </div>
           
           <div class="row">
               <div class="col-md-12">
                <label for="message-text" class="col-form-label">Remark:</label>
                <textarea class="form-control" name="health_remark" id="health_remark"></textarea>
              </div>
           </div>
           
          
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="return submitHealth();">Submit</button>
      </div>
    </div>
  </div>
</div>

<!--<div class="row" id="btnhealth">-->
<!--	<div class="col-md-12">-->
<!--		<div class="text-right mt-1">-->
		
<!--		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#healthmodal">Add Health</button>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<?php
        $fyquery = mysqli_query($Con, "SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrow=mysqli_fetch_array($fyquery)){
            $finyear=$fyrow['year'];
            
        $chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_health_wellness` where `sadmission`='$sadmission' and `FY`='$finyear'");
        if(mysqli_num_rows($chcekquery)==0){
            continue;
        }
        
?>
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Health Stats  (<?php echo $finyear;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-responsive table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center">Height</th>
                                              <th class="text-center">Weight</th>
                                              <th class="text-center">Blood Group</th>
                                              <!--<th class="text-center">Nails</th>-->
                                              <!--<th class="text-center">Skin</th>-->
                                              <!--<th class="text-center">Animia</th>-->
                                              <!--<th class="text-center">Nose</th>-->
                                              <!--<th class="text-center">Left Vision</th>-->
                                              <!--<th class="text-center">Right Vision</th>-->
                                              <!--<th class="text-center">Hair</th>-->
                                              <!--<th class="text-center">Ear</th>-->
                                              <!--<th class="text-center">Specific Disease</th>-->
                                              <th class="text-center">Throat</th>
                                              <th class="text-center">Vaccinated</th>
                                              <th class="text-center">Dental-health</th>
                                              <th class="text-center">Any Allergies</th>
                                              <th class="text-center">Any Illness</th>
                                              <th class="text-center" style="min-width:390px">Remark</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     $queryhealth = mysqli_query($Con, "SELECT * FROM `student_his_health_wellness` where `sadmission`='$sadmission' and `FY`='$finyear' order by `systemdatetime` desc");
                                     $srno=1;
                                     $fycounthealth=mysqli_num_rows($queryhealth);
                                     while($rowShealth=mysqli_fetch_array($queryhealth)){
                                     $FY=$rowShealth['FY'];
                                     $remark=$rowShealth['remark'];
                                     $throat=$rowShealth['throt'];
                                     $specific_desease=$rowShealth['specific_desease'];
                                     $ear=$rowShealth['ear'];
                                     $hair=$rowShealth['hair'];
                                     $right_vision=$rowShealth['right_vision'];
                                     $left_vision=$rowShealth['left_vision'];
                                     $nose=$rowShealth['nose'];
                                     $animia=$rowShealth['animia'];
                                     $skin=$rowShealth['skin'];
                                     $nails=$rowShealth['nails'];
                                     $blood_group=$rowShealth['blood_group'];
                                     $weight=$rowShealth['weight'];
                                     $height=$rowShealth['height'];
                                     $sclass=$rowShealth['sclass'];
                                     $denatlhealth=$rowShealth['dental_health'];
                                     $anyillness=$rowShealth['any_illness'];
                                     $anyallergies=$rowShealth['any_allergies'];
                                     $vaccinated=$rowShealth['vaccinated'];
                            ?>       
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $sclass;?></td>
                                              <td><?php echo $height;?></td>
                                              <td><?php echo $weight;?></td>
                                              <td><?php echo $blood_group;?></td>
                                              <!--<td><?php echo $nails;?></td>-->
                                              <!--<td><?php echo $skin;?></td>-->
                                              <!--<td><?php echo $animia;?></td>-->
                                              <!--<td><?php echo $nose;?></td>-->
                                              <!--<td><?php echo $left_vision;?></td>-->
                                              <!--<td><?php echo $right_vision;?></td>-->
                                              <!--<td><?php echo $hair;?></td>-->
                                              <!--<td><?php echo $ear;?></td>-->
                                              <!--<td><?php echo $specific_desease;?></td>-->
                                              <td><?php echo $throat;?></td>
                                              <td><?php echo $vaccinated;?></td>
                                              <td><?php echo $denatlhealth;?></td>
                                              <td><?php echo $anyallergies;?></td>
                                              <td><?php echo $anyillness;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                             
                                              
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

<div id="listings-container-health">
<!--- start show more functionality -->
<?php
        /*$fyquery = mysql_query("SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrow=mysql_fetch_array($fyquery)){
            $finyear=$fyrow['year'];
            
        $chcekquery=mysql_query("SELECT distinct`FY` FROM `student_his_health_wellness` where `sadmission`='$sadmission' and `FY`='$finyear'");
        if(mysql_num_rows($chcekquery)==0){
            continue;
        }*/
        $finyearmore = date('Y');
        //echo "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`!='$finyearmore'";
        $fyquery = mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_health_wellness` where `sadmission`='$sadmission' and `FY`!='$finyearmore'");
        while($fyrow=mysqli_fetch_array($fyquery)){
        $finyearShowMOre=$fyrow['FY'];
        
?>
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Health Stats  (<?php echo $finyearShowMOre;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-responsive table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center">Height</th>
                                              <th class="text-center">Weight</th>
                                              <th class="text-center">Blood Group</th>
                                              <!--<th class="text-center">Nails</th>-->
                                              <!--<th class="text-center">Skin</th>-->
                                              <!--<th class="text-center">Animia</th>-->
                                              <!--<th class="text-center">Nose</th>-->
                                              <!--<th class="text-center">Left Vision</th>-->
                                              <!--<th class="text-center">Right Vision</th>-->
                                              <th class="text-center">Throat</th>
                                              <th class="text-center">Vaccinated</th>
                                              <th class="text-center">Dental-health</th>
                                              <th class="text-center">Any Allergies</th>
                                              <th class="text-center">Any Illness</th>
                                              <th class="text-center" style="min-width:390px">Remark</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     $queryhealth = mysqli_query($Con, "SELECT * FROM `student_his_health_wellness` where `sadmission`='$sadmission' and `FY`='$finyearShowMOre' order by `FY` desc");
                                     $srno=1;
                                     $fycounthealth=mysqli_num_rows($queryhealth);
                                     while($rowShealth=mysqli_fetch_array($queryhealth)){
                                     $FY=$rowShealth['FY'];
                                     $remark=$rowShealth['remark'];
                                     $throat=$rowShealth['throt'];
                                     $specific_desease=$rowShealth['specific_desease'];
                                     $ear=$rowShealth['ear'];
                                     $hair=$rowShealth['hair'];
                                     $right_vision=$rowShealth['right_vision'];
                                     $left_vision=$rowShealth['left_vision'];
                                     $nose=$rowShealth['nose'];
                                     $animia=$rowShealth['animia'];
                                     $skin=$rowShealth['skin'];
                                     $nails=$rowShealth['nails'];
                                     $blood_group=$rowShealth['blood_group'];
                                     $weight=$rowShealth['weight'];
                                     $height=$rowShealth['height'];
                                     $sclass=$rowShealth['sclass'];
                                     $denatlhealth=$rowShealth['dental_health'];
                                     $anyillness=$rowShealth['any_illness'];
                                     $anyallergies=$rowShealth['any_allergies'];
                                     $vaccinated=$rowShealth['vaccinated'];
                            ?>       
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $sclass;?></td>
                                              <td><?php echo $height;?></td>
                                              <td><?php echo $weight;?></td>
                                              <td><?php echo $blood_group;?></td>
                                              <!--<td><?php echo $nails;?></td>-->
                                              <!--<td><?php echo $skin;?></td>-->
                                              <!--<td><?php echo $animia;?></td>-->
                                              <!--<td><?php echo $nose;?></td>-->
                                              <!--<td><?php echo $left_vision;?></td>-->
                                              <!--<td><?php echo $right_vision;?></td>-->
                                              <td><?php echo $throat;?></td>
                                              <td><?php echo $vaccinated;?></td>
                                              <td><?php echo $denatlhealth;?></td>
                                              <td><?php echo $anyallergies;?></td>
                                              <td><?php echo $anyillness;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                             
                                              
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
$chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_health_wellness` where `sadmission`='$sadmission'");
$fycount=mysqli_num_rows($chcekquery);
if($fycount>0){
?> 
                    <div class="row" id="btnhealth">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <!--<button type="button" class="btn btn-info" onclick="showmoredatahealth('<?php //echo $sadmission;?>')">More</button>-->
					            <button type="button" class="btn btn-info" onclick="showMoreDataHealth()">More</button>
					        </div>
					    </div>
					</div>
<?php } ?> 
<div id="show_admdatahealth"></div>    

                    <div class="row" id="btnhealthless">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <button type="button" class="btn btn-info" onclick="showlessdata('health')">Less</button>
					        </div>
					    </div>
					</div>
<script>
function submitHealth() {
            var height =  $("#height").val();
            var weight = $("#weight").val();
            var blood_group = $("#blood_group").val();
            var throat = $("#throat").val();
            var dental_health = $("#dental_health").val();
            var any_allergies = $("#any_allergies").val();
            var health_remark = $("#health_remark").val();
            
            if(height == "") {
                toastr.warning('Please enter height', 'Validation Error');
                $("#height").focus();
                return false;
            }
            
            else if(weight == "") {
                toastr.warning('Please enter weight', 'Validation Error');
                $("#weight").focus();
                return false;
            }
            
            /*else if(location == "") {
                alert('Please enter location');
                $("#location").focus();
                return false;
            }
            
            else if(position == "") {
                alert('Please select position');
                $("#position").focus();
                return false;
            }
            
            else if(remark == "") {
                alert('Please enter remark');
                $("#remark").focus();
                return false;
            }
            
            else if(organiser == "") {
                alert('Please enter organiser');
                $("#organiser").focus();
                return false;
            }*/
            else {
            
            var formData = new FormData(document.getElementById("addhealthfrm"));
            $.ajax({
                type: "POST",
                url: "add_health.php",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    toastr.success("Health detail added successfully!", "Success");
                    $("#addhealthfrm")[0].reset();
                }
            });
            }
        }
</script>