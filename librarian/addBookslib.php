<?php
session_start();
error_reporting(0);
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
// if(strlen($_SESSION['alogin'])==0)
//     {   
// header('location:index.php');
// }
// else{ 

if (isset($_POST['add'])) {
    // Gather data from the form
    $bookTitle = $_POST['bookname'];
    $bookCode = $_POST['bookcode'];

    $category = $_POST['category'];
    $format = $_POST['format'];
    $isbn = $_POST['isbn'];
    $shelf = $_POST['shelf'];
    $accountNumber = $_POST['accountNumber'];
    $bookCount = $_POST['count'];

    // Insert into booktbl (no book image anymore)
    $sql = "INSERT INTO booktbl (bookTitle, bookCode, category, format, isbnNumber, shelf, accountNumber, count)
            VALUES (:bookTitle,:bookcode, :category, :format, :isbn, :shelf, :accountNumber, :bookCount)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookTitle', $bookTitle, PDO::PARAM_STR);
    $query->bindParam(':bookcode', $bookCode, PDO::PARAM_STR);

    $query->bindParam(':category', $category, PDO::PARAM_INT);
    $query->bindParam(':format', $format, PDO::PARAM_INT);
    $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
    $query->bindParam(':shelf', $shelf, PDO::PARAM_STR);
    $query->bindParam(':accountNumber', $accountNumber, PDO::PARAM_INT);
    $query->bindParam(':bookCount', $bookCount, PDO::PARAM_INT);

    // Execute the query and check if successful
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
        echo "<script>alert('Book added successfully');</script>";
        echo "<script>window.location.href='bookManagementlib.php'</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
        echo "<script>window.location.href='bookManagementlib.php'</script>";
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
    <title>Online Library Management System | Add Book</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
        function checkisbnAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'isbn=' + $("#isbn").val(),
                type: "POST",
                success: function (data) {
                    $("#isbn-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () { }
            });
        }

    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/headerlib.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Add Book</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">

                                <!-- Book Code -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Code<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookcode" autocomplete="off"
                                            required />
                                    </div>
                                </div>
                                <!-- Book Title -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Title<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname" autocomplete="off"
                                            required />
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <select class="form-control" name="category" required="required">
                                            <option value="">Select Category</option>
                                            <!-- Populate from database -->
                                            <?php
                                            $sql = "SELECT * FROM categorytbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <option value="<?php echo htmlentities($result->ID); ?>">
                                                        <?php echo htmlentities($result->categoryName); ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Format -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Format<span style="color:red;">*</span></label>
                                        <select class="form-control" name="format" required="required">
                                            <option value="">Select Format</option>
                                            <!-- Populate from database -->
                                            <?php
                                            $sql = "SELECT * FROM formattbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <option value="<?php echo htmlentities($result->ID); ?>">
                                                        <?php echo htmlentities($result->formatName); ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- ISBN Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ISBN Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="isbn" id="isbn"
                                            required="required" autocomplete="off" onBlur="checkisbnAvailability()" />
                                        <span id="isbn-availability-status"></span>
                                    </div>
                                </div>

                                <!-- Shelf Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shelf<span style="color:red;">*</span></label>
                                        <select class="form-control" name="shelf" required="required">
                                            <option value="">Select Format</option>
                                            <!-- Populate from database -->
                                            <?php
                                            $sql = "SELECT * FROM shelftbl";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <option value="<?php echo htmlentities($result->ID); ?>">
                                                        <?php echo htmlentities($result->shelfLoc); ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Account Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="accountNumber"
                                            required="required" autocomplete="off" />
                                    </div>
                                </div>

                                <!-- Book Count -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Book Count<span style="color:red;">*</span></label>
                                        <input class="form-control" type="number" name="count" required="required"
                                            autocomplete="off" />
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button type="submit" name="add" class="btn btn-info">Submit</button>
                                </div>

                            </form>

                        </div>
                    </div>
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
    <!-- CUSTOM SCRIPTS  -->
    <script src="../assets/js/custom.js"></script>
</body>

</html>
<?php //} ?>