<?php include '../connection.php';?>
 <?php
 function album()
 {

	if(isset($_POST['get_album']))
	{
		$cnt=1;
		$html='';
		$album_id1=$_POST['get_album'];
		$ssql1="select `photo_name` from `gallery_album_photos` where `album_id`='$album_id1'";

        if (!$result1 = mysqli_query($Con, $ssql1));
        while($row1 = mysqli_fetch_assoc($result1))
        {
       		
       	    $photo_name=$row1['photo_name'];

       	    if($cnt == 1) {

       	    $html ='<div class="carousel-item active">
       	    <a href= "'.$photo_name.'" download>
              <img class="d-block w-100 image" src="'.$photo_name.'"  height="400px"  >
            </a>
            </div>';

       	    } else {

            $html .='<div class="carousel-item">
             <a href= "'.$photo_name.'" download>
              <img class="d-block w-100" src="'.$photo_name.'" height="400px" >
            </a>
            </div>';

       	    }

       	    $cnt++;
        }


echo $html;
       	// echo json_encode($data);
	}	

 }

album();
?>