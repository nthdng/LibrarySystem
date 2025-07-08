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

// Function to update penalty status dynamically
function updatePenaltyStatus($dbh) {
    try {
        $sql = "
            UPDATE returntbl
            SET penaltyStatus = CASE
                WHEN penalty = 0 THEN 'No penalty'
                WHEN penalty > 0 AND penaltyStatus != 'Paid' THEN 'Unpaid'
                ELSE penaltyStatus
            END
        ";
        $query = $dbh->prepare($sql);
        $query->execute();
    } catch (Exception $e) {
        error_log("Error updating penalty status: " . $e->getMessage());
    }
}

// Update penalty status on every page load
updatePenaltyStatus($dbh);

// Handle payment confirmation
if (isset($_POST['confirmPayment'])) {
    $returnID = intval($_POST['returnID']);
    try {
        $sql = "UPDATE returntbl SET penaltyStatus = 'Paid' WHERE returnID = :returnID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':returnID', $returnID, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Payment confirmed successfully.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error confirming payment: " . $e->getMessage();
    }
    header('Location: Payments.php');
    exit();
}

// Handle archiving records
if (isset($_POST['archiveRecord'])) {
    $returnID = intval($_POST['returnID']);
    $arcReasonrt = htmlspecialchars($_POST['arcReasonrt']);
    try {
        $sql = "UPDATE returntbl SET returnarc = 'Archived', arcReasonrt = :arcReasonrt WHERE returnID = :returnID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':arcReasonrt', $arcReasonrt, PDO::PARAM_STR);
        $query->bindParam(':returnID', $returnID, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Record archived successfully.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error archiving the record: " . $e->getMessage();
    }
    header('Location: Payments.php');
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Manage Payments</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        function openArchiveModal(returnID) {
            document.getElementById('modalReturnID').value = returnID;
            $('#archiveModal').modal('show');
        }

        function confirmPaymentAlert() {
            return confirm('Are you sure you want to confirm this payment?');
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Payment List</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Payment Records</div>
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
                                            <th>Penalty</th>
                                            <th>Payment Status</th>
                                            <th>Confirm Payment</th>
                                            <th>Archive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "
                                            SELECT 
                                                rt.returnID, rt.penalty, rt.penaltyStatus, rt.returnarc, rt.arcReasonrt,
                                                b.bookTitle,
                                                CONCAT(COALESCE(s.Fname, f.Fname), ' ', COALESCE(s.Lname, f.Lname)) AS borrowerFullName,
                                                CASE
                                                    WHEN s.StudentID IS NOT NULL THEN 'Student'
                                                    WHEN f.FacultyID IS NOT NULL THEN 'Faculty'
                                                    ELSE 'Unknown'
                                                END AS role,
                                                COALESCE(s.Email, f.Email) AS borrowerEmail
                                            FROM 
                                                returntbl rt
                                            JOIN 
                                                borrowtbl br ON rt.borrowID = br.borrowID
                                            LEFT JOIN 
                                                tblstudent s ON br.StudentID = s.StudentID
                                            LEFT JOIN 
                                                tblfaculty f ON br.FacultyID = f.FacultyID
                                            JOIN 
                                                booktbl b ON br.bookID = b.bookID;
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
                                                    <td class="center"><?php echo htmlentities($result->role); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->borrowerEmail); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->penalty); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->penaltyStatus); ?></td>
                                                    <td class="center">
                                                        <?php if ($result->penaltyStatus === 'Unpaid') { ?>
                                                            <form method="post" onsubmit="return confirmPaymentAlert();" style="display:inline;">
                                                                <input type="hidden" name="returnID" value="<?php echo htmlentities($result->returnID); ?>">
                                                                <button type="submit" name="confirmPayment" class="btn btn-primary">Confirm Payment</button>
                                                            </form>
                                                        <?php } else { ?>
                                                            <?php echo htmlentities($result->penaltyStatus); ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="center">
                                                        <?php if ($result->returnarc === 'Existing') { ?>
                                                            <button class="btn btn-danger" onclick="openArchiveModal(<?php echo htmlentities($result->returnID); ?>)">
                                                                Archive
                                                            </button>
                                                        <?php } else { ?>
                                                            Archived: <?php echo htmlentities($result->arcReasonrt); ?>
                                                        <?php } ?>
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

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="Payments.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveModalLabel">Archive Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="returnID" id="modalReturnID">
                        <div class="form-group">
                            <label for="arcReasonrt">Reason for Archiving:</label>
                            <textarea name="arcReasonrt" id="arcReasonrt" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="archiveRecord" class="btn btn-danger">Archive</button>
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
