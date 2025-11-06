<?php
include '../connection.php';

function edit_driver_detail()
{
if (isset($_POST['edit_admission'])) 
{
   
        $edit_admission = $_POST['edit_admission'];
    
        $sql="SELECT `srno`, `vehicle_type`,`vehicle_no`,`driver_name`,`driver_contact_no` ,`sadmission` FROM `student_master` WHERE `sadmission`='$edit_admission' ";

        $query = mysqli_query($Con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
                $data['srno']=$row['srno'];
                $data['vehicle_type']=$row['vehicle_type'];
                $data['vehicle_no']=$row['vehicle_no'];
                $data['driver_name']=$row['driver_name'];
                $data['driver_contact_no']=$row['driver_contact_no'];
                $data['sadmission']=$row['sadmission'];

        }



	echo json_encode($data);

}//if isset edit 
}


function update_driver_detail()
{
if (isset($_POST['ModalInput'])) 
{
   
        $ModalInput = $_POST['ModalInput'];
        $vehicle_type = $_POST['vehicle_type'];
        $vehicle_no = $_POST['vehicle_no'];
        $driver_name = $_POST['driver_name'];
        $driver_contact_no = $_POST['driver_contact_no'];
        $driver_admission = $_POST['driver_admission'];
        

    
    $sql=mysqli_query($Con, "Update `student_master` set `vehicle_type`= '$vehicle_type' , `vehicle_no`='$vehicle_no' , `driver_name`='$driver_name' , `driver_contact_no` ='$driver_contact_no'  WHERE `sadmission`='$driver_admission' and `srno`='$ModalInput' ");

        if(mysqli_affected_rows($Con)>0)
        {
        	echo json_encode(['status'=>true,'info'=>'Data Updated successfully' ]);
        }
        else
        {
          echo json_encode(['status'=>false,'info'=>'Not Updated']);
        }	

       


	

}//if isset edit 
}


edit_driver_detail();
update_driver_detail();