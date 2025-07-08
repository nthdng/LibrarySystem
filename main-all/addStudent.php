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
    $StudentID = $_POST['studentID'];
    $Fname = $_POST['fName'];
    $Mname = $_POST['mName'];
    $Lname = $_POST['lName'];
    $CourseStrand = $_POST['course'];
    $YrLevel = $_POST['yrLvl'];
    $ContactNo = $_POST['conNo'];
    $Email = $_POST['email'];
    $hAddress = $_POST['haddress'];

    $duplicateCheckQuery = "SELECT * FROM tblstudent WHERE StudentID = :studentID";
    $checkQuery = $dbh->prepare($duplicateCheckQuery);
    $checkQuery->bindParam(':studentID', $StudentID, PDO::PARAM_STR);
    $checkQuery->execute();

    // If the query returns any row, it means the StudentID already exists
    if ($checkQuery->rowCount() > 0) {
        // Duplicate found, return an error message
        echo "<script>alert('Student or StudentID already exists. Please use a different ID.'); window.location.href='studentList.php';</script>";
    } else {

        // Prepare the SQL query first
        $sql = "INSERT INTO tblstudent(StudentID, Fname, Mname, Lname, CourseStrand, YrLevel, ContactNo, Email, hAddress) 
            VALUES(:studentID, :fName, :mName, :lName, :course, :yrLvl, :conNo, :email, :haddress)";

        $query = $dbh->prepare($sql);

        // Bind the parameters after preparing the query
        $query->bindParam(':studentID', $StudentID, PDO::PARAM_STR);
        $query->bindParam(':fName', $Fname, PDO::PARAM_STR);
        $query->bindParam(':mName', $Mname, PDO::PARAM_STR);
        $query->bindParam(':lName', $Lname, PDO::PARAM_STR);
        $query->bindParam(':course', $CourseStrand, PDO::PARAM_STR);
        $query->bindParam(':yrLvl', $YrLevel, PDO::PARAM_STR);
        $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
        $query->bindParam(':email', $Email, PDO::PARAM_STR);
        $query->bindParam(':haddress', $hAddress, PDO::PARAM_STR);

        // Execute the query
        $query->execute();

        // Check if the insertion was successful
        $lastInsertId = $dbh->lastInsertId();
        // After successfully inserting the student record
        if ($lastInsertId) {
            // Generate the QR Code
            $data = "Student ID: $StudentID\n";
            // Name: $Fname $Mname $Lname\nCourse: $CourseStrand\nYear Level: $YrLevel\nContact: $ContactNo\nEmail: $Email\nAddress: $hAddress
            $qrCode = new QrCode($data);
            $writer = new PngWriter();

            // Save the QR code as an image file
            $qrPath = '../qrcodes/' . $StudentID . '.png';  // Directory to store QR codes
            $result = $writer->write($qrCode);
            $result->saveToFile($qrPath);

            // PHPMailer setup for sending confirmation email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'LibraryTestBSIS41@gmail.com';
                $mail->Password = 'oooa kckx xnlb cfap';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->Timeout = 30000;


                // Recipients
                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
                $mail->addAddress($Email, $Fname . ' ' . $Lname);  // Recipient email

                // Attach the QR code
                $mail->addAttachment($qrPath);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to the Library System';
                $mail->Body = 'Dear ' . $Fname . ',<br><br>' .
                    'You are now registered at the Library. Here is your QR code:<br>' .
                    '<img src="cid:qrcode" alt="QR Code"><br>' .
                    'Please show this code to the librarian.<br><br>' .
                    'Best regards,<br>The Library Team';

                // Inline QR Code
                $mail->addEmbeddedImage($qrPath, 'qrcode');

                // Send the email
                $mail->send();
                echo "<script>
                    alert('Registration successful. Email has been sent.');
                    window.location.href = 'studentList.php';
                    document.getElementById('loadingModal').style.display = 'none';                    
                    
                </script>";
            } catch (Exception $e) {
                // Handle email error with alert
                echo "<script>                    
                    alert('Registration successful, but email could not be sent. Mailer Error: " . $mail->ErrorInfo . "');
                    window.location.href = 'studentList.php';
                    document.getElementById('loadingModal').style.display = 'none';
                </script>";

            }
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
    <title>Online Library Management System | Student Signup</title>
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
                    <h4 class="header-line">Add Student</h4>

                </div>

            </div>
            <div class="row">

                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            STUDENT FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">

                                <!-- From ID to Address -->
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input class="form-control" type="text" name="studentID" autocomplete="off"
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

                                <div class="form-group">
                                    <label>Course/Strand</label>
                                    <input class="form-control" type="text" name="course" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Year/Level</label>
                                    <input class="form-control" type="text" name="yrLvl" autocomplete="off" required />
                                </div>




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

                                    <div class="form-group">
                                        <label>Address</label>
                                        <input class="form-control" type="text" name="haddress" autocomplete="off"
                                            required />
                                    </div>








                                    <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now
                                    </button>

                            </form>





                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="loadingModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: white; font-size: 24px; display: flex; align-items: center; justify-content: center; z-index: 1000;">
        Loading...
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