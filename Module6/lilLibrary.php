<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Library</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <h1>Lil Library</h1>

    <?php
    // Number of books per page
    $booksPerPage = 5;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'default';

    // Fetch books from your API
    $apiUrl = 'http://localhost:4000/books'; // Your local API endpoint

    $booksData = file_get_contents($apiUrl); // Fetch the data from your API
    $booksData = json_decode($booksData, true); // Decode the JSON data into a PHP array

    // Handle sorting in PHP
    if ($sortBy != 'default') {
        usort($booksData, function ($a, $b) use ($sortBy) {
            return strcmp($a[$sortBy], $b[$sortBy]); // Sort based on the selected option
        });
    }

    // Pagination logic
    $totalBooks = count($booksData);
    $totalPages = ceil($totalBooks / $booksPerPage);
    $startIndex = ($currentPage - 1) * $booksPerPage;
    $booksToShow = array_slice($booksData, $startIndex, $booksPerPage);

    // Display books
    echo '<div id="book-container" class="book-container">';
    foreach ($booksToShow as $book) {
        echo '<div class="book-card">';
        echo '<div class="book-title">' . $book['title'] . '</div>';
        echo '<div class="book-author">' . $book['author'] . '</div>';
        echo '<div class="book-shelves"><strong>Shelves:</strong> ' . $book['shelves'] . '</div>';
        echo '<div class="book-rating"><strong>Avg Rating:</strong> ' . $book['avg_rating'] . '</div>';
        echo '</div>';
    }
    echo '</div>';

    // Pagination controls
    echo '<div class="controls">';
    echo '<button ' . ($currentPage == 1 ? 'disabled' : '') . ' onclick="window.location.href=\'?page=1&sort=' . $sortBy . '\'">First</button>';
    echo '<div class="pagination">';
    echo '<button ' . ($currentPage == 1 ? 'disabled' : '') . ' onclick="window.location.href=\'?page=' . ($currentPage - 1) . '&sort=' . $sortBy . '\'">Previous</button>';
    echo '<span class="page-info">Page ' . $currentPage . ' of ' . $totalPages . '</span>';
    echo '<button ' . ($currentPage == $totalPages ? 'disabled' : '') . ' onclick="window.location.href=\'?page=' . ($currentPage + 1) . '&sort=' . $sortBy . '\'">Next</button>';
    echo '</div>';
    echo '<button ' . ($currentPage == $totalPages ? 'disabled' : '') . ' onclick="window.location.href=\'?page=' . $totalPages . '&sort=' . $sortBy . '\'">Last</button>';
    echo '</div>';
    ?>

    <!-- Sort by dropdown -->
    <div class="controls">
        <label for="sort">Sort by:</label>
        <select id="sort" onchange="window.location.href='?page=1&sort=' + this.value;">
            <option value="default" <?php echo $sortBy == 'default' ? 'selected' : ''; ?>>Default</option>
            <option value="title" <?php echo $sortBy == 'title' ? 'selected' : ''; ?>>Title</option>
            <option value="author" <?php echo $sortBy == 'author' ? 'selected' : ''; ?>>Author</option>
            <option value="avg_rating" <?php echo $sortBy == 'avg_rating' ? 'selected' : ''; ?>>Avg Rating</option>
        </select>
    </div>

</body>

</html>