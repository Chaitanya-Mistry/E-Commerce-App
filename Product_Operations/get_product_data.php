<?php
    require("../Database.php");

    function getProducts(){
        $database = new Database();

        #Query Templete
        $find_products = " SELECT * FROM product";

        $found_products = $database->getDbc()->query($find_products);

        return $found_products;
    }   
?>