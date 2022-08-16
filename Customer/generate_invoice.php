<?php
    session_start();

    // IF customer is not logged in ..
    if (!isset($_SESSION['CustomerLoginId'])) {
        header("location:../signIn.php");
        exit();
    }else{
        require('../Database.php');
        $database = new Database();
        // Find Customer by id
        require('../User_Operations/find_user_by_id.php');
        $customer_id = '';        
        $result = findUserByID($_SESSION['CustomerLoginId']);
        
        // Storing customer's id
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $customer_id = $row['iduser'];        
        } 
       
        // Today's date
        $today_date = date('Y-m-d');

        $insertedAllRecords = true;
        // $c_id = (int)$customer_id;
        // $sql = "INSERT INTO orders (iduser, idproduct, date, quantity,total_price) VALUES ($c_id, 20, '$today_date',21,399.99)";

        // if ($database->getDbc()->query($sql) === TRUE) {
        //     echo "New record created successfully";
        // } else {
        //     echo "Error: " . $sql . "<br>" . $database->getDbc()->error;
        // }

        $sql = '';
        foreach ($_SESSION['cart'] as $key => $value) {
            print_r($value);
            
            $p_id = (int)$value['product_id'];
            $c_id = (int)$customer_id;
            $p_total_price = doubleval($value['product_total_price']);
            $p_quantity = (int)$value['product_quantity'];
          
            $store_orders = "INSERT INTO orders(iduser,idproduct,date,quantity,total_price) VALUES(?,?,?,?,?)";
            $stmt = mysqli_prepare($database->getDbc(),$store_orders);
            // Bind the data in the query
            mysqli_stmt_bind_param(
                $stmt,
                'iisid',
                $c_id,
                $p_id,
                $today_date,
                $p_quantity,
                $p_total_price
            );
            // Execute the query
            $result = mysqli_stmt_execute($stmt);
            if(!$result){
                $insertedAllRecords = false;
            }       
        }
        // IF all the records are inserted successfully..
        if($insertedAllRecords){
            // Unset Cart Session Variable
            unset($_SESSION['cart']); 
            // echo "
            //     <script>
            //         alert('Orders details has been captured successfully ..');
            //         sessionStorage.removeItem('total_price_final');
            //         window.location.href='./Customer.php';
            //     </script>
            // ";
            include_once('../fpdf184/fpdf.php');

        }else{
            echo "<script>alert('Error occured while inserting many records ... ☠️')</script>";
        }
        
    }
?>