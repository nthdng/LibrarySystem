<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// if(strlen($_SESSION['login'])==0)
//   { 
// header('location:../login.php');
// }
// else{

// Block user
if (isset($_GET['inid'])) {
    $ID = $_GET['inid'];
    $Status = 0; // Set status to blocked
    $sql = "UPDATE tbluser SET Status = :status WHERE ID = :ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ID', $ID, PDO::PARAM_INT);
    $query->bindParam(':status', $Status, PDO::PARAM_INT);
    $query->execute();
    header('location:userList.php');
}

// Activate user
if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $Status = 1; // Set status to active
    $sql = "UPDATE tbluser SET Status = :status WHERE ID = :ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ID', $ID, PDO::PARAM_INT);
    $query->bindParam(':status', $Status, PDO::PARAM_INT);
    $query->execute();
    header('location:userList.php');
}

// Archive user
if (isset($_GET['archiveid'])) {
    $ID = $_GET['archiveid'];

    // Fetch user data
    $sql = "SELECT * FROM tbluser WHERE ID = :ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':ID', $ID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        // Insert into archive table
        $archiveSQL = "INSERT INTO tblrchiveusers (UserID, Username, Password, RoleID) VALUES (:ID, :Username, :Password, :Role)";
        $archiveQuery = $dbh->prepare($archiveSQL);
        $archiveQuery->bindParam(':ID', $result->ID, PDO::PARAM_INT);
        $archiveQuery->bindParam(':Username', $result->Username, PDO::PARAM_STR);
        $archiveQuery->bindParam(':Password', $result->Password, PDO::PARAM_STR);
        $archiveQuery->bindParam(':Role', $result->Role, PDO::PARAM_STR);
        $archiveQuery->execute();

        // Remove user from the main table
        $deleteSQL = "DELETE FROM tbluser WHERE ID = :ID";
        $deleteQuery = $dbh->prepare($deleteSQL);
        $deleteQuery->bindParam(':ID', $ID, PDO::PARAM_INT);
        $deleteQuery->execute();

        header('location:userList.php');
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
                                        $sql = "SELECT * from tbluser";
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
                                                    <td class="center"><?php echo htmlentities($result->updateDate); ?></td>
                                                    <td class="center">
                                                        <?php echo ($result->Status == 1) ? "Active" : "Blocked"; ?>
                                                    </td>
                                                    <td class="center">

                                                        <?php if ($result->Status == 1) { ?>
                                                            <a href="userList.php?inid=<?php echo htmlentities($result->ID); ?>"
                                                                onclick="return confirm('Are you sure you want to block this user?');">
                                                                <button class="btn btn-danger">Block</button></a>
                                                        <?php } else { ?>
                                                            <a href="userList.php?id=<?php echo htmlentities($result->ID); ?>"
                                                                onclick="return confirm('Are you sure you want to activate this user?');">
                                                                <button class="btn btn-primary">Activate</button></a>

                                                        <?php } ?>
                                                        <a href="userList.php?archiveid=<?php echo htmlentities($result->ID); ?>"
                                                            onclick="return confirm('Are you sure you want to archive this user?');">
                                                            <button class="btn btn-warning">Archive</button></a>
                                                        <a
                                                            href="updateUser.php?usid=<?php echo htmlentities($result->ID); ?>"><button
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
<?php //} ?>