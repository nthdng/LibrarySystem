<?php

session_start();
include('../includes/config.php');
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';


if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}

if (isset($_POST['signup'])) {
    $FacultyID = $_POST['facultyId'];
    $Fname = $_POST['fName'];
    $Mname = $_POST['mName'];
    $Lname = $_POST['lName'];
    $ContactNo = $_POST['conNo'];
    $Email = $_POST['email'];

    $duplicateCheckQuery = "SELECT * FROM tblfaculty WHERE FacultyID = :facultyId";
    $checkQuery = $dbh->prepare($duplicateCheckQuery);
    $checkQuery->bindParam(':facultyId', $FacultyID, PDO::PARAM_STR);
    $checkQuery->execute();

    // If the query returns any row, it means the FacultyID already exists
    if ($checkQuery->rowCount() > 0) {
        // Duplicate found, return an error message
        echo "<script>alert('Faculty or FacultyID already exists. Please use a different ID.'); window.location.href='facultyList.php';</script>";
    } else {


        // Prepare the SQL query first
        $sql = "INSERT INTO tblfaculty(FacultyID, Fname, Mname, Lname, ContactNo, Email) 
            VALUES(:facultyId, :fName, :mName, :lName, :conNo, :email)";

        $query = $dbh->prepare($sql);

        // Bind the parameters after preparing the query
        $query->bindParam(':facultyId', $FacultyID, PDO::PARAM_STR);
        $query->bindParam(':fName', $Fname, PDO::PARAM_STR);
        $query->bindParam(':mName', $Mname, PDO::PARAM_STR);
        $query->bindParam(':lName', $Lname, PDO::PARAM_STR);
        $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
        $query->bindParam(':email', $Email, PDO::PARAM_STR);

        // Execute the query
        $query->execute();

        // Check if the insertion was successful
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            // Generate the QR Code
            $data = "Faculty ID: $FacultyID\n";
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
                $mail->Password = 'oooa kckx xnlb cfap'; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->Timeout = 30000;


                // Recipients
                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
                $mail->addAddress($Email, $Fname . ' ' . $Lname); // Recipient email

                // Attach the QR code
                $mail->addAttachment($qrPath);  // Attach the QR code file
                $mail->addEmbeddedImage($qrPath, 'qrcode');  // Embed the QR code image

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to the Library System';
                $mail->Body = 'Dear ' . $Fname . ',<br><br>' .
                    'You are now registered at the Library. Here is your QR code:<br>' .
                    '<img src="cid:qrcode" alt="QR Code"><br>' .
                    'Please show this code to the librarian.<br><br>' .
                    'Best regards,<br>The Library Team';

                // Send the email
                $mail->send();
                echo "<script>
                    alert('Registration successful. Email has been sent.');
                    window.location.href = 'facultyList.php';
                    document.getElementById('loadingModal').style.display = 'none';                    
                    
                </script>";
            } catch (Exception $e) {
                echo "<script>                    
                    alert('Registration successful, but email could not be sent. Mailer Error: " . $mail->ErrorInfo . "');
                    window.location.href = 'facultyList.php';
                    document.getElementById('loadingModal').style.display = 'none';
                </script>";

            }
        } else {
            echo "<script>alert('Something went wrong. Please try again'); window.location.href='facultyList.php';</script>";
        }
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
    <title>Online Library Management System | Add Faculty</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('loadingModal').style.display = 'none';
        });
    </script>

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
                    <h4 class="header-line">ADD FACULTY</h4>

                </div>

            </div>
            <div class="row">

                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            FACULTY FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">







                                <!-- From ID to Address -->
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input class="form-control" type="text" name="facultyId" autocomplete="off"
                                        required />
                                </div>


                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="fName" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input class="form-control" type="text" name="mName" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="lName" autocomplete="off" required />
                                </div>




                                <!-- <style>
                                    input[type=number]::-webkit-inner-spin-button,
                                    input[type=number]::-webkit-outer-spin-button {
                                        -webkit-appearance: none;
                                        margin: 0;
                                    }
                                </style> -->

                                <div class="form-group">
                                    <label>Mobile Number :</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">+63</span>
                                        <input class="form-control" type="text" name="conNo" maxlength="10"
                                            pattern="\d*" inputmode="numeric"
                                            oninput="this.value = this.value.replace(/\D/g, '');"
                                            onkeypress="return isNumberKey(event)" onpaste="return false"
                                            autocomplete="off" required placeholder="Enter 10-digit mobile number" />
                                    </div>

                                    <div class="form-group">
                                        <label>Enter Email</label>
                                        <input class="form-control" type="email" name="email" id="emailid"
                                            autocomplete="off" required />
                                        <span id="user-availability-status" style="font-size:12px;"></span>
                                    </div>




                                    <!-- onBlur="checkAvailability()" -->

                                    <!-- <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="faculty">Faculty</option>
                                    </select>
                                </div> -->





                                    <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now
                                    </button>

                            </form>





                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loadingModal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: white; font-size: 24px; display: flex; align-items: center; justify-content: center; z-index: 1000;">
            Loading...
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

        const myModal = document.getElementById('myModal')
        const myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        })

    </script>

    <script>
        // Function to restrict input to digits only
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            // Allow backspace, delete, and arrow keys for navigation
            if (charCode > 31 && (charCode < script 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector('form[name="signup"]');
            form.addEventListener('submit', function () {
                document.getElementById('loadingModal').style.display = 'flex';
            });
        });


    </script>
</body>

</html>