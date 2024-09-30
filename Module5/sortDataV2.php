<?php
$apiUrl = 'http://localhost:3000/waters';


$languages = [
    'en' => [
        'title' => 'Pagentry',
        'sort_by' => 'Sort by:',
        'brand' => 'Brand',
        'type' => 'Type',
        'price' => 'Price',
        'sorted_by' => 'Sorted by:',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'results' => 'results',
        'page' => 'Page',
        'previous' => 'Previous',
        'next' => 'Next',
    ],
    'es' => [
        'title' => 'Paginación',
        'sort_by' => 'Ordenar por:',
        'brand' => 'Marca',
        'type' => 'Tipo',
        'price' => 'Precio',
        'sorted_by' => 'Ordenado por:',
        'showing' => 'Mostrando',
        'to' => 'a',
        'of' => 'de',
        'results' => 'resultados',
        'page' => 'Página',
        'previous' => 'Anterior',
        'next' => 'Siguiente',
    ]
];

// Set the default language to English if not selected
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$selectedLang = $languages[$lang];

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
if (isset($_GET['sort']) && $_GET['sort'] !== '') {
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

<head>
    <title><?php echo $selectedLang['title']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;
">
    <h1><?php echo $selectedLang['title']; ?></h1>

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <?php
                // Preserve language and page in query string for navigation links
                $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Define links with preserved query parameters
                $homeLink = '?page=1&lang=' . $lang;
                $featuresLink = '?page=2&lang=' . $lang;
                $pricingLink = '?page=3&lang=' . $lang;
                ?>

                <a class="nav-item nav-link active" href="<?php echo $homeLink; ?>">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="<?php echo $featuresLink; ?>">Features</a>
                <a class="nav-item nav-link" href="<?php echo $pricingLink; ?>">Pricing</a>
                <a class="nav-item nav-link disabled" href="#">Disabled</a>
            </div>
        </div>
    </nav> -->





    <!-- Language switcher -->
    <form method="GET" style="margin: 10px;">
        <label for="lang">Language:</label>
        <select name="lang" id="lang" onchange="this.form.submit()">
            <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>English</option>
            <option value="es" <?php if ($lang == 'es') echo 'selected'; ?>>Español</option>
        </select>
        <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'brand'; ?>">
        <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>">
    </form>

    <!-- Sorting dropdown -->
    <form method="GET" style="margin: 10px;">
        <label for="sort"><?php echo $selectedLang['sort_by']; ?></label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="" <?php if (!isset($_GET['sort']) || $_GET['sort'] == '') echo 'selected'; ?>>Default</option>
            <option value="brand" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'brand') echo 'selected'; ?>><?php echo $selectedLang['brand']; ?></option>
            <option value="type" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'type') echo 'selected'; ?>><?php echo $selectedLang['type']; ?></option>
            <option value="price" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price') echo 'selected'; ?>><?php echo $selectedLang['price']; ?></option>
        </select>
        <input type="hidden" name="lang" value="<?php echo $lang; ?>">
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
            echo '<p>' . $selectedLang['price'] . ': $' . $item['price'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <div style="margin: 10px;">
        <?php
        // result count
        echo '<div style="margin: 10px;">';
        echo $selectedLang['showing'] . ' ' . ($offset + 1) . ' ' . $selectedLang['to'] . ' ' . ($offset + count($data)) . ' ' . $selectedLang['of'] . ' ' . $totalItems . ' ' . $selectedLang['results'];
        echo '</div>';

        // Display pagination links
        echo '<div class="pagination" style="margin: 10px;">';
        for ($i = 1; $i <= $pages; $i++) {
            $activeClass = ($i == $page) ? 'active' : ''; // Highlight current page

            // Check if sortField is set, use an empty value if not
            $sortField = isset($_GET['sort']) ? $_GET['sort'] : '';

            echo '<a href="?page=' . $i . '&sort=' . $sortField . '&lang=' . $lang . '" class="' . $activeClass . '">' . $i . '</a> ';
        }
        echo '</div>';

        // Display the current page number
        echo '<div class="current-page" style="margin: 10px;">';
        echo $selectedLang['page'] . ' ' . $page . ' ' . $selectedLang['of'] . ' ' . $pages;
        echo '</div>';

        // Next and previous buttons
        echo '<div class="pagination" style="margin: 10px;">';

        // Check if sortField is set for prev/next links
        $sortField = isset($_GET['sort']) ? $_GET['sort'] : '';

        if ($page > 1) {
            $prev = $page - 1;
            echo '<a href="?page=' . $prev . '&sort=' . $sortField . '&lang=' . $lang . '" class="prev-next">' . $selectedLang['previous'] . '</a> ';
        }
        if ($page < $pages) {
            $next = $page + 1;
            echo '<a href="?page=' . $next . '&sort=' . $sortField . '&lang=' . $lang . '" class="prev-next">' . $selectedLang['next'] . '</a>';
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
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

</body>

</html>