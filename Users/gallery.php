<?php 
// Include the database connection and configuration files
require '../connection.php'; 
require '../AppConf.php';

// Start the session
session_start(); 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

// Handle AJAX request for album images
if(isset($_POST['action']) && $_POST['action'] === 'get_album_images' && isset($_POST['get_album'])) {
    $album_name = mysqli_real_escape_string($Con, $_POST['get_album']);
    $album_title = mysqli_real_escape_string($Con, $_POST['album_title']);
    
    $query = "SELECT photo_name, album_title 
              FROM gallery_album_photos 
              WHERE album_name = '$album_name' 
              AND album_title = '$album_title'
              AND status = '1' 
              ORDER BY srno ASC";
              
    $result = mysqli_query($Con, $query);
    
    if($result) {
        $images = array();
        while($row = mysqli_fetch_assoc($result)) {
            $images[] = array(
                'photo_name' => $row['photo_name'],
                'album_title' => $row['album_title']
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'images' => $images
        ));
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Database error: ' . mysqli_error($Con)
        ));
        exit;
    }
}

// Retrieve and sanitize session variables
$StudentName = isset($_SESSION['StudentName']) ? mysqli_real_escape_string($Con, $_SESSION['StudentName']) : '';
$class = isset($_SESSION['StudentClass']) ? mysqli_real_escape_string($Con, $_SESSION['StudentClass']) : '';
$sadmission = isset($_SESSION['userid']) ? mysqli_real_escape_string($Con, $_SESSION['userid']) : '';
$StudentRollNo = isset($_SESSION['StudentRollNo']) ? mysqli_real_escape_string($Con, $_SESSION['StudentRollNo']) : '';

// Initialize variables
$selectedYear = '';
$result = false;
$result_c = false;

// Handle form submission for year selection
if(isset($_POST['submit'])) {
    $selectedYear = isset($_POST['get_year']) ? mysqli_real_escape_string($Con, $_POST['get_year']) : '';

    // Validate selected year
    if(!empty($selectedYear)) {
        // Fetch albums from gallery_album_title table
        $sfinyr = "SELECT DISTINCT album_id, album_name, album_title, thumbnail_photo, financial_year 
                   FROM gallery_album_title 
                   WHERE financial_year='$selectedYear' AND status='1' 
                   ORDER BY financial_year DESC, album_id ASC";
        $result = mysqli_query($Con, $sfinyr);
        if(!$result) {
            die("Error fetching gallery albums: " . mysqli_error($Con));
        }
    }
}

// Fetch current active financial year if no year is selected
if(!isset($_POST['submit']) || empty($_POST['get_year'])) {
    $sfinyrc = "SELECT `year` FROM `FYmaster` WHERE `status`='Active' LIMIT 1";
    $yrresultc = mysqli_query($Con, $sfinyrc);
    if(!$yrresultc){
        die("Error fetching current financial year: " . mysqli_error($Con));
    }

    $current_year = '';
    if($rowfy1 = mysqli_fetch_assoc($yrresultc)) {
        $current_year = mysqli_real_escape_string($Con, $rowfy1['year']);
    }

    // Fetch albums from gallery_album_title table for current year
    $ssql_c = "SELECT DISTINCT album_id, album_name, album_title, thumbnail_photo, financial_year 
               FROM gallery_album_title 
               WHERE financial_year='$current_year' AND status='1' 
               ORDER BY financial_year DESC, album_id ASC";
    $result_c = mysqli_query($Con, $ssql_c);
    if(!$result_c) {    
        die("Error fetching gallery albums: " . mysqli_error($Con));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||Gallery</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
   <link rel="stylesheet" type="text/css" href="new-style.css">
    <link rel="stylesheet" type="text/css" href="../chart/Chart.min.css">
    <style>
.gallery-card {
    border-radius: 6px;
    border: 1px solid var(--Color-Stroke-Color, #E0E0E0);
    background: #FFF;
    margin-bottom: 25px;
}
.gallery-img {
    border-radius: 4px;
}
.gallery-img img.img-fluid { 
    border-radius: 4px 4px 0px 0px;
}
.gallery-content {
    padding: 15px;
}
.gallery-content h6 {
    color: #2A295C;;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}
.gallery-content p {
    color: rgba(42, 41, 92, 0.60);
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}
    </style>
</head>
<body>

    <?php include 'Header/header_new.php';?>
    
    <div class="page-wrapper chiller-theme">
        
        <?php include 'new_sidenav.php';?>
        
        <main class="page-content" style="margin-top:45px;">
           <div class="container-fluid page-border">
              <div class="row">
                 <div class="col-12 text-center bg-primary text-white">
                    <h4 class="m-t5"><i class="far fa-images"></i> School Gallery </h4>
                 </div>
                 
                 <div class="col">
                      <!-- Search Panel -->
                      <div class="card-padding-bottom p10">
                        <form name="frmGallery" id="frmGallery" method="post" action="gallery.php"> 
                             <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
    
                            <div class="row">
                               <div class="col-xl-3 col-lg-3 col-md-12">
                                  <div class="form-group">
                                     <label for="get_year">Select Year</label>
                                     <select class="form-control" name="get_year" id="get_year">
                                         <option value="">Select One</option>
                                         <?php
                                         // Generate years from 2001 to 2025
                                         for($year = 2022; $year <= 2025; $year++) {
                                             // Check if this year is selected
                                             $selected = ($year == $selectedYear || ($current_year == $year && empty($selectedYear))) ? 'selected' : '';
                                             echo "<option value=\"$year\" $selected>$year</option>";
                                         }
                                         ?>
                                     </select>
                                  </div>
                               </div>
                               <div class="col-xl-3 col-lg-3 col-md-12" style="margin-top:30px">
                                  <div class="form-group">
                                     <button type="submit" name="submit" class="btn btn-primary"> Search</button>
                                  </div>
                               </div>
                               <div class="col-xl-4 col-lg-4 col-md-12" style="margin-top:30px">
                                   <!-- Placeholder for additional content if needed -->
                               </div>
                               <div class="col-xl-2 col-lg-2 col-md-12" style="margin-top:30px">
                                   <!-- Placeholder for additional content if needed -->
                               </div>
                            </div>
                        </form>    
                      </div>
                      
                      <!-- Gallery Display -->
                      <div class="card-gallery">
                          <div class="row">
                              <?php
                              // Determine which result set to use based on year selection
                              if(isset($_POST['submit']) && !empty($_POST['get_year'])) {
                                  if(mysqli_num_rows($result) > 0){
                                      while($row = mysqli_fetch_assoc($result)) {
                                          $album_id = htmlspecialchars($row['album_id']);
                                          $album_name = htmlspecialchars($row['album_name']);
                                          $album_title = htmlspecialchars($row['album_title']);
                                          $ThumbNailImg = htmlspecialchars($row['thumbnail_photo']);
                                          $financial_year = htmlspecialchars($row['financial_year']);
                              ?>
                                  <div class="col-md-3 col-sm-6 col-12">
                                      <div class="gallery-card">
                                          <div class="gallery-img">
                                              <?php if(!empty($ThumbNailImg)): ?>
                                                  <img src="<?php echo $ThumbNailImg; ?>" alt="<?php echo $album_title; ?>" class="img-fluid" onclick="openExampleModal('<?php echo $album_name; ?>','<?php echo $album_title; ?>')">
                                              <?php else: ?>
                                                  <div class="no-image-container text-center p-4" onclick="openExampleModal('<?php echo $album_name; ?>','<?php echo $album_title; ?>')">
                                                      <i class="fas fa-image fa-3x text-muted"></i>
                                                      <p class="mt-2 text-muted">No Image Available</p>
                                                  </div>
                                              <?php endif; ?>
                                          </div>
                                          <div class="gallery-content">
                                              <h6><?php echo $album_title; ?></h6>
                                              <p class="mb-0"><i class="fas fa-calendar-alt"></i> <?php echo $financial_year; ?></p>
                                          </div>
                                      </div>
                                  </div>
                              <?php
                                      }
                                  }
                                  else{
                                      echo '<div class="col-12 text-center"><p>No albums found for the selected year.</p></div>';
                                  }
                              }
                              else {
                                  if(mysqli_num_rows($result_c) > 0){
                                      while($row_c = mysqli_fetch_assoc($result_c)) {
                                          $album_id = htmlspecialchars($row_c['album_id']);
                                          $album_name = htmlspecialchars($row_c['album_name']);
                                          $album_title = htmlspecialchars($row_c['album_title']);
                                          $ThumbNailImg = htmlspecialchars($row_c['thumbnail_photo']);
                                          $financial_year = htmlspecialchars($row_c['financial_year']);
                              ?>
                                  <div class="col-md-3 col-sm-6 col-12">
                                      <div class="gallery-card">
                                          <div class="gallery-img">
                                              <?php if(!empty($ThumbNailImg)): ?>
                                                  <img src="<?php echo $ThumbNailImg; ?>" alt="<?php echo $album_title; ?>" class="img-fluid" onclick="openExampleModal('<?php echo $album_name; ?>','<?php echo $album_title; ?>')">
                                              <?php else: ?>
                                                  <div class="no-image-container text-center p-4" onclick="openExampleModal('<?php echo $album_name; ?>','<?php echo $album_title; ?>')">
                                                      <i class="fas fa-image fa-3x text-muted"></i>
                                                      <p class="mt-2 text-muted">No Image Available</p>
                                                  </div>
                                              <?php endif; ?>
                                          </div>
                                          <div class="gallery-content">
                                              <h6><?php echo $album_title; ?></h6>
                                              <p class="mb-0"><i class="fas fa-calendar-alt"></i> <?php echo $financial_year; ?></p>
                                          </div>
                                      </div>
                                  </div>
                              <?php
                                      }
                                  }
                                  else{
                                      echo '<div class="col-12 text-center"><p>No active albums found.</p></div>';
                                  }
                              }
                              ?>
                          </div>
                      </div>
                   </div>
             
              </div>
              
              <!-- Bootstrap Modal for Gallery Images -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Gallery Images</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <!-- Carousel for displaying images -->
                      <div id="carouselExample" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner">
                          <!-- Images will be dynamically inserted here via AJAX -->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                        </a>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              
           </div>
        </main>
        <!--end page contents-->
    </div>
    
    </body>
    </html>
    
    <script>
    // JavaScript function to open the modal and show the clicked image
    function openExampleModal(album_name, album_title) {
        // Show the modal
        $("#exampleModal").modal('show');
        
        // Set the modal title
        $("#exampleModalLabel").text("Gallery: " + album_title);
        
        // Clear existing images in the carousel
        $("#carousel-inner").empty();
        
        // Show loading state
        $("#carousel-inner").append(
            '<div class="carousel-item active">' +
            '<div class="d-flex justify-content-center align-items-center" style="height: 400px;">' +
            '<div class="text-center">' +
            '<i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>' +
            '<p class="text-muted">Loading images...</p>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        
        // Fetch images for this album via AJAX
        $.ajax({
            url: 'gallery.php',
            type: 'POST',
            data: {
                action: 'get_album_images',
                get_album: album_name,
                album_title: album_title
            },
            success: function(response) {
                if(response.status === 'success' && response.images.length > 0) {
                    $("#carousel-inner").empty();
                    response.images.forEach(function(image, index) {
                        var isActive = index === 0 ? 'active' : '';
                        $("#carousel-inner").append(
                            '<div class="carousel-item ' + isActive + '">' +
                            '<img src="' + image.photo_name + '" class="d-block w-100" alt="' + image.album_title + '">' +
                            '<div class="carousel-caption d-none d-md-block">' +
                            '<h5>' + image.album_title + '</h5>' +
                            '</div>' +
                            '</div>'
                        );
                    });
                } else {
                    $("#carousel-inner").empty();
                    $("#carousel-inner").append(
                        '<div class="carousel-item active">' +
                        '<div class="d-flex justify-content-center align-items-center" style="height: 400px;">' +
                        '<div class="text-center">' +
                        '<i class="fas fa-image fa-3x text-muted mb-3"></i>' +
                        '<p class="text-muted">No Images Available</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                }
            },
            error: function() {
                $("#carousel-inner").empty();
                $("#carousel-inner").append(
                    '<div class="carousel-item active">' +
                    '<div class="d-flex justify-content-center align-items-center" style="height: 400px;">' +
                    '<div class="text-center">' +
                    '<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>' +
                    '<p class="text-danger">Error loading images</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        });
    }
    </script>
