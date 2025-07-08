<?php
session_start(); // Start the session
error_reporting(0);
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}


// Block user
if (isset($_GET['blId'])) {
    $userID = $_GET['blId'];
    $Status = 'Inactive'; // Set status to blocked
    $sql = "UPDATE tbluser SET Status = :status WHERE userID = :userID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':userID', $userID, PDO::PARAM_INT);
    $query->bindParam(':status', $Status, PDO::PARAM_STR); // Use PARAM_STR for string values
    $query->execute();
    header('location:userList.php');
}// Activate user
else if (isset($_GET['acId'])) {
    $userID = $_GET['acId'];
    $Status = 'Activated'; // Set status to active
    $sql = "UPDATE tbluser SET Status = :status WHERE userID = :userID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':userID', $userID, PDO::PARAM_INT);
    $query->bindParam(':status', $Status, PDO::PARAM_STR); // Use PARAM_STR for string values
    $query->execute();
    header('location:userList.php');
}

// Archive user
if (isset($_GET['delUser'])) {  // Changed from $_POST to $_GET
    $userID = $_GET['delUser'];
    $archUser = 'Archived'; // Set archUser to archive
    $Status = 'Inactive'; // Set status to blocked

    // Update both archUser and Status in one query
    $sql = "UPDATE tbluser SET archUser = :archUser, Status = :status WHERE userID = :userID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':userID', $userID, PDO::PARAM_INT);
    $query->bindParam(':archUser', $archUser, PDO::PARAM_STR); // Use PARAM_STR for enum
    $query->bindParam(':status', $Status, PDO::PARAM_STR); // Use PARAM_STR for string values

    $query->execute();

    // Check if a row was updated
    if ($query->rowCount() > 0) {
        echo "<script>alert('User Successfully Blocked and Archived'); window.location.href='userList.php';</script>";
    } else {
        echo "<script>alert('User not found'); window.location.href='userList.php';</script>";
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
    <title>Online Library Management System | Manage Users</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Accounts</h4>
                    <a href="#" class="btn btn-success dropdown-toggle pull-right" id="ddlmenuItem"
                        data-toggle="dropdown">+ ADD USER <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="ddlmenuItem">
                        <li><a href="createLibrarian.php">Add Librarian</a></li>
                        <li><a href="createAdmin.php">Add Admin</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Users</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fullname</th>
                                            <th>Username</th>
                                            <th>Email Address</th>
                                            <th>Role</th>
                                            <th>Creation Date</th>
                                            <th>Update Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * from tbluser WHERE archUser = 'Active'";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center">
                                                        <?php
                                                        $middleInitial = strtoupper(substr($result->Mname, 0, 1));
                                                        $fullname = $result->Fname . ' ' . $middleInitial . '. ' . $result->Lname;
                                                        echo htmlentities($fullname);
                                                        ?>
                                                    </td>
                                                    <td class="center"><?php echo htmlentities($result->Username); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->EmailAdd); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->Role); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->creationDate); ?></td>
                                                    <td class="center">
                                                        <?php echo !is_null($result->updateDate) ? htmlentities($result->updateDate) : ''; ?>
                                                    </td>
                                                    <td class="center">
                                                        <?php echo ($result->Status == 'Activated') ? "Active" : "Blocked"; ?>
                                                    </td>
                                                    <td class="center">

                                                        <?php if ($result->Status == 'Activated') { ?>
                                                            <a href="userList.php?blId=<?php echo htmlentities($result->userID); ?>"
                                                                onclick="return confirm('Are you sure you want to block this user?');">
                                                                <button class="btn btn-danger">Block</button></a>
                                                        <?php } else { ?>
                                                            <a href="userList.php?acId=<?php echo htmlentities($result->userID); ?>"
                                                                onclick="return confirm('Are you sure you want to activate this user?');">
                                                                <button class="btn btn-primary">Activate</button></a>

                                                        <?php } ?>
                                                        <a href="userList.php?delUser=<?php echo htmlentities($result->userID); ?>"
                                                            onclick="return confirm('Are you sure you want to archive this user?');">
                                                            <button class="btn btn-warning">Archive</button></a>
                                                        <a
                                                            href="updateUser.php?usid=<?php echo htmlentities($result->userID); ?>"><button
                                                                class="btn btn-primary"> Update</button>
                                                        </a>

                                                    </td>
                                                </tr>
                                                <?php $cnt++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>

</html>