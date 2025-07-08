<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}

$username = $_SESSION['login'];

// Check if the user has already changed their password
$sql = "SELECT force_password_change FROM tbluser WHERE Username = :username";
$query = $dbh->prepare($sql);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result && $result['force_password_change'] == 'Changed') {
    // Redirect to the dashboard if password change is not required
    header('Location: dashboard.php');
    exit();
}

if (isset($_POST['change'])) {
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);

    // Validate the current password
    $sql = "SELECT Password FROM tbluser WHERE Username = :username AND Password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Update the password and reset force_password_change flag
        $con = "UPDATE tbluser SET Password = :newpassword, force_password_change = 'Changed' WHERE Username = :username";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();

        // Set success message and redirect
        $msg = "Your Password has been successfully changed.";
        echo "<script>alert('$msg'); window.location.href='../login.php';</script>";
    } else {
        $error = "Your current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>
<script type="text/javascript">
    function valid() {
        if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
            alert("New Password and Confirm Password do not match!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }

    function togglePasswordVisibility(id) {
        var field = document.getElementById(id);
        field.type = field.type === "password" ? "text" : "password";
    }
</script>

<body>
    <?php include('../includes/hiddenIndex.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">PLEASE CHANGE YOUR PASSWORD</h4>
                </div>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
            <?php } elseif (!empty($msg)) { ?>
                <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Change Password</div>
                        <div class="panel-body">
                            <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input class="form-control" type="password" name="password" id="password"
                                        autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('password')"> Show Password
                                </div>
                                <div class="form-group">
                                    <label>Enter New Password</label>
                                    <input class="form-control" type="password" name="newpassword" id="newpassword"
                                        autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('newpassword')"> Show
                                    Password
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirmpassword"
                                        id="confirmpassword" autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('confirmpassword')"> Show
                                    Password
                                </div>
                                <button type="submit" name="change" class="btn btn-info">ENTER</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>