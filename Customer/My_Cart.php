<?php
    session_start();

    // IF customer is not logged in ..
    if (!isset($_SESSION['CustomerLoginId'])) {
        header("location:../signIn.php");
        exit();
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

            <!-- Order placed successfully prompt -->
            <div id="orderSuccess">
                
                <h3>Your Order Placed Successfully 🚚</h3>
                <button id='invoice_download_btn'>Download Invoice</button>

            </div>

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
                            if(isset($_SESSION['cart'])){
                                $sr_no = 0;
                                foreach ($_SESSION['cart'] as $key => $value) {
                                    $sr_no++;
                                    $str_to_print = "";
                                    $str_to_print .= "
                                        <tr>
                                            <td>$sr_no</td>
                                            <td> {$value['product_name']}</td>
                                            <td> {$value['product_price']}</td>
                                            <td><input type='number' value='1' data-product_price='{$value['product_price']}' data-position = '{$sr_no}' min='1' max='50' class='product_quantity'/></td>
                                            <td class='total_individual_price'>{$value['product_price']}</td>
                                            <td>
                                            <!-- To remove the particular product from the cart -->
                                                <form action='./manage_cart.php' method='post'>
                                                    <input type='submit' class='remove_item_btn' name='remove_item_btn' value='Remove'/>
                                                    <input type='hidden' name='product_to_be_removed' value='$value[product_name]'>
                                                </form>
                                            </td>
                                        </tr>
                                    ";
            
                                    echo $str_to_print;
                                }                
                            }                           
                    ?>  
                    
                </tbody>
            </table>
            
            <!-- Display total amount & place order if there is something in the card -->
            <?php 
                if(isset($_SESSION['cart']) && count($_SESSION['cart']) != 0){
                    $total_amount = 0;
                    foreach ($_SESSION['cart'] as $key => $value) {
                        // Counting & storing product's price
                        $total_amount+= $value['product_price'];
                    }
                    echo "<div id='confirm_order_container'>
                    <div class='total_price_container'>
                        <h3>Total Price:- <span id='total_price'>$$total_amount</span></h3>
                    </div>
                
                    <!-- Place Order -->
                    <button id='place_order_btn'>Place Order</button>
                </div>";
                }else{
                    echo "<h3 style='color:red;'>Cart is Emty please add something..</h3>";
                }
            ?>
                       
        </main>

        <!-- Footer -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>

        <!-- Embeded JS to implement Quantity functionality of product & display order completion -->
        <script>

            const product_quantity = document.getElementsByClassName('product_quantity');
            const total_individual_price = document.getElementsByClassName('total_individual_price');
            const total_price_displayer = document.getElementById('total_price');
            // Order Placed Prompt
            const orderSuccessPrompt = document.getElementById('orderSuccess');
            const invoice_download_btn = document.getElementById('invoice_download_btn');

            // Place Order Button
            const place_order_btn = document.getElementById('place_order_btn');
            place_order_btn.addEventListener('click',orderPlaced);

            // Invoice download Button
            invoice_download_btn.addEventListener('click',()=>{
                setTimeout(() => {
                    orderSuccessPrompt.style.visibility = 'hidden';
                }, 200);
            });
            
            // Quantity
            Array.from(product_quantity).forEach(element => {
               element.addEventListener('input',updatePrice);
            });

            // To display order placed message
            function orderPlaced(){
                orderSuccessPrompt.style.visibility = 'visible';
            }

            // To update product's price
            function updatePrice(){
                // console.log(this.value,this.dataset.product_price);         
                // console.warn((this.value * this.dataset.product_price).toFixed(2));
                // console.warn(this.dataset.position);
                // console.warn(total_individual_price[this.dataset.position-1])
                total_individual_price[this.dataset.position-1].innerText = (this.value * this.dataset.product_price).toFixed(2);
                
                updateTotalPrice();
            }

            // To update Grand Total 
            function updateTotalPrice(){
                let total_price = 0;
                Array.from(total_individual_price).forEach(element=>{
                    total_price+= parseFloat(element.innerText);
                });
                total_price_displayer.innerText = '$'+total_price.toFixed(2);
            }

        </script>
</body>
</html>