<?php
    require('Validation.php');
    require('./User_Operations/get_user.php');

    $validation = new Validation();

    $noError = true;

    // Define variables and set to empty values
    $userEmail = $userPassword = $userType = "";

    // Error holder variables
    $userEmailErr = $userPasswordErr = $userTypeErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Empty Fields checkers
        // User Email
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

        // Fetch user from the database
        if($noError){
            getUser($userEmail,$userPassword,$userType);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In &tilde; Very Nice Shopping</title>
    <!-- External Stylesheet Goes Here -->
    <link rel="stylesheet" href="./CSS/style.css">
    <!-- External Javascript Goes Here -->
    <script src="./JS/script.js" defer></script>
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
            <form class="generalForm" id="signInForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <h2>Sign In</h2>
                <input type="email" id="userEmail" name="userEmail" placeholder="Email" value="<?php echo $userEmail?>" required>
                <span class="error"><?php echo $userEmailErr;?></span>

                <input type="password" id="userPassword" name="userPassword" placeholder="Password" value="<?php echo $userPassword?>" required>
                <span class="error"><?php echo $userPasswordErr?></span>

                <label for="userType">Select User Type:</label>
                <span class="error"><?php echo $userTypeErr;?></span>
                <select name="userType" id="userType" required>
                    <option value="" selected disabled hidden>Select</option>
                    <option value="Customer">Customer</option>
                    <option value="Admin">Admin</option>
                </select>

                <input type="submit" value="Sign In" id="signInBtn">
            </form>
        </main>

        <!-- Footer -->
        <!-- Footer goes here -->
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
</body>

</html>