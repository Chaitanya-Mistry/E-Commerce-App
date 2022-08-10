<?php
    session_start();

    // IF customer is not logged in ..
    if (!isset($_SESSION['CustomerLoginId'])) {
        header("location:../signIn.php");
        exit();
    }else{
       
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart 🛍️</title>
    <!-- External Stylesheet -->
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- External Javascript -->
    <script src="../JS/script.js" defer></script>
</head>

<body>
    <div id="wrapper">
        <!-- Header & Navbar -->
        <?php include('../Customer/navbar.php') ?>

        <!-- Main -->
        <main style="margin-top: 10px;text-align:center;">
    
            <table class="my_cart_table">
                <thead>
                    <tr>
                        <th scope="col">Sr.No</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sr_no = 0;
                        foreach ($_SESSION['cart'] as $key => $value) {
                            $sr_no++;
                            $str_to_print = "";
                            $str_to_print .= "
                                <tr>
                                    <td>$sr_no</td>
                                    <td> {$value['product_name']}</td>
                                    <td> {$value['product_price']}</td>
                                    <td>1</td>
                                    <td>100</td>
                                    <td><button class='remove_item_btn'>Remove</button></td>
                                </tr>
                            ";
    
                            echo $str_to_print;
                        }
                    ?>  
                    
                </tbody>
            </table>

            <div id="confirm_order_container">
                <div class="total_price_container">
                    <h3>Total Price:- <span id="total_price">$399.99</span></h3>
                </div>
            
                <!-- Place Order -->
                <button id="place_order_btn">Place Order</button>
            </div>
            
        </main>

        <!-- Footer -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>

</body>
</html>