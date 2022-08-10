<?php
    session_start();
    // is there something already in the cart ?
    if(isset($_SESSION['cart'])){

        // Extract the array values based on specified key
        $available_products = array_column($_SESSION['cart'],'product_name');
    
        // is item already present in the cart ?
        if(in_array($_POST['product_name'],$available_products)){
            echo"
                    <script>
                        alert('Item is already present ..');
                        window.location.href = './Customer.php';
                    </script>
                ";
        }else{
            $no_items_present = count($_SESSION['cart']);
            $_SESSION['cart'][$no_items_present] = array("product_name"=>$_POST['product_name'], "product_price"=>$_POST['product_price']);

             echo"
             <script>
                 alert('Item added successfully 👍 ..');
                 window.location.href = './Customer.php';
             </script> 
             ";
        }

    }else{
        // Create session variable
        $_SESSION['cart'][0] = array("product_name"=>"$_POST[product_name]", "product_price"=>$_POST['product_price']);
        echo"
                <script>
                    alert('Item added successfully 👍 ..');
                    window.location.href = './Customer.php';
                </script>
            ";
    }

?>