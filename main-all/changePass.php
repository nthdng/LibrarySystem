<?php
session_start();
include('../includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('location:../login.php');
} else {
    if (isset($_POST['change'])) {
        $password = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $username = $_SESSION['login'];

        $sql = "SELECT Password FROM tbluser WHERE Username = :username AND Password = :password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $con = "UPDATE tbluser SET Password = :newpassword WHERE Username = :username";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg = "Your Password has been successfully changed.";
        } else {
            $error = "Your current password is incorrect.";
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <style>
        .errorWrap, .succWrap {
            padding: 10px;
            margin: 0 0 20px;
            background: #fff;
            border-left: 4px solid;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .errorWrap { border-left-color: #dd3d36; }
        .succWrap { border-left-color: #5cb85c; }
    </style>
    <script type="text/javascript">
        function valid() {
            const newPassword = document.chngpwd.newpassword.value;
            const confirmPassword = document.chngpwd.confirmpassword.value;
            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password do not match!");
                document.chngpwd.confirmpassword.focus();
                return false; // Prevent form submission
            }
            return true; // Allow form submission if validation passes
        }

        function togglePasswordVisibility(id) {
            const field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Change Password</h4>
                </div>
            </div>
            <?php if ($error) { ?>
                <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?></div>
            <?php } elseif ($msg) { ?>
                <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?></div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Change Password</div>
                        <div class="panel-body">
                            <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input class="form-control" type="password" name="password" id="password" autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('password')"> Show Password
                                </div>
                                <div class="form-group">
                                    <label>Enter New Password</label>
                                    <input class="form-control" type="password" name="newpassword" id="newpassword" autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('newpassword')"> Show Password
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password </label>
                                    <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" autocomplete="off" required />
                                    <input type="checkbox" onclick="togglePasswordVisibility('confirmpassword')"> Show Password
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
<?php } ?>
