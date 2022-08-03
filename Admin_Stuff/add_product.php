<?php
session_start();
if (!isset($_SESSION['AdminLoginId'])) {
    header("location:../signIn.php");
    exit();
}
require('../Validation.php');
require('../Product_Operations/add_product_data.php');

$validation = new Validation();

$noError = true;
// Define variables and set to empty values
$productName = $productDetails = $productPrice = $productImage = $uploaded = "";

// Error holder variables
$productNameErr = $productDetailsErr = $productPriceErr = $productImageErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Empty Fields checkers
    // Product Name
    if ($validation->checkEmpty($_POST['productName'])) {
        $productNameErr = "<p> Error!!!! Product name is required!!</p>";
        $noError = false;
    } else {
        $productName = $_POST['productName'];
        if ($validation->checkSpecialChars($productName)) {
            $productNameErr = "Only letters and white space are allowed";
            $noError = false;
        }
    }

    // Product Details
    if($validation->checkEmpty($_POST['productDetails'])){
        $productDetailsErr = "<p> Error!!!! Product details is required!!</p>";
        $noError = false;
    }else{
        $productDetails = $validation->sanitize_input($_POST['productDetails']);
    }

    // Product Price
    if($validation->checkEmpty($_POST['productPrice'])){
        $productPriceErr = "<p> Error!!!! Product price is must !!</p>";
        $noError = false;
    }else{
        // Check if provided value is a number..
        if(is_numeric($validation->sanitize_input($_POST['productPrice']))){
            $productPrice = $_POST['productPrice'];
        }else{
            $productPriceErr = "<p> Error!!!! Product price must be integer !!</p>";
            $noError = false;
        }
    }

    // Product Image
        $img_temp_loc = $_FILES['productImg']['tmp_name'];
        // Seperate image name & it's extension
        $img_detail = explode(".",$_FILES['productImg']['name']);
        $img_name = $img_detail[0];
        $img_extension = $img_detail[1];

        $img_final_location = "../Product Images/".$productName.".".$img_extension;
        // Move image from temporary location to permenant location
        $uploaded =  move_uploaded_file($img_temp_loc,$img_final_location);
    }

    // If everything is perfect, add data to the database.
    if ($noError && $uploaded) {     

        $result = addProduct($productName,$productDetails,$productPrice,$img_final_location);
        if ($result) {
            echo "<script>
                alert('Product added successfully')
            </script>";

            // Empty the values so that form input fields will be empty.
            $productName = $productDetails = $productPrice = $productImage = $uploaded = "";

            // Redirection
            header("location:product_details.php");
            exit();

        } else {
            echo "<script>alert('Product was not added successfully')</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up &tilde; Very Nice Shopping</title>
    <!-- External Stylesheet -->
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- External Javascript -->
    <script src="../JS/script.js" defer></script>
    <!-- Internal/Embedded CSS -->
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
            margin-top: 2px;
        }
    </style>
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
        <main>
            <!-- Add Product Form -->
            <form class="generalForm" id="add_product_form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

                <h2>Add Product</h2>
                <input type="text" id="productName" name="productName" placeholder="Name"
                    value="<?php echo $productName ?>">
                <span class="error">
                    <?php echo $productNameErr;?>
                </span>

                <textarea rows="5" style="resize:none;padding:4px;" name="productDetails"
                    placeholder="Details"><?php echo $productDetails; ?></textarea>
                <span class="error">
                    <?php echo $productDetailsErr;?>
                </span>

                <!-- Allowing only 2 decimal in input type -->
                <input type="number" min="0" step="0.01" placeholder="Price $" name="productPrice"
                    value="<?php echo $productPrice?>" />
                <span class="error">
                    <?php echo $productPriceErr;?>
                </span>

                <lable for="productImg"><b> Image: </b></lable>
                <input type="file" id="productImg" name="productImg" value="Choose Image" accept="image/*" required />
                <span class="error">
                    <?php echo $productImageErr;?>
                </span>

                <!-- Submit Button -->
                <input type="submit" value="Add Product" id="signUpBtn" />
                <p style="text-align:center"><b>or</b></p>
                <a id="displayProducts" onclick="window.location.href='./product_details.php'">
                    Display Products
                </a>
            </form>
        </main>

        <!-- Footer -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>

</html>