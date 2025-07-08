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

// Check if returnID is set in the URL for archiving
if (isset($_GET['returnID'])) {
    $returnID = intval($_GET['returnID']);

    // SQL to update the `returnarc` field to 'Archived'
    $sql = "UPDATE returntbl SET returnarc = 'Archived' WHERE returnID = :returnID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':returnID', $returnID, PDO::PARAM_INT);

    if ($query->execute()) {
        // Success message or redirection
        $_SESSION['msg'] = "Record archived successfully.";
    } else {
        $_SESSION['error'] = "Error archiving the record.";
    }

    // Redirect to the same page to refresh the list
    header('Location: ReturnList.php');
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Manage Returns</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        // JavaScript function to confirm archiving
        function confirmArchive(returnID) {
            const confirmation = confirm("Are you sure you want to archive this record?");
            if (confirmation) {
                // Redirect to archive the record
                window.location.href = "ReturnList.php?returnID=" + returnID;
            }
        }
    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Return List</h4>
                </div>
            </div>
            <!-- Display messages -->
            <?php
            if (isset($_SESSION['msg'])) {
                echo '<div class="alert alert-success">' . htmlentities($_SESSION['msg']) . '</div>';
                unset($_SESSION['msg']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . htmlentities($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Return Records
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Title</th>
                                            <th>Borrower's Full Name</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                            <!-- <th>Confirm Return</th> -->
                                            <th>Archive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "
                                            SELECT 
                                                rt.returnID,
                                                bk.bookTitle,
                                                CASE
                                                    WHEN br.FacultyID IS NOT NULL THEN tf.Email
                                                    WHEN br.StudentID IS NOT NULL THEN ts.Email
                                                END AS borrowerEmail,
                                                CASE
                                                    WHEN br.FacultyID IS NOT NULL THEN CONCAT(tf.Fname, ' ', LEFT(tf.Mname, 1), '. ', tf.Lname)
                                                    WHEN br.StudentID IS NOT NULL THEN CONCAT(ts.Fname, ' ', LEFT(ts.Mname, 1), '. ', ts.Lname)
                                                END AS borrowerFullName,
                                                CASE
                                                    WHEN br.FacultyID IS NOT NULL THEN 'Faculty'
                                                    WHEN br.StudentID IS NOT NULL THEN 'Student'
                                                END AS borrowerRole,
                                                CASE
                                                    WHEN rt.returnDate IS NULL THEN 'Not Returned Yet'
                                                    ELSE rt.returnDate
                                                END AS displayReturnDate,
                                                rt.status
                                            FROM 
                                                returntbl rt
                                            JOIN 
                                                borrowtbl br ON rt.borrowID = br.borrowID
                                            JOIN 
                                                booktbl bk ON br.bookID = bk.bookID
                                            LEFT JOIN 
                                                tblfaculty tf ON br.FacultyID = tf.FacultyID
                                            LEFT JOIN 
                                                tblstudent ts ON br.StudentID = ts.StudentID
                                            WHERE 
                                                rt.returnarc = 'Existing';
                                        ";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->bookTitle); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->borrowerFullName); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->borrowerRole); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->borrowerEmail); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->displayReturnDate); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->status); ?></td>
                                                    <!-- <td class="center">
                                                        <a
                                                            href="confirmReturn.php?returnID=<?php //echo htmlentities($result->returnID); ?>">
                                                            <button class="btn btn-primary">Confirm Return</button>
                                                        </a>
                                                    </td> -->
                                                    <td class="center">
                                                        <button class="btn btn-danger" onclick="confirmArchive(<?php echo htmlentities($result->returnID); ?>)">
                                                            Archive
                                                        </button>
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

    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>
