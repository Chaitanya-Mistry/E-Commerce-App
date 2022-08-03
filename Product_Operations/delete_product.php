<?php
    $error = null;

    if(!empty($_GET['product_id'])){
        $product_id = $_GET['product_id'];
    } else {
        $product_id = null;
        $error = "<p> Error! Product Id not found!</p>";
    }

    if($error == null){
        require('../Database.php');
        $database = new Database();

        // First select the data of particular product then execute delete query
        $selectProductQuery = "SELECT image FROM product WHERE idproduct=?";
        $stmt1 = $database->getDbc()->prepare($selectProductQuery);
        $stmt1->bind_param("i",$product_id);
        $stmt1->execute();
        $result = $stmt1->get_result(); // get the mysqli result
        $deleteProductImgPath = $result->fetch_assoc()['image']; // fetch data   
        // print_r($deleteProductImgPath['image']);
        // Execute delete query after select query
        if($stmt1){    
            $query = "DELETE FROM product WHERE idproduct=?"; // SQL Parameterized Query
            $stmt = $database->getDbc()->prepare($query); 
            $stmt->bind_param("i", $product_id);
            $stmt->execute();

            if($stmt){
                // Check if image exists
                if (file_exists($deleteProductImgPath)) 
                {
                    // Delete an image from the folder
                    unlink($deleteProductImgPath);
                    header("Location: ../Admin_Stuff/product_details.php");
                    exit;
                }else{
                    header("Location: ../Admin_Stuff/product_details.php");
                    exit;
                }                
            } else {
                echo "</br><p>Some error in Deleting a product.</p>";
            }
        }
        
    } else{
        echo "Somethinng went wrong. The error is : $error";
    }
?>