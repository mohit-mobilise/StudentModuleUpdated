<?php
/**
 * Script to create library book entries for current logged-in user
 * Run this file once to create issued books for the current user
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
    die('<h2 style="color:red;">Error: No user logged in. Please login first.</h2>');
}

echo "<h2>Creating Library Book Entries for Current User</h2>";
echo "<p><strong>Student ID:</strong> $StudentId</p>";
echo "<p><strong>Student Name:</strong> $StudentName</p>";
echo "<p><strong>Class:</strong> $StudentClass</p>";
echo "<p><strong>Roll No:</strong> $StudentRollNo</p>";
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

// Check if library_status1 table exists and has 'Activated' status
$statusCheck = mysqli_query($Con, "SELECT COUNT(*) as cnt FROM `library_status1` WHERE `status1_name`='Activated'");
$statusExists = false;
if ($statusCheck) {
    $statusRow = mysqli_fetch_assoc($statusCheck);
    $statusExists = $statusRow['cnt'] > 0;
}

if (!$statusExists) {
    // Create library_status1 table if it doesn't exist
    $createStatusTable = "CREATE TABLE IF NOT EXISTS `library_status1` (
        `srno` INT AUTO_INCREMENT PRIMARY KEY,
        `status1_code` VARCHAR(50) NOT NULL,
        `status1_name` VARCHAR(100) NOT NULL,
        UNIQUE KEY `status1_code` (`status1_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (mysqli_query($Con, $createStatusTable)) {
        echo "<p style='color:green;'>✅ Created library_status1 table</p>";
        
        // Insert Activated status
        $insertStatus = "INSERT INTO `library_status1` (`status1_code`, `status1_name`) VALUES ('ACT', 'Activated') ON DUPLICATE KEY UPDATE status1_name='Activated'";
        mysqli_query($Con, $insertStatus);
    }
}

// Check if library_book_master table exists and has books for this class
$bookMasterCheck = mysqli_query($Con, "SELECT COUNT(*) as cnt FROM `library_book_master` WHERE `Class`='$StudentClass'");
$bookCount = 0;
if ($bookMasterCheck) {
    $bookRow = mysqli_fetch_assoc($bookMasterCheck);
    $bookCount = $bookRow['cnt'];
}

if ($bookCount == 0) {
    // Create sample books in library_book_master for this class
    echo "<p style='color:orange;'>⚠ No books found in library_book_master for class $StudentClass. Creating sample books...</p>";
    
    // Get status code for Activated
    $statusQuery = mysqli_query($Con, "SELECT `status1_code` FROM `library_status1` WHERE `status1_name`='Activated' LIMIT 1");
    $statusCode = 'ACT';
    if ($statusQuery && mysqli_num_rows($statusQuery) > 0) {
        $statusRow = mysqli_fetch_assoc($statusQuery);
        $statusCode = $statusRow['status1_code'];
    }
    
    $sampleBooks = [
        ['BookName' => 'Mathematics for Class ' . $StudentClass, 'BookCode' => 'BK' . $StudentClass . '001', 'Author' => 'R.D. Sharma', 'Subject' => 'Mathematics', 'Class' => $StudentClass, 'PurchasingDate' => date('Y-m-d', strtotime('-6 months')), 'status1' => $statusCode],
        ['BookName' => 'Physics Textbook', 'BookCode' => 'BK' . $StudentClass . '002', 'Author' => 'H.C. Verma', 'Subject' => 'Physics', 'Class' => $StudentClass, 'PurchasingDate' => date('Y-m-d', strtotime('-5 months')), 'status1' => $statusCode],
        ['BookName' => 'Chemistry Fundamentals', 'BookCode' => 'BK' . $StudentClass . '003', 'Author' => 'P. Bahadur', 'Subject' => 'Chemistry', 'Class' => $StudentClass, 'PurchasingDate' => date('Y-m-d', strtotime('-4 months')), 'status1' => $statusCode],
        ['BookName' => 'English Literature', 'BookCode' => 'BK' . $StudentClass . '004', 'Author' => 'William Shakespeare', 'Subject' => 'English', 'Class' => $StudentClass, 'PurchasingDate' => date('Y-m-d', strtotime('-3 months')), 'status1' => $statusCode],
        ['BookName' => 'History of India', 'BookCode' => 'BK' . $StudentClass . '005', 'Author' => 'Bipin Chandra', 'Subject' => 'History', 'Class' => $StudentClass, 'PurchasingDate' => date('Y-m-d', strtotime('-2 months')), 'status1' => $statusCode],
    ];
    
    $booksCreated = 0;
    foreach ($sampleBooks as $book) {
        // Check if book already exists
        $checkBook = mysqli_query($Con, "SELECT COUNT(*) as cnt FROM `library_book_master` WHERE `BookCode`='" . mysqli_real_escape_string($Con, $book['BookCode']) . "'");
        $exists = false;
        if ($checkBook) {
            $checkRow = mysqli_fetch_assoc($checkBook);
            $exists = $checkRow['cnt'] > 0;
        }
        
        if (!$exists) {
            $insertBook = "INSERT INTO `library_book_master` (`BookName`, `BookCode`, `Author`, `Subject`, `Class`, `PurchasingDate`, `status1`) 
                          VALUES ('" . mysqli_real_escape_string($Con, $book['BookName']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['BookCode']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['Author']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['Subject']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['Class']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['PurchasingDate']) . "', 
                                  '" . mysqli_real_escape_string($Con, $book['status1']) . "')";
            
            if (mysqli_query($Con, $insertBook)) {
                $booksCreated++;
            }
        }
    }
    
    echo "<p style='color:green;'>✅ Created $booksCreated sample books in library_book_master</p>";
}

// Now create issued book transactions using prepared statements
$booksQuery = "SELECT `BookCode`, `BookName`, `Author`, `Subject` FROM `library_book_master` 
               WHERE `Class`=? AND `status1` IN (SELECT `status1_code` FROM `library_status1` WHERE `status1_name`='Activated')
               LIMIT 5";
$stmt = mysqli_prepare($Con, $booksQuery);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $StudentClass);
    mysqli_stmt_execute($stmt);
    $booksResult = mysqli_stmt_get_result($stmt);
} else {
    $booksResult = false;
}

if (!$booksResult || mysqli_num_rows($booksResult) == 0) {
    die('<p style="color:red;">❌ No books available for class ' . htmlspecialchars($StudentClass) . '</p>');
}

$issuedCount = 0;
$currentDate = date('Y-m-d');
$bookIndex = 0;

while (($book = mysqli_fetch_assoc($booksResult)) !== null && $issuedCount < 5) {
    $bookId = $book['BookCode'];
    $bookName = $book['BookName'];
    $bookAuthor = $book['Author'];
    $bookSubject = $book['Subject'];
    
    // Check if this book is already issued to this student - Use prepared statement
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
                echo "<p style='color:green;'>✅ Created transaction for book: <strong>" . htmlspecialchars($bookName) . "</strong> (Status: $status)</p>";
            } else {
                echo "<p style='color:red;'>❌ Error creating transaction for " . htmlspecialchars($bookName) . ": " . mysqli_error($Con) . "</p>";
            }
            mysqli_stmt_close($insertStmt);
        } else {
            echo "<p style='color:red;'>❌ Error preparing statement: " . mysqli_error($Con) . "</p>";
        }
    } else {
        echo "<p style='color:orange;'>⚠ Book <strong>" . htmlspecialchars($bookName) . "</strong> is already issued to this student</p>";
    }
    
    $bookIndex++;
}

if ($stmt) {
    mysqli_stmt_close($stmt);
}

if ($issuedCount > 0) {
    echo "<hr>";
    echo "<h3 style='color:green;'>✅ Successfully created $issuedCount book entries!</h3>";
    echo "<p><a href='../Users/issued_books.php'>View issued books</a></p>";
} else {
    echo "<p style='color:orange;'>⚠ No new entries created. All books may already be issued.</p>";
}
?>

