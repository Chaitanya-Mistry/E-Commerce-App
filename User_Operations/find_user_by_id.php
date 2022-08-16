<?php
require_once('../Database.php');

    // Get an existing user
    function findUserByID($uEmail){
        $database = new Database();
        $cleanUEmail = $database->prepare_string($uEmail);

        #Query Templete
        $find_user = " SELECT iduser FROM user WHERE email=? ";

        $stmt = $database->getDbc()->prepare($find_user); 
        $stmt->bind_param("s",$cleanUEmail);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        return $result;
    }
?>