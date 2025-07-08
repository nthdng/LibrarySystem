<?php
include('../includes/config.php');

$filterType = $_POST['filterType'] ?? 'All';
$whereClause = "WHERE b.archBook = 'Existing'";

switch ($filterType) {
    case 'Category':
        $whereClause .= " ORDER BY c.categoryName";
        break;
    case 'Format':
        $whereClause .= " ORDER BY f.formatName";
        break;
    case 'Shelf':
        $whereClause .= " ORDER BY s.shelfLoc";
        break;
    case 'All':
    default:
        break;
}

$sql = "SELECT b.bookID, b.bookCode, b.bookTitle, b.isbnNumber, 
            c.categoryName, f.formatName, s.shelfLoc, b.accountNumber 
        FROM booktbl b 
        LEFT JOIN categorytbl c ON b.category = c.categoryID 
        LEFT JOIN formattbl f ON b.format = f.formatID 
        LEFT JOIN shelftbl s ON b.shelf = s.shelfID
        $whereClause";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $result) {
        echo "<tr>
                <td><input type='checkbox' class='book-checkbox' value='{$result->bookID}'></td>
                <td>{$result->bookID}</td>
                <td>{$result->accountNumber}</td>
                <td>{$result->bookCode}</td>
                <td>{$result->bookTitle}</td>
                <td>{$result->isbnNumber}</td>
                <td>{$result->categoryName}</td>
                <td>{$result->formatName}</td>
                <td>{$result->shelfLoc}</td>
                <td>
                    <a href='bookManagement.php?del={$result->bookID}' onclick='return confirm(\"Are you sure?\");'>
                        <button class='btn btn-danger'>Archive</button>
                    </a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='10'>No books found.</td></tr>";
}
?>
