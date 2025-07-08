<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}

if (isset($_POST['add'])) {
    // Handle new category
    if (!empty($_POST['new_category'])) {
        $newCategory = $_POST['new_category'];
        $sql = "INSERT INTO categorytbl (categoryName) VALUES (:newCategory)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':newCategory', $newCategory, PDO::PARAM_STR);
        $query->execute();
        $category = $dbh->lastInsertId(); // Use new category ID
    } else {
        $category = $_POST['category'];
    }

    // Handle new format
    if (!empty($_POST['new_format'])) {
        $newFormat = $_POST['new_format'];
        $sql = "INSERT INTO formattbl (formatName) VALUES (:newFormat)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':newFormat', $newFormat, PDO::PARAM_STR);
        $query->execute();
        $format = $dbh->lastInsertId(); // Use new format ID
    } else {
        $format = $_POST['format'];
    }

    // Gather other data from the form
    $bookTitle = $_POST['bookname'];
    $bookCode = $_POST['bookcode'];
    $archBook = 'Existing';
    $isbn = $_POST['isbn'];
    $shelf = $_POST['shelf'];
    $accountNumber = $_POST['accountNumber'];
    $bookCount = $_POST['count'];

    // Check for duplicate ISBN
    $duplicateCheckQuery = "SELECT * FROM booktbl WHERE isbnNumber = :isbn";
    $checkQuery = $dbh->prepare($duplicateCheckQuery);
    $checkQuery->bindParam(':isbn', $isbn, PDO::PARAM_STR);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Book or ISBN already registered. Please try again.'); window.location.href='bookManagement.php';</script>";
    } else {
        // Insert into booktbl
        $sql = "INSERT INTO booktbl (bookTitle, bookCode, category, format, isbnNumber, shelf, accountNumber, count, archBook)
                VALUES (:bookTitle, :bookCode, :category, :format, :isbn, :shelf, :accountNumber, :bookCount, :archBook)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookTitle', $bookTitle, PDO::PARAM_STR);
        $query->bindParam(':bookCode', $bookCode, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_INT);
        $query->bindParam(':format', $format, PDO::PARAM_INT);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->bindParam(':shelf', $shelf, PDO::PARAM_STR);
        $query->bindParam(':accountNumber', $accountNumber, PDO::PARAM_INT);
        $query->bindParam(':bookCount', $bookCount, PDO::PARAM_INT);
        $query->bindParam(':archBook', $archBook, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            echo "<script>alert('Book added successfully');</script>";
            echo "<script>window.location.href='bookManagement.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
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
    <title>Online Library Management System | Add Book</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const categoryDropdown = document.getElementById('category-dropdown');
            const categoryInput = document.getElementById('new-category');
            const toggleCategoryButton = document.getElementById('toggle-category');

            const formatDropdown = document.getElementById('format-dropdown');
            const formatInput = document.getElementById('new-format');
            const toggleFormatButton = document.getElementById('toggle-format');

            // Toggle between dropdown and input for Category
            toggleCategoryButton.addEventListener('click', function () {
                if (categoryDropdown.style.display === 'none') {
                    categoryDropdown.style.display = 'block';
                    categoryInput.style.display = 'none';
                    categoryInput.value = ''; // Clear input field
                    this.textContent = 'Add New';
                } else {
                    categoryDropdown.style.display = 'none';
                    categoryInput.style.display = 'block';
                    this.textContent = 'Use Dropdown';
                }
            });

            // Toggle between dropdown and input for Format
            toggleFormatButton.addEventListener('click', function () {
                if (formatDropdown.style.display === 'none') {
                    formatDropdown.style.display = 'block';
                    formatInput.style.display = 'none';
                    formatInput.value = ''; // Clear input field
                    this.textContent = 'Add New';
                } else {
                    formatDropdown.style.display = 'none';
                    formatInput.style.display = 'block';
                    this.textContent = 'Use Dropdown';
                }
            });

            // Custom form validation to ensure one input is filled
            document.querySelector('form').addEventListener('submit', function (e) {
                if (
                    categoryDropdown.style.display !== 'none' && !categoryDropdown.value ||
                    categoryInput.style.display !== 'none' && !categoryInput.value
                ) {
                    e.preventDefault();
                    alert('Please select or add a category.');
                }

                if (
                    formatDropdown.style.display !== 'none' && !formatDropdown.value ||
                    formatInput.style.display !== 'none' && !formatInput.value
                ) {
                    e.preventDefault();
                    alert('Please select or add a format.');
                }
            });
        });
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Add Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">Book Info</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <!-- Book Code -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Code<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookcode" required />
                                    </div>
                                </div>
                                <!-- Book Title -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Title<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname" required />
                                    </div>
                                </div>
                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <div id="category-container">
                                            <select class="form-control" id="category-dropdown" name="category" style="display: block;">
                                                <option value="">Select Category</option>
                                                <?php
                                                $sql = "SELECT * FROM categorytbl";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) { ?>
                                                        <option value="<?php echo htmlentities($result->categoryID); ?>">
                                                            <?php echo htmlentities($result->categoryName); ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                            <input type="text" class="form-control" id="new-category" name="new_category"
                                                placeholder="Add new category" style="display:none;" />
                                            <button type="button" class="btn btn-secondary" id="toggle-category">Add New</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Format -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Format<span style="color:red;">*</span></label>
                                        <div id="format-container">
                                            <select class="form-control" id="format-dropdown" name="format" style="display: block;">
                                                <option value="">Select Format</option>
                                                <?php
                                                $sql = "SELECT * FROM formattbl";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) { ?>
                                                        <option value="<?php echo htmlentities($result->formatID); ?>">
                                                            <?php echo htmlentities($result->formatName); ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                            <input type="text" class="form-control" id="new-format" name="new_format"
                                                placeholder="Add new format" style="display:none;" />
                                            <button type="button" class="btn btn-secondary" id="toggle-format">Add New</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- ISBN Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ISBN Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="isbn" id="isbn"
                                            required autocomplete="off" />
                                    </div>
                                </div>
                                <!-- Shelf Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shelf<span style="color:red;">*</span></label>
                                        <select class="form-control" name="shelf" required>
                                            <option value="">Select Shelf</option>
                                            <?php
                                            $sql = "SELECT * FROM shelftbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <option value="<?php echo htmlentities($result->shelfID); ?>">
                                                        <?php echo htmlentities($result->shelfLoc); ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Account Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="accountNumber" required />
                                    </div>
                                </div>
                                <!-- Book Count -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Count<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="count" required />
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button type="submit" name="add" class="btn btn-info">Submit</button>
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
