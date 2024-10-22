<?php
session_set_cookie_params(0); // 0 lifetime means expire on browser close
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['login'])) {
    $Username = $_POST['username'];
    $password = md5($_POST['password']);

    // Fetch user by username and password
    $sql = "SELECT ID, Username, Password, Status, Role, force_password_change FROM tbluser WHERE Username=:username AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $Username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            if ($result->Status == 1) { // Active account
                $_SESSION['login'] = $result->Username;
                $_SESSION['role'] = $result->Role;

                // Check if the user needs to change their password on first login
                if ($result->force_password_change) {
                    echo "<script type='text/javascript'> document.location ='admin/forcedCpass.php'; </script>";
                    exit();
                }

                // Role-based redirection
                if ($result->Role == 'Librarian') {
                    echo "<script type='text/javascript'> document.location ='librarian/dashboardlib.php'; </script>";
                } elseif ($result->Role == 'Admin') {
                    echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
                }
            } else {
                // Blocked account
                echo "<script>alert('Your Account Has been blocked. Please contact admin.');</script>";
            }
        }
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Username or Password');</script>";
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
            height: 100vh;
            background-image: url('assets/img/home.jpg');
            background-size: cover;
            filter: blur(5px);
            z-index: -1;
            background-attachment: fixed;
            background-position: center;
        }

        .main-content {
            min-height: 100vh;
        }

        .footer-section {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1;
        }

        body,
        html {
            height: 100vh;
            overflow-y: hidden;
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
    </div>
    <?php include('includes/footer.php'); ?>

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