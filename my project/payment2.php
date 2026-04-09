<?php
session_start();
include 'db.php';
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;
$booking_id = isset($_SESSION['booking_id']) ? $_SESSION['booking_id'] : null; 
$p_id = isset($_SESSION['p_id']) ? $_SESSION['p_id'] : null; 
$journey_date = isset($_SESSION['journey_date']) ? $_SESSION['journey_date'] : null;
$_SESSION['total_price'] = $total_price;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Hide scrollbar */
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: navy;
        }
        input[type="text"],
        input[type="date"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
            color: black;
        }
        button {
            background-color: orange;
            color: black;
            border: none;
            padding: 10px 15px;
            margin: 10px 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: darkorange;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Details</h1>
        <form action="" method="post">  
            <input type="text" id="card_no1" placeholder="Enter 16 digits card no" name="card1" required value="<?php echo isset($_POST['card1']) ? $_POST['card1'] : ''; ?>"> 

            <input type="date" id="expiry_date" placeholder="Date" name="date" required value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>"> 

            <input type="text" id="card_no2" placeholder="Enter 4 digits card pin" name="card2" required value="<?php echo isset($_POST['card2']) ? $_POST['card2'] : ''; ?>"> 

            <button name="cancel" type="submit">Cancel</button>
            
            <button name="pay" type="submit">Pay Rs <?php echo number_format($total_price, 2); ?></button>
            
        </form>
        <?php   
        if (isset($_POST['pay']))
        {
            $card1 = $_POST['card1'];
            $date = $_POST['date'];
            $card2 = $_POST['card2'];
            if (strlen($card1) === 16 && is_numeric($card1)) 
            {
                $ins = "INSERT INTO payment(p_id, pnr, card_no, expiry_date, card_pin, payment_status, Ticket_price, journey_date) VALUES ('$p_id', '$booking_id', '$card1', '$date', '$card2', 'paid', '$total_price', '$journey_date');";
                $result = mysqli_query($con, $ins);
                if ($result) 
                {
                    $_SESSION['booking_id'] = $booking_id; 
                    $query21="select reward_points,wallet from rewards where p_id='$p_id';";
                    $res=mysqli_query($con,$query21);
                    if ($res->num_rows > 0) 
                    {
                        while ($row = $res->fetch_assoc())
                        {
                            $reward_points = $row["reward_points"];
                            $wallet = $row["wallet"];
                        }
                        if($total_price > 100)
                        {
                            $cal_reward=$total_price/100;
                            $n_reward_points=$reward_points+$cal_reward;
                            $_SESSION['cal_reward'] = $cal_reward;                            
                            $query22="update rewards set reward_points='$n_reward_points' where p_id='$p_id';";
                            $res=mysqli_query($con,$query22);

                            if($n_reward_points > 500)
                            {
                                $n_wallet=$wallet+100;
                                $query23="update rewards set wallet='$n_wallet' where p_id='$p_id';";
                                $res1=mysqli_query($con,$query21);
                            }
                            elseif($n_reward_points > 1000)
                            {
                                $n_wallet=$wallet+200;
                                $query23="update rewards set wallet='$n_wallet' where p_id='$p_id';";
                                $res1=mysqli_query($con,$query21);
                            }
                            elseif($n_reward_points > 1500)
                            {
                                $n_wallet=$wallet+300;
                                $query23="update rewards set wallet='$n_wallet' where p_id='$p_id';";
                                $res1=mysqli_query($con,$query21);
                            }
                            elseif($n_reward_points > 2000)
                            {
                                $n_wallet=$wallet+400;
                                $query23="update rewards set wallet='$n_wallet' where p_id='$p_id';";
                                $res1=mysqli_query($con,$query21);
                            }
                            else{
                                $n_wallet=$wallet+500;
                                $query23="update rewards set wallet='$n_wallet' where p_id='$p_id';";
                                $res1=mysqli_query($con,$query21);
                            }
                        }
                    }                
                    //echo '<script> 
                    // window.location="./payment3.php";</script>';
                    echo '<audio id="successAudio" src="payment-success.mp3"></audio>';
                echo '<script>
                    let audio = document.getElementById("successAudio");
                    audio.play();
                    audio.onended = function() {
                        window.location="./payment3.php";
                    };
                </script>';
                }
            } 
            else 
            {
                echo '<script>alert("Enter a valid 16-Digit Number"); </script>';
            }
        }   
        if (isset($_POST['cancel'])) {
            $query2 = "UPDATE booking SET payment_status='unpaid', booking_status='pending' WHERE booking_id='$booking_id';";
            $result1 = mysqli_query($con, $query2);
            if ($result1) 
            {
                header('Location: passenger.php');
            }
        }
        ?>
        <script>
            let today = new Date().toISOString().split('T')[0];
            document.getElementById('expiry_date').setAttribute('min', today);
        </script>
    </div>
</body>
</html>
