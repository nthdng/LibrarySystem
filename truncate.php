<?php
// Database connection
$host = 'localhost';
$dbname = 'librarySystem';
$username = 'root';  // Adjust as per your configuration
$password = '';

// Establish connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize message variable
    $message = '';

    // Check if a table truncation is requested
    if (isset($_POST['truncate_table'])) {
        $table = $_POST['truncate_table'];

        // Disable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // Truncate the selected table
        $pdo->exec("TRUNCATE TABLE `$table`");

        // Re-enable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // Success message
        $message = "<p class='success'>Table <strong>$table</strong> truncated successfully!</p>";
    }
} catch (PDOException $e) {
    $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }

        /* Table for organization */
        .truncate-table {
            width: 100%;
            border-collapse: collapse;
        }

        .truncate-table th,
        .truncate-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .truncate-table th {
            background-color: #007bff;
            color: white;
        }

        .truncate-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Adjust button style */
        .truncate-btn {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .truncate-btn:hover {
            background-color: #0056b3;
        }

        .truncate-btn:active {
            background-color: #00428a;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Database Management System</h1>

        <div class="message">
            <?= $message; ?>
        </div>

        <form method="POST" id="truncate-form">
            <table class="truncate-table">
                <thead>
                    <tr>
                        <th>Table Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>booktbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('booktbl')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>categorytbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('categorytbl')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>formattbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('formattbl')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>shelftbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('shelftbl')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>tblfaculty</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('tblfaculty')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>tblstudent</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('tblstudent')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>tbluser</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('tbluser')">Truncate</button></td>
                    </tr>
                    <!-- New tables -->
                    <tr>
                        <td>borrowtbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('borrowtbl')">Truncate</button></td>
                    </tr>
                    <tr>
                        <td>returntbl</td>
                        <td><button type="button" class="truncate-btn"
                                onclick="confirmTruncate('returntbl')">Truncate</button></td>
                    </tr>
                </tbody>

            </table>
        </form>
    </div>

    <!-- Modal -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to truncate this table?</p>
            <form method="POST">
                <input type="hidden" id="truncate_table" name="truncate_table">
                <div class="modal-buttons">
                    <button type="submit">Yes, truncate</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmTruncate(tableName) {
            document.getElementById('truncate_table').value = tableName;
            document.getElementById('confirm-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirm-modal').style.display = 'none';
        }
    </script>

</body>

</html>