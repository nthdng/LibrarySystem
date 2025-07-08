<?php
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit; // Exit after redirection
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Reports</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <!-- MENU SECTION START -->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Reports</h4>
                    <a href="reports.php" class="btn btn-primary">Save</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reports
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Borrower's ID</th>
                                            <th>Borrower Name</th>
                                            <th>Borrow Date</th>
                                            <th>Return Date</th>
                                            <th>Book Name</th>
                                            <th>Penalty</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // SQL query to fetch data
                                        $sql = "SELECT 
                                            b.BorrowID,
                                            CASE 
                                                WHEN b.FacultyID IS NOT NULL THEN b.FacultyID
                                                WHEN b.StudentID IS NOT NULL THEN b.StudentID
                                                WHEN b.Username IS NOT NULL THEN b.Username
                                            END AS BorrowerID,
                                            CASE 
                                                WHEN b.FacultyID IS NOT NULL THEN CONCAT(f.Fname, ' ', f.Mname, ' ', f.Lname)
                                                WHEN b.StudentID IS NOT NULL THEN CONCAT(s.Fname, ' ', s.Mname, ' ', s.Lname)
                                                WHEN b.Username IS NOT NULL THEN b.Name
                                            END AS BorrowerName,
                                            b.BorrowDate,
                                            r.ReturnDate,
                                            bk.BookTitle AS BookName,
                                            r.Penalty,
                                            r.PenaltyStatus
                                        FROM borrowtbl b
                                        LEFT JOIN returntbl r ON b.BorrowID = r.BorrowID
                                        LEFT JOIN tblfaculty f ON b.FacultyID = f.FacultyID
                                        LEFT JOIN tblstudent s ON b.StudentID = s.StudentID
                                        LEFT JOIN booktbl bk ON b.BookID = bk.BookID
                                        WHERE 
                                            (b.FacultyID IS NOT NULL OR b.StudentID IS NOT NULL OR b.Username IS NOT NULL) 
                                            AND 
                                            (f.Fname IS NOT NULL OR s.Fname IS NOT NULL OR b.Name IS NOT NULL)";
                                        
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $cnt = 1; // Counter for row numbering
                                        
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                                // Logic to determine Payment Status
                                                $penaltyStatus = $result->PenaltyStatus;

                                                if ($penaltyStatus !== "Paid") { // Only update if not paid
                                                    if (floatval($result->Penalty) > 0) {
                                                        $penaltyStatus = "Unpaid";
                                                    } else {
                                                        $penaltyStatus = "No penalty";
                                                    }
                                                    // Update PenaltyStatus in the database
                                                    $updateSQL = "UPDATE returntbl SET PenaltyStatus = :penaltyStatus WHERE BorrowID = :borrowID";
                                                    $updateQuery = $dbh->prepare($updateSQL);
                                                    $updateQuery->bindParam(':penaltyStatus', $penaltyStatus, PDO::PARAM_STR);
                                                    $updateQuery->bindParam(':borrowID', $result->BorrowID, PDO::PARAM_INT);
                                                    $updateQuery->execute();
                                                }
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->BorrowerID); ?></td>
                                                    <td><?php echo htmlentities($result->BorrowerName); ?></td>
                                                    <td><?php echo htmlentities($result->BorrowDate); ?></td>
                                                    <td><?php echo htmlentities($result->ReturnDate ?: "Not yet returned"); ?></td>
                                                    <td><?php echo htmlentities($result->BookName); ?></td>
                                                    <td><?php echo htmlentities($result->Penalty ?: "No penalty"); ?></td>
                                                    <td><?php echo htmlentities($penaltyStatus); ?></td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">No records found</td>
                                                <td class="center">-</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for DataTables -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                "autoWidth": false
            });
        });
    </script>
</body>

</html>
