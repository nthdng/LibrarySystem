<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);


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
    $Fname = $_POST['fname'];
    $Mname = $_POST['mname'];
    $Lname = $_POST['lname'];
    $ContactNo = $_POST['conNo'];
    $EmailAdd = $_POST['email'];
    $Username = $_POST['username'];

    // Generate a random password
    $plainPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'), 0, 8);
    $Password = md5($plainPassword); // Hash the password

    $role = 'Librarian';
    // $status = 'Active';

    // Check for duplicate ISBN in the database
    $duplicateCheckQuery = "SELECT * FROM tbluser WHERE Username = :username";
    $checkQuery = $dbh->prepare($duplicateCheckQuery);
    $checkQuery->bindParam(':username', $Username, PDO::PARAM_STR);  // Use $isbn here
    $checkQuery->execute();

    // If the query returns any row, it means the Username already exists
    if ($checkQuery->rowCount() > 0) {
        // Duplicate found, return an error message
        echo "<script>alert('Username was already registered. Please try different username.');</script>";
    } else {

        // Insert the librarian's details into the database
        $sql = "INSERT INTO tbluser(Fname, Mname, Lname, EmailAdd, ContactNo, Username, Password, Role) 
            VALUES(:fname, :mname, :lname, :email, :conNo, :username, :password, :role)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $Fname, PDO::PARAM_STR);
        $query->bindParam(':mname', $Mname, PDO::PARAM_STR);
        $query->bindParam(':lname', $Lname, PDO::PARAM_STR);
        $query->bindParam(':email', $EmailAdd, PDO::PARAM_STR);
        $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
        $query->bindParam(':username', $Username, PDO::PARAM_STR);
        $query->bindParam(':password', $Password, PDO::PARAM_STR);
        $query->bindParam(':role', $role, PDO::PARAM_STR);

        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
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
                $mail->addAddress($EmailAdd, $Fname . ' ' . $Lname);

                // Content 
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to the Library System';
                $mail->Body = 'Dear ' . $Fname . ',<br><br>' .
                    'Your registration was successful! Here are your login credentials:<br>' .
                    '<b>Username:</b> ' . $Username . '<br>' .
                    '<b>Password:</b> ' . $plainPassword . '<br><br>' .
                    'Please make sure to change your password after logging in.<br><br>' .
                    'Best regards,<br>The Library Team';

                $mail->send();
                echo "<script>alert('Your registration was successful. Check your email for login details.'); window.location.href='userList.php';</script>";
            } catch (Exception $e) {
                echo '<script>alert("Registration successful, but email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '");</script>';
                echo "<script>window.location.href='userList.php'</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again');  window.location.href='userList.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Librarian Signup</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script>
        function checkUsernameAvailability() {
            const username = document.getElementById("username").value;
            const statusElement = document.getElementById("username-status");

            if (username.trim() === "") {
                statusElement.textContent = "";
                return;
            }

            fetch("check_availability.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ username }),
            })
                .then((response) => response.text())
                .then((data) => {
                    if (data === "available") {
                        statusElement.textContent = "Username is available.";
                        statusElement.style.color = "green";
                    } else {
                        statusElement.textContent = "Username is already taken.";
                        statusElement.style.color = "red";
                    }
                })
                .catch((error) => {
                    console.error("Error checking username availability:", error);
                    statusElement.textContent = "Unable to check availability.";
                    statusElement.style.color = "red";
                });
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">LIBRARIAN FORM</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            LIBRARIAN FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username" id="username"
                                        autocomplete="off" required onkeyup="checkUsernameAvailability()" />
                                    <span id="username-status" style="font-size: 12px;"></span>
                                </div>
                                <div class="form-group">
                                    <label>First name</label>
                                    <input class="form-control" type="text" name="fname" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Middle name</label>
                                    <input class="form-control" type="text" name="mname" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input class="form-control" type="text" name="lname" autocomplete="off" required />
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
                                </div>

                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid"
                                        onBlur="checkAvailability()" autocomplete="off" required />
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input disabled class="form-control" type="text" placeholder="Librarian" />
                                </div>
                                <button type="submit" name="signup" class="btn btn-danger" id="submit">Register
                                    Now</button>
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
</body>

</html>