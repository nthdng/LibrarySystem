<?php
session_start();
error_reporting(0);
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to check if a record is in use in booktbl
function isRecordInUse($table, $column, $id) {
    global $dbh;
    $sql = "SELECT COUNT(*) as count FROM booktbl WHERE $column = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    return $result->count > 0;
}

// Function to delete and archive records
function deleteAndArchive($table, $id, $nameColumn, $bookColumn) {
    global $dbh;
    
    // Step 1: Check if the record is in use in the booktbl
    if (isRecordInUse('booktbl', $bookColumn, $id)) {
        // If record is in use, don't delete and set an error message
        return "The record cannot be deleted because it is being used.";
    }

    // Step 2: Fetch the record's name from the table
    $sql_select = "SELECT $nameColumn FROM $table WHERE ID=:id";
    $query_select = $dbh->prepare($sql_select);
    $query_select->bindParam(':id', $id, PDO::PARAM_INT);
    $query_select->execute();
    $result = $query_select->fetch(PDO::FETCH_OBJ);
    
    if ($result) {
        $name = $result->$nameColumn;

        // Step 3: Insert the name into the archive table
        $sql_insert = "INSERT INTO othersarchivedtbl (name) VALUES (:name)";
        $query_insert = $dbh->prepare($sql_insert);
        $query_insert->bindParam(':name', $name, PDO::PARAM_STR);
        $query_insert->execute();

        // Step 4: Delete the record from the original table
        $sql_delete = "DELETE FROM $table WHERE ID=:id";
        $query_delete = $dbh->prepare($sql_delete);
        $query_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $query_delete->execute();

        return true; // Deletion and archival succeeded
    } else {
        return "Record not found.";
    }
}

// Handle Category Deletion
if (isset($_GET['delcat'])) {
    $id = $_GET['delcat'];
    $result = deleteAndArchive('categorytbl', $id, 'categoryName', 'category');
    if ($result === true) {
        $_SESSION['delmsg'] = "Category deleted and archived successfully.";
    } else {
        $_SESSION['delmsg'] = $result;
    }
    header('location:othersListlib.php');
}

// Handle Format Deletion
if (isset($_GET['delfrmt'])) {
    $id = $_GET['delfrmt'];
    $result = deleteAndArchive('formattbl', $id, 'formatName', 'format');
    if ($result === true) {
        $_SESSION['delmsg'] = "Format deleted and archived successfully.";
    } else {
        $_SESSION['delmsg'] = $result;
    }
    header('location:othersListlib.php');
}

// Handle Shelf Deletion
if (isset($_GET['delshlf'])) {
    $id = $_GET['delshlf'];
    $result = deleteAndArchive('shelftbl', $id, 'shelfLoc', 'shelf');
    if ($result === true) {
        $_SESSION['delmsg'] = "Shelf deleted and archived successfully.";
    } else {
        $_SESSION['delmsg'] = $result;
    }
    header('location:othersListlib.php');
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Manage Books</title>

    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />

    <!-- CUSTOM STYLING -->
    <style>
        .header-line {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .panel-heading {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            background-color: #5cb85c;
            padding: 10px;
        }

        .btn-success {
            background-color: #5cb85c;
            border-color: #4cae4c;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary,
        .btn-danger {
            width: 100px;
        }

        .btn-primary {
            margin-right: 10px;
        }

        .btn-success.pull-right {
            margin-bottom: 15px;
        }

        img.book-cover {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Adjusting spacing for better alignment */
        .btn-success.pull-right {
            margin-bottom: 15px;
            margin-right: 10px;
        }

        /* Dropdown style */
        .dropdown-menu {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        /* Dropdown hover */
        .dropdown-menu>li>a:hover {
            background-color: #5cb85c;
            color: white;
        }
    </style>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END -->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Books</h4>

                    <a href="#" class="btn btn-success dropdown-toggle pull-right" id="ddlmenuItem"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        + ADD <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="ddlmenuItem">
                        <li><a href="addCategories.php">Add Category</a></li>
                        <li><a href="addFormat.php">Add Format</a></li>
                        <li><a href="addShelf.php">Add Shelf</a></li>
                    </ul>
                    <script>
                        $(document).ready(function () {
                            $('#ddlmenuItem').dropdown(); // Initialize the dropdown
                        });
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Category Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Category List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-category">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from categorytbl";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->categoryName); ?></td>
                                                    <td>
                                                        <a href="edit-book.php?bookid=<?php echo htmlentities($result->bookid); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                        <a href="manage-books.php?del=<?php echo htmlentities($result->bookid); ?>"
                                                           onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Category Tables -->

                    <!-- Advanced Format Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Format List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-Format">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from formattbl";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->formatName); ?></td>
                                                    <td>
                                                        <a href="edit-book.php?bookid=<?php echo htmlentities($result->bookid); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                        <a href="manage-books.php?del=<?php echo htmlentities($result->bookid); ?>"
                                                           onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Format Tables -->

                    <!-- Advanced Shelf Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Shelf List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-shelf">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from shelftbl";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->shelfLoc); ?></td>
                                                    <td>
                                                        <a href="edit-book.php?bookid=<?php echo htmlentities($result->bookid); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                        <a href="manage-books.php?del=<?php echo htmlentities($result->bookid); ?>"
                                                           onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Shelf Tables -->

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
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable for Category List
            $('#dataTables-category').DataTable({
                "lengthMenu": [10, 20, 50, 100], // Dropdown for selecting number of entries to show
                "pageLength": 10, // Default page length
                "searching": true, // Enable search bar
                "paging": true, // Enable pagination
                "ordering": true // Enable column ordering
            });

            // Initialize DataTable for Format List
            $('#dataTables-Format').DataTable({
                "lengthMenu": [10, 20, 50, 100],
                "pageLength": 10,
                "searching": true,
                "paging": true,
                "ordering": true
            });

            // Initialize DataTable for Shelf List
            $('#dataTables-shelf').DataTable({
                "lengthMenu": [10, 20, 50, 100],
                "pageLength": 10,
                "searching": true,
                "paging": true,
                "ordering": true
            });
        });
    </script>

</body>

</html>
