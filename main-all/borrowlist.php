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

// Function to update overdue status
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

// Archive action for both return and borrow data
if (isset($_POST['archiveBorrow'])) {
    $borrowID = intval($_POST['borrowID']);
    $borrowReason = $_POST['borrowReason'];
    $returnReason = $_POST['returnReason'];
    $customBorrowReason = !empty($_POST['customBorrowReason']) ? $_POST['customBorrowReason'] : null;
    $customReturnReason = !empty($_POST['customReturnReason']) ? $_POST['customReturnReason'] : null;

    $finalBorrowReason = $borrowReason === 'Other' ? $customBorrowReason : $borrowReason;
    $finalReturnReason = $returnReason === 'Other' ? $customReturnReason : $returnReason;

    try {
        $dbh->beginTransaction();

        // Archive in borrowtbl
        $sqlBorrow = "UPDATE borrowtbl SET borrowarc = 'Archived', arcReason = :borrowReason WHERE borrowID = :borrowID";
        $queryBorrow = $dbh->prepare($sqlBorrow);
        $queryBorrow->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $queryBorrow->bindParam(':borrowReason', $finalBorrowReason, PDO::PARAM_STR);
        $queryBorrow->execute();

        // Archive in returntbl
        $sqlReturn = "UPDATE returntbl SET returnarc = 'Archived', arcReasonrt = :returnReason WHERE borrowID = :borrowID";
        $queryReturn = $dbh->prepare($sqlReturn);
        $queryReturn->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $queryReturn->bindParam(':returnReason', $finalReturnReason, PDO::PARAM_STR);
        $queryReturn->execute();

        $dbh->commit();

        $_SESSION['msg'] = "Record archived successfully.";
    } catch (Exception $e) {
        $dbh->rollBack();
        $_SESSION['error'] = "Error archiving the record: " . $e->getMessage();
    }

    header('Location: borrowlist.php');
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Manage Borrow List</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        function openArchiveModal(borrowID) {
            document.getElementById('modalBorrowID').value = borrowID;
            $('#archiveModal').modal('show');
        }

        function toggleCustomReason(target, customFieldID) {
            const value = document.getElementById(target).value;
            const customDiv = document.getElementById(customFieldID);
            customDiv.style.display = (value === 'Other') ? 'block' : 'none';
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Borrow List</h4>
                </div>
            </div>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Borrow Records</div>
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
                                            <th>Expected Return Date</th>
                                            <th>Status</th>
                                            <th>Confirm Return</th>
                                            <th>Archive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "
                                            SELECT 
                                                rt.returnID,
                                                br.borrowID,
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
                                                br.expectedReturnDate,
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
                                                br.borrowarc = 'Existing';
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
                                                    <td class="center"><?php echo htmlentities($result->expectedReturnDate); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->status); ?></td>
                                                    <td class="center">
                                                        <a
                                                            href="confirmReturn.php?returnID=<?php echo htmlentities($result->returnID); ?>">
                                                            <button class="btn btn-primary">Confirm Return</button>
                                                        </a>
                                                    </td>
                                                    <td class="center">
                                                        <button class="btn btn-danger"
                                                            onclick="openArchiveModal(<?php echo htmlentities($result->borrowID); ?>)">
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="borrowlist.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveModalLabel">Archive Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="borrowID" id="modalBorrowID">
                        <div class="form-group">
                            <label for="borrowReason">Reason for Borrow Record:</label>
                            <select name="borrowReason" id="borrowReason" class="form-control"
                                onchange="toggleCustomReason('borrowReason', 'customBorrowReasonDiv')">
                                <option value="Not Needed">Not Needed</option>
                                <option value="Other">Other (please specify)</option>
                            </select>
                        </div>
                        <div class="form-group" id="customBorrowReasonDiv" style="display: none;">
                            <label for="customBorrowReason">Custom Borrow Reason:</label>
                            <input type="text" name="customBorrowReason" id="customBorrowReason" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="returnReason">Reason for Return Record:</label>
                            <select name="returnReason" id="returnReason" class="form-control"
                                onchange="toggleCustomReason('returnReason', 'customReturnReasonDiv')">
                                <option value="Not Needed">Not Needed</option>
                                <option value="Completed">Completed</option>
                                <option value="Other">Other (please specify)</option>
                            </select>
                        </div>
                        <div class="form-group" id="customReturnReasonDiv" style="display: none;">
                            <label for="customReturnReason">Custom Return Reason:</label>
                            <input type="text" name="customReturnReason" id="customReturnReason" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="archiveBorrow" class="btn btn-danger">Archive</button>
                    </div>
                </form>
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
