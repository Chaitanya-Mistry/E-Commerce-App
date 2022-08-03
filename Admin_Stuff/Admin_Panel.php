<?php
session_start();

// IF admin is not logged in ..
if (!isset($_SESSION['AdminLoginId'])) {
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
    <title>Admin Panel</title>
    <!-- External Stylesheet -->
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- External Javascript -->
    <script src="../JS/script.js" defer></script>
</head>

<body>
    <div id="wrapper">

        <!-- Header -->
        <header>
            <h2>Very Nice Shopping 🛒</h2>
        </header>

        <!-- Navigation -->
        <nav>
            <ul>
            <li><a href="Admin_Panel.php">🏡 Home</a></li>
                <li><a href="add_product.php">Add Product</a></li>                
                <li><a href="add_seller.php">Add Seller</a></li>
                <li><a id="logoutLink" href="../logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main -->
        <main style="display:grid;place-content:center">
            <h2>Welcome to the admin panel ... </h2>
        </main>

        <!-- Footer -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>
</html>