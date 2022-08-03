<?php
    session_start();
    $_SESSION['AdminLoginId'] = null;
    session_destroy();
    header("location:index.html");
?>