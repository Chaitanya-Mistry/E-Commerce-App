<?php
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // is Remove item from cart button pressed ?
        if(isset($_POST['remove_item_btn'])){
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
        else if(isset($_SESSION['cart'])){

            // Extract the array values based on specified key
            $available_products = array_column($_SESSION['cart'],'product_name');
        
            // is item already present in the cart ?
            if(in_array($_POST['product_name'],$available_products)){
            
                foreach ($_SESSION['cart'] as $key => $value) {
                    // Get the position of product to increase it's quantity
                    if(in_array($_POST['product_name'],$value)){
                        $index = $key;
                    }
                }
                // Increment Product's Quantity By 1 (One)
                $_SESSION['cart'][$index]['product_quantity'] = $_SESSION['cart'][$index]['product_quantity']+1;
                // Increment Product's Price As Well
                $_SESSION['cart'][$index]['product_total_price'] = $_SESSION['cart'][$index]['product_total_price']*2;
                echo"
                        <script>
                            alert(`Item's Quantity Updated ➕ ..`);
                            window.location.href = './Customer.php';
                        </script>
                    ";
            }else{
                $no_items_present = count($_SESSION['cart']);
                $_SESSION['cart'][$no_items_present] = array("product_id"=>$_POST['product_id'],"product_quantity"=>1,"product_name"=>$_POST['product_name'], "product_price"=>$_POST['product_price'],"product_total_price"=>$_POST['product_total_price']);

                echo"
                <script>
                    alert('Item added successfully 👍 ..');
                    window.location.href = './Customer.php';
                </script> 
                ";
            }

        }else{
            // Create session variable
            $_SESSION['cart'][0] = array("product_id"=>$_POST['product_id'],"product_quantity"=>1,"product_name"=>"$_POST[product_name]", "product_price"=>$_POST['product_price'],"product_total_price"=>$_POST['product_total_price']);
            echo"
                    <script>
                        alert('Item added successfully 👍 ..');
                        window.location.href = './Customer.php';
                    </script>
                ";
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        echo $_GET['product_id'];
        echo $_GET['product_quantity'];
        // Extract the array values based on specified key
        $available_product_id = array_column($_SESSION['cart'],'product_id');
        
        // is id already present in the cart ?
        if(in_array($_GET['product_id'],$available_product_id)){
        
            foreach ($_SESSION['cart'] as $key => $value) {
                // Get the position of product to increase it's quantity
                if(in_array($_GET['product_id'],$value)){
                    $index = $key;
                }
            }
            // echo $index;
            // Increment Product's Quantity By 1 (One)
            $_SESSION['cart'][$index]['product_quantity'] = $_GET['product_quantity'];
            // Increment/Decrease Product's Price As Well
            $_SESSION['cart'][$index]['product_total_price'] = $_SESSION['cart'][$index]['product_price']*$_GET['product_quantity'];
            echo"
                    <script>
                        alert(`Item's Quantity Updated ➕ ..`);
                        window.location.href = './My_Cart.php';
                    </script>
                ";
    }
}
    
?>