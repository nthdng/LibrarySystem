<?php
// Include the database configuration
include('includes/config.php');

// Enable detailed error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the search query is provided via POST
if (isset($_POST['search_query'])) {
    $searchQuery = $_POST['search_query']; // Get the search query

    // SQL query to search books by title, category, and format
    $sql = "SELECT b.bookID, b.bookTitle, c.categoryName, f.formatName, b.count 
            FROM booktbl b
            LEFT JOIN categorytbl c ON b.category = c.categoryID
            LEFT JOIN formattbl f ON b.format = f.formatID
            WHERE (b.bookTitle LIKE :query OR c.categoryName LIKE :query OR f.formatName LIKE :query)
            AND b.archBook = 'Existing'";

    // Prepare the SQL query
    $query = $dbh->prepare($sql);

    // Bind the query parameter with wildcards for flexible matching
    $query->bindValue(':query', "%$searchQuery%", PDO::PARAM_STR);

    // Execute the query
    $query->execute();

    // Fetch the results as objects
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // If there are results, display them in a table
    if ($query->rowCount() > 0) {
        echo '<table class="table table-striped suggestions-table">';
        echo '<thead><tr><th>Title</th><th>Category</th><th>Format</th><th>Count</th></tr></thead>';
        echo '<tbody>';
        foreach ($results as $result) {
            // Each row is clickable, leading to the book's detail page
            echo '<tr onclick="selectBook(\'' . htmlentities($result->bookID) . '\')" style="cursor:pointer;">';
            echo '<td>' . htmlentities($result->bookTitle) . '</td>';
            echo '<td>' . htmlentities($result->categoryName) . '</td>';
            echo '<td>' . htmlentities($result->formatName) . '</td>';
            echo '<td>' . htmlentities($result->count) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        // If no results are found, display a friendly message
        echo "<p>No records found.</p>";
    }

    // End the script after handling the request
    exit();
}
?>
