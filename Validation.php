<?php
    class Validation
    {
        // Empty Scenario function checker
        public function checkEmpty($data)
        {
            if (empty($data)) {
                return true;
            } else {
                return false;
            }
        }

        // Restricting the use of special characters
        function checkSpecialChars($data){
            if (!preg_match("/^[a-zA-Z-' ]*$/",$data)) {
                return true;
            }
            else{
                return false;
            }
        }

        // Email validation
        function validateEmail($data){
            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
               return true;
            } else {
                return false;
            }
        }

        // Phone number validation
        function isPhoneValid($data){
            if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $data)) {
                return true;
            }
            else{
                return false;
            }
        }

        // Postal code validation
        function postalValid($data){
            // Length of the password
            if (strlen($data < '6')) {
                return "Postal code Must Contain At Least 6 digits!";
            }
        }

        // Strip unnecessary characters (extra space, tab, newline) from the user input data (with trim())
        // Remove backslashes (\) from the user input data (with stripslashes())
        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }
?>
