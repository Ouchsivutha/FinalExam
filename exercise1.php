<?php
$mysqli = new mysqli("localhost", "root", "Year@2024", "blog_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$csvFile = 'products.csv';
function importProductsFromCSV($csvFilePath, $mysqli)
{

    if (!file_exists($csvFilePath) || !is_readable($csvFilePath)) {
        die("CSV file not found or not readable.");
    }
    if (($handle = fopen($csvFilePath, 'r')) !== false) {

        fgetcsv($handle);
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            
            if (count($row) < 3 || empty($row[0]) || empty($row[1]) || empty($row[2])) {
                continue;
            }


            $product_name = $mysqli->real_escape_string(trim($row[0]));
            $price = floatval($row[1]);
            $category = $mysqli->real_escape_string(trim($row[2]));

            $checkQuery = "SELECT id FROM products WHERE product_name = '$product_name' LIMIT 1";
            $result = $mysqli->query($checkQuery);

            if ($result && $result->num_rows > 0) {
                continue; // Skip duplicate product
            }

            $insertQuery = "
                INSERT INTO products (product_name, price, category)
                VALUES ('$product_name', $price, '$category')
            ";
            $mysqli->query($insertQuery);
        }


        fclose($handle);
        echo "Import completed.";
    }
}

importProductsFromCSV($csvFile, $mysqli);


$mysqli->close();
