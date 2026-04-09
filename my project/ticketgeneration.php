<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            background-color: #fff;
            border: 2px solid #000080;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 350px; /* Decreased width */
            height: 150px; /* Increased height */
            text-align: center;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #000080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 80%;
            margin-top: 15px; /* Space above the button */
        }

        button:hover {
            background-color: #003366;
        }

        .error-message {
            color: red;
            margin-top: 15px;
            font-size: 16px;
        }

        #home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 18px;
            text-decoration: none;           
            background-color: #000080; 
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<a id="home" href="passenger.php">Back</a>
<h1>View Ticket</h1>
    <div class="container">
        <form action="" method="post">
            <input type="text" placeholder="Enter Your PNR Number" name="pnr" required>
            <button type="submit" name="ticket">View Ticket</button>
        </form>

        <?php
        $current_date = date("Y-m-d");
        if (isset($_POST['ticket'])) {
         
            $pnr = $_POST['pnr'];
            $_SESSION['pnr'] = $pnr;
            $query1 = "SELECT journey_date FROM pnr WHERE pnr='$pnr';";
            $result = mysqli_query($con, $query1);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $journey_date = $row["journey_date"];
                }
                if ($current_date > $journey_date) {
                    echo '<div class="error-message">Journey Date has already passed</div>';
                } else {
                    echo '<script>window.location.href="viewticket.php";</script>';
                }
            } else {
                echo '<div class="error-message">Invalid PNR number.</div>';
            }
        }
        ?>
    </div>

    
</body>
</html>
