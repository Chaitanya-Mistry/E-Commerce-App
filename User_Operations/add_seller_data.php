<?php
require("../Database.php");

// CREATE a new user
function addSeller($sName,$sEmail,$sStreet,$sPostal,$sCountry,$sState,$sCity){
    $database = new Database();
    $cleanSName = $database->prepare_string($sName);
    $cleanSEmail = $database->prepare_string($sEmail);
    $cleanSStreet = $database->prepare_string($sStreet);
    $cleanSPostal = $database->prepare_string($sPostal);
    $cleanSCountry = $database->prepare_string($sCountry);
    $cleanSState = $database->prepare_string($sState);
    $cleanSCity = $database->prepare_string($sCity);
    // $cleanPId = $database->prepare_string($pId);
        // First store user's address
        $store_user_address_query = "INSERT INTO address(street,postal_code,city,state,country_name) VALUES(?,?,?,?,?)";
        $stmt = mysqli_prepare($database->getDbc(), $store_user_address_query);
        // Bind the data in the query
        mysqli_stmt_bind_param(
            $stmt,
            'sisss',
            $cleanSStreet,
            $cleanSPostal,
            $cleanSCity,
            $cleanSState,
            $cleanSCountry,
        );
        // Execute the query
        $results = mysqli_stmt_execute($stmt);

        // To get the id of the last inserted record
        $createdAddrId = mysqli_insert_id($database->getDbc());

        // Then store sellers's data
        if($results){
            $create_seller_query = "INSERT INTO sold_by(firm_name,email,idaddress) VALUES(?,?,?)";
            $stmt = mysqli_prepare($database->getDbc(), $create_seller_query);
            // Bind the data in the query
            mysqli_stmt_bind_param(
                $stmt,
                'ssi',
                $cleanSName,
                $cleanSEmail,
                $createdAddrId
            );
            // Execute the query
            $result = mysqli_stmt_execute($stmt);
            // To get the id of the last inserted seller's record
            $createdSellerId = mysqli_insert_id($database->getDbc());

            // Then store seller's products (Sold By Products Table)
            if($result && $createdSellerId){
                return $createdSellerId;
            }
        }
}
?>