<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}
if (!isset($_GET['bookid']) || empty($_GET['bookid'])) {
    echo "No book ID was provided!";
    exit();
} else {
    $bookID = $_GET['bookid'];

    // Query the book details
    $sql = "SELECT * FROM booktbl WHERE bookID = :bookID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookID', $bookID, PDO::PARAM_INT);
    $query->execute();
    $book = $query->fetch(PDO::FETCH_OBJ);  // Storing the book details in $book

    if (!$book) {
        echo "Book not found!";
        exit();
    }
}

if (isset($_POST['update'])) {
    // Collecting data from the form
    $bookCode = $_POST['bookcode'];
    $bookTitle = $_POST['bookname'];
    $format = $_POST['format'];
    $category = $_POST['category'];
    $shelf = $_POST['shelf'];
    $isbnNumber = $_POST['isbn_hidden']; // Use the hidden ISBN field
    $count = $_POST['count'];
    $accountNumber = $_POST['accountNumber'];

    // Update query for the book (without updating the ISBN)
    $sql = "UPDATE booktbl 
    SET bookCode = :bookcode, bookTitle = :bookname, format = :format, category = :category, 
        shelf = :shelf, count = :count, accountNumber = :accountNumber
    WHERE bookID = :bookID AND isbnNumber = :isbn";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookID', $bookID, PDO::PARAM_INT);
    $query->bindParam(':bookcode', $bookCode, PDO::PARAM_STR);
    $query->bindParam(':bookname', $bookTitle, PDO::PARAM_STR);
    $query->bindParam(':format', $format, PDO::PARAM_INT);
    $query->bindParam(':category', $category, PDO::PARAM_INT);
    $query->bindParam(':shelf', $shelf, PDO::PARAM_INT);
    $query->bindParam(':isbn', $isbnNumber, PDO::PARAM_STR);  // Reference the ISBN in the WHERE clause
    $query->bindParam(':count', $count, PDO::PARAM_INT);
    $query->bindParam(':accountNumber', $accountNumber, PDO::PARAM_INT);

    $updateSuccess = $query->execute();

    if ($updateSuccess) {
        echo "<script>alert('Updated successfully'); window.location.href='bookManagement.php';</script>";
    } else {
        echo "<script>alert('Update failed! Please try again.'); window.location.href='bookManagement.php';</script>";
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
    <title>Online Library Management System | Update Books</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">UPDATE BOOKS</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">UPDATE BOOK</div>
                        <div class="panel-body">
                            <form name="update" method="post">
                                <!-- Book Code -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Code<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookcode"
                                            value="<?php echo htmlentities($book->bookCode); ?>" />
                                    </div>
                                </div>

                                <!-- Book Title -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Title<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname"
                                            value="<?php echo htmlentities($book->bookTitle); ?>" />
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <select class="form-control" name="category" required="required">
                                            <option value="">Select Category</option>
                                            <?php
                                            $sql = "SELECT * FROM categorytbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($categories as $category) { ?>
                                                <option value="<?php echo htmlentities($category->categoryID); ?>" <?php if ($category->categoryID == $book->category)
                                                       echo 'selected'; ?>>
                                                    <?php echo htmlentities($category->categoryName); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Format -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Format<span style="color:red;">*</span></label>
                                        <select class="form-control" name="format" required="required">
                                            <option value="">Select Format</option>
                                            <?php
                                            $sql = "SELECT * FROM formattbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $formats = $query->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($formats as $format) { ?>
                                                <option value="<?php echo htmlentities($format->formatID); ?>" <?php if ($format->formatID == $book->format)
                                                       echo 'selected'; ?>>
                                                    <?php echo htmlentities($format->formatName); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- ISBN Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ISBN Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="isbn"
                                            value="<?php echo htmlentities($book->isbnNumber); ?>" readonly />
                                        <input type="hidden" name="isbn_hidden"
                                            value="<?php echo htmlentities($book->isbnNumber); ?>" />
                                    </div>
                                </div>
                                <!-- Shelf -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shelf<span style="color:red;">*</span></label>
                                        <select class="form-control" name="shelf" required="required">
                                            <option value="">Select Shelf</option>
                                            <?php
                                            $sql = "SELECT * FROM shelftbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $shelves = $query->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($shelves as $shelf) { ?>
                                                <option value="<?php echo htmlentities($shelf->shelfID); ?>" <?php if ($shelf->shelfID == $book->shelf)
                                                       echo 'selected'; ?>>
                                                    <?php echo htmlentities($shelf->shelfLoc); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Account Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="accountNumber"
                                            value="<?php echo htmlentities($book->accountNumber); ?>" />
                                    </div>
                                </div>

                                <!-- Book Count -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Count<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="count"
                                            value="<?php echo htmlentities($book->count); ?>" />
                                    </div>
                                </div>

                                <button type="submit" name="update" class="btn btn-primary" id="update">Update</button>
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