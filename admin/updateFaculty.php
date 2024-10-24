<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';

// Session check (uncomment if needed)
// if(strlen($_SESSION['login'])==0)
// { 
//     header('location:../login.php');
// }
// else{

if (isset($_GET['facultyid'])) {
    $ID = $_GET['facultyid'];

    // Query the faculty details
    $sql = "SELECT * FROM tblfaculty WHERE ID = :ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ID', $ID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "Faculty member not found!";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if (isset($_POST['update'])) {
    $FacultyID = $_POST['facultyId'];
    $Fname = $_POST['fName'];
    $Mname = $_POST['mName'];
    $Lname = $_POST['lName'];
    $Department = $_POST['department'];
    $ContactNo = $_POST['conNo'];
    $Email = $_POST['email'];

    // Prepare the SQL query
    $sql = "UPDATE tblfaculty 
            SET FacultyID = :facultyId, Fname = :fName, Mname = :mName, Lname = :lName, 
                Department = :department, ContactNo = :conNo, Email = :email 
            WHERE ID = :ID";

    $query = $dbh->prepare($sql);

    $query->bindParam(':ID', $ID, PDO::PARAM_INT);
    $query->bindParam(':facultyId', $FacultyID, PDO::PARAM_STR);
    $query->bindParam(':fName', $Fname, PDO::PARAM_STR);
    $query->bindParam(':mName', $Mname, PDO::PARAM_STR);
    $query->bindParam(':lName', $Lname, PDO::PARAM_STR);
    $query->bindParam(':department', $Department, PDO::PARAM_STR);
    $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
    $query->bindParam(':email', $Email, PDO::PARAM_STR);

    $updateSuccess = $query->execute();

    if ($updateSuccess) {
        // Generate the QR Code
        $data = "Faculty ID: $FacultyID\nName: $Fname $Mname $Lname\nDepartment: $Department\nContact: $ContactNo\nEmail: $Email";
        $qrCode = new QrCode($data);
        $writer = new PngWriter();

        // Save the QR code as an image file
        $qrPath = '../qrcodes/' . $FacultyID . '.png';  // Directory to store QR codes
        $result = $writer->write($qrCode);
        $result->saveToFile($qrPath);

        // PHPMailer setup for sending confirmation email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'LibraryTestBSIS41@gmail.com'; // Replace with your SMTP username
            $mail->Password = 'hmxz yuls dhst zcmj'; // Replace with your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
            $mail->addAddress($Email, $Fname . ' ' . $Lname); // Recipient email

            // Attach the QR code
            $mail->addAttachment($qrPath);  // Attach the QR code file
            $mail->addEmbeddedImage($qrPath, 'qrcode');  // Embed the QR code image

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Library System - Updated Faculty Information';
            $mail->Body = 'Dear ' . $Fname . ',<br><br>' .
                'Your information has been updated successfully. Here is your updated QR code:<br>' .
                '<img src="cid:qrcode" alt="QR Code"><br>' .
                'Please show this code to the librarian.<br><br>' .
                'Best regards,<br>The Library Team';

            // Send the email
            $mail->send();
            echo "<script>alert('Updated successfully. Email with updated QR code sent to the recipient.'); window.location.href='facultyList.php';</script>";
        } catch (Exception $e) {
            echo '<script>alert("Update successful, but email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '");</script>';
        }
    } else {
        echo "<script>alert('Update failed. Please try again.');</script>";
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
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <title>Online Library Management System | Manage Faculty</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="../assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="../assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />



        <script>
            function checkAvailability() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'emailid=' + $("#emailid").val(),
                    type: "POST",
                    success: function (data) {
                        $("#user-availability-status").html(data);
                        $("#loaderIcon").hide();
                    },
                    error: function () { }
                });
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
                        <h4 class="header-line">
                            UPDATE INFORMATION
                        </h4>


                    </div>

                </div>
                <div class="row">

                    <div class="col-md-9 col-md-offset-1">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                UPDATE FACULTY
                            </div>
                            <div class="panel-body">
                                <form name="update" method="post" onSubmit="return valid();">

                                    <!-- From ID to Address -->
                                    <div class="form-group">
                                        <label>ID Number</label>
                                        <input class="form-control" type="text" name="facultyId"
                                            value="<?php echo htmlentities($result->FacultyID); ?>" />
                                    </div>


                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input class="form-control" type="text" name="fName"
                                            value="<?php echo htmlentities($result->Fname); ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input class="form-control" type="text" name="mName"
                                            value="<?php echo htmlentities($result->Mname); ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input class="form-control" type="text" name="lName"
                                            value="<?php echo htmlentities($result->Lname); ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label>Department</label>
                                        <input class="form-control" type="text" name="department"
                                            value="<?php echo htmlentities($result->Department); ?>" />
                                    </div>


                                    <div class="form-group">
                                        <label>Mobile Number :</label>
                                        <input class="form-control" type="text" name="conNo" maxlength="11" pattern="\d*"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '');"
                                            onkeypress="return isNumberKey(event)" onpaste="return false"
                                            value="<?php echo htmlentities($result->ContactNo); ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label>Enter Email</label>
                                        <input class="form-control" type="email" name="email" id="emailid"
                                            value="<?php echo htmlentities($result->Email); ?>" />
                                        <span id="user-availability-status" style="font-size:12px;"></span>
                                    </div>


                                    <button type="submit" name="update" class="btn btn-primary" id="update">Update
                                    </button>





                                </form>





                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('../includes/footer.php'); ?>
        <script src="../assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="../assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="../assets/js/custom.js"></script>

        <script>
            document.getElementById('role').addEventListener('focus', function () {
                const selectRoleOption = this.querySelector('option[value=""]');
                if (selectRoleOption) {
                    selectRoleOption.style.display = 'none';
                }
            });
        </script>



        <script>
            // Function to restrict input to digits only
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                // Allow backspace, delete, and arrow keys for navigation
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
        </script>

    </body>

    </html>
<?php //} ?>