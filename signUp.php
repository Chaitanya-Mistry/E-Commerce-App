<?php

require('Validation.php');
// require('Database.php');
require('./User_Operations/add_user.php');

$validation = new Validation();

$noError = true;
// Define variables and set to empty values
$userName = $userEmail = $userPassword = $userType = $userPhoneNo = $userStreet = $userPostal = $userCountry = $userState = $userCity = "";

// Error holder variables
$userNameErr = $userEmailErr = $userPasswordErr = $userTypeErr = $userPhoneNoErr = $userStreetErr = $userPostalErr = $userCountryErr = $userStateErr = $userCityErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Empty Fields checkers
    // User Name
    if ($validation->checkEmpty($_POST['userName'])) {
        $userNameErr = "<p> Error!!!! User name is required!!</p>";     
        $noError = false;
    } else {
        $userName = $_POST['userName'];
        if ($validation->checkSpecialChars($userName)) {
            $userNameErr = "Only letters and white space allowed";    
            $noError = false;        
        }
    }

    // Email
    if($validation->checkEmpty(($_POST['userEmail']))){
        $userEmailErr = "<p> Error!!!! Email is required!!</p>";
        $noError = false;
    }else{
        $userEmail = $validation->sanitize_input($_POST['userEmail']);
        if(!$validation->validateEmail($userEmail)){
            $userEmailErr = "Invalid email format";
            $noError = false;
        }
    }

    // Password
    if($validation->checkEmpty($_POST['userPassword'])){
        $userPasswordErr = "<p> Error!!!! Password is must !!</p> ";
        $noError = false;
    }else{
        $userPassword = $validation->sanitize_input($_POST['userPassword']);
    }

    // User Type
    if(empty($_POST['userType'])){
        $userTypeErr = "<p> Error!!!! User Type is required !!</p> ";
        $noError = false;
    }else{
        $userType = $validation->sanitize_input($_POST['userType']);
    }

    // Phone number
    if($validation->isPhoneValid($_POST['userPhoneNo'])){
        $userPhoneNo = $_POST['userPhoneNo'];
    }else{
        $userPhoneNoErr = "<p> Error!!!! Phone number must be in 123-456-7890 format !!</p> ";
        $noError = false;
    }

    // Address 

    // Street
    if($validation->checkEmpty($_POST['userStreet'])){
        $userStreetErr = "<p> Error!!! Street Address is required !!</p>";
        $noError = false;
    }else{
        $userStreet =  $validation->sanitize_input($_POST['userStreet']);
    }
    // Postal
    if($validation->checkEmpty($_POST['userPostal'])){
        $userPostalErr = "<p> Error!!! Postal Code is required !!</p>";
        $noError = false;
    }else{
        $userPostal = $validation->sanitize_input($_POST['userPostal']);
        $userPostalErr = $validation->postalValid($userPostal);
    }
    // Country
    if(empty($_POST['userCountry'])){
        $userCountryErr = "<p> Error!!!! Contry is required !!</p> ";
        $noError = false;
    }else{
        $userCountry = $validation->sanitize_input($_POST['userCountry']);
    }
    // State
    if(empty($_POST['userState'])){
        $userStateErr = "<p> Error!!!! State is required !!</p>";
        $noError = false;
    }else{
        $userState = $validation->sanitize_input($_POST['userState']);
    }
    // City
    if(empty($_POST['userCity'])){
        $userCityErr = "<p> Error!!!! City is required !!</p>";
        $noError = false;
    }else{
        $userCity = $validation->sanitize_input($_POST['userCity']);
    }

    // If everything is perfect, add data to the database & redirect a user to the "Login" Page.
    if($noError){
        $result = addUser($userName,$userEmail,$userPassword,$userType,$userPhoneNo,$userStreet,$userPostal,$userCountry,$userState,$userCity);

        if($result){
            // Redirection
            header("Location:signIn.php");
            exit();
        }
        else{
            echo "<script>
                alert('There is some problem in the database 🤔')
            </script>";
        }
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
    <!-- External Stylesheet Goes Here -->
    <link rel="stylesheet" href="./CSS/style.css">
    <!-- External Javascript Goes Here -->
    <script src="./JS/script.js" defer></script>
    <style>
        .error{
            color:red;
            margin-bottom: 10px;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <!-- Header goes here -->
        <header>
            <h2>Very Nice Shopping 🛒</h2>
        </header>

        <!-- Navigation goes here -->
        <nav>
            <ul>
                <li><a href="index.html"> 🏡Home</a></li>
                <li><a href="signIn.php"> Sign In</a></li>
                <li><a href="signUp.php"> Sign Up</a></li>
            </ul>
        </nav>
        <!-- Main -->
        <main>
            <!-- Sign Up Form -->
            <form class="generalForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <h2>Sign up</h2>
                <input type="text" id="userName" name="userName" placeholder="Name" value="<?php echo $userName?>" required><span class="error"><?php echo $userNameErr;?></span>

                <input type="email" id="userEmail" name="userEmail" placeholder="Email" value="<?php echo $userEmail?>" required><span class="error"><?php echo $userEmailErr;?></span>

                <input type="password" id="userPassword" name="userPassword" placeholder="Set Password" value="<?php echo $userPassword?>" required><span class="error"><?php echo $userPasswordErr?></span>
                <!-- <input type="password" id="userPasswordAgain" placeholder="Confirm Password"> -->

                <label for="userType">Select User Type:</label><span class="error"><?php echo $userTypeErr;?></span>
                <select name="userType" id="userType" required>
                    <option value = "" selected disabled hidden>Select</option>
                    <option value="Customer">Customer</option>
                    <option value="Admin">Admin</option>
                </select>

                <label for="userPhoneNo">Phone No:</label><span class="error"><?php echo $userPhoneNoErr;?></span>
                <input type="tel" id="userPhoneNo" name="userPhoneNo" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $userPhoneNo ?>" required>

                <span>Address:</span>
                <input type="text" name="userStreet" id="userStreet" placeholder="Street" value="<?php echo $userStreet ?>" required><span class="error"><?php echo $userStreetErr;?></span>
                <input type="number" name="userPostal" id="userPostal" placeholder="Postal code" min="100000" value="<?php echo $userPostal?>" required>

                <!-- Country -->
                <label for="userCountry">Country:</label><span class="error"><?php echo $userCountryErr;?></span>
                <select name="userCountry" id="userCountry" required>
                    <option value="" selected disabled hidden>Select</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="India">India</option>
                </select>
                <!-- State -->
                <label for="userState">State:</label><span class="error"><?php echo $userStateErr;?></span>
                <select name="userState" id="userState" required>
                    <option value="" selected disabled hidden>Select</option>
                    <option value="Ontario">Ontario</option>
                    <option value="Alberta">Alberta</option>
                    <option value="Gujarat">Gujarat</option>
                </select>
                <!-- City -->
                <label for="userCity">City:</label><span class="error"><?php echo $userCityErr;?></span>
                <select name="userCity" id="userCity" required>
                    <option value="" selected disabled hidden>Select</option>
                    <option value="Waterloo">Waterloo</option>
                    <option value="New York">New York</option>
                    <option value="Ahemedabad">Ahemedabad</option>
                </select>

                <input type="submit" value="Sign Up" id="signUpBtn">
            </form>
        </main>

        <!-- Footer -->
        <!-- Footer goes here -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>

</html>