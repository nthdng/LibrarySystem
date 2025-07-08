<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Redirect to login if not authenticated
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Transaction History</title>
    <!-- Styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <!-- Include header -->
    <?php include('../includes/header.php'); ?>

    <!-- Content Section -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Page Header -->
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Student Transaction History</h4>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Transaction History
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Book Name</th>
                                            <th>Borrowed Date</th>
                                            <th>Returned Date</th>
                                            <th>Penalty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Query to fetch transaction history
                                        $sql = "SELECT 
                                                    s.StudentID, 
                                                    CONCAT(s.Fname, ' ', s.Mname, ' ', s.Lname) AS StudentName, 
                                                    b.bookTitle AS BookName, 
                                                    br.borrowDate AS BorrowedDate, 
                                                    CASE 
                                                        WHEN rt.returnDate IS NULL THEN 'Not yet returned'
                                                        ELSE rt.returnDate 
                                                    END AS ReturnedDate,
                                                    CASE 
                                                        WHEN rt.penalty = 0 THEN 'No penalty'
                                                        ELSE CONCAT('₱', FORMAT(rt.penalty, 2)) 
                                                    END AS Penalty 
                                                FROM tblstudent s
                                                JOIN borrowtbl br ON s.StudentID = br.StudentID
                                                JOIN booktbl b ON br.bookID = b.bookID
                                                LEFT JOIN returntbl rt ON br.borrowID = rt.borrowID
                                                WHERE s.archStd = 'Active'
                                                  AND br.borrowarc = 'Existing'
                                                  AND (rt.returnarc = 'Existing' OR rt.returnarc IS NULL)";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        // Render table rows
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->StudentName); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->BookName); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->BorrowedDate); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->ReturnedDate); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->Penalty); ?></td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No transaction history found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>
