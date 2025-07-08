<?php
session_start(); // Start the session
error_reporting(0);
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Adjust path if necessary

require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';




if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}


// If deleting a student record
if (isset($_POST['stID'])) {
    $stID = $_POST['stID'];
    $archStd = 'Archived'; // Set archStd to archive

    // Capture the reason
    $reason = $_POST['reasonSelect'];
    if ($reason === "Other") {
        $reason = $_POST['customReason']; // Use custom reason if "Other" is selected
    }

    // Update to archive student in database
    $sql = "UPDATE tblstudent SET archStd = :archStd WHERE stID = :stID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':stID', $stID, PDO::PARAM_INT);
    $query->bindParam(':archStd', $archStd, PDO::PARAM_STR); // Use PARAM_STR for enum
    $query->execute();

    // Check if the update was successful
    if ($query->rowCount() > 0) {
        // Fetch student's email and name for notification
        $fetchStudent = "SELECT Fname, Lname, Email FROM tblstudent WHERE stID = :stID";
        $fetchQuery = $dbh->prepare($fetchStudent);
        $fetchQuery->bindParam(':stID', $stID, PDO::PARAM_INT);
        $fetchQuery->execute();
        $student = $fetchQuery->fetch(PDO::FETCH_OBJ);

        if ($student) {
            // Initialize PHPMailer and configure the SMTP settings
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'LibraryTestBSIS41@gmail.com';
                $mail->Password = 'oooa kckx xnlb cfap';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->Timeout = 30000;

                // Set email parameters
                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System'); // Sender details
                $mail->addAddress($student->Email, $student->Fname . ' ' . $student->Lname); // Student's email

                // Email content with reason
                $mail->isHTML(true);
                $mail->Subject = 'Your Information Removed From Library';
                $mail->Body = 'Dear ' . $student->Fname . ',<br><br>' .
                    'We want to inform you that your information has been removed from the library system for the following reason: <strong>' . htmlentities($reason) . '</strong>.<br>' .
                    'If you have any questions, please contact us.<br><br>' .
                    'Best regards,<br>The Library Team';

                // Send the email
                $mail->send();
                echo "<script>alert('Student Successfully Removed and Archived. Notification email sent.'); 
                window.location.href='studentList.php';
                document.getElementById('loadingModal').style.display = 'none';
                </script>";
            } catch (Exception $e) {
                echo "<script>alert('Student Successfully Removed and Archived, but email could not be sent.'); 
                window.location.href='studentList.php';
                document.getElementById('loadingModal').style.display = 'none';
                </script>";
            }
        }
    } else {
        echo "<script>alert('Student not found'); window.location.href='studentList.php';</script>";
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
    <title>Online Library Management System | Manage Students</title>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('loadingModal').style.display = 'none';
        });
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
                    <h4 class="header-line">Student List</h4>
                    <a href="addStudent.php" class="btn btn-success pull-right">+ ADD STUDENT</a>
                    <!-- Import button -->
                    <a href="ImportStudent.php" id="import" class="btn btn-primary">
                        Import Data
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Users
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Course/Strand </th>
                                            <th>Year/Level</th>
                                            <th>Contact #</th>
                                            <th>Email Address</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from tblstudent WHERE archStd = 'Active'";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->StudentID); ?></td>
                                                    <td class="center"><?php
                                                    $middleInitial = strtoupper(substr($result->Mname, 0, 1));
                                                    $fullname = $result->Fname . ' ' . $middleInitial . '. ' . $result->Lname;
                                                    echo htmlentities($fullname);
                                                    ?>
                                                    </td>
                                                    <td class="center"><?php echo htmlentities($result->CourseStrand); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->YrLevel); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->ContactNo); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->Email); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->hAddress); ?></td>
                                                    <td class="center">
                                                        <!-- Update and Remove buttons -->
                                                        <!-- <a href="historyST.php?htrSt=<?php //echo htmlentities($result->StudentID); ?>">
                                                            <button class="btn btn-success">View Transactions</button>
                                                        </a> -->
                                                        <a
                                                            href="updateStudent.php?stdid=<?php echo htmlentities($result->stID); ?>">
                                                            <button class="btn btn-primary">Update</button>
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                            onclick="showDeletionModal(<?php echo htmlentities($result->stID); ?>);">
                                                            <button class="btn btn-danger">Remove</button>
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
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- Deletion Reason Modal -->
    <div id="deletionReasonModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reason for Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deletionForm" method="POST" action="studentList.php">
                        <input type="hidden" name="stID" id="stID">
                        <label for="reasonSelect">Select a reason:</label>
                        <select id="reasonSelect" name="reasonSelect" class="form-control">
                            <option value="Graduated">Graduated</option>
                            <option value="Transferred">Transferred</option>
                            <option value="Dropped Out">Dropped Out</option>
                            <option value="Other">Other</option>
                        </select>
                        <br>
                        <label for="customReason">If other, please specify:</label>
                        <input type="text" id="customReason" name="customReason" class="form-control"
                            placeholder="Enter reason">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitDeletionForm()">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: white; font-size: 24px; display: flex; align-items: center; justify-content: center; z-index: 1000;">
        Loading...
    </div>

    <!-- JavaScript to handle modal and form submission -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>

    <script>
        // Show the deletion reason modal and set the student ID
        function showDeletionModal(stID) {
            document.getElementById('stID').value = stID;
            $('#deletionReasonModal').modal('show');
        }

        // Triggered when the submit button in the deletion modal is clicked
        function submitDeletionForm() {
            // Hide the deletion reason modal
            $('#deletionReasonModal').modal('hide');

            // Show the loading modal once the deletion modal is fully hidden
            $('#deletionReasonModal').on('hidden.bs.modal', function () {
                document.getElementById('loadingModal').style.display = 'flex';

                // Submit the form after showing the loading modal
                document.getElementById('deletionForm').submit();
            });
        }
    </script>

</body>

</html>