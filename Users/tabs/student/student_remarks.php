<?php
        $fyqueryremark = mysqli_query($Con, "SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrowremark=mysqli_fetch_array($fyqueryremark)){
            $finyearremark=$fyrowremark['year'];
        $chcekquery=mysqli_query($Con, "SELECT distinct`FY`,`exam_type` FROM `exam_marks` where `sadmission`='$sadmission' and `FY`='$finyearremark'");
        if(mysqli_num_rows($chcekquery)==0){
            continue;
        }
?>
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Academics  (<?php echo $finyearremark;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center">Exam Type</th>
                                              <th class="text-center">Marks</th>
                                              <th class="text-center">Percentage</th>
                                              <th class="text-center">Grade</th>
                                              <th class="text-center">Remarks</th>
                                              
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     while($stu_examtype=mysqli_fetch_array($chcekquery)){
                                            
                                            $stu_examtype1=$stu_examtype['exam_type'];
                                            
                                            if($stu_examtype1==""){
                                                continue;
                                            } 
                                     $queryremark = mysqli_query($Con, "SELECT * FROM `exam_marks` where `sadmission`='$sadmission' and `FY`='$finyearremark' and exam_type='$stu_examtype1' and ob_marks not in('0','') order by `systemdatetime` limit 1");
                                     $srno=1;
                                     $fycountremark=mysqli_num_rows($queryremark);
                                     while($rowSremark=mysqli_fetch_array($queryremark)){
                                     $FY=$rowSremark['FY'];
                                     $remark=$rowSremark['remark'];
                                     $overallgrade=$rowSremark['overall_grade'];
                                     $overallper=$rowSremark['overall_per'];
                                     $overallmarks=$rowSremark['overall_marks'];
                                     $examtype=$rowSremark['exam_type'];
                                     
                                     $getexamtype=mysqli_query($Con, "select distinct examtype from exam_master where exam_code='$examtype' or examtype='$examtype'");
                                     $getstuexamtype=mysqli_fetch_assoc($getexamtype);
                                     $studentexamtype = ($getstuexamtype && isset($getstuexamtype['examtype']) && $getstuexamtype['examtype'] != '') ? $getstuexamtype['examtype'] : $examtype;
                                     
                                     $ssclass=$rowSremark['sclass'];
                            ?>  
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $ssclass;?></td>
                                              <td><?php echo htmlspecialchars($studentexamtype);?></td>
                                              <td><?php echo $overallmarks;?></td>
                                              <td><?php echo $overallper;?></td>
                                              <td><?php echo $overallgrade;?></td>
                                              <td><div class="comment more"><?php echo $remark;?></div></td>
                                              
                                            </tr>
                                    <?php
                                            $srno++;
                                                } 
                                     }
                                    ?>
                                          </tbody>
                                        </table>
    						       
                                </div>
                            </div>
						
                    </div>
<?php } ?>
<style>
    #listings-container-remarks div {
    display:none;
}
</style>
<script>
function showMoreDataRemarks() {
        //alert('hello');
        $("#listings-container-remarks").find("div:hidden:lt(6)").show();
    
}
</script>
<!-- start more functionality ----->
<div id="listings-container-remarks">
<?php
        /*$fyqueryremark = mysql_query("SELECT * FROM `FYmaster` order by `year` desc limit 1");
        while($fyrowremark=mysql_fetch_array($fyqueryremark)){
            $finyearremark=$fyrowremark['year'];
        $chcekquery=mysql_query("SELECT distinct`FY` FROM `exam_marks` where `sadmission`='$sadmission' and `FY`='$finyearremark'");
        if(mysql_num_rows($chcekquery)==0){
            continue;
        }*/
        
        $finyearmore = date('Y');
        //echo "SELECT distinct`FY`,exam_type FROM `exam_marks` where `sadmission`='$sadmission' and `FY`!='$finyearmore'";
        $fyquery = mysqli_query($Con, "SELECT distinct`FY` FROM `exam_marks` where `sadmission`='$sadmission' and `FY`!='$finyearmore'");
        while($fyrow=mysqli_fetch_array($fyquery)){
        $finyearShowMOre=$fyrow['FY'];
        
?>
<div class="portlet box green-ad">
						<div class="card" style="border:1px solid #191970;padding:0">
                                <h5 class="card-header" style="">
                                    Academics  (<?php echo $finyearShowMOre;?>)
                                </h5>
            <div class="card-body">
    						        
    						            <table class="table table-striped table-bordered table-hover text-center">
                                          <thead class="">
                                            <tr>
                                              <th class="text-center">Sr No.</th>
                                              <th class="text-center">Class</th>
                                              <th class="text-center">Exam Type</th>
                                              <th class="text-center">Marks</th>
                                              <th class="text-center">Percentage</th>
                                              <th class="text-center">Grade</th>
                                              <th class="text-center">Remarks</th>
                                              
                                            </tr>
                                          </thead>
                                          <tbody>
                            <?php
                                     $queryremark = mysqli_query($Con, "SELECT * FROM `exam_marks` where `sadmission`='$sadmission' and `FY`='$finyearShowMOre' and ob_marks not in('0','') group by exam_type order by `FY` desc");
                                     $srno=1;
                                     $fycountremark=mysqli_num_rows($queryremark);
                                     while($rowSremark=mysqli_fetch_array($queryremark)){
                                     $FY=$rowSremark['FY'];
                                     $remark=$rowSremark['remark'];
                                     $overallgrade=$rowSremark['overall_grade'];
                                     $overallper=$rowSremark['overall_per'];
                                     $overallmarks=$rowSremark['overall_marks'];
                                     $examtype=$rowSremark['exam_type'];
                                     
                                     $getexamtype=mysqli_query($Con, "select distinct examtype from exam_master where exam_code='$examtype' or examtype='$examtype'");
                                     $getstuexamtype=mysqli_fetch_assoc($getexamtype);
                                     $studentexamtype = ($getstuexamtype && isset($getstuexamtype['examtype']) && $getstuexamtype['examtype'] != '') ? $getstuexamtype['examtype'] : $examtype;
                                     
                                     $ssclass=$rowSremark['sclass'];
                            ?>  
                                            <tr>
                                              <th class="text-center" scope="row"><?php echo $srno;?></th>
                                              <td><?php echo $ssclass;?></td>
                                              <td><?php echo htmlspecialchars($studentexamtype);?></td>
                                              <td><?php echo $overallmarks;?></td>
                                              <td><?php echo $overallper;?></td>
                                              <td><?php echo $overallgrade;?></td>
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
<?php } ?>
</div>

<?php
$chcekqueryremark=mysqli_query($Con, "SELECT distinct`FY` FROM `exam_marks` where `sadmission`='$sadmission'");
$fycountremark=mysqli_num_rows($chcekqueryremark);
if($fycountremark>0){
?> 

                    <div class="row" id="btnremark">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <!--<button type="button" class="btn btn-info"  onclick="showmoredataremark('<?php //echo $sadmission;?>')">More</button>-->
					            <button type="button" class="btn btn-primary" onclick="showMoreDataRemarks()">More</button>
					        </div>
					    </div>
					</div>
<?php } ?> 
<div id="show_admdataremark"></div>	

                    <div class="row" id="btnremarkless">
					    <div class="col-md-12">
					        <div class="text-right mt-1">
					            <button type="button" class="btn btn-info" onclick="showlessdata('remark')">Less</button>
					        </div>
					    </div>
					</div>
                  