<?php
    session_start();
    // Remove all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    
    header("location:index.html");
?>