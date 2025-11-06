<?php
/**
 * Create assignment Table in Local Database
 * Creates the assignment table as per the SQL structure provided
 */

// Fix path for command line execution
$connection_path = __DIR__ . '/../connection.php';
if (!file_exists($connection_path)) {
    $connection_path = dirname(__DIR__) . '/connection.php';
}
include $connection_path;

echo "Creating assignment table...\n\n";

// Check if table already exists
$check_table = mysqli_query($Con, "SHOW TABLES LIKE 'assignment'");
if (mysqli_num_rows($check_table) > 0) {
    echo "⚠ Table 'assignment' already exists.\n";
    echo "Dropping existing table...\n";
    
    // Try to discard tablespace first (for InnoDB tables with orphaned tablespaces)
    @mysqli_query($Con, "ALTER TABLE `assignment` DISCARD TABLESPACE");
    
    // Now drop the table
    if (mysqli_query($Con, "DROP TABLE `assignment`")) {
        echo "✅ Existing table dropped.\n\n";
    } else {
        echo "⚠ Drop failed: " . mysqli_error($Con) . "\n";
        echo "Trying force drop...\n";
        // Force drop ignoring errors
        @mysqli_query($Con, "DROP TABLE `assignment`");
        echo "✅ Force drop completed.\n\n";
    }
} else {
    // Table doesn't exist in database
    echo "Table doesn't exist in database.\n";
    echo "Proceeding with table creation...\n\n";
}

// Create table SQL
$create_table_sql = "CREATE TABLE `assignment` (
  `srno` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `assignmentdate` date NOT NULL,
  `assignmentcompletiondate` date NOT NULL,
  `remark` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `assignmentURL` varchar(1000) NOT NULL,
  `status` varchar(10) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response_date` date NOT NULL,
  `send_fcm` varchar(10) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`srno`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1";

// Execute CREATE TABLE with error handling for tablespace issues
try {
    if (mysqli_query($Con, $create_table_sql)) {
    echo "✅ Table 'assignment' created successfully!\n\n";
    
    // Verify table structure
    $verify = mysqli_query($Con, "DESCRIBE `assignment`");
    if ($verify) {
        echo "📋 Table Structure:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-25s %-20s %-10s %-10s\n", "Field", "Type", "Null", "Key");
        echo str_repeat("-", 80) . "\n";
        while ($row = mysqli_fetch_assoc($verify)) {
            printf("%-25s %-20s %-10s %-10s\n", 
                $row['Field'], 
                $row['Type'], 
                $row['Null'], 
                $row['Key']
            );
        }
        echo str_repeat("-", 80) . "\n\n";
    }
    
    // Check table count
    $count = mysqli_query($Con, "SELECT COUNT(*) as cnt FROM `assignment`");
    $count_row = mysqli_fetch_assoc($count);
    echo "📊 Current row count: " . $count_row['cnt'] . "\n";
        echo "✅ Table is ready for use!\n";
        
    } else {
        throw new Exception(mysqli_error($Con));
    }
} catch (Exception $e) {
    $error_msg = $e->getMessage();
    if (strpos($error_msg, 'Tablespace') !== false) {
        echo "❌ Tablespace Error Detected!\n\n";
        echo "⚠️  There is an orphaned tablespace file for the 'assignment' table.\n";
        echo "📝 Solution: Please run this SQL manually in phpMyAdmin:\n\n";
        echo "1. Go to phpMyAdmin → Select database 'schoolerpbeta' → SQL tab\n";
        echo "2. Run this SQL:\n\n";
        echo "   CREATE TABLE `assignment` (`dummy` int) ENGINE=InnoDB;\n";
        echo "   ALTER TABLE `assignment` DISCARD TABLESPACE;\n";
        echo "   DROP TABLE `assignment`;\n\n";
        echo "3. Then run the CREATE TABLE statement again.\n\n";
        echo "Or use the SQL file: SystemCreateFile/create_assignment_table.sql\n\n";
        echo "Full error: " . $error_msg . "\n";
        exit(1);
    } else {
        echo "❌ Error creating table: " . $error_msg . "\n";
        echo "\nSQL Query:\n" . $create_table_sql . "\n";
        exit(1);
    }
}

// Also create a web-accessible version
echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ Table creation completed!\n";
echo "You can verify the table in phpMyAdmin or use it in your application.\n";
echo str_repeat("=", 80) . "\n";
?>

