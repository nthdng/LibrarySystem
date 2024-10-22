<?php

session_start();
error_reporting(0);
include('../includes/config.php');

// if(strlen($_SESSION['login'])==0)
//   { 
// header('location:../login.php');
// }
// else{
if (isset($_GET['del'])) {
    $id = $_GET['del'];

    // Fetch the faculty data based on the ID
    $sqlFetch = "SELECT * FROM tblfaculty WHERE ID = :id";
    $queryFetch = $dbh->prepare($sqlFetch);
    $queryFetch->bindParam(':id', $id, PDO::PARAM_STR);
    $queryFetch->execute();
    $facultyData = $queryFetch->fetch(PDO::FETCH_OBJ);

    if ($facultyData) {
        // Insert data into tblarchivefaculty
        $sqlArchive = "INSERT INTO tblarchivefaculty (FacultyID, Fname, Mname, Lname, Department, ContactNo, Email)
                       VALUES (:facultyID, :fName, :mName, :lName, :department, :conNo, :email)";
        $queryArchive = $dbh->prepare($sqlArchive);
        $queryArchive->bindParam(':facultyID', $facultyData->FacultyID, PDO::PARAM_STR);
        $queryArchive->bindParam(':fName', $facultyData->Fname, PDO::PARAM_STR);
        $queryArchive->bindParam(':mName', $facultyData->Mname, PDO::PARAM_STR);
        $queryArchive->bindParam(':lName', $facultyData->Lname, PDO::PARAM_STR);
        $queryArchive->bindParam(':department', $facultyData->Department, PDO::PARAM_STR);
        $queryArchive->bindParam(':conNo', $facultyData->ContactNo, PDO::PARAM_STR);
        $queryArchive->bindParam(':email', $facultyData->Email, PDO::PARAM_STR);
        $queryArchive->execute();

        // Delete the record from tblfaculty
        $sqlDelete = "DELETE FROM tblfaculty WHERE ID = :id";
        $queryDelete = $dbh->prepare($sqlDelete);
        $queryDelete->bindParam(':id', $id, PDO::PARAM_STR);
        $queryDelete->execute();

        
        echo "<script>alert('Faculty Successfully Removed and Archived'); window.location.href='facultyList.php';</script>";

    } else {
        echo "<script>alert('Faculty not found!'); window.location.href='facultyList.php';</script>";
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
    <title>Online Library Management System | Manage Reg Facultys</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Accounts</h4>
                    <a href="addFaculty.php" class="btn btn-success pull-right">+ ADD FACULTY</a>
                    <!-- Import button -->
                    <a href="ImportFaculty.php" id="import" class="btn btn-primary">
                        Import Data
                    </a>
                </div>


            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Users
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Faculty ID</th>
                                            <th>Faculty Name</th>
                                            <th>Department </th>
                                            <th>Contact #</th>
                                            <th>Email Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from tblfaculty";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;


                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->FacultyID); ?></td>
                                                    <td class="center">
                                                        <?php
                                                        // Concatenate Fname, MI, and Lname to form the Fullname
                                                        // Convert the middle name to its initial (first letter)
                                                        $middleInitial = strtoupper(substr($result->Mname, 0, 1));

                                                        // Concatenate Fname, MI, and Lname to form the Fullname
                                                        $fullname = $result->Fname . ' ' . $middleInitial . '. ' . $result->Lname;

                                                        // Display the Fullname with htmlentities for safety
                                                        echo htmlentities($fullname);
                                                        ?>
                                                    </td>
                                                    <td class="center"><?php echo htmlentities($result->Department); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->ContactNo); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->Email); ?></td>

                                                    <!-- For updating faculty, update later -->
                                                    <div>
                                                        <td class="center">
                                                            <a
                                                                href=".php?fctid=<?php echo htmlentities($result->FacultyID); ?>"><button
                                                                    class="btn btn-success"> Records</button></a>

                                                            <a
                                                                href="updateFaculty.php?facultyid=<?php echo htmlentities($result->ID); ?>"><button
                                                                    class="btn btn-primary"> Update</button></a>

                                                            <a href="facultyList.php?del=<?php echo htmlentities($result->ID); ?>"
                                                                onclick="return confirm('Are you sure you want to remove this faculty?');">
                                                                <button class="btn btn-danger">Remove</button></a>
                                                        </td>
                                                    </div>
                                                    <!-- End of Updating comment -->

                                                </tr>
                                                <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>



        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="../assets/js/custom.js"></script>
</body>

</html>
<?php //} ?>