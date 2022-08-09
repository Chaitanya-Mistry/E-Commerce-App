<?php
require('./Database.php');

// Get an existing user
function getUser($uEmail,$uPassword,$uType){
    $database = new Database();
    $cleanUEmail = $database->prepare_string($uEmail);
    $cleanUPassword = $database->prepare_string($uPassword);
    $cleanUType = $database->prepare_string($uType);

    #Query Templete
    $find_user = " SELECT * FROM user WHERE email=? AND password=? AND type=? ";

    #prepared statement 
    if($stmt = mysqli_prepare($database->getDbc(), $find_user)){
        // Bind the data in the Query Template
        mysqli_stmt_bind_param($stmt,'sss',$cleanUEmail,$cleanUPassword,$cleanUType);

        // Execute the query
        mysqli_stmt_execute($stmt); // Excecuting prepared statement
        mysqli_stmt_store_result($stmt); // Store result of execution in $stmt 

        if(mysqli_stmt_num_rows($stmt) == 1){
            session_start();
            if($cleanUType == 'Admin'){
                $_SESSION['AdminLoginId'] = $cleanUEmail;
                header("location: ./Admin_Stuff/Admin_Panel.php");
            }
            if($cleanUType == 'Customer'){
                $_SESSION['CustomerLoginId'] = $cleanUEmail;
                header("location:./Customer/Customer.php");
            }
            exit();
        }else{
            echo "<script>alert('Invalid Email OR Password...')</script>";
        }
        
    }else{
        echo "<script>SQL Query cannot be prepared</script>";
    }   
}
?>