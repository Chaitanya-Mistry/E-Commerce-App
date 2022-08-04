<?php
session_start();
if (!isset($_SESSION['AdminLoginId'])) {
    header("location:../signIn.php");
    exit();
}

$noError = true;
// Define variables and set to empty values
$firmName = $sellerEmail = $sellerStreet = $sellerPostal = $sellerCountry = $sellerState = $sellerCity = "";

$productId= $results = "";

// Error holder variables
$firmNameErr = $sellerEmailErr = $sellerStreetErr = $sellerPostalErr = $sellerCountryErr = $sellerStateErr = $sellerCityErr = "";

function loadProducts(){
    require_once('../Database.php');
    $database = new Database();
    $noData = false;
    $query = 'SELECT idproduct,name FROM product;'; 
    $stmt = mysqli_prepare($database->getDbc(), $query);
    $stmt->execute();
    return $stmt->get_result();
}

// For Post Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../Validation.php');
    require('../User_Operations/add_seller_data.php');
    require_once('../Database.php');
    $validation = new Validation();
    // Empty Fields checkers
    // Firm Name
    if ($validation->checkEmpty($_POST['firmName'])) {
        $firmNameErr = "<p> Error!!!! Firm name is required!!</p>";     
        $noError = false;
    } else {
        $firmName = $_POST['firmName'];
        loadProducts();
        if ($validation->checkSpecialChars($firmName)) {
            $firmNameErr = "Only letters and white space allowed";    
            $noError = false;        
        }
    }

    // Email
    if($validation->checkEmpty(($_POST['sellerEmail']))){
        $sellerEmailErr = "<p> Error!!!! Email is required!!</p>";
        $noError = false;
    }else{
        $sellerEmail = $validation->sanitize_input($_POST['sellerEmail']);
        loadProducts();
        if(!$validation->validateEmail($sellerEmail)){
            $sellerEmailErr = "Invalid email format";
            $noError = false;
        }
    }

    // Address 

    // Street
    if($validation->checkEmpty($_POST['sellerStreet'])){
        $sellerStreetErr = "<p> Error!!! Street Address is required !!</p>";
        $noError = false;
    }else{
        $sellerStreet =  $validation->sanitize_input($_POST['sellerStreet']);
        loadProducts();
    }
    // Postal
    if($validation->checkEmpty($_POST['sellerPostal'])){
        $sellerPostalErr = "<p> Error!!! Postal Code is required !!</p>";
        $noError = false;
    }else{
        $sellerPostal = $validation->sanitize_input($_POST['sellerPostal']);
        $sellerPostalErr = $validation->postalValid($sellerPostal);
        loadProducts();
    }
    // Country
    if(empty($_POST['sellerCountry'])){
        $sellerCountryErr = "<p> Error!!!! Contry is required !!</p> ";
        $noError = false;
    }else{
        $sellerCountry = $validation->sanitize_input($_POST['sellerCountry']);
        loadProducts();
    }
    // State
    if(empty($_POST['sellerState'])){
        $sellerStateErr = "<p> Error!!!! State is required !!</p>";
        $noError = false;
    }else{
        $sellerState = $validation->sanitize_input($_POST['sellerState']);
        loadProducts();
    }
    // City
    if(empty($_POST['sellerCity'])){
        $sellerCityErr = "<p> Error!!!! City is required !!</p>";
        $noError = false;
    }else{
        $sellerCity = $validation->sanitize_input($_POST['sellerCity']);
        loadProducts();
    }

    // If everything is perfect, add data to the database.
    if($noError){

        $sellerId = addSeller($firmName,$sellerEmail,$sellerStreet,$sellerPostal,$sellerCountry,$sellerState,$sellerCity);

        if($sellerId){
            $database = new Database();
            $sellerProducts = $_POST['sellerProducts'];
            $final_result = true;
            foreach ($sellerProducts as $productId) {
                $create_seller_product = "INSERT INTO sold_by_products(idproduct,idsold_by) VALUES(?,?)";
                $stmt = mysqli_prepare($database->getDbc(),$create_seller_product);
                // Bind the data in the query
                mysqli_stmt_bind_param(
                    $stmt,
                    'ii',
                    $productId,
                    $sellerId
                );
                // Execute the query
                $temp_result = mysqli_stmt_execute($stmt);
                if(!$temp_result){
                    $final_result = false;
                }
            }
            // End of foreach loop
            
            if($final_result){
                echo"<script>alert('Seller data added successfully ..')</script>";
                // Empty the values so that form input fields will be empty.
                $firmName = $sellerEmail = $sellerStreet = $sellerPostal = "";          
                $results = loadProducts();
            }
            else{
                echo "<script>
                    alert('There is some problem in the database');
                </script>";
                $results = loadProducts();
            }
        }
    }
}else{
    $results = loadProducts();
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
            <!-- Add Seller Form -->
            <form class="generalForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <h2>Add Seller</h2>
                <input type="text" id="firmName" name="firmName" placeholder="Firm name" value="<?php echo $firmName?>">
                <span class="error"><?php echo $firmNameErr;?></span>
             
                <input type="email" id="sellerEmail" name="sellerEmail" placeholder="Email" value="<?php echo $sellerEmail ?>">
                <span class="error"><?php echo $sellerEmailErr;?></span>

                <span>Address:</span>

                <input type="text" name="sellerStreet" id="sellerStreet" placeholder="Street" value="<?php echo $sellerStreet ?>">
                <span class="error"><?php echo $sellerStreetErr;?></span>            

                <input type="number" name="sellerPostal" id="sellerPostal" placeholder="Postal code" min="100000" value="<?php echo $sellerPostal ?>">
                <span class="error"><?php echo $sellerPostalErr;?></span>

                <!-- Country -->
                <label for="sellerCountry">Country:</label>
                <span class="error"><?php echo $sellerCountryErr;?></span>
                <select name="sellerCountry" id="sellerCountry" >
                    <option value="" selected disabled hidden>Select</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="India">India</option>
                </select>
                <!-- State -->
                <label for="sellerState">State:</label>
                <span class="error"><?php echo $sellerStateErr;?></span>
                <select name="sellerState" id="sellerState" >
                    <option value="" selected disabled hidden>Select</option>
                    <option value="Ontario">Ontario</option>
                    <option value="Alberta">Alberta</option>
                    <option value="Gujarat">Gujarat</option>
                </select>
                <!-- City -->
                <span class="error"><?php echo $sellerCityErr;?></span>
                <label for="sellerCity">City:</label>
                <select name="sellerCity" id="sellerCity" >
                    <option value="" selected disabled hidden>Select</option>
                    <option value="Waterloo">Waterloo</option>
                    <option value="New York">New York</option>
                    <option value="Ahemedabad">Ahemedabad</option>
                </select>
                <!-- Assign Product -->
                <!-- Error displayer-->
                <!-- <label for="sellerProduct">Assign Product:</label> -->
                <!-- <select name="sellerProduct" id="sellerProduct" required>
                    <option value="" selected disabled hidden>Select</option>
                </select> -->
                <p style="margin-top: 5px;"><b>Assign Product:</b></p>
                <div class="sellerProductsChoices">
                    <?php
                        if($results){
                            while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){                                
                                $str_to_print = "";
                                $str_to_print.= "<input type='checkbox' id='{$row['name']}' name='sellerProducts[]' value='{$row['idproduct']}'>
                                <label for='{$row['name']}'>{$row['name']}</label><br>";
    
                                echo $str_to_print;
                            }
                        }
                    ?>
                </div>
                <input type="submit" value="Add Seller" id="addSellerBtn">
            </form>
        </main>

        <!-- Footer -->    
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>

</html>