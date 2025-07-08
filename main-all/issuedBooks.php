<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../vendor/PHPMailer/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/PHPMailer/src/SMTP.php';

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}
// Book info
if (isset($_GET['borrowID'])) {
    $borrowID = intval($_GET['borrowID']);
    // Query to fetch borrow and book details
    $sql = "
        SELECT 
    br.borrowID, br.Username, br.FacultyID, br.StudentID, 
    br.borrowDate, br.expectedReturnDate, br.status, br.borrowarc,
    b.bookID, b.bookCode, b.bookTitle, b.isbnNumber, b.accountNumber, 
    s.shelfLoc, c.categoryName, f.formatName
FROM 
    borrowtbl br
JOIN 
    booktbl b ON br.bookID = b.bookID
LEFT JOIN 
    shelftbl s ON b.shelf = s.shelfID
LEFT JOIN 
    categorytbl c ON b.category = c.categoryID
LEFT JOIN 
    formattbl f ON b.format = f.formatID
WHERE 
    br.borrowID = :borrowID

    ";
    $query = $dbh->prepare($sql);
    $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    // Handle missing records
    if (!$result) {
        echo "No details found for this borrow record.";
        exit;
    }

    // Set the expected return date dynamically if not already set
    if (empty($result->expectedReturnDate)) {
        $result->expectedReturnDate = date('Y-m-d H:i:s', strtotime('+1 day'));
    }
}
// Confirm borrow
if (isset($_POST['confirmBorrow'])) {
    $borrowID = intval($_POST['borrowID']);
    $username = $_SESSION['login']; // Librarian/Admin username
    $status = "borrowed";

    // Retrieve role and ID from the session
    $borrowerRole = $_SESSION['borrowerRole'] ?? null;
    $borrowerID = $_SESSION['borrowerID'] ?? null;
    $facultyID = null;
    $studentID = null;

    // Set expected return date based on borrower role
    if ($borrowerRole === 'Faculty') {
        $facultyID = $borrowerID;
        $expectedReturnDate = date('Y-m-d H:i:s', strtotime('+6 months')); // Faculty: 6 months
    } elseif ($borrowerRole === 'Student') {
        $studentID = $borrowerID;
        $expectedReturnDate = date('Y-m-d H:i:s', strtotime('+1 week')); // Student: 1 week
    } else {
        $_SESSION['error'] = "Invalid borrower role. Please search for a borrower again.";
        header('Location: reservelist.php');
        exit;
    }

    try {
        // Validate borrowID
        if (empty($borrowID)) {
            $_SESSION['error'] = "Borrow ID is missing. Please select a record again.";
            header('Location: reservelist.php');
            exit;
        }

        // Update borrowtbl
        $sql = "
    UPDATE borrowtbl 
    SET 
        status = :status, 
        Username = :username, 
        FacultyID = :facultyID, 
        StudentID = :studentID, 
        expectedReturnDate = :expectedReturnDate 
    WHERE 
        borrowID = :borrowID
";

        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':facultyID', $facultyID, PDO::PARAM_STR);
        $query->bindParam(':studentID', $studentID, PDO::PARAM_STR);
        $query->bindParam(':expectedReturnDate', $expectedReturnDate, PDO::PARAM_STR);
        $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $query->execute();

        // Insert into returntbl
        $sqlReturn = "
            INSERT INTO returntbl (borrowID, status, penalty, returnarc)
            VALUES (:borrowID, 'borrowed', 0.00, 'Existing')
        ";
        $queryReturn = $dbh->prepare($sqlReturn);
        $queryReturn->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $queryReturn->execute();

        // Fetch borrower email and name based on role
        $emailQuery = null;
        if ($borrowerRole === 'Faculty') {
            $emailQuery = $dbh->prepare("SELECT Email AS email, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS name FROM tblfaculty WHERE FacultyID = :id");
        } elseif ($borrowerRole === 'Student') {
            $emailQuery = $dbh->prepare("SELECT Email AS email, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS name FROM tblstudent WHERE StudentID = :id");
        }
        $emailQuery->bindParam(':id', $borrowerID, PDO::PARAM_STR);
        $emailQuery->execute();
        $borrower = $emailQuery->fetch(PDO::FETCH_OBJ);

        if ($borrower) {
            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'LibraryTestBSIS41@gmail.com'; // Replace with your library's email
                $mail->Password = 'oooa kckx xnlb cfap'; // Replace with your email's app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('LibraryTestBSIS41@gmail.com', 'Library System');
                $mail->addAddress($borrower->email, $borrower->name);

                $mail->isHTML(true);
                $mail->Subject = 'Borrow Request Confirmed';
                $mail->Body = "Hello, {$borrower->name},<br><br>
                    Your borrow request has been confirmed. Please return the book by <b>{$expectedReturnDate}</b> 
                    to avoid a fine.<br><br>
                    Best regards,<br>The Library Team";

                $mail->send();
            } catch (Exception $e) {
                error_log("Error sending email: " . $mail->ErrorInfo);
            }
        }

        // Notify the user
        echo "<script>
            alert('Borrow record confirmed successfully and return entry created. Email sent to borrower.');
            window.location.href = 'borrowlist.php';
        </script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error confirming borrow: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'reservelist.php';</script>";
        exit;
    }
}


// Borrower's info
if (isset($_POST['searchID']) && isset($_POST['role'])) {
    $id = trim($_POST['searchID']);
    $role = trim($_POST['role']);
    if (empty($id) || empty($role)) {
        echo json_encode(['error' => 'ID or Role is missing']);
        exit;
    }
    $data = [];
    try {
        if ($role === 'Faculty') {
            $query = $dbh->prepare(
                "SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblfaculty 
                WHERE FacultyID = :id AND archFc = 'Active'"
            );
        } elseif ($role === 'Student') {
            $query = $dbh->prepare(
                "SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblstudent 
                WHERE StudentID = :id AND archStd = 'Active'"
            );
        } else {
            echo json_encode(['error' => 'Invalid role']);
            exit;
        }
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            echo json_encode(['error' => 'No data found for the given ID and Role']);
            exit;
        }
        // Store the role and ID in session or a hidden input field
        $_SESSION['borrowerRole'] = $role;
        $_SESSION['borrowerID'] = $id;

        echo json_encode($data);
        exit;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => 'An error occurred while fetching data']);
        exit;
    }
}
if (isset($_POST['qr_data']) && isset($_POST['role'])) {
    $scannedData = trim($_POST['qr_data']);
    $role = trim($_POST['role']);
    if (empty($scannedData) || empty($role)) {
        echo json_encode(['error' => 'Scanned data or Role is missing']);
        exit;
    }
    $data = [];
    try {
        if ($role === 'Faculty') {
            $query = $dbh->prepare(
                "SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblfaculty 
                WHERE FacultyID = :id AND archFc = 'Active'"
            );
        } elseif ($role === 'Student') {
            $query = $dbh->prepare(
                "SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblstudent 
                WHERE StudentID = :id AND archStd = 'Active'"
            );
        } else {
            echo json_encode(['error' => 'Invalid role']);
            exit;
        }
        $query->bindParam(':id', $scannedData, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            echo json_encode(['error' => 'No data found for the given ID and Role']);
            exit;
        }
        // Store the role and ID in session for future use
        $_SESSION['borrowerRole'] = $role;
        $_SESSION['borrowerID'] = $scannedData;
        echo json_encode($data);
        exit;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => 'An error occurred while fetching data']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Manage Borrowers</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script src="../node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        .hidden-section {
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-dialog {
            max-width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
        }

        #reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 10px;
        }

        #result {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
            text-align: center;
        }
    </style>
    <script>
        function showModal() {
            document.getElementById('roleModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('roleModal').style.display = 'none';
        }
        function fetchData() {
            const role = document.getElementById('role').value;
            const searchID = document.getElementById('searchInput').value; // This will now include scanned QR data.

            if (searchID && role) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'qr_data=' + encodeURIComponent(searchID) + '&role=' + encodeURIComponent(role),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error); // Optional: You can remove this alert if you want no interruption
                        } else {
                            // Populate the borrower details section
                            document.getElementById('fullName').value = data['FullName'];
                            document.getElementById('mobileNumber').value = data['ContactNo'];
                            document.getElementById('emailAddress').value = data['Email'];

                            if (role === 'Student') {
                                document.getElementById('course').value = data['CourseStrand'];
                                document.getElementById('yearLevel').value = data['YrLevel'];
                                document.getElementById('address').value = data['hAddress'];
                            } else if (role === 'Faculty') {
                                document.getElementById('course').value = '';
                                document.getElementById('yearLevel').value = '';
                                document.getElementById('address').value = '';
                            }

                            // Make borrower details visible
                            document.getElementById('borrowerDetails').style.display = 'block';

                            // Close the modal
                            closeModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('An error occurred while fetching data.');
                    });
            } else {
                alert('Please enter an ID and select a role.');
            }
        }
        let qrScanner;
        function openQRScanner() {
            // Show the modal
            document.getElementById('qrScannerModal').style.display = 'block';
            // Ensure the scanner initializes AFTER the modal opens
            setTimeout(() => {
                if (!qrScanner) {
                    qrScanner = new Html5Qrcode("reader");
                }
                qrScanner.start(
                    { facingMode: "environment" }, // Use the back camera
                    { fps: 10, qrbox: { width: 300, height: 300 } }, // Larger scanning box
                    onScanSuccess,
                    onScanError
                )
                    .catch(err => {
                        console.error("Camera start failed:", err);
                        alert("Unable to start camera. Please check permissions or try a different browser.");
                    });
            }, 300); // Slight delay to ensure modal and DOM are rendered
        }
        function onScanSuccess(qrCodeMessage) {
            console.log("Scanned content:", qrCodeMessage);
            // Updated regex to capture IDs with hyphens
            const match = qrCodeMessage.match(/(?:Student ID|Faculty ID):\s*([\w-]+)/i);
            if (match && match[1]) {
                const extractedID = match[1]; // Capture the full ID value
                console.log("Extracted ID:", extractedID);
                // Populate input field for manual search or fetch details directly
                document.getElementById('searchInput').value = extractedID;
                // Automatically trigger the search function
                fetchData();
            } else {
                console.error("QR code does not contain a valid ID format.");
                alert("Invalid QR code format. Please try again.");
            }
            // Stop the scanner and close the modal
            closeQRScanner();

            // Close the modal
            // closeModal();
        }
        function onScanError(errorMessage) {
            console.error("QR Scan error:", errorMessage);
        }
        function closeQRScanner() {
            if (qrScanner) {
                qrScanner.stop().then(() => {
                    console.log("QR Scanner stopped.");
                    qrScanner = null; // Reset scanner
                }).catch(err => {
                    console.error("Error stopping QR scanner:", err);
                });
            }
            document.getElementById('qrScannerModal').style.display = 'none';
        }
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Borrowers</h4>
                </div>
            </div>

            <!-- Left and Right Wrappers -->
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">Borrower Details</div>
                        <div class="panel-body">
                            <form name="updatest" method="post">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" name="role" id="role" onchange="showModal()">
                                        <option value="" selected disabled>Select Role</option>
                                        <option value="Student">Student</option>
                                        <option value="Faculty">Faculty</option>
                                    </select>
                                </div>
                                <div id="borrowerDetails" class="hidden-section">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input class="form-control" type="text" id="fullName" disabled />
                                    </div>
                                    <div class="form-group" id="courseDiv">
                                        <label>Course/Strand (Students Only)</label>
                                        <input class="form-control" type="text" id="course" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label>Year/Level (Students Only)</label>
                                        <input class="form-control" type="text" id="yearLevel" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input class="form-control" type="text" id="mobileNumber" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input class="form-control" type="text" id="emailAddress" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label>Address (Students Only)</label>
                                        <input class="form-control" type="text" id="address" disabled />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Right Section -->
                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">Book Details</div>
                        <div class="panel-body">
                            <fieldset disabled>
                                <div class="form-group">
                                    <label>Book Code</label>
                                    <input class="form-control" type="text" name="bookCode"
                                        value="<?php echo htmlspecialchars($result->bookCode); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Book Title</label>
                                    <input class="form-control" type="text" name="bookTitle"
                                        value="<?php echo htmlspecialchars($result->bookTitle); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <input class="form-control" type="text"
                                        value="<?php echo htmlspecialchars($result->categoryName); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Format</label>
                                    <input class="form-control" type="text"
                                        value="<?php echo htmlspecialchars($result->formatName); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>ISBN Number</label>
                                    <input class="form-control" type="text" name="isbnNumber"
                                        value="<?php echo htmlspecialchars($result->isbnNumber); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Shelf Location</label>
                                    <input class="form-control" type="text"
                                        value="<?php echo htmlspecialchars($result->shelfLoc); ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input class="form-control" type="text" name="accountNumber"
                                        value="<?php echo htmlspecialchars($result->accountNumber); ?>" readonly />
                                </div>
                                <!-- <div class="form-group">
                                    <label>Expected Return Date</label>
                                    <input class="form-control" type="text" name="expectedReturnDate"
                                        value="<?php //echo htmlspecialchars($result->expectedReturnDate); ?>" readonly />
                                </div> -->
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="form-group text-center">
                <form method="post">
                    <input type="hidden" name="borrowID" value="<?php echo htmlspecialchars($result->borrowID); ?>">
                    <button type="submit" name="confirmBorrow" class="btn btn-primary">Confirm Borrow</button>
                </form>
            </div>
        </div>
    </div>
    <div id="roleModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search or Scan QR</h5>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchInput" class="form-control" placeholder="Enter ID Number">
                    <button id="searchBtn" class="btn btn-primary mt-2" onclick="fetchData()">Search</button>
                    <button class="btn btn-secondary mt-2" onclick="openQRScanner()">Scan QR Code</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for QR Code Scanning -->
    <div id="qrScannerModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code</h5>
                    <span class="close" onclick="closeQRScanner()">&times;</span>
                </div>
                <div class="modal-body">
                    <div id="reader"></div>
                    <div id="result">Waiting for QR code...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeQRScanner()">Close</button>
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