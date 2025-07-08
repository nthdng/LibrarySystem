<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Ensure the session is valid
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}

// Set timezone to Philippine Time
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Today's Reports</title>
    <style>
        /* General page layout */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        /* Header styling */
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            position: relative;
        }

        .header img {
            width: 100px;
            height: auto;
            margin: 0 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        /* Footer styling (hidden in UI) */
        .footer {
            display: none;
        }

        /* Styles for printing */
        @media print {
            .no-print {
                display: none; /* Hide buttons during print */
            }

            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background-color: #fff;
                border-bottom: 2px solid #ddd;
                padding: 10px;
                z-index: 1000;
            }

            .footer {
                display: block; /* Show footer only when printing */
                text-align: center;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #fff;
                border-top: 1px solid #ddd;
                padding: 5px;
                font-size: 12px;
                color: #555;
            }

            .footer .page-number:before {
                counter-increment: page;
                content: "Page " counter(page);
            }

            .content {
                margin-top: 180px; /* Adjust for fixed header height */
                margin-bottom: 50px; /* Adjust for fixed footer height */
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="../assets/img/PWU.png" alt="Logo">
        <div style="display: inline-block; text-align: center;">
            <h1>PHILIPPINE WOMEN’S UNIVERSITY</h1>
            <p>CAREER DEVELOPMENT AND CONTINUING EDUCATION CENTER - BATAAN</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Today's Report</h2>
        <p><strong>Date:</strong> <?php echo date("l, F j, Y - h:i A"); ?></p>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Borrower's ID</th>
                    <th>Borrower Name</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Book Name</th>
                    <th>Penalty</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get today's date in the format 'YYYY-MM-DD'
                $today = date('Y-m-d');

                // SQL Query to fetch today's data (matching Reportsmain.php structure)
                $sql = "
SELECT 
    b.BorrowID,
    CASE 
        WHEN b.FacultyID IS NOT NULL THEN b.FacultyID
        WHEN b.StudentID IS NOT NULL THEN b.StudentID
        WHEN b.Username IS NOT NULL THEN b.Username
    END AS BorrowerID,
    CASE 
        WHEN b.FacultyID IS NOT NULL THEN CONCAT(f.Fname, ' ', f.Mname, ' ', f.Lname)
        WHEN b.StudentID IS NOT NULL THEN CONCAT(s.Fname, ' ', s.Mname, ' ', s.Lname)
        WHEN b.Username IS NOT NULL THEN b.Name
    END AS BorrowerName,
    b.BorrowDate,
    r.ReturnDate,
    bk.BookTitle AS BookName,
    r.Penalty,
    r.PenaltyStatus
FROM 
    borrowtbl b
LEFT JOIN 
    returntbl r ON b.BorrowID = r.BorrowID
LEFT JOIN 
    tblfaculty f ON b.FacultyID = f.FacultyID
LEFT JOIN 
    tblstudent s ON b.StudentID = s.StudentID
LEFT JOIN 
    booktbl bk ON b.BookID = bk.BookID
WHERE 
    (DATE(b.BorrowDate) = :today OR DATE(r.ReturnDate) = :today)
    AND (
        CASE 
            WHEN b.FacultyID IS NOT NULL THEN b.FacultyID
            WHEN b.StudentID IS NOT NULL THEN b.StudentID
            WHEN b.Username IS NOT NULL THEN b.Username
        END IS NOT NULL
    )
    AND (
        CASE 
            WHEN b.FacultyID IS NOT NULL THEN CONCAT(f.Fname, ' ', f.Mname, ' ', f.Lname)
            WHEN b.StudentID IS NOT NULL THEN CONCAT(s.Fname, ' ', s.Mname, ' ', s.Lname)
            WHEN b.Username IS NOT NULL THEN b.Name
        END IS NOT NULL
    );

";

                $query = $dbh->prepare($sql);
                $query->bindParam(':today', $today, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        $penaltyStatus = $result->PenaltyStatus;

                        // Update PenaltyStatus dynamically if not "Paid"
                        if ($penaltyStatus !== "Paid") {
                            if (floatval($result->Penalty) > 0) {
                                $penaltyStatus = "Unpaid";
                            } else {
                                $penaltyStatus = "No penalty";
                            }

                            // Update the database
                            $updateSQL = "UPDATE returntbl SET PenaltyStatus = :penaltyStatus WHERE BorrowID = :borrowID";
                            $updateQuery = $dbh->prepare($updateSQL);
                            $updateQuery->bindParam(':penaltyStatus', $penaltyStatus, PDO::PARAM_STR);
                            $updateQuery->bindParam(':borrowID', $result->BorrowID, PDO::PARAM_INT);
                            $updateQuery->execute();
                        }

                        echo "<tr>";
                        echo "<td>" . htmlentities($cnt) . "</td>";
                        echo "<td>" . htmlentities($result->BorrowerID) . "</td>";
                        echo "<td>" . htmlentities($result->BorrowerName) . "</td>";
                        echo "<td>" . htmlentities($result->BorrowDate) . "</td>";
                        echo "<td>" . htmlentities($result->ReturnDate ?: "Not yet returned") . "</td>";
                        echo "<td>" . htmlentities($result->BookName) . "</td>";
                        echo "<td>" . htmlentities($result->Penalty ?: "No penalty") . "</td>";
                        echo "<td>" . htmlentities($penaltyStatus) . "</td>";
                        echo "</tr>";
                        $cnt++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found for today</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span class="page-number"></span>
    </div>

    <!-- Buttons -->
    <button class="btn no-print" id="printCurrentPage">Print</button>
    <button class="btn no-print" id="backButton">Back</button>

    <script>
        // JavaScript to handle "Print Current Page" button
        document.getElementById("printCurrentPage").addEventListener("click", function () {
            window.print(); // Opens the print dialog for the current page
        });

        // JavaScript to handle "Back" button
        document.getElementById("backButton").addEventListener("click", function () {
            window.history.back(); // Go back to the previous page
        });
    </script>
</body>

</html>
