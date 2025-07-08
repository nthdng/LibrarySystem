<?php

session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}


if (isset($_GET['usid'])) {
    $userID = $_GET['usid'];

    // Query the user details
    $sql = "SELECT * FROM tbluser WHERE userID = :userID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':userID', $userID, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "User not found!";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if (isset($_POST['update'])) {
    $Username = $_POST['username'];
    $Fname = $_POST['fName'];
    $Mname = $_POST['mName'];
    $Lname = $_POST['lName'];
    $ContactNo = $_POST['conNo'];
    $EmailAdd = $_POST['email'];
    $Password = $_POST['password']; // Get the password input

    // Start building the SQL query
    $sql = "UPDATE tbluser 
            SET Fname = :fName, Mname = :mName, Lname = :lName, EmailAdd = :email, ContactNo = :conNo, Username = :username";

    // Check if a new password was provided
    if (!empty($Password)) {
        // Hash the new password
        $hashedPassword = md5($Password); // Hashing the password
        $sql .= ", Password = :password"; // Include the password in the update statement
    }

    $sql .= " WHERE userID = :userID"; // Finish the query

    $query = $dbh->prepare($sql);

    // Bind common parameters
    $query->bindParam(':userID', $userID, PDO::PARAM_INT);
    $query->bindParam(':fName', $Fname, PDO::PARAM_STR);
    $query->bindParam(':mName', $Mname, PDO::PARAM_STR);
    $query->bindParam(':lName', $Lname, PDO::PARAM_STR);
    $query->bindParam(':email', $EmailAdd, PDO::PARAM_STR);
    $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
    $query->bindParam(':username', $Username, PDO::PARAM_STR);

    // Bind the hashed password if it's set
    if (!empty($Password)) {
        $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    }

    // Execute the query
    if ($query->execute()) {
        echo '<script>alert("User details updated successfully!");</script>';
        echo "<script type='text/javascript'>window.location='userList.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
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
    <title>Online Library Management System | Manage User</title>
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
<script type="text/javascript">
    function valid() {
        if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
            alert("New Password and Confirm Password Field do not match  !!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }

    function togglePasswordVisibility(id) {
        var field = document.getElementById(id);
        if (field.type === "password") {
            field.type = "text";
        } else {
            field.type = "password";
        }
    }
</script>

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
                            UPDATE USER
                        </div>
                        <div class="panel-body">
                            <form name="update" method="post" onSubmit="return valid();">

                                <!-- From ID to Address -->
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username"
                                        value="<?php echo htmlentities($result->Username); ?>" />
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
                                    <label>Set Password</label>
                                    <input class="form-control" type="password" name="password" id="password"
                                        autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('password')"> Show Password
                                </div>

                                <label>Mobile Number :</label>
                                <div class="input-group">
                                    <span class="input-group-addon">+63</span>
                                    <input class="form-control" type="text" name="conNo" maxlength="10" pattern="\d*"
                                        inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '');"
                                        onkeypress="return isNumberKey(event)" onpaste="return false" autocomplete="off"
                                        required placeholder="Enter 10-digit mobile number"
                                        value="<?php echo htmlentities($result->ContactNo); ?>" />
                                </div>

                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid"
                                        value="<?php echo htmlentities($result->EmailAdd); ?>" />
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