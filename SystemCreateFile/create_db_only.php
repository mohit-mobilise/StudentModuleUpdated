<?php
/**
 * Simple script to create the database only
 * Port 3308 as shown in your phpMyAdmin
 */

$localhost_username = "root";
$localhost_password = ""; // XAMPP default
$database_name = "schoolerpbeta";

echo "<html><head><title>Create Database</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;}</style>";
echo "</head><body>";
echo "<h2>Create Database: $database_name</h2>";

// Try port 3308 first (as shown in your phpMyAdmin)
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    // Fallback to default port 3306
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<p class='error'>❌ Failed to connect to MySQL.<br>Error: " . mysqli_connect_error() . "<br><br>Please make sure MySQL is running in XAMPP Control Panel.</p>");
}

echo "<p class='success'>✅ Connected to MySQL server</p>";

// Create database
$create_db_query = "CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $create_db_query)) {
    echo "<p class='success'>✅ Database '$database_name' created successfully!</p>";
    echo "<p>📝 You can now refresh phpMyAdmin to see the database, or run the import script.</p>";
    echo "<p>🔗 <a href='import_database.php'>Click here to import the SQL file</a></p>";
} else {
    echo "<p class='error'>❌ Error creating database: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
echo "</body></html>";
?>

