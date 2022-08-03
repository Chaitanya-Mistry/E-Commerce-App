<?php
require("../Database.php");

// CREATE a new user
function updateProduct($pName,$pDetails,$pPrice,$pImage,$pId){
    $database = new Database();
    $cleanPName = $database->prepare_string($pName);
    $cleanPDetails = $database->prepare_string($pDetails);
    $cleanPPrice = $database->prepare_string($pPrice);
    $cleanPImage = $database->prepare_string($pImage);
    $productId = $database->prepare_string($pId);

    $update_product_query = "UPDATE product SET name = ?, description = ?, price = ?, image = ? WHERE idproduct = ?;";

    $stmt = mysqli_prepare($database->getDbc(), $update_product_query);
    // Bind the data in the query
    mysqli_stmt_bind_param(
        $stmt,
        'ssdsi',
        $cleanPName,
        $cleanPDetails,
        $cleanPPrice,
        $cleanPImage,
        $productId
    );
    // Execute the query
    $result = mysqli_stmt_execute($stmt);
    
    if($result){
       header("location:../Admin_Stuff/product_details.php");
       exit();
    }else{
        echo "<script>alert('Product was not updated successfully ❎')</script>";
    }
}
?>