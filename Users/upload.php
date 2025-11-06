<?php 
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

include '../../connection.php';
?>

<?php
function std_img_upload()
{
	global $Con;
	
	if (isset($_POST['image'])) 
	{
		// Validate and sanitize inputs
		$croped_image = $_POST['image'] ?? '';
		$imguploadfor = validate_input($_POST['imguploadfor'] ?? '', 'string', 1);
		$id = validate_input($_POST['adm'] ?? '', 'string', 50);
		
		// Validate allowed upload types
		$allowed_types = ['S', 'F', 'M', 'G', 'D', 'E'];
		if (!in_array($imguploadfor, $allowed_types)) {
			echo "Invalid upload type";
			return;
		}
		
		// Validate base64 image format
		if (strpos($croped_image, 'data:image') !== 0) {
			echo "Invalid image format";
			return;
		}
		
		// Parse base64 data
		$parts = explode(';', $croped_image);
		if (count($parts) < 2) {
			echo "Invalid image data";
			return;
		}
		
		$data_parts = explode(',', $parts[1]);
		if (count($data_parts) < 2) {
			echo "Invalid image data";
			return;
		}
		
		$croped_image = base64_decode($data_parts[1]);
		
		// Validate decoded image
		if ($croped_image === false || empty($croped_image)) {
			echo "Invalid image data";
			return;
		}
		
		// Validate image content using getimagesize
		$image_info = @getimagesizefromstring($croped_image);
		if ($image_info === false) {
			echo "Invalid image file";
			return;
		}
		
		// Check image size (max 5MB)
		if (strlen($croped_image) > 5242880) {
			echo "Image size exceeds 5MB limit";
			return;
		}
		
		// Generate secure filename (prevent path traversal)
		$safe_id = preg_replace('/[^a-zA-Z0-9_-]/', '', $id); // Remove any path traversal characters
		$image_name = $safe_id . '-' . $imguploadfor . '.png';
		
		// Define upload paths
		$upload_path = '';
		if ($imguploadfor == 'S') {
			$upload_path = '../Admin/StudentManagement/StudentPhotos/' . $image_name;
		}
		else if ($imguploadfor == 'F' || $imguploadfor == 'M') {
			$upload_path = '../Admin/StudentManagement/StudentParentPhoto/' . $image_name;
		}
		else if ($imguploadfor == 'G' || $imguploadfor == 'D' || $imguploadfor == 'E') {
			$upload_path = '../Admin/StudentManagement/StudentDocuments/' . $image_name;
		}
		else {
			echo 'Invalid upload type';
			return;
		}
		
		// Validate path doesn't contain directory traversal
		$real_path = realpath(dirname($upload_path));
		$base_path = realpath('../Admin/StudentManagement/');
		if (strpos($real_path, $base_path) !== 0) {
			echo 'Invalid upload path';
			return;
		}
		
		// Upload file
		if (file_put_contents($upload_path, $croped_image) !== false) {
			echo $image_name;
		} else {
			echo 'Failed to upload image';
		}
	}
	else
	{
		echo "Not Uploaded";
	}
}


function search_bus(){
	global $Con;
	
	if (isset($_POST['search_bus'])) {
		// Use prepared statement (though this query has no user input, it's good practice)
		$q = mysqli_query($Con, "SELECT DISTINCT (`routeno`) FROM `RouteMaster`");
		if ($q) {
			$html = '';
			$html .= '<select name="mode_selection_type" id="mode_selection_type" class="form-control">'; 
			while ($row = mysqli_fetch_assoc($q)) {
				if ($row['routeno'] != 'VAN' && $row['routeno'] != 'SELF') {
					// Safe output to prevent XSS
					$html .= '<option value="' . safe_output($row['routeno']) . '">' . safe_output($row['routeno']) . '</option>';
				}
			}
			$html .='</select>';
			echo $html;
		}
	}
}




switch ($_POST) {
	case isset($_POST['image']):
		std_img_upload();
		break;

case isset($_POST['search_bus']):
		search_bus();
		break;

	default:
		# code...
		break;
}


?>