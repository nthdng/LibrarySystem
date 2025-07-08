<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Ensure the session is valid
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
    exit;
}

// Get the student ID from the query parameter
$StudentID = isset($_GET['htrSt']) ? trim($_GET['htrSt']) : '';
if (empty($StudentID)) {
    echo "<script>alert('Invalid Student ID'); window.location.href='studentlist.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Transaction History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            height: auto;
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
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="../assets/img/PWU.png" alt="Logo">
        <div>
            <h1>PHILIPPINE WOMEN’S UNIVERSITY</h1>
            <p>CAREER DEVELOPMENT AND CONTINUING EDUCATION CENTER - BATAAN</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Transaction History</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book Name</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Penalty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL Query to fetch all transaction history for the given student
                $sql = "
                SELECT 
                    bk.BookTitle AS BookName, 
                    br.BorrowDate AS BorrowedDate, 
                    CASE 
                        WHEN rt.ReturnDate IS NULL THEN 'Not yet returned' 
                        ELSE rt.ReturnDate 
                    END AS ReturnedDate, 
                    CASE 
                        WHEN rt.Penalty IS NULL OR rt.Penalty = 0 THEN 'No penalty' 
                        ELSE CONCAT('₱', FORMAT(rt.Penalty, 2)) 
                    END AS Penalty
                FROM 
                    borrowtbl br
                JOIN 
                    booktbl bk ON br.BookID = bk.BookID
                LEFT JOIN 
                    returntbl rt ON br.BorrowID = rt.BorrowID
                WHERE 
                    br.StudentID = :StudentID
                ORDER BY 
                    br.BorrowDate DESC
                ";

                $query = $dbh->prepare($sql);
                $query->bindParam(':StudentID', $StudentID, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($cnt) . "</td>";
                        echo "<td>" . htmlentities($result->BookName) . "</td>";
                        echo "<td>" . htmlentities($result->BorrowedDate) . "</td>";
                        echo "<td>" . htmlentities($result->ReturnedDate) . "</td>";
                        echo "<td>" . htmlentities($result->Penalty) . "</td>";
                        echo "</tr>";
                        $cnt++;
                    }
                } else {
                    echo "<tr><td colspan='5'>No transaction history found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
s