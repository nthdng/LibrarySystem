<?php


include('../includes/config.php');

if (isset($_GET['stdid'])) {
    $StudentID = $_GET['stdid'];

    // Query the student details
    $sql = "SELECT * FROM tblstudent WHERE StudentID = :studentID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentID', $StudentID, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "Student not found!";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
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
    <title>Online Library Management System | Manage Student</title>
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

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/headerlib.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">
                        <?php
                        echo "$StudentID's Details";
                        ?>
                    </h4>


                </div>

            </div>
            <div class="row">

                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            STUDENT DETAILS
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="get" onSubmit="return valid();">

                                <!-- From ID to Address -->
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input class="form-control" type="text" name="studentID"
                                        value="<?php echo htmlentities($result->StudentID); ?>" readonly />
                                </div>


                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="fName"
                                        value="<?php echo htmlentities($result->Fname); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input class="form-control" type="text" name="mName"
                                        value="<?php echo htmlentities($result->Mname); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="lName"
                                        value="<?php echo htmlentities($result->Lname); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Course/Strand</label>
                                    <input class="form-control" type="text" name="course"
                                        value="<?php echo htmlentities($result->CourseStrand); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Year/Level</label>
                                    <input class="form-control" type="text" name="yrLvl"
                                        value="<?php echo htmlentities($result->YrLevel); ?>" readonly />
                                </div>


                                <div class="form-group">
                                    <label>Mobile Number :</label>
                                    <input class="form-control" type="text" name="conNo" maxlength="11" pattern="\d*"
                                        inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '');"
                                        onkeypress="return isNumberKey(event)" onpaste="return false"
                                        value="<?php echo htmlentities($result->ContactNo); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid"
                                        value="<?php echo htmlentities($result->Email); ?>" readonly />
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label>Address</label>
                                    <input class="form-control" type="text" name="haddress"
                                        value="<?php echo htmlentities($result->hAddress); ?>" readonly />
                                </div>

                                <a href="updateStudent.php?stdid=<?php echo htmlentities($result->StudentID); ?>">
                                    <button class="btn btn-danger">Remove</button>
                                </a>

                                <a href="updateStudent.php?stdid=<?php echo htmlentities($result->StudentID); ?>">
                                    <button class="btn btn-primary">Update</button>
                                </a>




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