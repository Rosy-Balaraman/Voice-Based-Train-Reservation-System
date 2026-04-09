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
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            color: navy;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        h2 {
            margin-top: 20px;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #00509E;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        button {
            background-color: #00509E;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #003f7f;
        }
        .total-price {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
<?php
$trainDetails = $_SESSION['train_details'] ?? [];
if (!empty($trainDetails)) 
{
    $train_no=htmlspecialchars($trainDetails['train_no']);
    $train_name=htmlspecialchars($trainDetails['train_name']);
    $start_station=htmlspecialchars($trainDetails['start_station']);
    $end_station=htmlspecialchars($trainDetails['end_station']) ;
    $journey_date=htmlspecialchars($trainDetails['journey_date']);
    $class= htmlspecialchars($trainDetails['class']); 
    echo "<h1>" . htmlspecialchars("Train No: $train_no, Train Name: $train_name, Start Station: $start_station, End Station: $end_station, Date: $journey_date, Class: $class") . "</h1>";
   
}

$total_price=0;
$no_of_pas=$_SESSION['no_of_pas'];

$passengerDetails = $_SESSION['passenger_details'] ?? [];
if (!empty($passengerDetails))
{
    echo '<h2>Booking Details</h2>';
    echo '<table border="1">';
    echo '<tr><th>Name</th><th>Age</th><th>Gender</th><th>Ticket Price</th></tr>';

    foreach ($passengerDetails as $passenger) {
        $name = htmlspecialchars($passenger['name']);
        $age = htmlspecialchars($passenger['age']);
        $gender = htmlspecialchars($passenger['gender']);
        $ticketPrice = htmlspecialchars($passenger['ticket_price']);
        $booking_id = htmlspecialchars($passenger['booking_id']);
        $train_no = htmlspecialchars($passenger['train_no']);
        $journey_date= htmlspecialchars($passenger['journey_date']);
        $booking_date= htmlspecialchars($passenger['booking_date']);
        $p_id= htmlspecialchars($passenger['p_id']);
        $total_price=$total_price + $ticketPrice;
        echo "<tr><td>$name</td><td>$age</td><td>$gender</td><td>$ticketPrice</td></tr>";
    }
    echo '</table>';
    $_SESSION['total_price'] = $total_price;
    $_SESSION['booking_id'] = $booking_id;
    $_SESSION['start_station'] = $start_station;
    $_SESSION['end_station'] = $end_station;
    $_SESSION['start_time'] = $start_time;
    $_SESSION['end_time'] = $end_time;
    echo'<h2>total price   '.$total_price.'</h2>';
    echo '<button name="confirm" >Confirm</button>';
} 
else
{
    echo '<p>No passenger details found.</p>';
}
?>
</div>
<script>
    let passengerDetails = <?php echo json_encode($passengerDetails); ?>;
    let totalPrice = <?php echo $total_price; ?>;
    function readPassengerDetails(index)
    {
        if (index < passengerDetails.length)
        {
            const passenger = passengerDetails[index];
            const utterance = new SpeechSynthesisUtterance(`Passenger ${index+1}, Name is : ${passenger.name}, Age is : ${passenger.age}, Ticket Price: ${passenger.ticket_price}`);
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);
            utterance.onend = () => readPassengerDetails(index + 1);
        } 
        else 
        {
            announceTotalPrice();
        }
    }
    function announceTotalPrice()
    {
        const totalPriceUtterance = new SpeechSynthesisUtterance(`The total price is ${totalPrice}. Do you want to proceed with payment? Say yes or no.`);
        totalPriceUtterance.lang = "en-IN";
        speechSynthesis.speak(totalPriceUtterance);
        totalPriceUtterance.onend = () => startRecognition();
    }
    function startRecognition()
    {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let rec = new SpeechRecognition();
        rec.lang = "en-IN";
        rec.interimResults = false;
        rec.continuous = false;
        rec.start();

        rec.onresult = function(event) 
        {
            const speechResult = event.results[0][0].transcript.toLowerCase().trim();
            if (speechResult.includes("yes")) 
            {
            window.location.href = 'visualpayment.php';
            } 
            else if (speechResult.includes("no")) 
            {
                const noUtterance = new SpeechSynthesisUtterance("Okay...");
                noUtterance.lang = "en-IN";
                speechSynthesis.speak(noUtterance);
                noUtterance.onend = () => window.location.href = 'visualperson.php';
            } 
            else if (speechResult.includes("wrong")||speechResult.includes("wrong details")||speechResult.includes("i want to change")) 
            {
                const noUtterance = new SpeechSynthesisUtterance("Okay, let's change the details.");
                noUtterance.lang = "en-IN";
                speechSynthesis.speak(noUtterance);
                noUtterance.onend = () => window.location.href = 'visualbooking.php';
            } 
            else if (speechResult.includes("repeat")) 
            {
            const repeatUtterance = new SpeechSynthesisUtterance("Repeating passenger details.");
            repeatUtterance.lang = "en-IN";
            speechSynthesis.speak(repeatUtterance);
            repeatUtterance.onend = () => readPassengerDetails(0);
            }
            else 
            {
            const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that...");
            errorUtterance.lang = "en-IN";
            speechSynthesis.speak(errorUtterance);
            errorUtterance.onend = () => startRecognition();
            }
        };
    }
    readPassengerDetails(0);
</script>
</body>
</html>
