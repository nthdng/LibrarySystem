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

// Handle mass archiving (NEW CODE STARTS HERE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'massArchive') {
    $bookIDs = $_POST['bookIDs'];
    $archBook = 'Archived';

    if (!empty($bookIDs) && is_array($bookIDs)) {
        $placeholders = implode(',', array_fill(0, count($bookIDs), '?')); // Create placeholders
        $sql = "UPDATE booktbl SET archBook = ? WHERE bookID IN ($placeholders)";
        $query = $dbh->prepare($sql);

        // Bind values: archBook first, then all book IDs
        $params = array_merge([$archBook], $bookIDs);

        if ($query->execute($params)) {
            echo "Selected books successfully archived.";
        } else {
            echo "Failed to archive selected books.";
        }
    } else {
        echo "No valid book IDs provided.";
    }
    exit;
}
// Handle mass archiving (NEW CODE ENDS HERE)

// If deleting a book record
if (isset($_GET['del'])) {
    $bookID = $_GET['del'];
    $archBook = 'Archived'; // Set archBook to archive

    $sql = "UPDATE booktbl SET archBook = :archBook WHERE bookID = :bookID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookID', $bookID, PDO::PARAM_INT);
    $query->bindParam(':archBook', $archBook, PDO::PARAM_STR); // Use PARAM_STR for enum

    if ($query->execute()) {
        echo "<script>alert('Book Successfully Removed and Archived'); window.location.href='bookManagement.php';</script>";
    } else {
        echo "<script>alert('Book not found!'); window.location.href='bookManagement.php';</script>";
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
    <title>Library Management System | Manage Books</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END-->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Books</h4>

                    <!-- Mass Archive Button (NEW BUTTON ADDED) -->
                    <button id="archiveSelected" class="btn btn-warning pull-right">Archive Selected</button>

                    <!-- Add Book Dropdown -->
                    <a href="#" class="btn btn-success dropdown-toggle pull-right" id="ddlmenuItem"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        + ADD <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="ddlmenuItem">
                        <li><a href="importBooks.php">Import Books</a></li>
                        <li><a href="addBooks.php">Add New Books</a></li>
                    </ul>

                    <!-- Filter Dropdown -->
                    <div class="dropdown pull-right" style="margin-right: 10px;">
                        <button class="btn btn-info dropdown-toggle" type="button" id="filterDropdown"
                            data-toggle="dropdown">
                            Filter Books <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="filterDropdown">
                            <li><a href="#" id="filterCategory">Category</a></li>
                            <li><a href="#" id="filterFormat">Format</a></li>
                            <li><a href="#" id="filterShelf">Shelf</a></li>
                            <li><a href="#" id="filterAll">Show All</a></li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Books List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <!-- New Column for Checkboxes -->
                                            <th><input type="checkbox" id="selectAll"></th> <!-- Select All -->
                                            <th>#</th>
                                            <th>Account #</th>
                                            <th>Book Code</th>
                                            <th>Book Title</th>
                                            <th>ISBN Number</th>
                                            <th class="category-col">Category</th>
                                            <th class="format-col">Format</th>
                                            <th class="shelf-col">Shelf</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query to fetch books along with category, format, and shelf names
                                        $sql = "SELECT b.bookID, b.bookCode, b.bookTitle, b.isbnNumber, 
                                                    c.categoryName, f.formatName, s.shelfLoc, b.accountNumber 
                                                FROM booktbl b 
                                                LEFT JOIN categorytbl c ON b.category = c.categoryID 
                                                LEFT JOIN formattbl f ON b.format = f.formatID 
                                                LEFT JOIN shelftbl s ON b.shelf = s.shelfID
                                                WHERE b.archBook = 'Existing'";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <!-- New Checkbox for Mass Selection -->
                                                    <td><input type="checkbox" class="book-checkbox"
                                                            value="<?php echo htmlentities($result->bookID); ?>"></td>
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->accountNumber); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->bookCode); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->bookTitle); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->isbnNumber); ?></td>
                                                    <td class="center category-col">
                                                        <?php echo htmlentities($result->categoryName); ?>
                                                    </td>
                                                    <td class="center format-col">
                                                        <?php echo htmlentities($result->formatName); ?>
                                                    </td>
                                                    <td class="center shelf-col"><?php echo htmlentities($result->shelfLoc); ?>
                                                    </td>

                                                    <td class="center">
                                                        <!-- Update Book button -->
                                                        <a
                                                            href="updateBooks.php?bookid=<?php echo htmlentities($result->bookID); ?>">
                                                            <button class="btn btn-primary">Update</button></a>

                                                        <!-- Delete Book button -->
                                                        <a href="bookManagement.php?del=<?php echo htmlentities($result->bookID); ?>"
                                                            onclick="return confirm('Are you sure you want to remove this book?');">
                                                            <button class="btn btn-danger">Remove</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->

    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>

    <!-- New JavaScript for Mass Archive Feature -->
    <script>
        $(document).ready(function () {
            // Select All functionality
            $('#selectAll').click(function () {
                $('.book-checkbox').prop('checked', this.checked);
            });

            // Archive Selected Books
            $('#archiveSelected').click(function () {
                let selectedBooks = [];
                $('.book-checkbox:checked').each(function () {
                    selectedBooks.push($(this).val());
                });

                if (selectedBooks.length === 0) {
                    alert('No books selected.');
                    return;
                }

                if (confirm('Are you sure you want to archive the selected books?')) {
                    $.ajax({
                        url: 'bookManagement.php',
                        method: 'POST',
                        data: { action: 'massArchive', bookIDs: selectedBooks },
                        success: function (response) {
                            alert(response);
                            location.reload();
                        },
                        error: function () {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Filtering functionality
            $('#filterCategory').click(function () {
                $('.category-col').show();
                $('.format-col, .shelf-col').hide();
            });

            $('#filterFormat').click(function () {
                $('.format-col').show();
                $('.category-col, .shelf-col').hide();
            });

            $('#filterShelf').click(function () {
                $('.shelf-col').show();
                $('.category-col, .format-col').hide();
            });

            $('#filterAll').click(function () {
                $('.category-col, .format-col, .shelf-col').show();
            });
        });

    </script>
</body>

</html>