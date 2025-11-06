<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php
function fetch_data($arg)
{


  

	if (isset($arg)) 
	{
		$srno = $arg;
		
		//$q = "SELECT * FROM `IDCardInformation` WHERE sadmission = $srno and `status`='Active'";
                  $q = "SELECT * FROM `form_field_data` WHERE `sadmission` = '$srno'";
		$run=mysqli_query($Con, $q);
		$count = mysqli_num_rows($run);
		if ($count > 0) 
		{
			$data_arr = array();
			while ($obj = mysqli_fetch_array($run)) 
			{
			   $data_arr[] = $obj;
			}
			return json_encode($data_arr);
		}
	}
	
}





 ?>