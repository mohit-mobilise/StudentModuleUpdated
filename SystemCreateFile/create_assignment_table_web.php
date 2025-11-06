<?php
/**
 * Create assignment Table in Local Database (Web Version)
 * Creates the assignment table as per the SQL structure provided
 */

include '../connection.php';

echo "<html><head><title>Create assignment Table</title>";
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

echo "<h1>📋 Create assignment Table</h1>";

// Check if table already exists
$check_table = mysqli_query($Con, "SHOW TABLES LIKE 'assignment'");
if (mysqli_num_rows($check_table) > 0) {
    echo "<div class='warning'><h3>⚠️ Table Already Exists</h3>";
    echo "<p>The <code>assignment</code> table already exists in the database.</p>";
    echo "<p>Dropping existing table to recreate...</p>";
    echo "</div>";
    
    // Try to drop table normally first
    if (!mysqli_query($Con, "DROP TABLE IF EXISTS `assignment`")) {
        echo "<div class='info'>⚠️ Standard DROP failed, trying with tablespace handling...</div>";
        // If tablespace exists, we need to discard it first for InnoDB
        @mysqli_query($Con, "ALTER TABLE `assignment` DISCARD TABLESPACE");
        mysqli_query($Con, "DROP TABLE IF EXISTS `assignment`");
    }
    
    if (mysqli_query($Con, "DROP TABLE IF EXISTS `assignment`") || mysqli_query($Con, "SHOW TABLES LIKE 'assignment'") && mysqli_num_rows(mysqli_query($Con, "SHOW TABLES LIKE 'assignment'")) == 0) {
        echo "<div class='success'>✅ Existing table dropped successfully.</div>";
    } else {
        echo "<div class='error'>❌ Error dropping table: " . mysqli_error($Con) . "</div>";
        echo "<div class='info'>💡 Try dropping the table manually in phpMyAdmin first, then run this script again.</div>";
        echo "</body></html>";
        exit;
    }
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

// Execute CREATE TABLE
if (mysqli_query($Con, $create_table_sql)) {
    echo "<div class='success'><h3>✅ Table 'assignment' Created Successfully!</h3></div>";
    
    // Verify table structure
    $verify = mysqli_query($Con, "DESCRIBE `assignment`");
    if ($verify) {
        echo "<div class='box'><h2>📋 Table Structure</h2>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = mysqli_fetch_assoc($verify)) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra'] ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    
    // Check table count
    $count = mysqli_query($Con, "SELECT COUNT(*) as cnt FROM `assignment`");
    $count_row = mysqli_fetch_assoc($count);
    echo "<div class='info'><strong>Current row count:</strong> " . $count_row['cnt'] . "</div>";
    
    echo "<div class='success'><h3>✅ Table is Ready for Use!</h3>";
    echo "<p>The <code>assignment</code> table has been created successfully in your local database.</p>";
    echo "<p>You can now use this table in your application.</p>";
    echo "</div>";
    
} else {
    echo "<div class='error'><h3>❌ Error Creating Table</h3>";
    echo "<p>Error: " . mysqli_error($Con) . "</p>";
    echo "<p><strong>SQL Query:</strong></p>";
    echo "<pre><code>" . htmlspecialchars($create_table_sql) . "</code></pre>";
    echo "</div>";
}

echo "</body></html>";
?>

