<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">-->
<style>
    a {
	color: #0254EB
}
a:visited {
	color: #0254EB
}
a.morelink {
	text-decoration:none;
	outline: none;
}
.morecontent span {
	display: none;
}
.comment {
	width: 400px;
	background-color: #f0f0f0;
	margin: 10px;
}
#listings-container div {
    display:none;
}
@media screen and (max-width: 1368px) {
  table {
      zoom:85%
  }
}
</style>
<script>
function showMoreData() {
        //alert('hello');
        $("#listings-container").find("div:hidden:lt(6)").show();
    
}
</script>
<script>
    $(document).ready(function() {
    //alert('dsdsdsdsds');
	var showChar = 100;
	var ellipsestext = "...";
	var moretext = "More";
	var lesstext = "Less";
	$('.more').each(function() {
		var content = $(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar-1, content.length - showChar);

			var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

			$(this).html(html);
		}

	});

	$(".morelink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});
	
	
});




</script>

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Achievement</h5>
        <!--<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>-->
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        <form name="addAchievmentfrm" id="addAchievmentfrm">
        <div class="row">

              <div class="col-md-6">
                <label for="recipient-name" class="col-form-label">Achievment:</label>
                <select name="achievments" id="achievments" class="form-control">
                    <option value="">Select Achievment</option>
                    <option value="Science">Science</option>
                    <option value="Art">Art</option>
                    
                </select>
              </div>
              
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Date:</label>
                <input type="date" class="form-control" name="achievment_date" id="achievment_date">
              </div>
          </div>
          
          <div class="row">
              
              
              <div class="col-md-6">
                <label for="message-text" class="col-form-label">Location:</label>
                <input type="text" class="form-control" name="location" id="location">
              </div>
              <div class="col-md-6">
                <label for="recipient-name" class="col-form-label">Position:</label>
                <select name="position" id="position" class="form-control">
                    <option value="">Select position</option>
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>
                    <option value="3rd">3rd</option>
                    
                </select>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                <label for="message-text" class="col-form-label">Remark:</label>
                <textarea class="form-control" name="remark" id="remark"></textarea>
              </div>
              
              
           </div>
           
           <div class="row">
               <div class="col-md-12">
                <label for="message-text" class="col-form-label">Organiser:</label>
                <textarea class="form-control" name="organiser" id="organiser"></textarea>
              </div>
           </div>
           
          <div class="row">
          <div class="col-md-12">
            <label for="message-text" class="col-form-label">Attach File:</label>
            <input type="file" class="form-control" name="achievment_file" id="achievment_file">
          </div>
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="return submitAchievment();">Submit</button>
      </div>
    </div>
  </div>
</div>





<!--<div class="row" id="btnachievement">-->
<!--	<div class="col-md-12">-->
<!--		<div class="text-right mt-1">-->
		
<!--		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add Achievment</button>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<?php
        $fyquery = mysqli_query($Con, "SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrow=mysqli_fetch_array($fyquery)){
            $finyear=$fyrow['year'];
            
        $chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`='$finyear'");
        if(mysqli_num_rows($chcekquery)==0){
            continue;
        }
        
?>					
					<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    <!--color:white;background-color:#0071bc-->
                                    Achievement  (<?php echo $finyear;?>) 
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Achievment</th>
                                              <th class="text-center">Date</th>
                                              <th class="text-center">Organiser</th>
                                              <th class="text-center">Position</th>
                                              <th class="text-center">Remark</th>
                                              <th class="text-center">Location</th>
                                              <th class="text-center">Download</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                           <?php
                                     $query = mysqli_query($Con, "SELECT * FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`='$finyear' order by `systemdatetime` desc");
                                     $srno=1;
                                     $fycount=mysqli_num_rows($query);
                                     while($rowS=mysqli_fetch_array($query)){
                                     $FY=$rowS['FY'];
                                     $remark=$rowS['remark'];
                                     $position=$rowS['position'];
                                     $organizer=$rowS['organizer'];
                                     $doc_file=$rowS['doc_file'];
                                     $location=$rowS['location'];
                                     $date=$rowS['date'];
                                     $title=$rowS['title'];
                            ?>  
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $title;?></td>
                                              <td><?php echo $date;?></td>
                                              <td><?php echo $organizer;?></td>
                                              <td><?php echo $position;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                              <td><?php echo $location;?></td>
                                              <td><a href="achievment_file/<?php echo $doc_file ?>" download><i class="fa fa-download"></i></a></td>
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

<!-- show more section start --->
<div id="listings-container">
<?php
        $finyearmore = date('Y');
        //echo "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`!='$finyearmore'";
        $fyquery = mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY`!='$finyearmore'");
        while($fyrow=mysqli_fetch_array($fyquery)){
        $finyearShowMOre=$fyrow['FY'];
            
        
        
?>					<span>
					<div class="portlet box green-ad show-achievment-yearwise">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    <!--color:white;background-color:#0071bc-->
                                    Achievement  (<?php echo $finyearShowMOre;?>) 
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Achievment</th>
                                              <th class="text-center">Date</th>
                                              <th class="text-center">Organiser</th>
                                              <th class="text-center">Position</th>
                                              <th class="text-center">Remark</th>
                                              <th class="text-center">Location</th>
                                              <th class="text-center">Download</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                           <?php
                                     //echo "SELECT * FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY` IN('2022','2021','2020') order by `systemdatetime` desc";
                                     $query = mysqli_query($Con, "SELECT * FROM `student_his_achievement` where `sadmission`='$sadmission' and `FY` = $finyearShowMOre order by `FY` desc");
                                     $srno=1;
                                     $fycount=mysqli_num_rows($query);
                                     while($rowS=mysqli_fetch_array($query)){
                                     $FY=$rowS['FY'];
                                     $remark=$rowS['remark'];
                                     $position=$rowS['position'];
                                     $organizer=$rowS['organizer'];
                                     $doc_file=$rowS['doc_file'];
                                     $location=$rowS['location'];
                                     $date=$rowS['date'];
                                     $title=$rowS['title'];
                            ?>  
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $title;?></td>
                                              <td><?php echo $date;?></td>
                                              <td><?php echo $organizer;?></td>
                                              <td><?php echo $position;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                              <td><?php echo $location;?></td>
                                              <td> <i class="fa fa-download"></i></td>
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
                    </span>
                    
                    
                    
                    
<?php 
}
?>
</div>

<?php
$chcekquery=mysqli_query($Con, "SELECT distinct`FY` FROM `student_his_achievement` where `sadmission`='$sadmission'");
$fycount=mysqli_num_rows($chcekquery);
if($fycount>0){
?>                    
                    <div class="row" id="btnachievement">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <!--<button type="button" class="btn btn-info" onclick="showmoredata('<?php echo $sadmission;?>')">More</button>-->
					            <button type="button" class="btn btn-info" onclick="showMoreData()">More</button>
					        </div>
					    </div>
					</div>
<?php } ?> 
<div id="show_admdata"></div>

                    <div class="row" id="btnachievementless">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <button type="button" class="btn btn-info" onclick="showlessdata('achievement')">Less</button>
					        </div>
					    </div>
					</div>
<script>
function submitAchievment() {
            var achievments =  $("#achievments").val();
            var achievment_date = $("#achievment_date").val();
            var location = $("#location").val();
            var position = $("#position").val();
            var remark = $("#remark").val();
            var organiser = $("#organiser").val();
            var achievment_file = $("#achievment_file").val();
            
            if(achievments == "") {
                toastr.warning('Please select achievment', 'Validation Error');
                $("#achievments").focus();
                return false;
            }
            
            else if(achievment_date == "") {
                toastr.warning('Please choose date', 'Validation Error');
                $("#achievment_date").focus();
                return false;
            }
            
            else if(location == "") {
                toastr.warning('Please enter location', 'Validation Error');
                $("#location").focus();
                return false;
            }
            
            else if(position == "") {
                toastr.warning('Please select position', 'Validation Error');
                $("#position").focus();
                return false;
            }
            
            else if(remark == "") {
                toastr.warning('Please enter remark', 'Validation Error');
                $("#remark").focus();
                return false;
            }
            
            else if(organiser == "") {
                toastr.warning('Please enter organiser', 'Validation Error');
                $("#organiser").focus();
                return false;
            }
            else {
            
            var formData = new FormData(document.getElementById("addAchievmentfrm"));
            $.ajax({
                type: "POST",
                url: "add_achievment.php",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    toastr.success("Achievment added successfully!", "Success");
                    $("#addAchievment")[0].reset();
                }
            });
            }
        }
</script>