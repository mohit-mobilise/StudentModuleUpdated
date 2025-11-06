<?php
/**
 * Create Transport Data for TEST001 User
 * Inserts test transport entries without changing code or database structure
 */

// Fix path for command line execution
$connection_path = __DIR__ . '/../connection.php';
if (!file_exists($connection_path)) {
    $connection_path = dirname(__DIR__) . '/connection.php';
}
include $connection_path;

echo "Creating transport data for TEST001 user...\n\n";

$admission_no = 'TEST001';

// Check if student exists
$check_student = mysqli_query($Con, "SELECT `sadmission` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_student) == 0) {
    die("ERROR: Student TEST001 does not exist. Please create the student first.\n");
}

echo "✓ Student TEST001 exists\n";

// Check if transport data already exists
$check_transport = mysqli_query($Con, "SELECT `sadmission` FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_transport) > 0) {
    echo "⚠ Transport data already exists for TEST001. Updating...\n";
    mysqli_query($Con, "DELETE FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
    echo "✓ Old transport data deleted\n";
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

echo "✓ Financial Year: $financial_year\n\n";

// Step 1: Create RouteMaster entries
echo "Step 1: Creating RouteMaster entries...\n";

$route_no_1 = 'R001';
$route_no_2 = 'R002';

// Check if routes already exist
$check_route1 = mysqli_query($Con, "SELECT `routeno` FROM `RouteMaster` WHERE `routeno` = '$route_no_1'");
$check_route2 = mysqli_query($Con, "SELECT `routeno` FROM `RouteMaster` WHERE `routeno` = '$route_no_2'");

if (mysqli_num_rows($check_route1) > 0) {
    echo "⚠ Route $route_no_1 already exists. Skipping...\n";
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
        echo "✓ Route $route_no_1 created\n";
    } else {
        echo "⚠ Error creating route $route_no_1: " . mysqli_error($Con) . "\n";
    }
}

if (mysqli_num_rows($check_route2) > 0) {
    echo "⚠ Route $route_no_2 already exists. Skipping...\n";
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
        echo "✓ Route $route_no_2 created\n";
    } else {
        echo "⚠ Error creating route $route_no_2: " . mysqli_error($Con) . "\n";
    }
}

echo "\n";

// Step 2: Create student_transport_detail entry
echo "Step 2: Creating student_transport_detail entry...\n";

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
    echo "✓ Transport detail created for TEST001\n";
} else {
    die("ERROR: Failed to create transport detail: " . mysqli_error($Con) . "\n");
}

// Step 3: Verify the data
echo "\nStep 3: Verifying data...\n";

$verify_transport = mysqli_query($Con, "SELECT `route_1`, `route_2`, `pick_up_stoppage`, `drop_stoppage` FROM `student_transport_detail` WHERE `sadmission` = '$admission_no'");
if ($verify_transport && mysqli_num_rows($verify_transport) > 0) {
    $transport_data = mysqli_fetch_assoc($verify_transport);
    echo "✓ Transport data verified:\n";
    echo "   Route 1: " . $transport_data['route_1'] . "\n";
    echo "   Route 2: " . $transport_data['route_2'] . "\n";
    echo "   Pick Up Stoppage: " . $transport_data['pick_up_stoppage'] . "\n";
    echo "   Drop Stoppage: " . $transport_data['drop_stoppage'] . "\n";
}

$verify_route1 = mysqli_query($Con, "SELECT `routeno`, `bus_no`, `driver_name` FROM `RouteMaster` WHERE `routeno` = '$route_no_1'");
if ($verify_route1 && mysqli_num_rows($verify_route1) > 0) {
    $route1_data = mysqli_fetch_assoc($verify_route1);
    echo "\n✓ Route 1 verified:\n";
    echo "   Route No: " . $route1_data['routeno'] . "\n";
    echo "   Bus No: " . $route1_data['bus_no'] . "\n";
    echo "   Driver: " . $route1_data['driver_name'] . "\n";
}

$verify_route2 = mysqli_query($Con, "SELECT `routeno`, `bus_no`, `driver_name` FROM `RouteMaster` WHERE `routeno` = '$route_no_2'");
if ($verify_route2 && mysqli_num_rows($verify_route2) > 0) {
    $route2_data = mysqli_fetch_assoc($verify_route2);
    echo "\n✓ Route 2 verified:\n";
    echo "   Route No: " . $route2_data['routeno'] . "\n";
    echo "   Bus No: " . $route2_data['bus_no'] . "\n";
    echo "   Driver: " . $route2_data['driver_name'] . "\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ Transport data created successfully!\n";
echo "You can now view the Transport page with data for TEST001 user.\n";
echo str_repeat("=", 80) . "\n";
?>

