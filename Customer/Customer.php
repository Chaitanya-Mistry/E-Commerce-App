<?php
    session_start();

    // IF customer is not logged in ..
    if (!isset($_SESSION['CustomerLoginId'])) {
        header("location:../signIn.php");
        exit();
    }else{
        require('../Product_Operations/get_product_data.php');
        $found_products = getProducts();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Panel</title>
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
            <h3>Welcome to the Customer panel </h3>

            <div class="products">
                <?php
                    if ($found_products->num_rows > 0) {
                        // output data of each row
                        while($row = mysqli_fetch_array($found_products, MYSQLI_ASSOC)) {
                            $str_to_prnt = "";
                            $str_to_prnt .= "
                            <div class='individualProduct'>
                                <form action='manage_cart.php' method='POST'>       
                                        <!-- Product Image -->
                                        <div class='productImageContainer'>
                                            <img src='{$row['image']}'>
                                        </div>
                                        <hr/>
                                        <!-- Products details -->
                                        <div class='productDescContainer'>
                                            <!-- Name -->
                                            <p><b>{$row['name']}</b></p>
                                            <p><span style='color:red'><b>Price:</b></span> &dollar;{$row['price']}</p>
                                            <p style='text-align:justify;font-size:19px'>{$row['description']}</p>       
                                            
                                            <input type='hidden' name='product_name' value='{$row['name']}'>
                                            <input type='hidden' name='product_price' value='{$row['price']}'>
                                            <button class='addCartBtn' name='add_to_cart_btn'>Add To Cart </button>
                                        </div>
                                </form>
                            </div>";
                            echo $str_to_prnt;
                        }
                    } else {
                        echo "<h2>No products available to sell .. </h2>";
                    }
                ?>                
            </div>
        </main>

        <!-- Footer -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>
</html>