<?php
require("../Database.php");

// CREATE a new user
function addProduct($pName,$pDetails,$pPrice,$pImage){
    $database = new Database();
    $cleanPName = $database->prepare_string($pName);
    $cleanPDetails = $database->prepare_string($pDetails);
    $cleanPPrice = $database->prepare_string($pPrice);
    $cleanPImage = $database->prepare_string($pImage);

    $create_product_query = "INSERT INTO product(name,description,price,image) VALUES(?,?,?,?)";
    $stmt = mysqli_prepare($database->getDbc(), $create_product_query);
    // Bind the data in the query
    mysqli_stmt_bind_param(
        $stmt,
        'ssds',
        $cleanPName,
        $cleanPDetails,
        $cleanPPrice,
        $cleanPImage
    );
    // Execute the query
    $result = mysqli_stmt_execute($stmt);
    return $result;
}
?>