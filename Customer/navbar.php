<?php
    $count = 0;
    if($_SESSION['cart']){
        $count = count($_SESSION['cart']);
    }
     echo"
     <header>
         <h2>Very Nice Shopping 🛒</h2>
     </header>

     <nav>
         <ul>
             <li><a href='Customer.php'>🏡 Home</a></li>
             <li><a href='my_cart.php'>My Cart ($count)</a></li>                
             <li><a id='logoutLink' href='../logout.php'>Logout</a></li>
         </ul>
     </nav>";
?>