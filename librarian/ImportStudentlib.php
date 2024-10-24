<?php
session_start();
include('../includes/config.php');
error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';

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
      if ($count > 0) {
        // Extract values from the row
        $StudentID = $row[0];
        $Fname = $row[1];
        $Mname = $row[2];
        $Lname = $row[3];
        $CourseStrand = $row[4];
        $YrLevel = $row[5];
        $ContactNo = $row[6];
        $Email = $row[7];
        $hAddress = $row[8];

        // Check if the student ID or email already exists
        $sqlCheck = "SELECT * FROM tblstudent WHERE StudentID = :studentID OR Email = :email";
        $queryCheck = $dbh->prepare($sqlCheck);
        $queryCheck->bindParam(':studentID', $StudentID, PDO::PARAM_STR);
        $queryCheck->bindParam(':email', $Email, PDO::PARAM_STR);
        $queryCheck->execute();

        if ($queryCheck->rowCount() > 0) {
          // Fetch the existing record to check which one is a duplicate
          $existingRecord = $queryCheck->fetch(PDO::FETCH_ASSOC);

          if ($existingRecord['StudentID'] === $StudentID) {
            // If StudentID is a duplicate, skip the record
            $skippedRecords[] = $Fname . ' ' . $Lname . ' (Duplicate Student ID)';
          } elseif ($existingRecord['Email'] === $Email) {
            // If Email is a duplicate, skip the record
            $skippedRecords[] = $Fname . ' ' . $Lname . ' (Duplicate Email)';
          }
        } else {
          // If no duplicate found, insert the new student record
          $sql = "INSERT INTO tblstudent(StudentID, Fname, Mname, Lname, CourseStrand, YrLevel, ContactNo, Email, hAddress) 
                        VALUES(:studentID, :fName, :mName, :lName, :course, :yrLvl, :conNo, :email, :haddress)";
          $query = $dbh->prepare($sql);

          // Bind parameters
          $query->bindParam(':studentID', $StudentID, PDO::PARAM_STR);
          $query->bindParam(':fName', $Fname, PDO::PARAM_STR);
          $query->bindParam(':mName', $Mname, PDO::PARAM_STR);
          $query->bindParam(':lName', $Lname, PDO::PARAM_STR);
          $query->bindParam(':course', $CourseStrand, PDO::PARAM_STR);
          $query->bindParam(':yrLvl', $YrLevel, PDO::PARAM_STR);
          $query->bindParam(':conNo', $ContactNo, PDO::PARAM_STR);
          $query->bindParam(':email', $Email, PDO::PARAM_STR);
          $query->bindParam(':haddress', $hAddress, PDO::PARAM_STR);

          // Execute the query
          $query->execute();

          $insertedRecords[] = [
            'name' => $Fname . ' ' . $Lname,
            'email' => $Email,
            'studentID' => $StudentID,
            'fname' => $Fname,
            'mname' => $Mname,
            'lname' => $Lname,
            'course' => $CourseStrand,
            'yrLvl' => $YrLevel,
            'conNo' => $ContactNo,
            'haddress' => $hAddress
          ];
        }
      } else {
        $count = 1; // Skip header row
      }
    }

    // Generate a summary message
    $message = "Summary:\n";

    // Check if inserted records exist
    if (!empty($insertedRecords)) {
      $insertedCount = count($insertedRecords);
      $mail = new PHPMailer(true);
      $successfulEmails = []; // Initialize the array to track successful emails

      try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'LibraryTestBSIS41@gmail.com'; // Your SMTP username
        $mail->Password = 'hmxz yuls dhst zcmj'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Set sender info
        $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');

        foreach ($insertedRecords as $record) {
          // Add recipient info
          $mail->addAddress($record['email'], $record['name']); // Recipient email and name

          // Generate QR Code for the student with all details
          $qrCodeData = "Student ID: " . $record['studentID'] . "\n" .
            "Name: " . $record['fname'] . " " . $record['mname'] . " " . $record['lname'] . "\n" .
            "Course: " . $record['course'] . "\n" .
            "Year Level: " . $record['yrLvl'] . "\n" .
            "Contact: " . $record['conNo'] . "\n" .
            "Email: " . $record['email'] . "\n" .
            "Address: " . $record['haddress'];

          $qrCode = new QrCode($qrCodeData);
          $writer = new PngWriter();
          $qrPath = '../qrcodes/' . $record['studentID'] . '.png'; // Directory to store QR codes
          $result = $writer->write($qrCode);
          $result->saveToFile($qrPath);

          // Attach the QR code to the email
          $mail->addAttachment($qrPath);  // Attach the QR code image file
          $mail->addEmbeddedImage($qrPath, 'qrcode');  // Embed the QR code in the email

          // Email content
          $mail->isHTML(true);
          $mail->Subject = 'Welcome to the Library System';
          $mail->Body = 'Dear ' . $record['name'] . ',<br><br>' .
            'Your data has been added to the Library system. Here is your QR code:<br>' .
            '<img src="cid:qrcode" alt="QR Code"><br>' .
            'Please show this code to the Librarian when borrowing.<br><br>' .
            'Best regards,<br>The Library Team';

          // Try sending the email
          if ($mail->send()) {
            $successfulEmails[] = $record['name']; // Track successful email sends
          }

          // Clear attachments and recipients for the next email
          $mail->clearAttachments();  // Clear the attached QR code
          $mail->clearAddresses();    // Clear the recipient address
        }
      } catch (Exception $e) {
        echo "<script>alert('Emails could not be sent. Error: {$mail->ErrorInfo}');</script>";
      }

      // Add to the message: inserted records
      $message .= "INSERTED RECORDS:\n";

      $displayedInserted = array_slice($insertedRecords, 0, 5);
      $insertedNames = array_map(function ($record) {
        return $record['name'];
      }, $displayedInserted);

      $message .= implode(", ", $insertedNames);

      if ($insertedCount > 5) {
        $message .= " and " . ($insertedCount - 5) . " more...\n";
      } else {
        $message .= "\n";
      }
    } else {
      $message .= "INSERTED RECORDS: None\n";
    }

    // Add to the message: emails sent
    if (!empty($successfulEmails)) {
      $emailCount = count($successfulEmails);
      $message .= "EMAILS SENT TO:\n";

      // Show only the first 5 names and indicate if there are more
      $displayedEmails = array_slice($successfulEmails, 0, 5);
      $message .= implode(", ", $displayedEmails);

      if ($emailCount > 5) {
        $message .= " and " . ($emailCount - 5) . " more...\n";
      } else {
        $message .= "\n";
      }
    } else {
      $message .= "EMAILS SENT TO: None\n";
    }

    // Check if skipped records exist
    if (!empty($skippedRecords)) {
      $skippedCount = count($skippedRecords);
      $message .= "SKIPPED RECORDS:\n";

      // Show only the first 5 names and indicate if there are more
      $displayedSkipped = array_slice($skippedRecords, 0, 5);
      $message .= implode(", ", $displayedSkipped);

      if ($skippedCount > 5) {
        $message .= " and " . ($skippedCount - 5) . " more...\n";
      } else {
        $message .= "\n";
      }
    } else {
      $message .= "SKIPPED RECORDS: None\n";
    }

    // Escape newlines for JavaScript alert (replace \n with \\n or use line breaks <br>):
    $message = str_replace("\n", "\\n", $message);

    // Display the summary message as an alert and then redirect
    echo '<script>alert("' . $message . '");</script>';
    echo "<script type='text/javascript'>window.location='LibstudentList.php';</script>";

  } else {
    echo "<script>alert('File must be .xls, .csv, .xlsx');</script>";
    echo "<script type='text/javascript'>window.location='LibstudentList.php';</script>";
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


<body>
  <!------MENU SECTION START-->
  <?php include('../includes/headerlib.php'); ?>
  <!-- MENU SECTION END-->
  <div class="content-wrapper">
    <div class="container">
      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line">Import Student</h4>
        </div>
      </div>

      <!--LOGIN PANEL START-->
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              Import Student
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
<?php //}?>