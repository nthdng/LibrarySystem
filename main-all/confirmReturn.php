<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}

if (isset($_GET['returnID'])) {
    $borrowID = intval($_GET['returnID']); 

    // Query to fetch borrow and book details
    $sql = "
    SELECT 
        br.borrowID, br.Username, br.FacultyID, br.StudentID, 
        br.borrowDate, br.expectedReturnDate, br.status, br.borrowarc,
        b.bookID, b.bookCode, b.bookTitle, b.format, 
        b.category, b.shelf, b.isbnNumber, b.accountNumber, b.archBook,
        f.Fname AS FacultyFname, f.Lname AS FacultyLname, f.Email AS FacultyEmail,
        s.Fname AS StudentFname, s.Lname AS StudentLname, s.Email AS StudentEmail
    FROM 
        borrowtbl br
    LEFT JOIN 
        tblfaculty f ON br.FacultyID = f.FacultyID
    LEFT JOIN 
        tblstudent s ON br.StudentID = s.StudentID
    JOIN 
        booktbl b ON br.bookID = b.bookID
    WHERE 
        br.borrowID = :borrowID
    LIMIT 1
    ";

    $query = $dbh->prepare($sql);
    $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    // Check if result exists
    if ($result) {
        // Calculate penalty dynamically
        $expectedReturnDate = new DateTime($result->expectedReturnDate);
        $returnDate = new DateTime(); // Today
        if ($returnDate > $expectedReturnDate) {
            $interval = $expectedReturnDate->diff($returnDate);
            $overdueDays = $interval->days;
            $penalty = $overdueDays * 10; // Penalty is 10 per overdue day
        } else {
            $penalty = 0;
        }

        // If penalty exists, update penalty and penalty status
        if ($penalty > 0) {
            $updateSql = "
                UPDATE returntbl 
                SET penalty = :penalty, penaltyStatus = 'Unpaid' 
                WHERE returnID = :borrowID
            ";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':penalty', $penalty, PDO::PARAM_STR);
            $updateQuery->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
            $updateQuery->execute();
        }
    } else {
        echo "No record found for the given borrow ID.";
    }
}


if (isset($_POST['update'])) {
    try {
        $returnDateStr = date('Y-m-d H:i:s'); // Today's date as returnDate
        $enteredPenalty = intval($_POST['penalty']); // Fetch user input

        $expectedReturnDate = new DateTime($result->expectedReturnDate);
        $returnDate = new DateTime(); // Today's date

        // Calculate default penalty if the field is empty
        if (empty($enteredPenalty)) {
            if ($returnDate > $expectedReturnDate) {
                $interval = $expectedReturnDate->diff($returnDate);
                $overdueDays = $interval->days;
                $penalty = $overdueDays * 10; // Default penalty: 10 per day overdue
                $status = 'overdue';
            } else {
                $penalty = 0;
                $status = 'returned';
            }
        } else {
            $penalty = $enteredPenalty; // Use manually entered penalty
            $status = $returnDate > $expectedReturnDate ? 'overdue' : 'returned';
        }

        // Update returntbl
        $sqlReturnUpdate = "
            UPDATE returntbl 
            SET returnDate = :returnDate, 
                status = :status, 
                penalty = :penalty 
            WHERE borrowID = :borrowID
        ";
        $queryReturnUpdate = $dbh->prepare($sqlReturnUpdate);
        $queryReturnUpdate->bindParam(':returnDate', $returnDateStr, PDO::PARAM_STR);
        $queryReturnUpdate->bindParam(':status', $status, PDO::PARAM_STR);
        $queryReturnUpdate->bindParam(':penalty', $penalty, PDO::PARAM_STR);
        $queryReturnUpdate->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $queryReturnUpdate->execute();

        // Update booktbl count
        $sqlBook = "UPDATE booktbl SET count = count + 1 WHERE bookID = :bookID";
        $queryBook = $dbh->prepare($sqlBook);
        $queryBook->bindParam(':bookID', $result->bookID, PDO::PARAM_INT);
        $queryBook->execute();

        // Archive borrowtbl record
        $sqlBorrow = "UPDATE borrowtbl 
                      SET borrowarc = 'Archived', arcReason = 'Returned' 
                      WHERE borrowID = :borrowID";
        $queryBorrow = $dbh->prepare($sqlBorrow);
        $queryBorrow->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $queryBorrow->execute();

        // Set up email notification
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'LibraryTestBSIS41@gmail.com';  // Replace with your email
            $mail->Password = 'oooa kckx xnlb cfap';  // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $borrowerEmail = $result->FacultyEmail ?? $result->StudentEmail;
            $borrowerName = ($result->FacultyFname ?? $result->StudentFname) . ' ' . ($result->FacultyLname ?? $result->StudentLname);
            $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
            $mail->addAddress($borrowerEmail, $borrowerName);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Book Return Confirmation';

            if ($penalty > 0) {
                $mail->Body = "Dear $borrowerName,<br><br>" .
                    "Thank you for returning the book titled '<b>{$result->bookTitle}</b>'.<br>" .
                    "You have paid a penalty of <b>₱$penalty</b> due to the late return.<br><br>" .
                    "Please remember to return books on time in the future to avoid penalties.<br><br>" .
                    "Best regards,<br>The Library Team";
            } else {
                $mail->Body = "Dear $borrowerName,<br><br>" .
                    "Thank you for returning the book titled '<b>{$result->bookTitle}</b>' on time.<br>" .
                    "We appreciate your punctuality and look forward to serving you again.<br><br>" .
                    "Best regards,<br>The Library Team";
            }

            // Send email
            $mail->send();
            echo "<script>alert('Book return updated successfully! Email notification sent to the borrower.');
            window.location.href = 'ReturnList.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Book return updated successfully, but email notification could not be sent. Error: {$mail->ErrorInfo}');
            window.location.href = 'ReturnList.php';</script>";
        }
    } catch (Exception $e) {
        // General error handling for the outer try block
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');
        window.location.href = 'ReturnList.php';</script>";
    }
}




?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Manage Return</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .overdue {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Return</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">Manage Return</div>
                        <div class="panel-body">
                            <form name="updatest" method="post">
                                <div class="row">
                                    <!-- Column 1 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Borrower ID</label>
                                            <input class="form-control" type="text" name="borrowerID"
                                                value="<?php echo htmlentities($result->FacultyID ?? $result->StudentID); ?>"
                                                readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>Borrower Full Name</label>
                                            <input class="form-control" type="text" name="borrowerName"
                                                value="<?php echo htmlentities(($result->FacultyFname ?? $result->StudentFname) . ' ' . ($result->FacultyLname ?? $result->StudentLname)); ?>"
                                                readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>Borrower Email</label>
                                            <input class="form-control" type="text" name="borrowerEmail"
                                                value="<?php echo htmlentities($result->FacultyEmail ?? $result->StudentEmail); ?>"
                                                readonly />
                                        </div>
                                    </div>

                                    <!-- Column 2 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Borrowed Book</label>
                                            <input class="form-control" type="text" name="bookTitle"
                                                value="<?php echo htmlentities($result->bookTitle); ?>" readonly />
                                        </div>
                                        <div class="form-group">
                                            <label>Expected Return Date</label>
                                            <div>
                                                <input class="form-control" type="text" name="expectedReturnDate"
                                                    value="<?php echo htmlspecialchars($result->expectedReturnDate); ?>"
                                                    readonly />
                                                <?php if ((new DateTime()) > new DateTime($result->expectedReturnDate)): ?>
                                                    <span class="overdue">Overdue</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Penalty</label>
                                            <input class="form-control" type="text" name="penalty"
                                                value="<?php echo htmlentities($penalty); ?>" />

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="update" class="btn btn-primary"
                                        id="update">Confirm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>