<?php
    session_start();

    // IF customer is not logged in ..
    if (!isset($_SESSION['CustomerLoginId'])) {
        header("location:../signIn.php");
        exit();
    }else{
        require('../Database.php');
        $database = new Database();
        // Find Customer by id
        require('../User_Operations/find_user_by_id.php');
        $customer_id = '';        
        $result = findUserByID($_SESSION['CustomerLoginId']);
        
        // Storing customer's id
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $customer_id = $row['iduser'];        
        } 
       
        // Today's date
        $today_date = date('Y-m-d');

        $insertedAllRecords = true;

        $sql = '';
        foreach ($_SESSION['cart'] as $key => $value) {
            // print_r($value);
            
            $p_id = (int)$value['product_id'];
            $c_id = (int)$customer_id;
            $p_total_price = doubleval($value['product_total_price']);
            $p_quantity = (int)$value['product_quantity'];
          
            $store_orders = "INSERT INTO orders(iduser,idproduct,date,quantity,total_price) VALUES(?,?,?,?,?)";
            $stmt = mysqli_prepare($database->getDbc(),$store_orders);
            // Bind the data in the query
            mysqli_stmt_bind_param(
                $stmt,
                'iisid',
                $c_id,
                $p_id,
                $today_date,
                $p_quantity,
                $p_total_price
            );
            // Execute the query
            $result = mysqli_stmt_execute($stmt);
            if(!$result){
                $insertedAllRecords = false;
            }       
        }
        // IF all the records are inserted successfully..
        if($insertedAllRecords){
            // Unset Cart Session Variable
            unset($_SESSION['cart']); 

            // PDF Generation Goes Here 👇
            ob_start();
            include_once('../fpdf184/fpdf.php');
            class PDF extends FPDF
            {
                // Page header
                function Header()
                {
                    // Logo
                        $this->Image('../logo/conestogalogo.png',92,10,25);
                        $this->Ln(10);
                        $this->Ln(10);
                    // 
                
                    // College Title Part
                        $this->SetFont('Arial','B',13);
                        // Move to the right
                        $this->Cell(55);
                        // Title
                        $this->Cell(80,10,'Very Nice Store',0,0,'C');
                        // Line break
                        $this->Ln(26.7);
                    // 
                }
                // Page footer
                function Footer()
                {
                    // Position at 1.5 cm from bottom
                    $this->SetY(-15);
                    // Arial italic 8
                    $this->SetFont('Arial','I',8);
                    // Page number
                    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                }        
            }
            // Fetching customer's Email
            $customerEmail = mysqli_query($database->getDbc(), "SELECT email from user WHERE iduser = $customer_id") or die("database error:". mysqli_error($conn));
            
            // Fetching customer's name
            $customerName = mysqli_query($database->getDbc(), "SELECT name from user WHERE iduser = $customer_id") or die("database error:". mysqli_error($conn));

            // Fetching customer's total_price
            $total_amount = mysqli_query($database->getDbc(),"SELECT sum(total_price) FROM orders WHERE orders.iduser = $customer_id;");

            // Fetching data from order table
            $customer_order_data = mysqli_query($database->getDbc(),"SELECT product.name ,quantity, total_price FROM user u INNER JOIN orders ON u.iduser = orders.iduser INNER JOIN product ON product.idproduct = orders.idproduct WHERE u.iduser = $customer_id;");

            // foreach($customer_order_data as $key){
            //     print_r($key);
            // }
            $pdf = new PDF();
            // //header
            $pdf->AddPage();
            // //foter page
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','B',11);

            // Rest of the content
                    // For customer's Email
                    foreach($customerEmail as $row){
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Ln();
                        foreach($row as $cEmail)
                        $pdf->Cell(-10,0,'',0,0);
                    }
                     // For customer's Name
                    foreach($customerName as $row){
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Ln();
                        foreach($row as $cName)
                        $pdf->Cell(-10,0,'',0,0);
                    }
                    // // For customer's total_amount
                    foreach ($total_amount as $row) {
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Ln();
                        foreach($row as $cTotal)
                        $pdf->Cell(-10,0,'',0,0);
                    }

                    // Move to the right
                    $pdf->Cell(23);
                    $pdf->Cell(43,5,'Customer Name :',0,0);
                    // Move to the left
                    $pdf->Cell(-10);
                    $pdf->Cell(33,5,$cName,0,0);
                    // Move to the right
                    $pdf->Cell(48);
                    $pdf->Cell(20,5,'Date :',0,0);
                    // Move to the left
                    $pdf->Cell(-7);
                    $pdf->Cell(25,5,$today_date,0,0);
                    $pdf->ln(6);
                    // Move to the right
                    $pdf->Cell(13);
                    $pdf->Cell(32,5,'Email :',0,0);
                    $pdf->Cell(10,5,$cEmail,0,0);
                    $pdf->ln(20);
                    // Move to the right
                    $pdf->Cell(13);
                    $pdf->Ln();

                    // Customers's Products details
                    $pdf->Ln();
                    $pdf->Cell(13);
                    $pdf->SetFont('Arial','B',10);
                    $pdf->Cell(55,12,'Product Name',1,0,'C');
                    $pdf->Cell(55,12,'Quantity',1,0,'C');
                    $pdf->Cell(55,12,'Price',1,0,'C');

                    foreach($customer_order_data as $row){
                        $pdf->Ln();
                        $pdf->Cell(13);
                        foreach($row as $column)
                        $pdf->Cell(55,12,$column,1,0);
                    }

                    // $pdf->Cell(150,40,'',10,6);
                    $pdf->Line(200,190,145,190);
                    $pdf->Ln(80);
                    // Move to the right
                    $pdf->Cell(150);
                    $pdf->Cell(23,5,'Total Price: $',0,0);
                    $pdf->Cell(20,5,$cTotal,0,0);

                    $pdf->Output();
                    ob_end_flush();
        }else{
            echo "<script>alert('Error occured while inserting many records ... ☠️')</script>";
        }
    }
?>