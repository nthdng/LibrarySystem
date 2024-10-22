<?php
session_start();
error_reporting(0);
include('../includes/config.php');
// if ($_SESSION['login'] != '') {
//     $_SESSION['login'] = '';
// }
if (isset($_POST['login'])) {
    $Username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT ID, Username, Password, Status, Role FROM tbluser WHERE Username=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $Username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['stdid'] = $result->StudentId;
            if ($result->Status == 1) {
                $_SESSION['login'] = $result->StudentId;
                if ($result->Role == 'Admin') {
                    echo "<script type='text/javascript'> document.location ='../Admin/dashboard.php'; </script>";
                } else{
                    echo "<script>alert('Please Try Again.');</script>";
                }
            } else {
                echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
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
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
    .background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('../assets/img/home.jpg');
        background-size: cover;
        filter: blur(5px); /* Adjust the blur intensity as needed */
        z-index: -1; /* Ensure it's behind other content */
    }
    </style>

</head>
<body>
    <!------MENU SECTION START-->
    <?php include('../includes/headerindexadmin.php');?>
    <!-- MENU SECTION END-->
    <div class="background"></div>

    <div class="main-content">
        <div class="content-wrapper">
            <!--LOGIN PANEL START-->           
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Re-enter your Login
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username" required autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" required autocomplete="off" />
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
        <?php include('../includes/footer.php');?>
    </div>

    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>
</html>
