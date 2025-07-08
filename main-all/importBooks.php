<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;  // Make sure to exit after header redirection
}

if (isset($_POST['import'])) {
    $filename = $_FILES['importFile']['name'];
    $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

    $allowed_extensions = ['xls', 'csv', 'xlsx'];

    if (in_array($file_extension, $allowed_extensions)) {

        $inputFileNamePath = $_FILES['importFile']['tmp_name'];

        // Load the spreadsheet file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        $insertedRecords = [];
        $skippedRecords = [];

        foreach ($data as $row) {
            if ($count > 0) { // Skip header row
                // Extract values from the row
                $bookCode = $row[0];
                $bookTitle = $row[1];
                $format = $row[2];  // Format from the import
                $category = $row[3]; // Category from the import
                $shelf = $row[4];    // Shelf from the import
                $isbnNumber = $row[5];
                $countBooks = $row[6];
                $accountNumber = $row[7];

                // Skip rows with any empty required fields, including bookTitle
                if (empty($bookCode) || empty($bookTitle) || empty($format) || empty($category) || empty($shelf) || empty($isbnNumber)) {
                    continue; // Skip this row and move to the next
                }

                // Check if the format exists in formattbl
                $sqlCheckFormat = "SELECT formatID FROM formattbl WHERE formatName = :format";
                $queryCheckFormat = $dbh->prepare($sqlCheckFormat);
                $queryCheckFormat->bindParam(':format', $format, PDO::PARAM_STR);
                $queryCheckFormat->execute();
                if ($queryCheckFormat->rowCount() == 0) {
                    // Insert format if it doesn't exist
                    $sqlInsertFormat = "INSERT INTO formattbl (formatName) VALUES (:format)";
                    $queryInsertFormat = $dbh->prepare($sqlInsertFormat);
                    $queryInsertFormat->bindParam(':format', $format, PDO::PARAM_STR);
                    $queryInsertFormat->execute();
                    $formatId = $dbh->lastInsertId(); // Get the inserted format ID
                } else {
                    $resultFormat = $queryCheckFormat->fetch(PDO::FETCH_ASSOC);
                    $formatId = $resultFormat['formatID'];
                }

                // Check if the category exists in categorytbl
                $sqlCheckCategory = "SELECT categoryID FROM categorytbl WHERE categoryName = :category";
                $queryCheckCategory = $dbh->prepare($sqlCheckCategory);
                $queryCheckCategory->bindParam(':category', $category, PDO::PARAM_STR);
                $queryCheckCategory->execute();
                if ($queryCheckCategory->rowCount() == 0) {
                    // Insert category if it doesn't exist
                    $sqlInsertCategory = "INSERT INTO categorytbl (categoryName) VALUES (:category)";
                    $queryInsertCategory = $dbh->prepare($sqlInsertCategory);
                    $queryInsertCategory->bindParam(':category', $category, PDO::PARAM_STR);
                    $queryInsertCategory->execute();
                    $categoryId = $dbh->lastInsertId(); // Get the inserted category ID
                } else {
                    $resultCategory = $queryCheckCategory->fetch(PDO::FETCH_ASSOC);
                    $categoryId = $resultCategory['categoryID'];
                }

                // Check if the shelf exists in shelftbl
                $sqlCheckShelf = "SELECT shelfID FROM shelftbl WHERE shelfLoc = :shelf";
                $queryCheckShelf = $dbh->prepare($sqlCheckShelf);
                $queryCheckShelf->bindParam(':shelf', $shelf, PDO::PARAM_STR);
                $queryCheckShelf->execute();
                if ($queryCheckShelf->rowCount() == 0) {
                    // Insert shelf if it doesn't exist
                    $sqlInsertShelf = "INSERT INTO shelftbl (shelfLoc) VALUES (:shelf)";
                    $queryInsertShelf = $dbh->prepare($sqlInsertShelf);
                    $queryInsertShelf->bindParam(':shelf', $shelf, PDO::PARAM_STR);
                    $queryInsertShelf->execute();
                    $shelfId = $dbh->lastInsertId(); // Get the inserted shelf ID
                } else {
                    $resultShelf = $queryCheckShelf->fetch(PDO::FETCH_ASSOC);
                    $shelfId = $resultShelf['shelfID'];
                }

                // Check if the book already exists using the ISBN number
                $sqlCheck = "SELECT * FROM booktbl WHERE isbnNumber = :isbnNumber";
                $queryCheck = $dbh->prepare($sqlCheck);
                $queryCheck->bindParam(':isbnNumber', $isbnNumber, PDO::PARAM_STR);
                $queryCheck->execute();

                if ($queryCheck->rowCount() == 0) {
                    // If the book doesn't exist, insert new record
                    $sql = "INSERT INTO booktbl (bookCode, bookTitle, format, category, shelf, isbnNumber, count, accountNumber) 
                            VALUES (:bookCode, :bookTitle, :format, :category, :shelf, :isbnNumber, :countBooks, :accountNumber)";
                    $query = $dbh->prepare($sql);

                    // Bind parameters
                    $query->bindParam(':bookCode', $bookCode, PDO::PARAM_STR);
                    $query->bindParam(':bookTitle', $bookTitle, PDO::PARAM_STR);
                    $query->bindParam(':format', $formatId, PDO::PARAM_INT); // Use the new formatId
                    $query->bindParam(':category', $categoryId, PDO::PARAM_INT); // Use the new categoryId
                    $query->bindParam(':shelf', $shelfId, PDO::PARAM_INT); // Use the new shelfId
                    $query->bindParam(':isbnNumber', $isbnNumber, PDO::PARAM_STR);
                    $query->bindParam(':countBooks', $countBooks, PDO::PARAM_INT);
                    $query->bindParam(':accountNumber', $accountNumber, PDO::PARAM_INT);

                    // Execute the query
                    $query->execute();
                    $insertedRecords[] = $bookTitle;
                } else {
                    // Record exists, skip it
                    $skippedRecords[] = $bookTitle;
                }
            } else {
                $count = 1; // Skip header row
            }
        }

        // Generate a summary prompt
        $message = "";

        // Handle inserted records
        if (!empty($insertedRecords)) {
            $insertedCount = count($insertedRecords);
            $visibleInserted = array_slice($insertedRecords, 0, 5);  // First 5 books
            $message .= "INSERTED RECORDS:\n" . implode(", ", $visibleInserted);

            if ($insertedCount > 5) {
                $message .= " and " . ($insertedCount - 5) . " more...\n";  // Show the remaining count as +N
            }
            $message .= "\n";
        } else {
            $message .= "INSERTED RECORDS: None\n";
        }

        // Handle skipped records
        if (!empty($skippedRecords)) {
            $skippedCount = count($skippedRecords);
            $visibleSkipped = array_slice($skippedRecords, 0, 5);  // First 5 skipped books
            $message .= "SKIPPED RECORDS:\n" . implode(", ", $visibleSkipped);

            if ($skippedCount > 5) {
                $message .= " and " . ($skippedCount - 5) . " more...\n";  // Show the remaining count as +N
            }
            $message .= "\n";
        } else {
            $message .= "SKIPPED RECORDS: None\n";
        }

        // Escape newlines for JavaScript alert
        $message = str_replace("\n", "\\n", $message);
        echo '<script>alert("' . $message . '");</script>';
        echo "<script type='text/javascript'>window.location='bookManagement.php';</script>";
    } else {
        echo "<script>alert('File must be .xls, .csv, .xlsx');</script>";
        echo "<script type='text/javascript'>window.location='bookManagement.php';</script>";
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

</head>
<!-- <script type="text/javascript">
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script> -->

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Import Books</h4>
                </div>
            </div>

            <!--LOGIN PANEL START-->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Import Books
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" name="importstu" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label>Upload File</label>
                                    <input class="form-control" type="file" name="importFile" required>
                                </div>

                                <button type="submit" name="import" class="btn btn-info">Import </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!---LOGIN PABNEL END-->


        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="../assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="../assets/js/custom.js"></script>
</body>

</html>