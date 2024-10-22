<?php
session_start();
include('../includes/config.php');
error_reporting(0);

// If deleting a book record
if (isset($_GET['del'])) {
    $id = $_GET['del'];

    // Retrieve the book data before deleting it
    $sqlSelect = "SELECT * FROM booktbl WHERE ID = :id";
    $querySelect = $dbh->prepare($sqlSelect);
    $querySelect->bindParam(':id', $id, PDO::PARAM_INT);
    $querySelect->execute();
    $bookData = $querySelect->fetch(PDO::FETCH_OBJ);

    if ($bookData) {
        // Insert data into archiveBookstbl
        $sqlArchive = "INSERT INTO archiveBookstbl (ID, bookCode, bookTitle, format, category, shelf, isbnNumber, count, accountNumber)
                   VALUES (:ID, :bookCode, :bookTitle, :format, :category, :shelf, :isbnNumber, :count, :accountNumber)";
        $queryArchive = $dbh->prepare($sqlArchive);
        $queryArchive->bindParam(':ID', $bookData->ID, PDO::PARAM_INT); // Book ID
        $queryArchive->bindParam(':bookCode', $bookData->bookCode, PDO::PARAM_STR);
        $queryArchive->bindParam(':bookTitle', $bookData->bookTitle, PDO::PARAM_STR);
        $queryArchive->bindParam(':format', $bookData->format, PDO::PARAM_INT);
        $queryArchive->bindParam(':category', $bookData->category, PDO::PARAM_INT);
        $queryArchive->bindParam(':shelf', $bookData->shelf, PDO::PARAM_INT);
        $queryArchive->bindParam(':isbnNumber', $bookData->isbnNumber, PDO::PARAM_STR);
        $queryArchive->bindParam(':count', $bookData->count, PDO::PARAM_INT);
        $queryArchive->bindParam(':accountNumber', $bookData->accountNumber, PDO::PARAM_INT);
        $queryArchive->execute();

        // Delete the record from booktbl
        $sqlDelete = "DELETE FROM booktbl WHERE ID = :id";
        $queryDelete = $dbh->prepare($sqlDelete);
        $queryDelete->bindParam(':id', $id, PDO::PARAM_INT);
        $queryDelete->execute();

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
                        <button class="btn btn-info dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown">
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
                                        $sql = "SELECT b.ID, b.bookCode, b.bookTitle, b.isbnNumber, 
                                                    c.categoryName, f.formatName, s.shelfLoc, b.accountNumber 
                                                FROM booktbl b 
                                                LEFT JOIN categorytbl c ON b.category = c.ID 
                                                LEFT JOIN formattbl f ON b.format = f.ID 
                                                LEFT JOIN shelftbl s ON b.shelf = s.ID";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->accountNumber); ?></td>

                                                    <td class="center"><?php echo htmlentities($result->bookCode); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->bookTitle); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->isbnNumber); ?></td>
                                                    <td class="center category-col"><?php echo htmlentities($result->categoryName); ?></td>
                                                    <td class="center format-col"><?php echo htmlentities($result->formatName); ?></td>
                                                    <td class="center shelf-col"><?php echo htmlentities($result->shelfLoc); ?></td>

                                                    <td class="center">
                                                        <!-- Update Book button -->
                                                        <a href="updateBooks.php?bookid=<?php echo htmlentities($result->ID); ?>">
                                                            <button class="btn btn-primary">Update</button></a>

                                                        <!-- Delete Book button -->
                                                        <a href="bookManagement.php?del=<?php echo htmlentities($result->ID); ?>"
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
    <!-- CORE JQUERY  -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="../assets/js/custom.js"></script>

    <script>
        $(document).ready(function () {
    $('#ddlmenuItem').dropdown(); // Initialize the Add Book dropdown

    // Handle filter selection
    $('#filterCategory').click(function() {
        hideAllColumns(); // First hide all columns
        showColumn('.category-col'); // Show only the Category column
    });

    $('#filterFormat').click(function() {
        hideAllColumns(); // First hide all columns
        showColumn('.format-col'); // Show only the Format column
    });

    $('#filterShelf').click(function() {
        hideAllColumns(); // First hide all columns
        showColumn('.shelf-col'); // Show only the Shelf column
    });

    $('#filterAll').click(function() {
        showAllColumns(); // Show all columns
    });

    function hideAllColumns() {
        // Hide all the columns (both header and data cells)
        $('.category-col, .format-col, .shelf-col').hide();
    }

    function showColumn(columnClass) {
        // Show only the specified column (both header and data cells)
        $(columnClass).show();
    }

    function showAllColumns() {
        // Show all columns (both header and data cells)
        $('.category-col, .format-col, .shelf-col').show();
    }
});

    </script>

</body>
</html>


