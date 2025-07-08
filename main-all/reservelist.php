<?php
session_start(); // Start the session
error_reporting(0);
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}

// Function to fetch borrow records with "confirmed" status
function getBorrowRecords($dbh)
{
    $sql = "SELECT 
            borrowtbl.borrowID, 
            booktbl.bookTitle, 
            borrowtbl.status
        FROM 
            borrowtbl
        JOIN 
            booktbl 
        ON 
            borrowtbl.bookID = booktbl.bookID
        WHERE 
            borrowtbl.borrowarc = 'Existing' AND borrowtbl.status = 'confirmed'";
    $query = $dbh->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

// Handle archive action
if (isset($_POST['archiveBorrow'])) {
    $borrowID = intval($_POST['borrowID']);
    $reason = $_POST['reason'];
    $customReason = $_POST['customReason'];

    // Determine final reason
    $finalReason = ($reason === "Other") ? $customReason : $reason;

    try {
        // Start transaction
        $dbh->beginTransaction();

        // Get bookID associated with the borrowID
        $sql = "SELECT bookID FROM borrowtbl WHERE borrowID = :borrowID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $bookID = $result->bookID;

            // Update borrowtbl: Archive record and set the reason
            $sql = "UPDATE borrowtbl 
                    SET borrowarc = 'Archived', arcReason = :arcReason 
                    WHERE borrowID = :borrowID";
            $query = $dbh->prepare($sql);
            $query->bindParam(':arcReason', $finalReason, PDO::PARAM_STR);
            $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
            $query->execute();

            // Increment book count in booktbl
            $sql = "UPDATE booktbl SET count = count + 1 WHERE bookID = :bookID";
            $query = $dbh->prepare($sql);
            $query->bindParam(':bookID', $bookID, PDO::PARAM_INT);
            $query->execute();
        }

        // Commit transaction
        $dbh->commit();
        echo "<script>alert('Record archived successfully.');</script>";
        echo "<script>window.location.href = 'pendingList.php';</script>";
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $dbh->rollBack();
        echo "<script>alert('Error archiving record: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'pendingList.php';</script>";
        exit;
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
    <title>Reserved Borrow List</title>
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
                    <h4 class="header-line">Reserved Borrow List</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reserved Borrow Records
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Title</th>
                                            <th>Status</th>
                                            <th>Confirm</th>
                                            <th>Archive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $results = getBorrowRecords($dbh);
                                        $cnt = 1;

                                        if (count($results) > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->bookTitle); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->status); ?></td>
                                                    <td class="center">
                                                        <a href="issuedBooks.php?borrowID=<?php echo htmlentities($result->borrowID); ?>" class="btn btn-success">Confirm</a>
                                                    </td>
                                                    <td class="center">
                                                        <button type="button" class="btn btn-danger" onclick="openArchiveModal(<?php echo htmlentities($result->borrowID); ?>)">Remove</button>
                                                    </td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">-</td>
                                                <td class="center">No reserved records found</td>
                                            </tr>
                                        <?php } ?>
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

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="pendinglist.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveModalLabel">Archive Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="borrowID" id="modalBorrowID">
                        <div class="form-group">
                            <label for="reason">Select a reason:</label>
                            <select name="reason" id="reason" class="form-control">
                                <option value="Not borrowed">Not borrowed</option>
                                <option value="Other">Other (please specify)</option>
                            </select>
                        </div>
                        <div class="form-group" id="customReasonDiv" style="display: none;">
                            <label for="customReason">Custom Reason:</label>
                            <input type="text" name="customReason" id="customReason" class="form-control">
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

    <script>
        // Show or hide custom reason input based on selection
        document.getElementById('reason').addEventListener('change', function () {
            const customReasonDiv = document.getElementById('customReasonDiv');
            if (this.value === 'Other') {
                customReasonDiv.style.display = 'block';
            } else {
                customReasonDiv.style.display = 'none';
            }
        });

        // Set borrowID in modal
        function openArchiveModal(borrowID) {
            document.getElementById('modalBorrowID').value = borrowID;
            $('#archiveModal').modal('show');
        }
    </script>

    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>

    <!-- Ensure DataTables initializes after the table is rendered -->
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable();
        });
    </script>
</body>

</html>
