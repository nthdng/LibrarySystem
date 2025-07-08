<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';

// Fetch the borrowID from the URL
if (isset($_GET['borrowID']) && !empty($_GET['borrowID'])) {
    $borrowID = intval($_GET['borrowID']); // Sanitize input

    // Fetch the borrow details including book and borrower information
    $sql = "SELECT 
    b.bookID, 
    b.bookCode, 
    b.bookTitle, 
    b.isbnNumber, 
    c.categoryName, 
    b.count, 
    f.formatName, 
    s.shelfLoc, 
    br.Name AS borrowerName, 
    br.Email AS borrowerEmail, 
    br.status, 
    br.borrowDate, 
    br.borrowReason 
FROM 
    borrowtbl br
JOIN 
    booktbl b ON br.bookID = b.bookID
LEFT JOIN 
    categorytbl c ON b.category = c.categoryID
LEFT JOIN 
    formattbl f ON b.format = f.formatID
LEFT JOIN 
    shelftbl s ON b.shelf = s.shelfID
WHERE 
    br.borrowID = :borrowID";

    $query = $dbh->prepare($sql);
    $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "<p>Borrow record not found.</p>";
        exit();
    }
} else {
    echo "<p>No borrow record selected.</p>";
    exit();
}

// Handle actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        if ($action === 'accept') {
            // Confirm borrow request
            $dbh->beginTransaction();

            // Update borrowtbl status to 'confirmed'
            $updateBorrowSql = "UPDATE borrowtbl SET status = 'confirmed' WHERE borrowID = :borrowID";
            $updateBorrowQuery = $dbh->prepare($updateBorrowSql);
            $updateBorrowQuery->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
            $updateBorrowQuery->execute();

            // Reduce book count in booktbl
            // $newCount = $result->count - 1;
            // $updateBookSql = "UPDATE booktbl SET count = :newCount WHERE bookID = :bookID";
            // $updateBookQuery = $dbh->prepare($updateBookSql);
            // // $updateBookQuery->bindParam(':newCount', $newCount, PDO::PARAM_INT);
            // $updateBookQuery->bindParam(':bookID', $result->bookID, PDO::PARAM_INT);
            // $updateBookQuery->execute();

            $dbh->commit();

            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'LibraryTestBSIS41@gmail.com';
                $mail->Password = 'oooa kckx xnlb cfap';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
                $mail->addAddress($result->borrowerEmail, $result->borrowerName);

                $mail->isHTML(true);
                $mail->Subject = 'Borrow Request Confirmed';
                $mail->Body = 'Hello, ' . $result->borrowerName . ',<br><br>' .
                    'Your borrow request has been confirmed. Please visit the library to claim it. If not claimed within a day, it will expire.<br><br>' .
                    'Best regards,<br>The Library Team';

                $mail->send();
                echo "<script>alert('Borrow request confirmed and email sent!'); window.location.href='pendingList.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Borrow request confirmed, but email could not be sent. Mailer Error: " . $mail->ErrorInfo . "'); window.location.href='pendingList.php';</script>";
            }
        } elseif ($action === 'deny') {
            // Archive borrow request
            $reason = $_POST['deny_reason'];
            $dbh->beginTransaction();

            // Update borrowtbl status to 'denied' and set borrowArc to 'Archived'
            $updateBorrowSql = "UPDATE borrowtbl SET status = 'denied', borrowarc = 'Archived', arcReason = :reason WHERE borrowID = :borrowID";
            $updateBorrowQuery = $dbh->prepare($updateBorrowSql);
            $updateBorrowQuery->bindParam(':reason', $reason, PDO::PARAM_STR);
            $updateBorrowQuery->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
            $updateBorrowQuery->execute();

            // Restore book count in booktbl
            $newCount = $result->count + 1;
            $updateBookSql = "UPDATE booktbl SET count = :newCount WHERE bookID = :bookID";
            $updateBookQuery = $dbh->prepare($updateBookSql);
            $updateBookQuery->bindParam(':newCount', $newCount, PDO::PARAM_INT);
            $updateBookQuery->bindParam(':bookID', $result->bookID, PDO::PARAM_INT);
            $updateBookQuery->execute();

            $dbh->commit();

            // Send denial email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'LibraryTestBSIS41@gmail.com';
                $mail->Password = 'oooa kckx xnlb cfap';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
                $mail->addAddress($result->borrowerEmail, $result->borrowerName);

                $mail->isHTML(true);
                $mail->Subject = 'Borrow Request Denied';
                $mail->Body = 'Hello, ' . $result->borrowerName . ',<br><br>' .
                    'Your borrow request has been denied. Reason: ' . $reason . '<br><br>' .
                    'Best regards,<br>The Library Team';

                $mail->send();
                echo "<script>alert('Borrow request denied and email sent!'); window.location.href='pendingList.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Borrow request denied, but email could not be sent. Mailer Error: " . $mail->ErrorInfo . "'); window.location.href='pendingList.php';</script>";
            }
        }
    } catch (Exception $e) {
        $dbh->rollBack();
        echo "<p>Failed to process the request. Please try again later.</p>";
    }
}

// Auto-deny unclaimed requests
$currentDate = date('Y-m-d H:i:s');
$autoDenySql = "UPDATE borrowtbl SET status = 'denied', borrowarc = 'Archived', arcReason = 'Not claimed within a day' 
    WHERE status = 'confirmed' AND TIMESTAMPDIFF(DAY, borrowDate, :currentDate) >= 1";
$autoDenyQuery = $dbh->prepare($autoDenySql);
$autoDenyQuery->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
$autoDenyQuery->execute();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Borrow Request Confirmation</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/headerindex.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Borrow Request Confirmation</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Book Details</h5>
                    <div class="form-group">
                        <label>Book Code</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->bookCode); ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->bookTitle); ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>ISBN</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->isbnNumber); ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input class="form-control" type="text"
                            value="<?php echo htmlentities($result->categoryName); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->formatName); ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>Shelf</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->shelfLoc); ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>Available Copies</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->count); ?>"
                            readonly />
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Borrower Information</h5>
                    <form method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text"
                                value="<?php echo htmlentities($result->borrowerName); ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="email"
                                value="<?php echo htmlentities($result->borrowerEmail); ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label>Borrow Reason</label>
                            <textarea class="form-control"
                                readonly><?php echo htmlentities($result->borrowReason); ?></textarea>
                        </div>

                        <button type="submit" name="action" value="accept" class="btn btn-info">Accept Request</button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal">Deny
                            Request</button>
                        <a href="pendingList.php" class="btn btn-warning">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Deny Modal -->
    <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="denyModalLabel">Deny Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="deny_reason">Reason for Denial:</label>
                            <textarea class="form-control" name="deny_reason" id="deny_reason" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="action" value="deny" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>