<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
            height: 100vh; /* Full height of the viewport */
            margin: 0; /* Remove default margin */
            background-color: #f2f2f2; /* Light gray background */
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: navy;
        }
        a {
            display: inline-block;
            margin: 20px;
            text-decoration: none;
            color: white;
            background-color: navy;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: darkblue;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex; /* Use flexbox */
            flex-direction: column; /* Stack children vertically */
            align-items: center; /* Center children horizontally */
        }
        input[type="text"] {
            width: 80%; /* Set width to 80% of the container */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 90%; /* Set width to 80% of the container */
            padding: 10px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: darkblue;
        }
        .message {
            text-align: center;
            margin: 20px;
            font-size: 18px;
            color: green;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 20px;
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
    <h1>Cancel Ticket</h1>
    <a href="passenger.php" id="home">Back</a>
    <div class="container">
        <form action="" method="post">
            <input type="text" placeholder="Enter your PNR Number" name="pnr" id="pnr" required><br>
            <button >Cancel Ticket</button>
        </form>
    </div>
    <script>
     document.addEventListener("DOMContentLoaded",function(){askpnr();});

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        function askpnr()
        {
            const utterance = new SpeechSynthesisUtterance("Please say your PNR Number.");
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function() 
            {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;

                recognition.start();

                recognition.onresult = function(event)
                {
                    let speechResult = event.results[0][0].transcript.trim();
                    speechResult = speechResult.replace(/\.$/, '');
                    if (speechResult) 
                    {
                        document.getElementById('pnr').value = speechResult;
                        console.log(speechResult);
                        document.querySelector('form').submit(); 
                    } 
                    else
                    {
                        askpnrAgain();
                    }
                    
                };
                recognition.onend = function() 
                {
                    const pnrField = document.getElementById('pnr').value.trim();
                    if (!pnrField) 
                    {
                        askpnrAgain();
                    }
                };
            };
        }
        function askpnrAgain() 
        {
            const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() 
            {
            askpnr();
            };
        }          
    </script>

<?php  
$compartments = [];
$refund_amt = [];
$sum_refund_amt = 0;
$current_date = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {        

    $pnr = mysqli_real_escape_string($con, $_POST['pnr']);
    $qu1="select booking_id from booking where booking_id='$pnr';";
    $re1=mysqli_query($con, $qu1);
    if ($re1->num_rows > 0) 
    {

        $q1 = "SELECT ref_id, train_no, compartment, journey_date, class, ticket_price, p_id, sum(ticket_price) as total_price FROM booking WHERE booking_id = '$pnr' group by '$pnr';";
        $r1 = mysqli_query($con, $q1);
    
        if ($r1->num_rows > 0) 
        {
            $ref_ids = [];
            $passenger_data = [];
            while ($row = $r1->fetch_assoc())
            {
                $passenger_data[] = $row; 
                $ref_ids[] = $row["ref_id"];
                $compartments[] = $row["compartment"];
            }
    
            $pass = count($passenger_data);
            $journey_date = $passenger_data[0]["journey_date"];
            $class = $passenger_data[0]["class"];
            $ticket_price = $passenger_data[0]["ticket_price"];
            $train_no = $passenger_data[0]["train_no"]; 
            $p_id = $passenger_data[0]["p_id"]; 
            $total_price = $passenger_data[0]["total_price"]; 
    
            if (strtotime($journey_date) > strtotime($current_date))
            {
                $payment_status = 'Refund';
                for ($i = 0; $i < $pass; $i++) 
                {
                    switch ($class) 
                    {
                        case 'AC':
                            $refund_amt[$i] = $ticket_price - 200;
                            break;
                        case 'AC 2 Tier':
                            $refund_amt[$i] = $ticket_price - 150;
                            break;
                        case 'AC 3 Tier':
                            $refund_amt[$i] = $ticket_price - 100;
                            break;
                        case 'sleeper':
                            $refund_amt[$i] = $ticket_price - 50;
                            break;
                        case 'ladies':
                            $refund_amt[$i] = $ticket_price - 150;
                            break;
                        default:
                            $refund_amt[$i] = 0;
                            break;
                    }
                    $sum_refund_amt += $refund_amt[$i];
                }
            } 
            else 
            {
                $payment_status = 'No Refund';
                $sum_refund_amt = 0;
            }
    
            $query0 = "UPDATE booking SET booking_status='cancelled', payment_status='$payment_status' WHERE booking_id='$pnr';";
            if (mysqli_query($con, $query0))
            {
                $query1 = "UPDATE payment SET payment_status='$payment_status', refund_date=NOW(), refund_amount='$sum_refund_amt' WHERE pnr='$pnr';";
                if (mysqli_query($con, $query1))
                {
                    if (!empty($ref_ids)) 
                    {
                        $ref_ids_string = implode("','", $ref_ids);
    
                        $query5 = "DELETE FROM seat WHERE ref_id IN ('$ref_ids_string');";  
                        if (mysqli_query($con, $query5)) 
                        {
                            $query4 = "SELECT compartment, COUNT(ref_id) as no_of_pass FROM booking WHERE booking_id='$pnr' GROUP BY compartment;";
                            $result4 = mysqli_query($con, $query4);
                            while ($row = $result4->fetch_assoc())
                            {
                                $compartment = $row["compartment"]; 
                                $no_of_pass = $row["no_of_pass"];
                                
                                $query2 = "SELECT booked_seat, avail_seat FROM compartment WHERE compartment='$compartment' AND class='$class' AND train_no='$train_no';";
                                $result2 = mysqli_query($con, $query2);
                                if ($result2->num_rows > 0) 
                                {
                                    $row = $result2->fetch_assoc();
                                    $booked_seat = $row["booked_seat"];
                                    $avail_seat = $row["avail_seat"];
                                    
                                    $n_avail_seat = $avail_seat + $no_of_pass; 
                                    $n_booked_seat = $booked_seat - $no_of_pass;
                                    
                                    $query3 = "UPDATE compartment SET booked_seat='$n_booked_seat', avail_seat='$n_avail_seat' WHERE compartment='$compartment' AND train_no='$train_no';";
                                    mysqli_query($con, $query3);
                                }
                            }
                        }
                        $query22 = "SELECT ref_id, p_id FROM booking WHERE train_no='$train_no' AND class='$class' AND booking_status='waiting' LIMIT $no_of_pass;";
                        $result22 = mysqli_query($con, $query22);
                        
                        if ($result22->num_rows > 0) {
                            while ($row22 = $result22->fetch_assoc()) 
                            {
                                $ref_id = $row22["ref_id"];
                                $p_id = $row22["p_id"];
                        
                                $query51 = "SELECT compartment FROM compartment WHERE train_no='$train_no' AND class='$class';";
                                $result51 = mysqli_query($con, $query51);
                                if ($result51->num_rows > 0) {
                                    while ($row51 = $result51->fetch_assoc()) 
                                    {
                                        $compartments[] = $row51['compartment'];
                                    }
                        
                                    $comp1 = isset($compartments[0]) ? $compartments[0] : null;
                                    $comp2 = isset($compartments[1]) ? $compartments[1] : null;
                        
                                    $query21 = "SELECT booked_seat, avail_seat, max_seat FROM compartment WHERE train_no='$train_no' AND class='$class' AND compartment='$comp1';";
                                    $result21 = mysqli_query($con, $query21);
                        
                                    if ($result21->num_rows > 0) {
                                        $row21 = $result21->fetch_assoc();
                                        $booked_seat = $row21["booked_seat"];
                                        $avail_seat = $row21["avail_seat"];
                                        $max_seat = $row21["max_seat"];
                        
                                        if ($booked_seat < $max_seat && $avail_seat > 0) 
                                        {                            
                                            $query7 = "UPDATE booking SET booking_status='booked', compartment='$comp1' WHERE ref_id='$ref_id';";
                                            if (mysqli_query($con, $query7))
                                            {
                                                $query8 = "SELECT seat_no FROM seat WHERE compartment='$comp1' AND train_no='$train_no' ORDER BY seat_no ASC;";
                                                $result8 = mysqli_query($con, $query8);
                        
                                                $occupied_seats = [];
                                                while ($row8 = $result8->fetch_assoc()) 
                                                {
                                                    $occupied_seats[] = $row8["seat_no"];
                                                }
                        
                                                $seat_no = 1;
                                                while (in_array($seat_no, $occupied_seats)) 
                                                {
                                                    $seat_no++;
                                                }
                        
                                                $query9 = "INSERT INTO seat (ref_id, compartment, seat_no, train_no) VALUES ('$ref_id', '$comp1', '$seat_no', '$train_no');";
                                                mysqli_query($con, $query9);
                                                $query91 = "update booking set seat='$seat_no' where ref_id='$ref_id';";
                                                mysqli_query($con, $query91);
                                                $query10 = "UPDATE compartment SET booked_seat=booked_seat+1, avail_seat=avail_seat-1 WHERE compartment='$compartment' AND train_no='$train_no';";
                                                mysqli_query($con, $query10);
                                            }
                                        } 
                                        elseif ($booked_seat == $max_seat) 
                                        {
                                            $query21_2 = "SELECT booked_seat, avail_seat, max_seat FROM compartment WHERE train_no='$train_no' AND class='$class' AND compartment='$comp2';";
                                            $result21_2 = mysqli_query($con, $query21_2);
                        
                                            if ($result21_2->num_rows > 0) {
                                                $row21_2 = $result21_2->fetch_assoc();
                                                $booked_seat = $row21_2["booked_seat"];
                                                $avail_seat = $row21_2["avail_seat"];
                                                $max_seat = $row21_2["max_seat"];
                        
                                                if ($booked_seat < $max_seat && $avail_seat > 0) 
                                                {
                                                    $query7 = "UPDATE booking SET booking_status='booked', compartment='$comp2' WHERE ref_id='$ref_id';";
                                                    if (mysqli_query($con, $query7))
                                                     {
                                                        $query8 = "SELECT seat_no FROM seat WHERE compartment='$comp2' AND train_no='$train_no' ORDER BY seat_no ASC;";
                                                        $result8 = mysqli_query($con, $query8);
                        
                                                        $occupied_seats = [];
                                                        while ($row8 = $result8->fetch_assoc()) {
                                                            $occupied_seats[] = $row8["seat_no"];
                                                        }
                        
                                                        $seat_no = 1;
                                                        while (in_array($seat_no, $occupied_seats)) {
                                                            $seat_no++;
                                                        }
                        
                                                        $query9 = "INSERT INTO seat (ref_id, compartment, seat_no, train_no) VALUES ('$ref_id', '$comp2', '$seat_no', '$train_no');";
                                                        mysqli_query($con, $query9);
                                                        $query91 = "update booking set seat='$seat_no' where ref_id='$ref_id';";
                                                        mysqli_query($con, $query91);
                                                        $query10 = "UPDATE compartment SET booked_seat=booked_seat+1, avail_seat=avail_seat-1 WHERE compartment='$compartment' AND train_no='$train_no';";
                                                        mysqli_query($con, $query10);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }  
                    $query21="select reward_points,wallet from rewards where p_id='$p_id';";
                    $res=mysqli_query($con,$query21);
                    if ($res->num_rows > 0) 
                    {
                        while ($row = $res->fetch_assoc())
                        {
                            $reward_points = $row["reward_points"];
                        }
                        if($total_price > 100)
                        {
                            $cal_reward=$total_price/100;
                            $n_reward_points=$reward_points-$cal_reward; 
                            $query22="update rewards set reward_points='$n_reward_points' where p_id='$p_id';";
                            $res=mysqli_query($con,$query22);
                        }
                    }               
                    echo "<script>
                        const utterance = new SpeechSynthesisUtterance('Cancelled Successfully');
                        utterance.lang = 'en-IN';
                        speechSynthesis.speak(utterance);
                        window.location.href='./visualperson.php';
                    </script>";
                              
                }
            }
        }

        else 
        {
            echo "<script>
                    const utterance = new SpeechSynthesisUtterance(PNR number not found.);
                    utterance.lang = 'en-IN';
                    speechSynthesis.speak(utterance);
                    askpnr();
                </script>";
        }

    mysqli_close($con);
}
}
?>


</body>
</html>