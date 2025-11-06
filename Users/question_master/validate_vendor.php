<?php
 include '../../connection.php';

function get_vendor_submit_question()
{
   $app_id = $_POST['app_id'];
   $vendor_code = $_POST['user_id'];
   $teacher_name = $_POST['teacher_name'];
   $subject_name = $_POST['subject_name'];
   $quest_ans_display = "yes";
   
                    

$html='';

   $sqlcategoryId = mysqli_query($Con, "SELECT `category_id` FROM `master_ques_category` WHERE `app_id`='$app_id'");
    while($row=mysqli_fetch_assoc($sqlcategoryId)){

     $category_id = $row['category_id'];


  $sqlcategory = mysqli_query($Con, "SELECT `Category_desc` FROM `master_ques_category` WHERE `category_id`='$category_id'");
  $rscategory = mysqli_fetch_assoc($sqlcategory);
  $category = $rscategory['Category_desc'];

 $html .='<tr><th colspan="3" style="background-color:#333333;color:white;">Category: '.$category.'</th></tr>';


   $question_query = mysqli_query($Con, "SELECT `srno`, `department_code`, `category_id`, `subject_id`, `topic_id`, `ques_id`, `ques_desc`, `ques_type`, `max_score`, `ref_id`, `app_id`, `plant_code`, `created_by`, `status`, `image_url`, `video_url`, `audio_url`, `modal_answer`, `is_mandatory` FROM `master_ques` WHERE `category_id`='$category_id' and `app_id`='$app_id'  and `status`='Active'  ");
        $qcount = mysqli_num_rows($question_query);
        

        if ($qcount > 0) 
        {       
                
                while ($row = mysqli_fetch_assoc($question_query)) 
                {
                    $ref_id = $row['ref_id'];
                    $ques_type = $row['ques_type'];
                    $ques_id_vendor = $row['ques_id'];

                     $vendorresponse='';
                     $vendorresponseanswer='';
                     $vendorques_type='';
                     $response='';
                     $chr = substr($vendor_code,0,1);
                    if($chr=='M'){
                        $sqlquesrespvendor = mysqli_query($Con, "SELECT `vqr`.`ques_response`,`mqo`.`options`,`mq`.`ques_type` FROM `vendor_ques_response` as `vqr` LEFT JOIN `master_ques` as `mq` ON (`vqr`.`ques_id`=`mq`.`ques_id`) LEFT JOIN `master_ques_options` as `mqo` on (`mqo`.`ref_id`=`mq`.`ref_id` && `mqo`.`options_value`=`vqr`.`ques_response`)  WHERE `vqr`.`ques_id`='$ques_id_vendor' and `vqr`.`vendor_id`='$teacher_name' and `vqr`.`updated_by`='$vendor_code'  and `vqr`.`app_id`='$app_id' and `vqr`.`Emp_Subject`='$subject_name' and `vqr`.vendor_type='employee'");
                    } 
                    else{
                       $sqlquesrespvendor = mysqli_query($Con, "SELECT `vqr`.`ques_response`,`mqo`.`options`,`mq`.`ques_type` FROM `vendor_ques_response` as `vqr` LEFT JOIN `master_ques` as `mq` ON (`vqr`.`ques_id`=`mq`.`ques_id`) LEFT JOIN `master_ques_options` as `mqo` on (`mqo`.`ref_id`=`mq`.`ref_id` && `mqo`.`options_value`=`vqr`.`ques_response`)  WHERE `vqr`.`ques_id`='$ques_id_vendor' and `vqr`.`vendor_id`='$teacher_name' and `vqr`.`updated_by`='$vendor_code'  and `vqr`.`app_id`='$app_id' and `vqr`.`Emp_Subject`='$subject_name' and `vqr`.vendor_type='student'");  
                    }
                     
                     $rowquesrespvendor = mysqli_fetch_assoc($sqlquesrespvendor);
                     $vendorresponse = $rowquesrespvendor['options'];
                     $vendorresponseanswer = $rowquesrespvendor['ques_response'];
                     $vendorques_type = $rowquesrespvendor['ques_type'];


                    if ($ques_type == 'text') { 

                        $response = $vendorresponseanswer;
                        
                        $td='<input type="text" name="submit_rate_id[]" class="form-control" value="'.$response.'"><input type="hidden" name="parameter_id1[]" value="'.$row['ques_id'].'">';                        
                          }
                          else if ($ques_type == 'date') { 

                        $response = date('Y-m-d', strtotime($vendorresponseanswer));
                  
                        $td ='<input type="date" name="submit_rate_id[]" class="form-control" value="'.$response.'"><input type="hidden" name="parameter_id1[]" value="'.$row['ques_id'].'">';   
                          }
                          else if ($ques_type == 'email') { 

                        $response = $vendorresponseanswer;

                           $td ='<input type="email" name="submit_rate_id[]" class="form-control" value="'.$response.'"><input type="hidden" name="parameter_id1[]" value="'.$row['ques_id'].'">';
                        
                          }
                          else if ($ques_type == 'number') { 

                        $response = $vendorresponseanswer;

                        $td ='<input type="number" name="submit_rate_id[]" class="form-control" value="'.$response.'"><input type="hidden" name="parameter_id1[]" value="'.$row['ques_id'].'">';

                             
                          }
                          else if ($ques_type == 'file') { 
                        
                            if ($vendorresponseanswer != '') 
                            {
                               $response = '<a href="downloadfile.php?file_name='.$vendorresponseanswer.'">download</a>';

                               $td =$response.'&nbsp;<input type="file" name="submit_rate_id_doc[]" class="form-control"><input type="hidden" name="parameter_id1_doc[]" value="'.$row['ques_id'].'">';

                            } 
                            else
                            {
                               $td ='<input type="file" name="submit_rate_id_doc[]" class="form-control"><input type="hidden" name="parameter_id1_doc[]" value="'.$row['ques_id'].'">';
                            }
                        
                              
                          }
                          else if ($ques_type == 'option') { 

                        $response = $vendorresponseanswer;

                            $td ='<select name="submit_rate_id[]" class="form-control vendor_rate_select" >
                              <option value="">Select One</option>';
                            
                            $fire = mysqli_query($Con, "SELECT `options`,`options_value` FROM `master_ques_options` WHERE `ref_id` = '$ref_id'");

                               if (mysqli_num_rows($fire) > 0) 
                               {
                                    while ($rows = mysqli_fetch_assoc($fire)) 
                                    {

                                      $options_value = $rows["options_value"];
                                   
                                 if ($response == $options_value) {
                                  
                                 $td .='<option class="myselectques" value="'.$options_value.'" selected >'.$rows["options"].'</option>';

                                 }
                                 else
                                 {
                                
                                 $td .='<option class="myselectques" value="'.$options_value.'">'.$rows["options"].'</option>';

                                 }
                                     
                                    }


                                    
                                    mysql_free_result($fire);

                                }
                                
                            
                              $td .='</select><input type="hidden" name="parameter_id1[]" value="'.$row['ques_id'].'">';
                           
                          
                          }
                          else
                          {
                              $td='';
                          }
                       

                if ($quest_ans_display == 'yes') 
                {
                    $html .='<tr>
                          <td><h5>'.$row['ques_desc'].'</h5></td>
                          <td>'.$td.'</td>
                         </tr>';
                } 
                
                if ($quest_ans_display == 'no') {
                   $html .='<tr>
                          <td><h5>'.$row['ques_desc'].'</h5></td>
                         </tr>'; 
                }

                       
                    
                    
    
                
                }
           
   

           
            
        }
        else
        {
           $html .= "<tr><td colspan='2'>Question not found</td></tr>";  

        }


 }

 echo $html;


}


function UpdateSubmitQuestion()
    {
if ( isset($_POST['update_submit_question']) )

{

      date_default_timezone_set("Asia/Kolkata");
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $teacher_name   = $_POST['teacher_name'];
        $subject_name   = $_POST['subject_name'];
        $current_class   = $_POST['class_name'];
        $app_id1   = $_POST['app_id1'];
        $loggedin_user   = $_POST['user_id1'];
          
        $vendor_code  = $_POST['vendor_code'];

                
         if ((sizeof($_POST['submit_rate_id']) > 0)) {
                   
            for ($i = 0; $i < sizeof($_POST['submit_rate_id']); $i++) {
 
                $answer = $_POST['submit_rate_id'][$i];
                
                if ($answer == '') {
                 
                 echo json_encode(['status' => false, 'info' => 'Questionnaire is mandatory']);exit();
                }
               

            } 
        } 

        
         if (sizeof($_POST['submit_rate_id']) > 0) {
                    

                    for ($i = 0; $i < sizeof($_POST['submit_rate_id']); $i++) {

                        $parameter_id   = $_POST['parameter_id1'][$i];
                        $rating         = $_POST['submit_rate_id'][$i];
                        $vendorcode     = $vendor_code;
                        $updateby       = $vendor_code;
                        $assess_id      = "1";

                $rschk = mysqli_query($Con, "SELECT * FROM `vendor_ques_response` WHERE `vendor_id`='$teacher_name' and `updated_by`='$updateby' and `ques_id`='$parameter_id' and `assess_id`='$assess_id' and `EmpId` ='$vendorcode' and  `Emp_Subject`='$subject_name' and `sclass`='$current_class' and `app_id`='$app_id1'");
                  

                if (mysqli_num_rows($rschk) == 0) 
                {
                    $chr = substr($loggedin_user,0,1);
              
                    if($chr=='M'){
                    
                    mysqli_query($Con, "INSERT INTO `vendor_ques_response`(`vendor_id`, `ques_id`, `ques_response`, `response_date`, `response_time`,`updated_by`,`assess_id`, `EmpId`, `Emp_Subject`,`sclass`,`app_id`, `vendor_type`) VALUES ('$teacher_name' ,'$parameter_id','$rating','$current_date','$current_time','$updateby','$assess_id', '$vendorcode','$subject_name' ,'$current_class','$app_id1','employee')");
                    } 
                    else
                    {
                       mysqli_query($Con, "INSERT INTO `vendor_ques_response`(`vendor_id`, `ques_id`, `ques_response`, `response_date`, `response_time`,`updated_by`,`assess_id`, `EmpId`, `Emp_Subject`,`sclass`,`app_id`, `vendor_type`) VALUES ('$teacher_name' ,'$parameter_id','$rating','$current_date','$current_time','$updateby','$assess_id', '$vendorcode','$subject_name' ,'$current_class','$app_id1','student')");   
                    }
                }
                else
                {
                    $chr = substr($loggedin_user,0,1);
                    if($chr=='M'){
                        mysqli_query($Con, "UPDATE `vendor_ques_response` SET `ques_response`='$rating', `response_date`='$current_date', `response_time`='$current_time' WHERE `vendor_id`='$teacher_name' and `ques_id`='$parameter_id' and `updated_by`='$updateby' and `assess_id`='$assess_id' and  `EmpId` ='$vendorcode' and  `Emp_Subject`='$subject_name' and `sclass`='$current_class' and `app_id`='$app_id1'  and `vendor_type`='employee' ");
                    }
                    else
                    {
                           mysqli_query($Con, "UPDATE `vendor_ques_response` SET `ques_response`='$rating', `response_date`='$current_date', `response_time`='$current_time' WHERE `vendor_id`='$teacher_name' and `ques_id`='$parameter_id' and `updated_by`='$updateby' and `assess_id`='$assess_id' and  `EmpId` ='$vendorcode' and  `Emp_Subject`='$subject_name' and `sclass`='$current_class' and `app_id`='$app_id1'  and `vendor_type`='student' ");
                    }
                }



                    } //end of for loop
                } //end of if question
             




               
       

        if (mysqli_affected_rows($Con) > 0) {
                        
                 echo json_encode(['status' => true, "info" => 'Question Submitted Sucessfully']);
            } else {
                 echo json_encode( ['status' => false, "info" => 'Question not submitted!']);
            }


  
                
}    

    } //end of function  


function subject()

{

    if(isset($_POST['teacher_name']))

    {

            $teacher_name=$_POST['teacher_name'];
            $class_name=$_POST['class_name'];
            $chr = substr($teacher_name,0,1);
              
                if($chr=='M'){
                     $query = "SELECT distinct `SubjectAssigned` FROM `feedback_form_mapping` WHERE `EmpID`='$teacher_name' ";
                     
                }
                else
                {
                   $query = "SELECT distinct `SubjectAssigned` FROM `feedback_form_mapping` WHERE `EmpID`='$teacher_name' and `Class`='$class_name' ";
          
                }
  
         
          $data = array();
      $sql = mysqli_query($Con, $query);
            if (mysqli_num_rows($sql) > 0) {
               while ($row = mysqli_fetch_assoc($sql)) {
                $data[] = $row;
              }
            }
            else{
              $data = array();
            }
         


          echo json_encode($data);

    }

}



switch(!empty($_POST))
{
 case !empty($_POST['get_vendor_submit_question']):     
    get_vendor_submit_question();    
      break; 
 
 case !empty($_POST['update_submit_question']):     
    UpdateSubmitQuestion();    
      break; 

 case !empty($_POST['teacher_name']):     
    subject();    
      break;    
      
//   case !empty($_POST['get_vendor_submit_btn_new']):     
//     update_btn();    
//         break;    

     default:
         break;
     
}


?>