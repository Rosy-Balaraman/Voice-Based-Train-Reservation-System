<?php
session_start();
include 'db.php';
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheerful Journey</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px auto;
            text-align: center;
            background-color: navy; 
            overflow: hidden;
            width: 90%;
            border-radius:20px;
        }

        ul li {
            display: inline-block;
            margin-right: 10px;
        }

        ul li a{
            text-decoration: none;
            padding: 10px 15px;
            color: white; 
            background-color: navy; 
            border: none;
            cursor: pointer;
        }

        ul li :hover{
            background-color: #1a1a1a; 
            padding:10px;
        }

        .dropdown {
            overflow: hidden;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: navy; 
            min-width: 180px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content li {
            color: white;
            padding: 5px 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        ul{
            padding: 10px;
        }
        .dropdown-content a {
            float: none;
            color: white; 
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background-color: navy; 
            padding-bottom: -10px;
        }

        .dropdown-content a:hover {
            background-color: #1a1a1a;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        #paragraph {
            width: 70%;
            margin: 30px auto;
            background-color: #e6e6f7; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top:150px;
        }

        #paragraph p {
            font-size: 18px;
            line-height: 1.6;
            text-align: justify;
            color: #333;
        }

        h1 {
            color: navy;
        }

    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['f_name']) && isset($_SESSION['l_name'])) {
        $f_name = $_SESSION['f_name'];
        echo '<h1>Welcome to Cheerful Journey, ' . ucfirst(strtolower(htmlspecialchars($_SESSION['f_name']))) . ' ' . ucfirst(strtolower(htmlspecialchars($_SESSION['l_name']))) . '</h1>';
        echo '<br>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="reservation1.php">Book Ticket</a></li>
            <li><a href="viewtrain.php">View Train Info</a></li>
            <li class="dropdown">
                <a class="btn">History</a>
                <div class="dropdown-content">
                    <a href="bookinghistory.php">Booked Ticket History</a>
                    <a href="cancelledhistory.php">Ticket Cancellation History</a>
                    <a href="refundhistory.php">Refunded History</a>
                    <a href="pendinghistory.php">Failed Transaction History</a>
                </div>
            </li>
            <li><a href="seatcheck.php">Seat Availability</a></li>
            <li class="dropdown">
                <a class="btn">Profile</a>
                <div class="dropdown-content">
                    <a href="profile.php">View Profile</a>
                    <a href="updatepro.php">Update Profile</a>
                    <a href="changepass.php">Change Password</a>
                </div>
            </li>
            <li><a href="ticketgeneration.php">View Ticket</a></li>
            <li><a href="cancelticket.php">Cancel Ticket</a></li>
            <li><a href="reward.php">Reward Points</a></li>
            <li><a href="wallet.php">Wallet</a></li>
            <li><a href="Logout.php">Logout</a></li>
        </ul>
        <div id="paragraph"><p>Welcome to our Train Ticket Reservation System, designed to provide a seamless and user-friendly experience for all passengers. Whether you are booking a ticket, checking seat availability, or managing your reservations, our platform is here to assist you every step of the way. With a focus on accessibility, we ensure that visually impaired individuals can navigate our services with ease. Enjoy hassle-free travel planning, and let us help you reach your destination comfortably and efficiently!</p></div>';
    } 
    else 
    {
        echo '<h1>You are not logged in. Please <a href="login.php">login</a>.</h1>';
    }
    ?>
</body>
</html>
