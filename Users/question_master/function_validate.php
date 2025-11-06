<?php
 include '../../connection.php';

function update_btn()

{

    if(isset($_POST['get_vendor_submit_btn_new']))

    {

        $app_id = $_POST['app_id'];
        $vendor_code = $_POST['user_id'];
        $teacher_name = $_POST['teacher_name'];
        $subject_name = $_POST['subject_name'];
              
 
        $sql=mysqli_query($Con, "SELECT `vendor_id`, `EmpId`, `Emp_Subject`, `app_id`  FROM `vendor_ques_response` WHERE `vendor_id`='$teacher_name' and  `app_id`='$app_id' and `EmpId`='$vendor_code' and `Emp_Subject`='$subject_name'");
        if(mysqli_num_rows($sql)==0){
            
               echo json_encode(['status' => true]);
        }
        else
        {
               echo json_encode(['status' => false]);
               
        }
                     
               
  
         
     

    }

}


switch(!empty($_POST))
{
  case !empty($_POST['get_vendor_submit_btn_new']):     
    update_btn();    
        break;    

     default:
         break;
     
}


?>
