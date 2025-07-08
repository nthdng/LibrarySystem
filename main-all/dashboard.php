<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');


if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}

// Get the username and role from the session
$username = $_SESSION['login'];
$role = $_SESSION['role'];

// Set the dashboard title and style class based on the role
if ($role === 'Admin') {
    $dashboardTitle = "Admin Dashboard";
    $titleClass = "text-danger"; // Red color for Admin
} elseif ($role === 'Librarian') {
    $dashboardTitle = "Librarian Dashboard";
    $titleClass = "text-success"; // Green color for Librarian
}

function updateOverdueStatus($dbh) {
    try {
        // Query to find and update overdue records
        $sql = "
            UPDATE returntbl rt
            JOIN borrowtbl br ON rt.borrowID = br.borrowID
            SET rt.status = 'overdue'
            WHERE rt.status = 'borrowed' AND br.expectedReturnDate < CURDATE();
        ";
        $query = $dbh->prepare($sql);
        $query->execute();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating overdue status: " . $e->getMessage();
    }
}

// Call the function to update overdue records
updateOverdueStatus($dbh);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $dashboardTitle; ?></title>
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
    <!-- Include header section -->
    <?php include('../includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <!-- Dynamically set the h4 header based on role, displaying username -->
                    <h4 class="header-line <?php echo $titleClass; ?>">
                        <?php echo $dashboardTitle . " - " . htmlentities($username); ?>
                    </h4>
                </div>
            </div>

            <div class="row">
                <a href="pendingList.php">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="alert alert-warning back-widget-set text-center">
                            <i class="fa fa-inbox fa-5x"></i>
                            <?php

                            // Fetch number of books not returned
                            $sqlPending = "SELECT COUNT(*) AS count FROM borrowtbl WHERE status = 'pending' AND borrowarc = 'Existing'";
                            $queryPending = $dbh->prepare($sqlPending);
                            $queryPending->execute();
                            $pendingCount = $queryPending->fetch(PDO::FETCH_ASSOC)['count'];
                            ?>
                            <h3><?php echo htmlentities($pendingCount); ?></h3>
                            Pending Borrows
                        </div>
                    </div>
                </a>

                <a href="bookManagement.php">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <?php
                            // Fetch number of listed books
                            $sql = "SELECT bookID  from booktbl WHERE archBook = 'Existing'";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $listdbooks = $query->rowCount();
                            ?>
                            <h3><?php echo htmlentities($listdbooks); ?></h3>
                            Books Listed
                        </div>
                    </div>
                </a>

                <a href="borrowlist.php">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="alert alert-warning back-widget-set text-center">
                            <i class="fa fa-recycle fa-5x"></i>
                            <?php

                            // Fetch number of books not returned
                            $sqlBorrowed = "SELECT COUNT(*) AS count FROM returntbl WHERE status = 'borrowed' AND returnarc = 'Existing'";
                            $queryBorrowed = $dbh->prepare($sqlBorrowed);
                            $queryBorrowed->execute();
                            $borrowedCount = $queryBorrowed->fetch(PDO::FETCH_ASSOC)['count'];
                            ?>
                            <h3><?php echo htmlentities($borrowedCount); ?></h3>
                            Borrowed Books
                        </div>
                    </div>
                </a>

                <a href="borrowlist.php">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="alert alert-warning back-widget-set text-center">
                            <i class="fa fa-exclamation fa-5x"></i>
                            <?php

                            // Fetch number of books not returned
                            $sqlOverdue = "SELECT COUNT(*) AS count FROM returntbl WHERE status = 'overdue' AND returnarc = 'Existing'";
                            $queryOverdue = $dbh->prepare($sqlOverdue);
                            $queryOverdue->execute();
                            $overdueCount = $queryOverdue->fetch(PDO::FETCH_ASSOC)['count'];
                            ?>
                            <h3><?php echo htmlentities($overdueCount); ?></h3>
                            Overdue Books
                        </div>
                    </div>
                </a>

                <?php if ($role === 'Admin') { ?>
                    <a href="userList.php">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="alert alert-danger back-widget-set text-center">
                                <i class="fa fa-users fa-5x"></i>
                                <?php
                                // Fetch number of registered users
                                $sql3 = "SELECT userID  FROM tbluser WHERE archUser = 'Active' AND Status = 'Activated'";
                                $query3 = $dbh->prepare($sql3);
                                $query3->execute();
                                $regstds = $query3->rowCount();
                                ?>
                                <h3><?php echo htmlentities($regstds); ?></h3>
                                Active Registered Users
                            </div>
                        </div>
                    </a>
                <?php } ?>

                <!-- <a href="manage-authors.php">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="alert alert-success back-widget-set text-center">
                                <i class="fa fa-user fa-5x"></i>
                                <?php
                                // Fetch number of authors (assuming this is needed)
                                // $sql4 = "SELECT id FROM tblauthors";
                                // $query4 = $dbh->prepare($sql4);
                                // $query4->execute();
                                // $authorsCount = $query4->rowCount();
                                ?>
                                <h3><?php //echo htmlentities($authorsCount); ?></h3>
                                Authors Registered
                            </div>
                        </div>
                    </a> -->
            </div>
        </div>
    </div>

    <!-- Footer section -->
    <?php include('../includes/footer.php'); ?>

    <!-- JavaScript files placed at the bottom to reduce loading time -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>

    <script>
        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });
    </script>

</body>

</html>