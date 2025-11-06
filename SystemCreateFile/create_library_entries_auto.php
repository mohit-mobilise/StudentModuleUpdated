<?php
/**
 * Auto-create library book entries for current logged-in user
 * This script will automatically detect the logged-in user and create entries
 */

session_start();
require_once '../connection.php';
require_once '../AppConf.php';

// Get current logged-in user details
$StudentId = $_SESSION['userid'] ?? '';
$StudentName = $_SESSION['StudentName'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';

if (empty($StudentId)) {
    // Try to get from URL parameter or use a default test user
    $StudentId = $_GET['student_id'] ?? '';
    if (empty($StudentId)) {
        die('<h2 style="color:red;">Error: No user logged in. Please login first or provide student_id parameter.</h2>');
    }
    
    // Get student details from database
    $stmt = mysqli_prepare($Con, "SELECT `sname`, `sclass`, `srollno` FROM `student_master` WHERE `sadmission`=? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $StudentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $StudentName = $row['sname'] ?? '';
            $StudentClass = $row['sclass'] ?? '';
            $StudentRollNo = $row['srollno'] ?? '';
        }
        mysqli_stmt_close($stmt);
    }
}

if (empty($StudentClass)) {
    $StudentClass = '10'; // Default fallback
}

echo "<!DOCTYPE html><html><head><title>Creating Library Entries</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:0 auto;}";
echo ".success{color:green;} .error{color:red;} .info{color:blue;}</style></head><body>";
echo "<h2>Creating Library Book Entries</h2>";
echo "<p><strong>Student ID:</strong> " . htmlspecialchars($StudentId) . "</p>";
echo "<p><strong>Student Name:</strong> " . htmlspecialchars($StudentName) . "</p>";
echo "<p><strong>Class:</strong> " . htmlspecialchars($StudentClass) . "</p>";
echo "<p><strong>Roll No:</strong> " . htmlspecialchars($StudentRollNo) . "</p>";
echo "<hr>";

// Get financial year
$fyQuery = "SELECT `financialyear`, `year` FROM `FYmaster` WHERE `Status`='Active' LIMIT 1";
$fyResult = mysqli_query($Con, $fyQuery);
$financialYear = '';
$currentYear = date('Y');
if ($fyResult && mysqli_num_rows($fyResult) > 0) {
    $fyRow = mysqli_fetch_assoc($fyResult);
    $financialYear = $fyRow['financialyear'] ?? '';
    $currentYear = $fyRow['year'] ?? date('Y');
}

// Ensure library_status1 table exists with Activated status
$createStatusTable = "CREATE TABLE IF NOT EXISTS `library_status1` (
    `srno` INT AUTO_INCREMENT PRIMARY KEY,
    `status1_code` VARCHAR(50) NOT NULL,
    `status1_name` VARCHAR(100) NOT NULL,
    UNIQUE KEY `status1_code` (`status1_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($Con, $createStatusTable);

// Insert Activated status if it doesn't exist
$insertStatus = "INSERT INTO `library_status1` (`status1_code`, `status1_name`) VALUES ('ACT', 'Activated') ON DUPLICATE KEY UPDATE status1_name='Activated'";
mysqli_query($Con, $insertStatus);

// Check if library_book_master table exists, create if not
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

mysqli_query($Con, $createBookMasterTable);

// Check if library_book_transaction table exists, create if not
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
    `returndate` DATE,
    `fine` DECIMAL(10,2) DEFAULT 0,
    `fine_discount` DECIMAL(10,2) DEFAULT 0,
    `status` VARCHAR(50) DEFAULT 'issued',
    `IssuerType` VARCHAR(50),
    `FinancialYear` VARCHAR(20),
    INDEX `idx_sadmission` (`sadmission`),
    INDEX `idx_bookid` (`bookid`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($Con, $createTransactionTable);

// Create sample books in library_book_master for this class if they don't exist
$sampleBooks = [
    ['BookName' => 'Mathematics for Class ' . $StudentClass, 'BookCode' => 'BK' . $StudentClass . '001', 'Author' => 'R.D. Sharma', 'Subject' => 'Mathematics'],
    ['BookName' => 'Physics Textbook', 'BookCode' => 'BK' . $StudentClass . '002', 'Author' => 'H.C. Verma', 'Subject' => 'Physics'],
    ['BookName' => 'Chemistry Fundamentals', 'BookCode' => 'BK' . $StudentClass . '003', 'Author' => 'P. Bahadur', 'Subject' => 'Chemistry'],
    ['BookName' => 'English Literature', 'BookCode' => 'BK' . $StudentClass . '004', 'Author' => 'William Shakespeare', 'Subject' => 'English'],
    ['BookName' => 'History of India', 'BookCode' => 'BK' . $StudentClass . '005', 'Author' => 'Bipin Chandra', 'Subject' => 'History'],
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
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($booksCreated > 0) {
    echo "<p class='success'>✅ Created $booksCreated sample books in library_book_master</p>";
}

// Now create issued book transactions
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
    echo "<p class='error'>❌ No books available for class " . htmlspecialchars($StudentClass) . ". Created sample books but they may not be visible yet.</p>";
    echo "<p><a href='?student_id=" . urlencode($StudentId) . "&refresh=1'>Refresh to try again</a></p>";
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
    
    // Check if this book is already issued to this student
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
        // Calculate dates - issue date varies, return date is 30 days from issue
        $issueDate = date('Y-m-d', strtotime('-' . (30 - $bookIndex * 7) . ' days'));
        $tillDate = date('Y-m-d', strtotime($issueDate . ' +30 days'));
        $returnDate = null;
        $status = 'issued';
        $fine = 0;
        
        // Some books are returned, some are pending
        if ($bookIndex >= 2) {
            // Books 3-5 are returned
            $returnDate = date('Y-m-d', strtotime($tillDate . ' +5 days'));
            $status = 'returned';
            // Calculate fine if returned late
            $daysLate = (strtotime($returnDate) - strtotime($tillDate)) / (60 * 60 * 24);
            if ($daysLate > 0) {
                $fine = $daysLate * 5; // 5 rupees per day
            }
        }
        
        // Use prepared statement for INSERT
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
            
            if (mysqli_stmt_execute($insertStmt)) {
                $issuedCount++;
                echo "<p class='success'>✅ Created transaction for book: <strong>" . htmlspecialchars($bookName) . "</strong> (Status: $status)</p>";
            } else {
                echo "<p class='error'>❌ Error creating transaction for " . htmlspecialchars($bookName) . ": " . mysqli_error($Con) . "</p>";
            }
            mysqli_stmt_close($insertStmt);
        } else {
            echo "<p class='error'>❌ Error preparing statement: " . mysqli_error($Con) . "</p>";
        }
    } else {
        echo "<p class='info'>⚠ Book <strong>" . htmlspecialchars($bookName) . "</strong> is already issued to this student</p>";
    }
    
    $bookIndex++;
}

if ($stmt) {
    mysqli_stmt_close($stmt);
}

if ($issuedCount > 0) {
    echo "<hr>";
    echo "<h3 class='success'>✅ Successfully created $issuedCount book entries!</h3>";
    echo "<p><a href='../Users/issued_books.php' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>View Issued Books →</a></p>";
    echo "<script>setTimeout(function(){ window.location.href='../Users/issued_books.php'; }, 2000);</script>";
} else {
    echo "<p class='info'>⚠ No new entries created. All books may already be issued.</p>";
    echo "<p><a href='../Users/issued_books.php'>View Issued Books</a></p>";
}

echo "</body></html>";
?>



