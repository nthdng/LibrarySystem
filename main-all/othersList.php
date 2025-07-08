<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['delF'])) {
    $formatID = $_GET['delF'];
    $archFor = 'Removed'; // Set archFor to archive

    $sql = "UPDATE formattbl SET archFor = :archFor WHERE formatID = :formatID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':formatID', $formatID, PDO::PARAM_INT);
    $query->bindParam(':archFor', $archFor, PDO::PARAM_STR); // Use PARAM_STR for enum

    if ($query->execute()) {
        echo "<script>alert('Format Successfully Removed and Archived'); window.location.href='othersList.php';</script>";
    } else {
        echo "<script>alert('Failed to remove the Format.'); window.location.href='othersList.php';</script>";
    }
}

// If deleting a Category record
else if (isset($_GET['delC'])) {
    $categoryID = $_GET['delC'];
    $archCat = 'Removed'; // Set archCat to archive

    $sql = "UPDATE categorytbl SET archCat = :archCat WHERE categoryID = :categoryID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
    $query->bindParam(':archCat', $archCat, PDO::PARAM_STR); // Use PARAM_STR for enum

    if ($query->execute()) {
        echo "<script>alert('Category Successfully Removed and Archived'); window.location.href='othersList.php';</script>";
    } else {
        echo "<script>alert('Failed to remove the category.'); window.location.href='othersList.php';</script>";
    }
}
// If deleting a Shelf record
else if (isset($_GET['delS'])) {
    $shelfID = $_GET['delS'];
    $archShelf = 'Removed'; // Set archShelf to archive

    $sql = "UPDATE shelftbl SET archShelf = :archShelf WHERE shelfID = :shelfID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':shelfID', $shelfID, PDO::PARAM_INT);
    $query->bindParam(':archShelf', $archShelf, PDO::PARAM_STR); // Use PARAM_STR for enum

    if ($query->execute()) {
        echo "<script>alert('Shelf Successfully Removed and Archived'); window.location.href='othersList.php';</script>";
    } else {
        echo "<script>alert('Failed to remove the Shelf.'); window.location.href='othersList.php';</script>";
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
    <title>Online Library Management System | Manage Others</title>

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
                    <h4 class="header-line">Manage Others</h4>

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
                                        <?php $sql = "SELECT * from categorytbl  WHERE archCat = 'Active'";
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
                                                        <a
                                                            href="edit-book.php?idCat=<?php echo htmlentities($result->categoryID); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i>
                                                                Edit</button>
                                                        </a>
                                                        <a href="othersList.php?delC=<?php echo htmlentities($result->categoryID); ?>"
                                                            onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i>
                                                                Delete</button>
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
                                        <?php $sql = "SELECT * from formattbl WHERE archFor = 'Active'";
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
                                                        <a
                                                            href="edit-book.php?idForm=<?php echo htmlentities($result->formatID); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i>
                                                                Edit</button>
                                                        </a>
                                                        <a href="othersList.php?delF=<?php echo htmlentities($result->formatID); ?>"
                                                            onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i>
                                                                Delete</button>
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
                                        <?php $sql = "SELECT * from shelftbl WHERE archShelf = 'Active'";
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
                                                        <a
                                                            href="edit-book.php?delShelf=<?php echo htmlentities($result->shelfID); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i>
                                                                Edit</button>
                                                        </a>
                                                        <a href="othersList.php?delS=<?php echo htmlentities($result->shelfID); ?>"
                                                            onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i>
                                                                Delete</button>
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