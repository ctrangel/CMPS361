<?php
$apiUrl = 'http://localhost:3000/waters';

function fetchData($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true); // Decode JSON response to an array
}

$data = fetchData($apiUrl); // Store the API response in a variable

// Sorting function
if (isset($_GET['sort'])) {
    $sortField = $_GET['sort'];

    usort($data, function ($a, $b) use ($sortField) {
        if ($sortField == 'price') {
            return $a['price'] - $b['price']; // Numeric sorting for price
        } else {
            return strcmp($a[$sortField], $b[$sortField]); // String sorting for brand or type
        }
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<title>Pagentry</title>

<head>

</head>

<body style="
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;
">
    <h1>Pagentry</h1>

    <!-- Sorting dropdown -->
    <form method="GET" style="margin: 10px;">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="brand" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'brand') echo 'selected'; ?>>Brand</option>
            <option value="type" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'type') echo 'selected'; ?>>Type</option>
            <option value="price" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price') echo 'selected'; ?>>Price</option>
        </select>
        <input type="hidden" name="page" value="1"> <!-- Reset to first page on sort change -->
    </form>

    <div id="data-box" style="
    width: auto;
    padding: 10px;
    margin: 10px;
    border: 10px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;
    font-size: 14px;
    display: flex;
    flex-direction: row;
    align-items: center;
">
        <?php
        // Pagination logic
        $perPage = 5; // Number of items to display per page
        $totalItems = count($data); // Total number of items
        $pages = ceil($totalItems / $perPage); // Total number of pages

        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number

        $offset = ($page - 1) * $perPage; // Calculate the offset for the query

        $data = array_slice($data, $offset, $perPage); // Get the items for the current page

        // Display the items
        foreach ($data as $item) {
            echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">';
            echo '<div style="width: 150px; height: 150px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 5px; margin: 10px; padding: 5px;">';
            echo '<h3>' . $item['brand'] . '</h3>';
            echo '<p>' . $item['type'] . '</p>';
            echo '<p>Price: $' . $item['price'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <div style="margin: 10px;">
        <?php
        // Display pagination links
        echo '<div class="pagination" style="margin: 10px;">';
        for ($i = 1; $i <= $pages; $i++) {
            $activeClass = ($i == $page) ? 'active' : ''; // Highlight current page
            echo '<a href="?page=' . $i . '&sort=' . $sortField . '" class="' . $activeClass . '">' . $i . '</a> ';
        }
        echo '</div>';

        // Display the current page number
        echo '<div class="current-page" style="margin: 10px;">';
        echo 'Page ' . $page . ' of ' . $pages;
        echo '</div>';

        // Next and previous buttons
        echo '<div class="pagination" style="margin: 10px;">';
        if ($page > 1) {
            $prev = $page - 1;
            echo '<a href="?page=' . $prev . '&sort=' . $sortField . '" class="prev-next">Previous</a> ';
        }
        if ($page < $pages) {
            $next = $page + 1;
            echo '<a href="?page=' . $next . '&sort=' . $sortField . '" class="prev-next">Next</a>';
        }
        echo '</div>';
        ?>
    </div>

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination a {
            text-decoration: none;
            color: white;
            background-color: #b24d7b;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }

        .pagination .active {
            background-color: #0056b3;
            /* Active page color */
            pointer-events: none;
            /* Disable clicking on active link */
            font-weight: bold;
        }

        .current-page {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .prev-next {
            text-decoration: none;
            color: white;
            background-color: #28A745;
            /* Green color for next/prev */
            padding: 10px 15px;
            border-radius: 5px;
            margin: 0 5px;
            transition: background-color 0.3s;
        }

        .prev-next:hover {
            background-color: #218838;
            /* Darker green on hover */
        }
    </style>

</body>

</html>