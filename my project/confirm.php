<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
    width: 70%;
    margin: 20px auto;
    border: 1px solid #ddd;
    padding: 20px;
    background-color: white;
    position: relative;
    border-radius:15px;
    margin-top:100px;
}
        h2 {
            color: navy;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: transparent; /* No background color for the heading */
            color: navy;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        form {
            margin-top: 20px;
        }
        button {
            background-color: blue;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkblue;
        }
        .total-price {
            font-weight: bold;
            color: #d9534f;
        }
        .journey-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .journey-info div {
            flex: 1;
            padding: 10px;
        }
        .uppercase
        {
            text-transform:uppercase;
        }
    </style>
</head>
<body>
<?php

$trainDetails = $_SESSION['train_details'] ?? [];
if (!empty($trainDetails)) {
    $train_no = htmlspecialchars($trainDetails['train_no']);
    $train_name = htmlspecialchars($trainDetails['train_name']);
    $start_station = htmlspecialchars($trainDetails['start_station']);
    $end_station = htmlspecialchars($trainDetails['end_station']);
    $start_time = htmlspecialchars($trainDetails['start_time']);
    $end_time = htmlspecialchars($trainDetails['end_time']);
    $journey_date = htmlspecialchars($trainDetails['journey_date']);
    $class = htmlspecialchars($trainDetails['class']);
}

?>
<div class="container">
<h2><?php echo$train_name . "(" .$train_no. ")";?></h2>

<div class="journey-info">
    <div class="source">
        <h2 class="uppercase" style="text-align: left;"><?php echo $start_station;?></h2>
        <p class="uppercase" style="text-align: left;"><?php echo $start_time;?></p>
    </div>

    <div class="class">
        <h3>Journey Date: <?php echo$journey_date;?></h3>
    </div>

    <div class="destination">
        <h2 class="uppercase" style="text-align: right;"><?php echo $end_station;?></h2>
        <p class="uppercase" style="text-align: right;"><?php echo $end_time;?></p>
    </div>
</div>

<?php
$total_price = 0;

$passengerDetails = $_SESSION['passenger_details'] ?? [];
if (!empty($passengerDetails)) {
    echo '<h2>Booking Details</h2>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Age</th><th>Gender</th><th>Ticket Price</th></tr>';

    foreach ($passengerDetails as $passenger) {
        $name = htmlspecialchars($passenger['name']);
        $age = htmlspecialchars($passenger['age']);
        $gender = htmlspecialchars($passenger['gender']);
        $ticketPrice = htmlspecialchars($passenger['ticket_price']);
        $booking_id = htmlspecialchars($passenger['booking_id']);
        $journey_date = htmlspecialchars($passenger['journey_date']);
        $_SESSION['booking_id'] = $booking_id;
        $_SESSION['journey_date'] = $journey_date;
        $_SESSION['start_station'] = $start_station;
        $_SESSION['end_station'] = $end_station;
        $_SESSION['start_time'] = $start_time;
        $_SESSION['end_time'] = $end_time;
        $_SESSION['train_name'] = $train_name;
        $_SESSION['class'] = $class;

        $total_price += $ticketPrice;
        echo "<tr><td>$name</td><td>$age</td><td>$gender</td><td>$ticketPrice</td></tr>";
    }
    echo '</table>';

    $_SESSION['total_price'] = $total_price;
    echo '<h2 class="total-price">Total Price: ₹' . $total_price . '</h2>';
    echo '<form method="post">';
    echo '<button type="submit" name="back">Back</button>';
    echo '<button type="submit" name="confirm">Confirm</button>';
    echo '</form>';
} 
else 
{
    echo '<p>No passenger details found.</p>';
}

if (isset($_POST['back']) && isset($_SESSION['booking_id'])) {
    $q1 = "UPDATE booking SET booking_status='pending', payment_status='unpaid' WHERE booking_id='$booking_id';";
    $result1 = mysqli_query($con, $q1);
    if ($result1) 
    {
        header('Location: reservation1.php');
        exit();
    } 
    else {
        echo 'Error updating booking status: ' . mysqli_error($con);
    }
}

if (isset($_POST['confirm'])) 
{
    header('Location: payment.php');
    exit();
}
?>
</div>
</body>
</html>
