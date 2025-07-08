<?php
session_start();
include('../includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}

// Function to get the primary key for Faculty or Student
function getPrimaryKey($dbh, $role, $searchID) {
    try {
        if ($role === 'Faculty') {
            $sql = "SELECT fcID FROM tblfaculty WHERE FacultyID = :searchID AND archFc = 'Active'";
        } elseif ($role === 'Student') {
            $sql = "SELECT stID FROM tblstudent WHERE StudentID = :searchID AND archStd = 'Active'";
        } else {
            return null; // Invalid role
        }

        $query = $dbh->prepare($sql);
        $query->bindParam(':searchID', $searchID, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $role === 'Faculty' ? $result['fcID'] : $result['stID'];
        }
        return null; // No match found
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}

// Get Book Info
if (isset($_GET['borrowID'])) {
    $borrowID = intval($_GET['borrowID']);

    $sql = "
        SELECT 
            br.borrowID, br.userID, br.fcID, br.stID, br.borrowDate, 
            br.expectedReturnDate, br.status, b.bookID, b.bookCode, 
            b.bookTitle, b.format, b.category, b.shelf, 
            b.isbnNumber, b.accountNumber, b.archBook
        FROM 
            borrowtbl br
        JOIN 
            booktbl b ON br.bookID = b.bookID
        WHERE 
            br.borrowID = :borrowID
    ";
    $query = $dbh->prepare($sql);
    $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "No details found for this borrow record.";
        exit;
    }

    if (empty($result->expectedReturnDate)) {
        $result->expectedReturnDate = date('Y-m-d H:i:s', strtotime('+1 day'));
    }
}

// Confirm Borrow Logic
if (isset($_POST['confirmBorrow'])) {
    echo "Form submitted successfully.<br>"; // Debugging output

    $borrowID = intval($_POST['borrowID']);
    $searchID = trim($_POST['searchedID']);
    $role = trim($_POST['role']);
    $userID = intval($_SESSION['userID']);
    $status = "borrowed";
    $expectedReturnDate = date('Y-m-d H:i:s', strtotime('+1 day'));

    // Debug POST values
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Get primary key based on role
    $primaryKey = getPrimaryKey($dbh, $role, $searchID);

    if (!$primaryKey) {
        echo "Primary key not found for role: $role and searchID: $searchID<br>";
        exit;
    }

    $fcID = $role === 'Faculty' ? $primaryKey : null;
    $stID = $role === 'Student' ? $primaryKey : null;

    try {
        $dbh->beginTransaction();

        // Update borrowtbl
        $sql = "
            UPDATE borrowtbl 
            SET 
                status = :status, 
                userID = :userID, 
                expectedReturnDate = :expectedReturnDate, 
                fcID = :fcID, 
                stID = :stID
            WHERE 
                borrowID = :borrowID
        ";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':userID', $userID, PDO::PARAM_INT);
        $query->bindParam(':expectedReturnDate', $expectedReturnDate, PDO::PARAM_STR);
        $query->bindParam(':fcID', $fcID, PDO::PARAM_INT);
        $query->bindParam(':stID', $stID, PDO::PARAM_INT);
        $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $query->execute();

        echo "Borrow table updated successfully.<br>"; // Debugging output

        // Insert into returntbl
        $sql = "
            INSERT INTO returntbl (borrowID, status, penalty, returnarc)
            VALUES (:borrowID, 'borrowed', 10.00, 'Existing')
        ";
        $query = $dbh->prepare($sql);
        $query->bindParam(':borrowID', $borrowID, PDO::PARAM_INT);
        $query->execute();

        echo "Return table updated successfully.<br>"; // Debugging output

        $dbh->commit();

        $_SESSION['message'] = "Borrow confirmed successfully!";
        header('Location: borrowList.php');
        exit;
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Error confirming borrow: " . $e->getMessage();
        exit;
    }
}

// Search for Faculty or Student
if (isset($_POST['searchID']) && isset($_POST['role'])) {
    $id = trim($_POST['searchID']);
    $role = trim($_POST['role']);

    if (empty($id) || empty($role)) {
        echo json_encode(['error' => 'ID or Role is missing']);
        exit;
    }

    try {
        if ($role === 'Faculty') {
            $query = $dbh->prepare("
                SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblfaculty 
                WHERE FacultyID = :id AND archFc = 'Active'
            ");
        } elseif ($role === 'Student') {
            $query = $dbh->prepare("
                SELECT *, CONCAT(Fname, ' ', IFNULL(LEFT(Mname, 1), ''), '. ', Lname) AS FullName 
                FROM tblstudent 
                WHERE StudentID = :id AND archStd = 'Active'
            ");
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
    <style>
        .hidden-section {
            display: none;
        }
    </style>
    <script>
        

        function showModal() {
            const role = document.getElementById('role').value;
            if (!role) {
                alert('Please select a role first.');
                return;
            }
            document.getElementById('roleModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('roleModal').style.display = 'none';
        }

        function fetchData() {
            const role = document.getElementById('role').value;
            const searchID = document.getElementById('searchInput').value;

            if (!searchID || !role) {
                alert('Please enter an ID and select a role.');
                return;
            }

            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `searchID=${encodeURIComponent(searchID)}&role=${encodeURIComponent(role)}`
            })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch data.');
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('fullName').value = data['FullName'];
                        document.getElementById('mobileNumber').value = data['ContactNo'];
                        document.getElementById('emailAddress').value = data['Email'];
                        if (role === 'Student') {
                            document.getElementById('course').value = data['CourseStrand'];
                            document.getElementById('yearLevel').value = data['YrLevel'];
                            document.getElementById('address').value = data['hAddress'];
                        }
                        document.getElementById('searchedID').value = searchID;
                        document.getElementById('role').value = role;
                        document.getElementById('borrowerDetails').style.display = 'block';
                        closeModal();
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('An error occurred while fetching data.');
                });
        }

        

    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <form name="updatest" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role" id="role" onchange="showModal()">
                                <option value="" selected disabled>Select Role</option>
                                <option value="Student">Student</option>
                                <option value="Faculty">Faculty</option>
                            </select>
                        </div>

                        <div id="borrowerDetails" class="hidden-section">
                            <div class="form-group"><label>Full Name</label><input class="form-control" type="text"
                                    id="fullName" disabled /></div>
                            <div class="form-group"><label>Course/Strand</label><input class="form-control" type="text"
                                    id="course" disabled /></div>
                            <div class="form-group"><label>Year Level</label><input class="form-control" type="text"
                                    id="yearLevel" disabled /></div>
                            <div class="form-group"><label>Mobile</label><input class="form-control" type="text"
                                    id="mobileNumber" disabled /></div>
                            <div class="form-group"><label>Email</label><input class="form-control" type="text"
                                    id="emailAddress" disabled /></div>
                            <div class="form-group"><label>Address</label><input class="form-control" type="text"
                                    id="address" disabled /></div>
                        </div>
                    </div>

                    <!-- Right Column: Book Information -->
                    <div class="col-md-6">
                        <fieldset disabled class="bg-light border p-3 rounded">
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
                                <input class="form-control" type="text" name="category"
                                    value="<?php echo htmlspecialchars($result->category); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Format</label>
                                <input class="form-control" type="text" name="format"
                                    value="<?php echo htmlspecialchars($result->format); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>ISBN Number</label>
                                <input class="form-control" type="text" name="isbnNumber"
                                    value="<?php echo htmlspecialchars($result->isbnNumber); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Shelf</label>
                                <input class="form-control" type="text" name="shelf"
                                    value="<?php echo htmlspecialchars($result->shelf); ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label>Account Number</label>
                                <input class="form-control" type="text" name="accountNumber"
                                    value="<?php echo htmlspecialchars($result->accountNumber); ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>Expected Return Date</label>
                                <input class="form-control" type="text" name="expectedReturnDate"
                                    value="<?php echo htmlspecialchars($result->expectedReturnDate); ?>" readonly />
                            </div>

                        </fieldset>
                    </div>
                </div>
                <input type="hidden" name="borrowID" value="<?php echo htmlentities($result->borrowID); ?>">
                <input type="hidden" name="searchedID" id="searchedID">
                <input type="hidden" name="role" id="role">
                <button type="submit" name="confirmBorrow" class="btn btn-primary">Confirm Borrow</button>
            </form>
        </div>
    </div>

    <div id="roleModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Search</h5><span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <input type="text" id="searchInput" class="form-control" placeholder="Enter ID Number">
                    <button class="btn btn-primary mt-2" onclick="fetchData()">Search</button>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        onclick="closeModal()">Close</button></div>
            </div>
        </div>
    </div>
</body>

</html>