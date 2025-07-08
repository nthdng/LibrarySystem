<?php
// Include the database configuration
include('includes/config.php');

// Enable detailed error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System</title>

    <!-- Stylesheets -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>

    <style>
        /* Background styling */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/home.jpg');
            background-size: cover;
            filter: blur(5px);
            z-index: -1;
        }

        /* Center main content */
        .content-wrapper {
            display: flex;
            justify-content: center;
        }

        /* Increase width of the search bar */
        .searchInput {
            width: 100%;
            max-width: 800px;
            min-width: 300px;
            margin: 0 auto;
            display: block;
        }

        .suggestions-table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .suggestions-box {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            max-height: 420px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .suggestion {
            padding: 12px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
            border-bottom: 1px solid #eaeaea;
        }

        .suggestion p {
            margin: 0;
            font-size: 14px;
        }

        .suggestion:hover {
            background-color: #f9f9f9;
        }

        @media screen and (max-width: 768px) {
            .searchInput,
            .suggestions-table,
            .suggestions-box {
                width: 90%;
                max-width: none;
            }
        }

        @media screen and (max-width: 480px) {
            .searchInput {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/headerindex.php'); ?>
    <div class="background"></div>

    <div class="main-content">
        <div class="content-wrapper">
            <!-- SEARCH PANEL START -->
            <div class="col-md-6 col-sm-8 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading text-center">SEARCH BOOKS</div>
                    <div class="panel-body">
                        <form role="form">
                            <div class="form-group">
                                <label>Enter Book Title, Category, or Format</label>
                                <input class="form-control searchInput" type="text" name="search_query" id="searchInput"
                                    required autocomplete="off" />
                                <div id="suggestions" class="suggestions-box" role="listbox"
                                    aria-label="Search suggestions"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- SEARCH PANEL END -->
        </div>
        <?php include('includes/footer.php'); ?>
    </div>

    <!-- Custom Script to Handle DataTables and AJAX -->
    <script>
        $(document).ready(function () {
            // AJAX Search functionality
            $('#searchInput').on('input', function () {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'search.php', // Adjust to match the search PHP file location
                        method: 'POST',
                        data: { search_query: query },
                        success: function (data) {
                            $('#suggestions').html(data);
                        }
                    });
                } else {
                    $('#suggestions').html('');
                }
            });

            // Redirect to book details page when a suggestion is clicked
            $(document).on('click', 'tr', function () {
                let bookId = $(this).data('bookid');
                if (bookId) {
                    window.location.href = 'main-all/confirmReserve.php?bookid=' + bookId;
                }
            });

            // Initialize DataTable if there's a table element with an ID of `myTable`
            $('#myTable').DataTable(); // Replace `#myTable` with your actual table ID
        });

        function selectBook(bookID) {
            // Redirect to confirmReserve.php with bookID as a parameter
            const url = `main-all/confirmReserve.php?bookid=${encodeURIComponent(bookID)}`;
            window.location.href = url;
        }
    </script>
</body>

</html>
