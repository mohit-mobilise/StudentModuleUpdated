<?php
/**
 * Create Transport Data for TEST001 User (Web Version)
 * Inserts test transport entries without changing code or database structure
 */

include '../connection.php';

echo "<html><head><title>Create Transport Data</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    h1{color:#007bff;}
    .box{border:2px solid #007bff;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
    table{border-collapse:collapse;width:100%;margin:20px 0;}
    th,td{border:1px solid #ddd;padding:8px;text-align:left;}
    th{background-color:#007bff;color:white;}
    tr:nth-child(even){background-color:#f2f2f2;}
</style>";
echo "</head><body>";

echo "<h1>🚌 Create Transport Data for TEST001 User</h1>";

$admission_no = 'TEST001';

// Check if student exists
$check_student = mysqli_query($Con, "SELECT `sadmission` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_student) == 0) {
    die("<div class='error'><h3>❌ Student Not Found</h3><p>Student TEST001 does not exist. Please create the student first.</p></div></body></html>");
}

echo "<div class='success'>✅ Student TEST001 exists</div>";

// Check if transport data already exists
$check_transport = mysqli_query($Con, "SELECT `sadmission` FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_transport) > 0) {
    echo "<div class='warning'><h3>⚠️ Transport Data Already Exists</h3>";
    echo "<p>Transport data already exists for TEST001. Updating...</p></div>";
    mysqli_query($Con, "DELETE FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
    echo "<div class='info'>✅ Old transport data deleted</div>";
}

// Get current financial year if available
$financial_year = '';
$fy_query = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status` = 'Active' LIMIT 1");
if ($fy_query && mysqli_num_rows($fy_query) > 0) {
    $fy_row = mysqli_fetch_assoc($fy_query);
    $financial_year = $fy_row['year'];
} else {
    $financial_year = date('Y');
}

echo "<div class='info'><strong>Financial Year:</strong> $financial_year</div>";

// Step 1: Create RouteMaster entries
echo "<div class='box'><h2>Step 1: Creating RouteMaster Entries</h2>";

$route_no_1 = 'R001';
$route_no_2 = 'R002';

// Check if routes already exist
$check_route1 = mysqli_query($Con, "SELECT `routeno` FROM `RouteMaster` WHERE `routeno` = '$route_no_1'");
$check_route2 = mysqli_query($Con, "SELECT `routeno` FROM `RouteMaster` WHERE `routeno` = '$route_no_2'");

$route1_created = false;
$route2_created = false;

if (mysqli_num_rows($check_route1) > 0) {
    echo "<div class='warning'>⚠️ Route $route_no_1 already exists. Skipping...</div>";
    $route1_created = true;
} else {
    $insert_route1 = "INSERT INTO `RouteMaster` (
        `routeno`,
        `bus_no`,
        `timing`,
        `driver_name`,
        `driver_mobile`,
        `UserId`,
        `Password`,
        `in_bus_timing`,
        `out_bus_timing`,
        `datetime`,
        `routecharges`,
        `route_details`,
        `financialyear`,
        `route_slab`,
        `attendant_name`,
        `attendant_mobile`,
        `teacher_name`,
        `teacher_mobile`
    ) VALUES (
        '$route_no_1',
        'BUS-001',
        'Morning',
        'John Driver',
        '9876543210',
        'gpsuser1',
        'gps123',
        '07:30:00',
        '14:30:00',
        NOW(),
        '5000',
        'Route 1 - Main Campus',
        '$financial_year',
        '1',
        'Mary Attendant',
        '9876543211',
        'Mr. Smith Teacher',
        '9876543212'
    )";
    
    if (mysqli_query($Con, $insert_route1)) {
        echo "<div class='success'>✅ Route $route_no_1 created</div>";
        $route1_created = true;
    } else {
        echo "<div class='error'>❌ Error creating route $route_no_1: " . mysqli_error($Con) . "</div>";
    }
}

if (mysqli_num_rows($check_route2) > 0) {
    echo "<div class='warning'>⚠️ Route $route_no_2 already exists. Skipping...</div>";
    $route2_created = true;
} else {
    $insert_route2 = "INSERT INTO `RouteMaster` (
        `routeno`,
        `bus_no`,
        `timing`,
        `driver_name`,
        `driver_mobile`,
        `UserId`,
        `Password`,
        `in_bus_timing`,
        `out_bus_timing`,
        `datetime`,
        `routecharges`,
        `route_details`,
        `financialyear`,
        `route_slab`,
        `attendant_name`,
        `attendant_mobile`,
        `teacher_name`,
        `teacher_mobile`
    ) VALUES (
        '$route_no_2',
        'BUS-002',
        'Evening',
        'Jane Driver',
        '9876543220',
        'gpsuser2',
        'gps456',
        '15:00:00',
        '18:00:00',
        NOW(),
        '5000',
        'Route 2 - Secondary Campus',
        '$financial_year',
        '1',
        'Peter Attendant',
        '9876543221',
        'Mrs. Johnson Teacher',
        '9876543222'
    )";
    
    if (mysqli_query($Con, $insert_route2)) {
        echo "<div class='success'>✅ Route $route_no_2 created</div>";
        $route2_created = true;
    } else {
        echo "<div class='error'>❌ Error creating route $route_no_2: " . mysqli_error($Con) . "</div>";
    }
}

echo "</div>";

// Step 2: Create student_transport_detail entry
echo "<div class='box'><h2>Step 2: Creating Student Transport Detail</h2>";

$insert_transport = "INSERT INTO `student_transport_detail` (
    `sadmission`,
    `route_1`,
    `route_2`,
    `pick_up_stoppage`,
    `drop_stoppage`
) VALUES (
    '$admission_no',
    '$route_no_1',
    '$route_no_2',
    'Main Gate Stop',
    'School Gate Stop'
)";

if (mysqli_query($Con, $insert_transport)) {
    echo "<div class='success'>✅ Transport detail created for TEST001</div>";
} else {
    echo "<div class='error'>❌ Failed to create transport detail: " . mysqli_error($Con) . "</div>";
    echo "</div></body></html>";
    exit;
}

echo "</div>";

// Step 3: Verify the data
echo "<div class='box'><h2>Step 3: Verification</h2>";

$verify_transport = mysqli_query($Con, "SELECT `route_1`, `route_2`, `pick_up_stoppage`, `drop_stoppage` FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
if ($verify_transport && mysqli_num_rows($verify_transport) > 0) {
    $transport_data = mysqli_fetch_assoc($verify_transport);
    echo "<div class='success'><h3>✅ Transport Data Verified</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td><strong>Route 1</strong></td><td>" . htmlspecialchars($transport_data['route_1']) . "</td></tr>";
    echo "<tr><td><strong>Route 2</strong></td><td>" . htmlspecialchars($transport_data['route_2']) . "</td></tr>";
    echo "<tr><td><strong>Pick Up Stoppage</strong></td><td>" . htmlspecialchars($transport_data['pick_up_stoppage']) . "</td></tr>";
    echo "<tr><td><strong>Drop Stoppage</strong></td><td>" . htmlspecialchars($transport_data['drop_stoppage']) . "</td></tr>";
    echo "</table>";
    echo "</div>";
}

$verify_route1 = mysqli_query($Con, "SELECT `routeno`, `bus_no`, `driver_name`, `driver_mobile`, `attendant_name`, `teacher_name`, `in_bus_timing`, `out_bus_timing` FROM `RouteMaster` WHERE `routeno` = '$route_no_1'");
if ($verify_route1 && mysqli_num_rows($verify_route1) > 0) {
    $route1_data = mysqli_fetch_assoc($verify_route1);
    echo "<div class='info'><h3>Route 1 Details</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td><strong>Route No</strong></td><td>" . htmlspecialchars($route1_data['routeno']) . "</td></tr>";
    echo "<tr><td><strong>Bus No</strong></td><td>" . htmlspecialchars($route1_data['bus_no']) . "</td></tr>";
    echo "<tr><td><strong>Driver Name</strong></td><td>" . htmlspecialchars($route1_data['driver_name']) . "</td></tr>";
    echo "<tr><td><strong>Driver Mobile</strong></td><td>" . htmlspecialchars($route1_data['driver_mobile']) . "</td></tr>";
    echo "<tr><td><strong>Attendant Name</strong></td><td>" . htmlspecialchars($route1_data['attendant_name']) . "</td></tr>";
    echo "<tr><td><strong>Teacher Name</strong></td><td>" . htmlspecialchars($route1_data['teacher_name']) . "</td></tr>";
    echo "<tr><td><strong>In Time</strong></td><td>" . htmlspecialchars($route1_data['in_bus_timing']) . "</td></tr>";
    echo "<tr><td><strong>Out Time</strong></td><td>" . htmlspecialchars($route1_data['out_bus_timing']) . "</td></tr>";
    echo "</table>";
    echo "</div>";
}

$verify_route2 = mysqli_query($Con, "SELECT `routeno`, `bus_no`, `driver_name`, `driver_mobile`, `attendant_name`, `teacher_name`, `in_bus_timing`, `out_bus_timing` FROM `RouteMaster` WHERE `routeno` = '$route_no_2'");
if ($verify_route2 && mysqli_num_rows($verify_route2) > 0) {
    $route2_data = mysqli_fetch_assoc($verify_route2);
    echo "<div class='info'><h3>Route 2 Details</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td><strong>Route No</strong></td><td>" . htmlspecialchars($route2_data['routeno']) . "</td></tr>";
    echo "<tr><td><strong>Bus No</strong></td><td>" . htmlspecialchars($route2_data['bus_no']) . "</td></tr>";
    echo "<tr><td><strong>Driver Name</strong></td><td>" . htmlspecialchars($route2_data['driver_name']) . "</td></tr>";
    echo "<tr><td><strong>Driver Mobile</strong></td><td>" . htmlspecialchars($route2_data['driver_mobile']) . "</td></tr>";
    echo "<tr><td><strong>Attendant Name</strong></td><td>" . htmlspecialchars($route2_data['attendant_name']) . "</td></tr>";
    echo "<tr><td><strong>Teacher Name</strong></td><td>" . htmlspecialchars($route2_data['teacher_name']) . "</td></tr>";
    echo "<tr><td><strong>In Time</strong></td><td>" . htmlspecialchars($route2_data['in_bus_timing']) . "</td></tr>";
    echo "<tr><td><strong>Out Time</strong></td><td>" . htmlspecialchars($route2_data['out_bus_timing']) . "</td></tr>";
    echo "</table>";
    echo "</div>";
}

echo "<div class='success'><h3>✅ Transport Data Created Successfully!</h3>";
echo "<p>You can now view the Transport page with data for TEST001 user.</p>";
echo "<p><a href='../Users/Transport.php' target='_blank' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>View Transport Page</a></p>";
echo "</div>";

echo "</div></body></html>";
?>

