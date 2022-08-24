<?php
session_start();
    // IF admin is not logged in ..
    if (!isset($_SESSION['AdminLoginId'])) {
        header("location:../signIn.php");
        exit();
    }else{
        require('../Database.php');
        $database = new Database();
        $noData = false;
        $query = 'SELECT * FROM product;'; 
        $stmt = mysqli_prepare($database->getDbc(), $query);
    
        $results = @mysqli_query($database->getDbc(),$query);
        if(mysqli_num_rows($results) == 0){
            $noData = true;
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details &tilde; Very Nice Shopping</title>
    <!-- External Stylesheet -->
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- To use font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- External Javascript -->
    <script src="../JS/script.js" defer></script>
    <!-- Internal/Embeded Stylesheet -->
    <style>
            table {
                margin: 20px auto;
                padding-top: 2%;
                padding-bottom: 5%;
                border-collapse: collapse;
            }
            .font-icons{
                display: flex;
                justify-content: space-evenly;
                align-items: center;
            }
            .icons{
                padding: 2px;
                font-weight: bold;
                font-size: 25px;
                position: relative;
                top: 40px;
                min-height: 120px;
            }
            #edit{
                color: dodgerblue;
            }
            #delete{
                color: red;
            }
            td, th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            tr:nth-child(even){background-color: #f2f2f2;}
            tr:nth-child(odd){background-color: #fff;}

            tr:hover {background-color: #ddd;}

            th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #15172b;
                color: white;
            }
            #addProductBtn{
                display: block;
                margin: 0px auto;
                padding: 13px;
                background-color: black;
                color:white;
                border:none;
                cursor: pointer;
                margin-bottom: 13px;
            }
            #addProductBtn:hover{
                background-color: rgb(54, 54, 54);
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
                <li><a href="Admin_Panel.php">🏡 Go Home</a></li>
            </ul>
        </nav>
        <!-- Main -->
        <main>
            <?php 
                if($noData){
                    echo "<h3 style='color:red;text-align:center;margin:13px 0px;'>No data to display..</h3>";
                }
            ?>
            <table width="80%" id="product_details_table">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Operatations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            $sr_no = 0;
                            while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
                                $sr_no++;
                                $str_to_print = "";
                                $str_to_print .= "<tr><td>$sr_no</td>";
                                $str_to_print .= "<td> {$row['name']}</td>";
                                $str_to_print .= "<td> {$row['description']}</td>";
                                $str_to_print .= "<td> {$row['price']}</td>";
                                $str_to_print .= "<td><img src='{$row['image']}' class='productImg'/></td>";
                                $str_to_print .= "<td class='font-icons'> 
                                        <a href='edit_product.php?product_id={$row['idproduct']}'>
                                            <i class='fa fa-edit icons' id='edit'></i>
                                        </a>
                                        <i class='fa fa-remove icons deleteIcons' id='delete' data-productname='{$row['name']}' data-productid='{$row['idproduct']}'></i>                            
                                    </td> 
                                </tr>";
        
                                echo $str_to_print;
                            }
                    ?>
                </tbody>
            </table>
        <button id="addProductBtn" onclick="window.location.href='./add_product.php'">Add Products</button>
        </main>

        <!-- Footer -->    
        <footer>
            <section id="Copyright"> Copyright &copy; Very Nice Studio 2021. All Rights Reserved</section>
        </footer>
        
        <!-- Embeded JS -->
        <script>
            const deleteLinks = document.getElementsByClassName('deleteIcons');
            Array.from(deleteLinks).forEach(element => {
               element.addEventListener('click',deleteProduct);
            });

            // To delete the product
            function deleteProduct(){
                // Get confirmation from the user
                const confirmation = confirm(`${this.dataset.productname} will be deleted permenantly`);

                if(confirmation){
                    window.location.href = `../Product_Operations/delete_product.php?product_id=${this.dataset.productid}`;
                }                
            }
        </script>
</body>

</html>