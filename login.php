<?php
session_set_cookie_params(0); // 0 lifetime means session expires on browser close
session_start();
error_reporting(0);
include('includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    $Username = $_POST['username'];
    $password = md5($_POST['password']);

    // Fetch user by username and password
    $sql = "SELECT userID, Username, Password, Status, Role, force_password_change FROM tbluser WHERE Username=:username AND Password=:password";
    $archUser = 'Active';
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $Username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            if ($result->Status == 'Activated') { // Active account
                // Store user information in session
                session_regenerate_id(true); // Prevent session fixation attacks
                $_SESSION['userID'] = $result->userID; // Store userID for borrowing and other actions
                $_SESSION['login'] = $result->Username;
                $_SESSION['role'] = $result->Role;

                // Check if user needs to change their password on first login
                if ($result->force_password_change) {
                    echo "<script type='text/javascript'> document.location ='main-all/forcedCpass.php'; </script>";
                    exit();
                }

                // Redirect to shared dashboard for both Admin and Librarian
                echo "<script type='text/javascript'> document.location ='main-all/dashboard.php'; </script>";
            } else {
                // Account is blocked
                echo "<script>alert('Your account has been blocked. Please contact the admin.');</script>";
            }
        }
    } else {
        // Invalid login attempt
        echo "<script>alert('Invalid Username or Password');</script>";

        if ($archUser == 'Archived') {
            echo "<script>alert('Invalid Username or Password');</script>";

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
    <title>Online Library Management System | </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/home.jpg');
            background-size: cover;
            filter: blur(5px);
            /* Adjust the blur intensity as needed */
            z-index: -1;
            /* Ensure it's behind other content */
        }
    </style>

</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/headerindex.php'); ?>
    <!-- MENU SECTION END-->
    <div class="background"></div>

    <div class="main-content">
        <div class="content-wrapper">
            <!--LOGIN PANEL START-->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            LOGIN
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username" required
                                        autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" id="password" required
                                        autocomplete="off" />
                                    <input type="checkbox" onclick="togglePassword()"> Show Password
                                    <p class="help-block"><a href="user-forgot-password.php">Forgot Password</a></p>
                                </div>
                                <button type="submit" name="login" class="btn btn-info">LOGIN</button>
                                <!-- | <a href="signup.php">Not Register Yet</a> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!---LOGIN PANEL END-->
        </div>
        <?php include('includes/footer.php'); ?>
    </div>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>

</html>