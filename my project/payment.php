<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            overflow: hidden; /* Hide scrollbar */
        }
        h1 {
            text-align: center;
            color: navy;
            margin-top: 20px;
        }
        .container {
            width: 100%; /* Adjust width to prevent overflow */
            max-width: 500px; /* Set a maximum width */
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #d9534f; /* Set a distinct color for the total amount */
        }
        button {
            background-color: orange; /* Button background color */
            color: black; /* Button text color */
            border: none;
            padding: 10px 15px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        button:hover {
            background-color: darkorange; /* Button hover color */
            color: white; /* Button text color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment</h1>
        <?php
        $total_price = $_SESSION['total_price'] ?? 0; 
        echo '<h2>Total amount to be Paid: Rs ' . number_format($total_price, 2) . '</h2>';
        ?>
        <form action="" method="post">
            <button name="cancel" type="submit">Cancel Payment</button> 
            <button name="payment" type="submit">Make Payment</button>
        </form>
        <?php
        if (isset($_POST['cancel']))
        {
            $booking_id = $_SESSION['booking_id'];
            $query2 = "UPDATE booking SET payment_status='unpaid', booking_status='pending' WHERE booking_id='$booking_id';";
            mysqli_query($con, $query2);
            mysqli_close($con); 
            header('Location: reservation1.php');
            exit(); 
        }
        if (isset($_POST['payment'])) 
        {
            header('Location: payment2.php');
            exit(); 
        }
        ?>
    </div>
</body>
</html>
