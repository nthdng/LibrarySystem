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
    $sql = "SELECT b.bookID, b.bookCode, b.bookTitle, b.isbnNumber, c.categoryName, b.count, 
                   b.format, b.shelf, b.regDate 
            FROM booktbl b
            LEFT JOIN categorytbl c ON b.category = c.categoryID
            WHERE b.bookID = :bookID AND b.archBook = 'Existing'";
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
            // $borrowDate = date('Y-m-d H:i:s');
            // $expectedReturnDate = date('Y-m-d H:i:s', strtotime('+1 day')); // 1 days from now

            $insertSql = "INSERT INTO borrowtbl (bookID, status) 
                          VALUES (:bookID, 'pending')";
            $insertQuery = $dbh->prepare($insertSql);
            $insertQuery->bindParam(':bookID', $bookID, PDO::PARAM_INT);
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
        echo "<script>alert('Selected book is currently out of stack'); window.location.href='../index.php';</script>";;
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Details
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
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
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->format); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Shelf</label>
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->shelf); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Registered Date</label>
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->regDate); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Available Copies</label>
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->count); ?>" readonly />
                                </div>

                                <button type="submit" name="confirm_borrow" class="btn btn-info">Confirm Borrow</button>
                                <a href="../index.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END -->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END -->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>
</body>

</html>
