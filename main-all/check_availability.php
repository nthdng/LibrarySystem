<?php
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';

    if (empty($username)) {
        echo 'unavailable';
        exit;
    }

    $sql = "SELECT * FROM tbluser WHERE Username = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();

    echo $query->rowCount() > 0 ? 'unavailable' : 'available';
}
?>
