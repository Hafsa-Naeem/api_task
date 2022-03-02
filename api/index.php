<?php

require "../vendor/autoload.php";

set_exception_handler("ErrorHandler::handleException");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();
?>
<form action="" method="POST">
    <label for="town">Town:</label>
    <input type="text" name="arr[Town]">

    <label for="bedrooms">Number of Bedrooms:</label>
    <input type="number" name="arr[Number_of_Bedrooms]">

    <label for="price">Price:</label>
    <input type="number" name="arr[Price]">


    <p>Property Type:</p>

    <input type="radio" id="Terraced" name="arr[PropertyType]" value="Terraced"">
    <label for="Terraced">Terraced</label>

    <input type="radio" id="Flat" name="arr[PropertyType]" value="Flat">
    <label for="Flat">Flat</label>

    <input type="radio" name="arr[PropertyType]" id="Semi-detached" value="Semi-detached">
    <label for="Semi-detached">Semi-Detached</label>

    <input type="radio" id="Bungalow" name="arr[PropertyType]" value="Bungalow">
    <label for="Bungalow">Bungalow</label>

    <input type="radio" id="Cottage" name="arr[PropertyType]" value="Cottage">
    <label for="Cottage">Cottage</label>

    <input type="radio" id="End of Terrace" name="arr[PropertyType]" value="End of Terrace">
    <label for="End of Terrace">End of Terrace</label>
    <br>

    <p>For:</p>

    <input type="radio" id="Rent" name="arr[For]" value="Rent"">
    <label for="for">Rent</label>
    <input type="radio" id="Rent" name="arr[For]" value="Sale"">
    <label for="for">Sale</label>


    <input type="submit" name="submit"/>
</form>

<?php
$mydatabase = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

$gateway = new TaskGateway($mydatabase);

$controller = new TaskController($gateway);

$getdata = new TaskController($gateway);

if (isset($_POST['submit'])) {
    $formData = $_POST["arr"];

    $filterdata = $getdata->processFilter($formData);
}

$limit = 30;

/*
get all adata and totalrows*/
if (isset($_POST['submit'])) {
    $total_rows = sizeof($filterdata);
} else {
    $results = $getdata->getAllData();
    $total_rows = sizeof($results);
}
$limit = 30;
$total_pages = ceil($total_rows / $limit);


if (!isset ($_GET['page'])) {
    $page_number = 1;
} else {
    $page_number = $_GET['page'];
}


$initial_page = ($page_number - 1) * $limit;

if (isset($_POST['submit'])) {
    $getLimitedRows = $getdata->processFilterLimit($initial_page, $limit, $formData);
    echo sizeof($getLimitedRows);
} else {
    $getLimitedRows = $getdata->getLimitedData($initial_page, $limit);
}
if (sizeof($getLimitedRows) < 30) {
    $dataset = sizeof($getLimitedRows);
} else {
    $dataset = $limit;
}
echo "<table>
  <tr>
    <th>County</th>
    <th>Country</th>
    <th>DisplayableAddress</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>Number_of_Bedrooms</th>
    <th>Number_of_Bathrooms</th>
    <th>Price</th>
    <th>ForSale_ForRent</th>
    <th>Prop_Type</th>
  </tr>";

for ($i = 0; $i < $dataset; $i++) {
    echo "<tr>
         <td>" . $getLimitedRows[$i]['County'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Country'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['DisplayableAddress'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Latitude'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Longitude'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Number_of_Bedrooms'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Number_of_Bathrooms'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Price'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['ForSale_ForRent'] . "</td>" .
        "<td>" . $getLimitedRows[$i]['Prop_Type'] . "</td>
    </tr>";
}
for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
    echo '<a href = "index.php?page=' . $page_number . '">' . $page_number . ' </a>';
}

?>

