<?php
require("./Database.php");

$database = new Database();
// CREATE a new user
function addUser($uName,$uEmail,$uPassword,$uType,$uPhone,$uStreet,$uPostal,$uCountry,$uState,$uCity){
    $database = new Database();
    $cleanUName = $database->prepare_string($uName);
    $cleanUEmail = $database->prepare_string($uEmail);
    $cleanUPassword = $database->prepare_string($uPassword);
    $cleanUType = $database->prepare_string($uType);
    $cleanUPhone= $database->prepare_string($uPhone);
    $cleanUStreet = $database->prepare_string($uStreet);
    $cleanUPostal = $database->prepare_string($uPostal);
    $cleanUCountry = $database->prepare_string($uCountry);
    $cleanUState = $database->prepare_string($uState);
    $cleanUCity = $database->prepare_string($uCity);


    // First store user's address
    $store_user_address_query = "INSERT INTO address(street,postal_code,city,state,country_name) VALUES(?,?,?,?,?)";
    $stmt = mysqli_prepare($database->getDbc(), $store_user_address_query);
    // Bind the data in the query
    mysqli_stmt_bind_param(
        $stmt,
        'sisss',
        $cleanUStreet,
        $cleanUPostal,
        $cleanUCity,
        $cleanUState,
        $cleanUCountry,
    );
    // Execute the query
    $results = mysqli_stmt_execute($stmt);
    // To get the id of the last inserted record
    $createdAddrId = mysqli_insert_id($database->getDbc());

    // Then store user's data
    if($results){
        $create_user_query = "INSERT INTO user(name,email,password,type,phone,idaddress) VALUES(?,?,?,?,?,?)";
        $stmt = mysqli_prepare($database->getDbc(), $create_user_query);
        // Bind the data in the query
        mysqli_stmt_bind_param(
            $stmt,
            'sssssi',
            $cleanUName,
            $cleanUEmail,
            $cleanUPassword,
            $cleanUType,
            $cleanUPhone,
            $createdAddrId
        );
        // Execute the query
        $result = mysqli_stmt_execute($stmt);
        return $result;
    }
}
?>