<?php
session_start();
include'db.php';
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Rewards</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 50%;
            max-width: 600px;
            text-align: center;
        }

        h1 {
            color: #003366;
            font-size: 2.5em;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        h2 {
            color: #ff6600;
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.2em;
            color: #666;
        }

        .button-container {
            margin-top: 30px;
        }

        .btn {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #00509e;
        }

        .reward-points {
            background-color: #ffcc00;
            color: #333;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php 
    $wallet=0;
    $query = "SELECT p_id FROM user WHERE email='$email';";
    $result = mysqli_query($con, $query);
 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $p_id = $row["p_id"];
        }
        $query1 = "SELECT wallet FROM rewards WHERE p_id='$p_id';";
        $result1 = mysqli_query($con, $query1);

        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                $wallet = $row["wallet"];
            }            
        }
    }

    if (isset($_SESSION['f_name']) && isset($_SESSION['l_name'])) {
        $f_name = $_SESSION['f_name'];
        echo '<div class="container">';
        echo '<h1>Welcome ' . ucfirst(strtolower(htmlspecialchars($_SESSION['f_name']))) . ' ' . ucfirst(strtolower(htmlspecialchars($_SESSION['l_name']))) . '</h1>';
        echo '<h2>Your Wallet Amount</h2>';
        echo '<div class="reward-points">₹' . $wallet . ' Rupees</div>';
        echo '<div class="button-container">';
        echo '<button class="btn" onClick="next()">Home</button>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <script>
        function next()
        {
            window.location.href="passenger.php";
        }
    </script>

</body>
</html>
