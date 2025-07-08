<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

// Fetch the bookID from the URL
if (isset($_GET['bookid']) && !empty($_GET['bookid'])) {
    $bookID = intval($_GET['bookid']); // Sanitize input

    // Fetch the book details
    $sql = "SELECT 
        b.bookID, 
        b.bookCode, 
        b.bookTitle, 
        b.isbnNumber, 
        c.categoryName, 
        b.count, 
        f.formatName, 
        s.shelfLoc, 
        b.regDate 
    FROM 
        booktbl b
    LEFT JOIN 
        categorytbl c ON b.category = c.categoryID
    LEFT JOIN 
        formattbl f ON b.format = f.formatID
    LEFT JOIN 
        shelftbl s ON b.shelf = s.shelfID 
    WHERE 
        b.bookID = :bookID 
        AND b.archBook = 'Existing';";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookID', $bookID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "<p>Book not found or archived.</p>";
        exit();
    }
} else {
    echo "<p>No book selected.</p>";
    exit();
}

// Handle confirm borrow action
if (isset($_POST['confirm_borrow'])) {
    if ($result->count > 0) { // Ensure the book is in stock
        try {
            // Capture input values
            $borrowerName = $_POST['borrower_name'];
            $borrowerEmail = $_POST['borrower_email'];
            $borrowReason = $_POST['borrow_reason'];

            // Validate email
            if (!filter_var($borrowerEmail, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Invalid email address!');</script>";
                return;
            }

            // Start a transaction
            $dbh->beginTransaction();

            // Reduce the book count in booktbl
            $newCount = $result->count - 1;
            $updateSql = "UPDATE booktbl SET count = :newCount WHERE bookID = :bookID";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':newCount', $newCount, PDO::PARAM_INT);
            $updateQuery->bindParam(':bookID', $bookID, PDO::PARAM_INT);
            $updateQuery->execute();

            // Insert entry into borrowtbl
            $insertSql = "INSERT INTO borrowtbl (bookID, Name, Email, status, borrowReason) 
                          VALUES (:bookID, :borrowerName, :borrowerEmail, 'pending', :borrowReason)";
            $insertQuery = $dbh->prepare($insertSql);
            $insertQuery->bindParam(':bookID', $bookID, PDO::PARAM_INT);
            $insertQuery->bindParam(':borrowerName', $borrowerName, PDO::PARAM_STR);
            $insertQuery->bindParam(':borrowerEmail', $borrowerEmail, PDO::PARAM_STR);
            $insertQuery->bindParam(':borrowReason', $borrowReason, PDO::PARAM_STR);
            $insertQuery->execute();

            // Commit the transaction
            $dbh->commit();

            echo "<script>alert('Please proceed to the librarian to complete the borrow!'); window.location.href='../index.php';</script>";
            exit();
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $dbh->rollBack();
            echo "<p>Failed to borrow the book. Please try again later.</p>";
        }
    } else {
        echo "<script>alert('Selected book is currently out of stock'); window.location.href='../index.php';</script>";
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
    <title>Online Library Management System | Borrow Confirmation</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/headerindex.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Borrow Confirmation</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Book Details</h5>
                    <div class="form-group">
                        <label>Book Code</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->bookCode); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->bookTitle); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>ISBN</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->isbnNumber); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->categoryName); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->formatName); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Shelf</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->shelfLoc); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Registered Date</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->regDate); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Available Copies</label>
                        <input class="form-control" type="text" value="<?php echo htmlentities($result->count); ?>" readonly />
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Borrower Information</h5>
                    <form method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="borrower_name" required />
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="email" name="borrower_email" required />
                        </div>

                        <div class="form-group">
                            <label>Borrow Reason</label>
                            <input class="form-control" type="text" name="borrow_reason" required />
                        </div>
                        <button type="submit" name="confirm_borrow" class="btn btn-info">Confirm Borrow</button>
                        <a href="../index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END -->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END -->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>
