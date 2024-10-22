<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Modal Example</title>
    <style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .modal-content label {
            display: block;
            margin-bottom: 10px;
        }
        .modal-content input {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        .modal-content button {
            padding: 10px 20px;
            margin: 5px;
        }
    </style>
</head>
<body>

<!-- Delete Button -->
<button id="deleteBtn">Delete Item</button>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <form id="deleteForm" method="POST">
            <label for="reasonInput">Please provide a reason for deletion:</label>
            <input type="text" id="reasonInput" name="deleteReason" required>
            <button type="submit">Confirm</button>
            <button type="button" id="cancelBtn">Cancel</button>
        </form>
    </div>
</div>

<script>
// Get modal elements
var modal = document.getElementById("myModal");
var deleteBtn = document.getElementById("deleteBtn");
var cancelBtn = document.getElementById("cancelBtn");

// Show the modal when delete button is clicked
deleteBtn.onclick = function() {
    modal.style.display = "flex"; // Show modal (flexbox centers it)
}

// Close the modal if cancel button is clicked
cancelBtn.onclick = function() {
    modal.style.display = "none";
}

// Optionally, close modal if clicking outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php
// Handle form submission in PHP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteReason'])) {
    $deleteReason = $_POST['deleteReason'];
    echo "Deletion reason: " . htmlspecialchars($deleteReason);
    // Handle deletion logic here
}
?>

</body>
</html>
