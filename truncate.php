<?php
// Database connection
$host = 'localhost';
$dbname = 'importdb';
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
        .button-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }
        button {
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }
        button:active {
            background-color: #00428a;
            transform: translateY(1px);
        }
        button[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 130%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        <div class="button-container">
            <button type="button" data-tooltip="Truncate archivebookstbl" onclick="confirmTruncate('archivebookstbl')">Truncate archivebookstbl</button>
            <button type="button" data-tooltip="Truncate booktbl" onclick="confirmTruncate('booktbl')">Truncate booktbl</button>
            <button type="button" data-tooltip="Truncate categorytbl" onclick="confirmTruncate('categorytbl')">Truncate categorytbl</button>
            <button type="button" data-tooltip="Truncate formattbl" onclick="confirmTruncate('formattbl')">Truncate formattbl</button>
            <button type="button" data-tooltip="Truncate othersarchivedtbl" onclick="confirmTruncate('othersarchivedtbl')">Truncate othersarchivedtbl</button>
            <button type="button" data-tooltip="Truncate shelftbl" onclick="confirmTruncate('shelftbl')">Truncate shelftbl</button>
            <button type="button" data-tooltip="Truncate tblarchivefaculty" onclick="confirmTruncate('tblarchivefaculty')">Truncate tblarchivefaculty</button>
            <button type="button" data-tooltip="Truncate tblarchivestudent" onclick="confirmTruncate('tblarchivestudent')">Truncate tblarchivestudent</button>
            <button type="button" data-tooltip="Truncate tblfaculty" onclick="confirmTruncate('tblfaculty')">Truncate tblfaculty</button>
            <button type="button" data-tooltip="Truncate tblrchiveusers" onclick="confirmTruncate('tblrchiveusers')">Truncate tblrchiveusers</button>
            <button type="button" data-tooltip="Truncate tblstudent" onclick="confirmTruncate('tblstudent')">Truncate tblstudent</button>
            <button type="button" data-tooltip="Truncate tbluser" onclick="confirmTruncate('tbluser')">Truncate tbluser</button>
        </div>
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
