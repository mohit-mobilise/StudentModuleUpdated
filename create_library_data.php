<?php
/**
 * Direct database entry creation script for library books
 * This script will create database entries directly
 * Access: http://localhost/cursorai/Testing/studentportal/create_library_data.php?student_id=YOUR_STUDENT_ID
 */

session_start();
require_once 'connection.php';
require_once 'AppConf.php';

// Get student ID from session, URL parameter, or use first available student
$StudentId = $_SESSION['userid'] ?? $_GET['student_id'] ?? '';

if (empty($StudentId)) {
    // Get first active student from database
    $firstStudent = mysqli_query($Con, "SELECT `sadmission`, `sname`, `sclass`, `srollno` FROM `student_master` WHERE `erp_status`='Active' LIMIT 1");
    if ($firstStudent && mysqli_num_rows($firstStudent) > 0) {
        $row = mysqli_fetch_assoc($firstStudent);
        $StudentId = $row['sadmission'];
        $StudentName = $row['sname'] ?? '';
        $StudentClass = $row['sclass'] ?? '10';
        $StudentRollNo = $row['srollno'] ?? '';
    } else {
        die('<h2 style="color:red;">No active students found. Please create a student first.</h2>');
    }
} else {
    // Get student details from database
    $stmt = mysqli_prepare($Con, "SELECT `sname`, `sclass`, `srollno` FROM `student_master` WHERE `sadmission`=? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $StudentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $StudentName = $row['sname'] ?? '';
            $StudentClass = $row['sclass'] ?? '10';
            $StudentRollNo = $row['srollno'] ?? '';
        } else {
            die('<h2 style="color:red;">Student not found: ' . htmlspecialchars($StudentId) . '</h2>');
        }
        mysqli_stmt_close($stmt);
    }
}

if (empty($StudentClass)) {
    $StudentClass = '10';
}

echo "<!DOCTYPE html><html><head><title>Creating Library Data</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:900px;margin:0 auto;line-height:1.6;}";
echo ".success{color:green;font-weight:bold;} .error{color:red;} .info{color:blue;}";
echo "table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:8px;text-align:left;}";
echo "th{background-color:#4CAF50;color:white;}</style></head><body>";
echo "<h2>Creating Library Book Entries</h2>";
echo "<p><strong>Student ID:</strong> " . htmlspecialchars($StudentId) . "</p>";
echo "<p><strong>Student Name:</strong> " . htmlspecialchars($StudentName) . "</p>";
echo "<p><strong>Class:</strong> " . htmlspecialchars($StudentClass) . "</p>";
echo "<p><strong>Roll No:</strong> " . htmlspecialchars($StudentRollNo) . "</p>";
echo "<hr>";

// Get financial year
$fyQuery = "SELECT `financialyear`, `year` FROM `FYmaster` WHERE `Status`='Active' LIMIT 1";
$fyResult = mysqli_query($Con, $fyQuery);
$financialYear = date('Y') . '-' . (date('Y') + 1);
$currentYear = date('Y');
if ($fyResult && mysqli_num_rows($fyResult) > 0) {
    $fyRow = mysqli_fetch_assoc($fyResult);
    $financialYear = $fyRow['financialyear'] ?? $financialYear;
    $currentYear = $fyRow['year'] ?? date('Y');
}

// Step 1: Create library_status1 table if it doesn't exist
echo "<h3>Step 1: Setting up library_status1 table</h3>";
$createStatusTable = "CREATE TABLE IF NOT EXISTS `library_status1` (
    `srno` INT AUTO_INCREMENT PRIMARY KEY,
    `status1_code` VARCHAR(50) NOT NULL,
    `status1_name` VARCHAR(100) NOT NULL,
    UNIQUE KEY `status1_code` (`status1_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($Con, $createStatusTable)) {
    echo "<p class='success'>✅ library_status1 table ready</p>";
} else {
    echo "<p class='error'>❌ Error creating library_status1: " . mysqli_error($Con) . "</p>";
}

// Insert Activated status
$insertStatus = "INSERT INTO `library_status1` (`status1_code`, `status1_name`) VALUES ('ACT', 'Activated') ON DUPLICATE KEY UPDATE status1_name='Activated'";
mysqli_query($Con, $insertStatus);
echo "<p class='success'>✅ Activated status created</p>";

// Step 2: Create library_book_master table if it doesn't exist
echo "<h3>Step 2: Setting up library_book_master table</h3>";
$createBookMasterTable = "CREATE TABLE IF NOT EXISTS `library_book_master` (
    `srno` INT AUTO_INCREMENT PRIMARY KEY,
    `BookName` VARCHAR(255) NOT NULL,
    `BookCode` VARCHAR(100) NOT NULL,
    `Author` VARCHAR(255),
    `Subject` VARCHAR(100),
    `Class` VARCHAR(20),
    `PurchasingDate` DATE,
    `status1` VARCHAR(50) DEFAULT 'ACT',
    UNIQUE KEY `BookCode` (`BookCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($Con, $createBookMasterTable)) {
    echo "<p class='success'>✅ library_book_master table ready</p>";
} else {
    echo "<p class='error'>❌ Error creating library_book_master: " . mysqli_error($Con) . "</p>";
}

// Step 3: Create library_book_transaction table if it doesn't exist
echo "<h3>Step 3: Setting up library_book_transaction table</h3>";
$createTransactionTable = "CREATE TABLE IF NOT EXISTS `library_book_transaction` (
    `srno` INT AUTO_INCREMENT PRIMARY KEY,
    `sadmission` VARCHAR(50) NOT NULL,
    `sname` VARCHAR(255),
    `sclass` VARCHAR(20),
    `srollno` VARCHAR(20),
    `bookid` VARCHAR(100) NOT NULL,
    `bookname` VARCHAR(255),
    `bookauthor` VARCHAR(255),
    `booksubject` VARCHAR(100),
    `issue_date` DATE,
    `tilldate` DATE,
    `returndate` DATE NULL,
    `fine` DECIMAL(10,2) DEFAULT 0,
    `fine_discount` DECIMAL(10,2) DEFAULT 0,
    `status` VARCHAR(50) DEFAULT 'issued',
    `IssuerType` VARCHAR(50),
    `FinancialYear` VARCHAR(20),
    INDEX `idx_sadmission` (`sadmission`),
    INDEX `idx_bookid` (`bookid`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

// If table already exists, alter it to allow NULL for returndate
$alterTable = "ALTER TABLE `library_book_transaction` MODIFY `returndate` DATE NULL";
@mysqli_query($Con, $alterTable); // Suppress error if column already allows NULL

if (mysqli_query($Con, $createTransactionTable)) {
    echo "<p class='success'>✅ library_book_transaction table ready</p>";
} else {
    echo "<p class='error'>❌ Error creating library_book_transaction: " . mysqli_error($Con) . "</p>";
}

// Step 4: Create sample books for this class
echo "<h3>Step 4: Creating sample books for class " . htmlspecialchars($StudentClass) . "</h3>";
$sampleBooks = [
    ['BookName' => 'Mathematics for Class ' . $StudentClass, 'BookCode' => 'BK' . $StudentClass . '001', 'Author' => 'R.D. Sharma', 'Subject' => 'Mathematics'],
    ['BookName' => 'Physics Textbook', 'BookCode' => 'BK' . $StudentClass . '002', 'Author' => 'H.C. Verma', 'Subject' => 'Physics'],
    ['BookName' => 'Chemistry Fundamentals', 'BookCode' => 'BK' . $StudentClass . '003', 'Author' => 'P. Bahadur', 'Subject' => 'Chemistry'],
    ['BookName' => 'English Literature', 'BookCode' => 'BK' . $StudentClass . '004', 'Author' => 'William Shakespeare', 'Subject' => 'English'],
    ['BookName' => 'History of India', 'BookCode' => 'BK' . $StudentClass . '005', 'Author' => 'Bipin Chandra', 'Subject' => 'History'],
    ['BookName' => 'Biology Essentials', 'BookCode' => 'BK' . $StudentClass . '006', 'Author' => 'Pradeep Publications', 'Subject' => 'Biology'],
    ['BookName' => 'Computer Science Basics', 'BookCode' => 'BK' . $StudentClass . '007', 'Author' => 'Sumita Arora', 'Subject' => 'Computer Science'],
];

$booksCreated = 0;
foreach ($sampleBooks as $book) {
    $checkBook = mysqli_prepare($Con, "SELECT COUNT(*) as cnt FROM `library_book_master` WHERE `BookCode`=?");
    $exists = false;
    if ($checkBook) {
        mysqli_stmt_bind_param($checkBook, "s", $book['BookCode']);
        mysqli_stmt_execute($checkBook);
        $result = mysqli_stmt_get_result($checkBook);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $exists = $row['cnt'] > 0;
        }
        mysqli_stmt_close($checkBook);
    }
    
    if (!$exists) {
        $purchaseDate = date('Y-m-d', strtotime('-' . (6 - $booksCreated) . ' months'));
        $insertBook = "INSERT INTO `library_book_master` (`BookName`, `BookCode`, `Author`, `Subject`, `Class`, `PurchasingDate`, `status1`) 
                      VALUES (?, ?, ?, ?, ?, ?, 'ACT')";
        
        $stmt = mysqli_prepare($Con, $insertBook);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $book['BookName'], $book['BookCode'], $book['Author'], $book['Subject'], $StudentClass, $purchaseDate);
            if (mysqli_stmt_execute($stmt)) {
                $booksCreated++;
                echo "<p class='success'>✅ Created book: " . htmlspecialchars($book['BookName']) . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($booksCreated > 0) {
    echo "<p class='success'><strong>✅ Created $booksCreated new books</strong></p>";
} else {
    echo "<p class='info'>ℹ All books already exist</p>";
}

// Step 5: Create issued book transactions
echo "<h3>Step 5: Creating issued book transactions</h3>";

// Get books for this class
$booksQuery = "SELECT `BookCode`, `BookName`, `Author`, `Subject` FROM `library_book_master` 
               WHERE `Class`=? AND `status1`='ACT' LIMIT 5";
$stmt = mysqli_prepare($Con, $booksQuery);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $StudentClass);
    mysqli_stmt_execute($stmt);
    $booksResult = mysqli_stmt_get_result($stmt);
} else {
    $booksResult = false;
}

if (!$booksResult || mysqli_num_rows($booksResult) == 0) {
    echo "<p class='error'>❌ No books available for class " . htmlspecialchars($StudentClass) . "</p>";
    echo "</body></html>";
    exit;
}

$issuedCount = 0;
$bookIndex = 0;

while (($book = mysqli_fetch_assoc($booksResult)) !== null && $issuedCount < 5) {
    $bookId = $book['BookCode'];
    $bookName = $book['BookName'];
    $bookAuthor = $book['Author'];
    $bookSubject = $book['Subject'];
    
    // Check if already issued
    $checkIssuedStmt = mysqli_prepare($Con, "SELECT COUNT(*) as cnt FROM `library_book_transaction` 
                                       WHERE `sadmission`=? AND `bookid`=? AND `status`='issued'");
    $alreadyIssued = false;
    if ($checkIssuedStmt) {
        mysqli_stmt_bind_param($checkIssuedStmt, "ss", $StudentId, $bookId);
        mysqli_stmt_execute($checkIssuedStmt);
        $checkResult = mysqli_stmt_get_result($checkIssuedStmt);
        if ($checkResult) {
            $checkRow = mysqli_fetch_assoc($checkResult);
            $alreadyIssued = $checkRow['cnt'] > 0;
        }
        mysqli_stmt_close($checkIssuedStmt);
    }
    
    if (!$alreadyIssued) {
        // Calculate dates
        $issueDate = date('Y-m-d', strtotime('-' . (30 - $bookIndex * 7) . ' days'));
        $tillDate = date('Y-m-d', strtotime($issueDate . ' +30 days'));
        $returnDate = null;
        $status = 'issued';
        $fine = 0;
        
        // Books 3-5 are returned
        if ($bookIndex >= 2) {
            $returnDate = date('Y-m-d', strtotime($tillDate . ' +5 days'));
            $status = 'returned';
            $daysLate = (strtotime($returnDate) - strtotime($tillDate)) / (60 * 60 * 24);
            if ($daysLate > 0) {
                $fine = $daysLate * 5;
            }
        }
        
        // Insert transaction using prepared statement
        // Build query dynamically to handle NULL return date
        if ($returnDate === null) {
            $insertTransaction = "INSERT INTO `library_book_transaction` 
                                (`sadmission`, `sname`, `sclass`, `srollno`, `bookid`, `bookname`, `bookauthor`, `booksubject`, 
                                 `issue_date`, `tilldate`, `returndate`, `fine`, `fine_discount`, `status`, `IssuerType`, `FinancialYear`) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, 0, ?, 'Library', ?)";
            $insertStmt = mysqli_prepare($Con, $insertTransaction);
            if ($insertStmt) {
                mysqli_stmt_bind_param($insertStmt, "ssssssssssdss", 
                    $StudentId, $StudentName, $StudentClass, $StudentRollNo, 
                    $bookId, $bookName, $bookAuthor, $bookSubject,
                    $issueDate, $tillDate, $fine, $status, $financialYear);
            }
        } else {
            $insertTransaction = "INSERT INTO `library_book_transaction` 
                                (`sadmission`, `sname`, `sclass`, `srollno`, `bookid`, `bookname`, `bookauthor`, `booksubject`, 
                                 `issue_date`, `tilldate`, `returndate`, `fine`, `fine_discount`, `status`, `IssuerType`, `FinancialYear`) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, 'Library', ?)";
            $insertStmt = mysqli_prepare($Con, $insertTransaction);
            if ($insertStmt) {
                mysqli_stmt_bind_param($insertStmt, "sssssssssssdss", 
                    $StudentId, $StudentName, $StudentClass, $StudentRollNo, 
                    $bookId, $bookName, $bookAuthor, $bookSubject,
                    $issueDate, $tillDate, $returnDate, $fine, $status, $financialYear);
            }
        }
        
        if ($insertStmt) {
            if (mysqli_stmt_execute($insertStmt)) {
                $issuedCount++;
                echo "<p class='success'>✅ Created transaction: <strong>" . htmlspecialchars($bookName) . "</strong> (Status: $status" . ($fine > 0 ? ", Fine: ₹$fine" : "") . ")</p>";
            } else {
                echo "<p class='error'>❌ Error: " . mysqli_error($Con) . "</p>";
            }
            mysqli_stmt_close($insertStmt);
        }
    } else {
        echo "<p class='info'>ℹ Book already issued: " . htmlspecialchars($bookName) . "</p>";
    }
    
    $bookIndex++;
}

if ($stmt) {
    mysqli_stmt_close($stmt);
}

echo "<hr>";
if ($issuedCount > 0) {
    echo "<h3 class='success'>✅ Successfully created $issuedCount book transactions!</h3>";
    echo "<p><a href='Users/issued_books.php' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-top:10px;'>View Issued Books →</a></p>";
    echo "<script>setTimeout(function(){ window.location.href='Users/issued_books.php'; }, 3000);</script>";
} else {
    echo "<p class='info'>ℹ No new transactions created. All books may already be issued.</p>";
    echo "<p><a href='Users/issued_books.php'>View Issued Books</a></p>";
}

echo "</body></html>";
?>

