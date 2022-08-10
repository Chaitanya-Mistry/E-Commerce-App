<?php
    session_start();

    // is Remove item from cart button pressed ?
    if(isset($_POST['product_to_be_removed'])){
        // remove item from cart
        foreach ($_SESSION['cart'] as $key => $value) {
            if($value['product_name'] == $_POST['product_to_be_removed']){
                // Remove item from array
                unset($_SESSION['cart'][$key]);
                // Rearrange the order of the index
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                echo"
                <script>
                    alert('Item removed successfully 👍 ..');
                    window.location.href = './My_Cart.php';
                </script> 
                ";
                exit();
            }
        }
        
    }
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