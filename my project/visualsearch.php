<?php
session_start();
include 'include.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: navy;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        button {
            background-color: navy;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004080;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        th, td {
            border: 2px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: navy;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        #no-trains {
            margin: 20px auto;
            font-size: 1.2em;
            color: red;
        }
    </style>
<body>
    
</body>
</html><?php
session_start();
?>
<?php
$from = isset($_POST['from']) ? trim($_POST['from'], " .") : '';
$to = isset($_POST['to']) ? trim($_POST['to'], " .") : '';
$class = isset($_POST['class']) ? trim($_POST['class'], " .") : '';
$date = isset($_POST['date']) ? trim($_POST['date'], " .") : '';
$_SESSION['date'] = $date;
    function getClosestMatch($input, $options)
    {
        $closest_match = null;
        $shortest_distance = 5; 
        foreach ($options as $option) 
        {
            $distance = levenshtein(strtolower(trim($input)), strtolower(trim($option)));
            if ($distance < $shortest_distance) 
            {
                $shortest_distance = $distance;
                $closest_match = $option;
            }
        }
        return $closest_match;
    }
    $from_closest_station = getClosestMatch($from, ["Villupuram", "Tambaram", "Madurai","Tindivanam"]);
    $to_closest_station = getClosestMatch($to, ["Villupuram", "Tindivanam", "Tambaram","Madurai"]);
    $closest_class = getClosestMatch($class, ["AC", "sleeper", "ladies"]);
    $_SESSION['class'] = $closest_class;

    
    $trains = [];
    $index = 0;

    $query = "SELECT t.train_no, t.train_name, t.start_station, t.end_station, t.start_time, t.end_time, c.ticket_price, SUM(c.avail_seat) AS total_seat, c.class 
              FROM train t JOIN compartment c ON t.train_no = c.train_no WHERE lower(trim(t.start_station)) = lower('$from_closest_station')
              AND lower(trim(t.end_station)) = lower('$to_closest_station') AND lower(trim(c.class)) = lower('$closest_class')
              GROUP BY t.train_no, t.train_name, t.start_station, t.end_station, t.start_time, t.end_time, c.ticket_price;";

    $result = mysqli_query($con, $query);

    if ($result->num_rows > 0) 
    {
?>
        <center><br><br>
           <table border="5" width="500">
           <tr>
                <th>Option no</th>
               <th>Train_no</th>
               <th>Train_name</th>
               <th>Start station</th>
               <th>End station</th>
               <th>Start time</th>
               <th>End time</th>
               <th>Ticket price</th>
               <th>Available seats</th>
               <th>Class</th>
           </tr>
       <?php
       while ($row = $result->fetch_assoc()) 
       {
        ?>
        <tr>
            <td><?php echo ++$index; ?></td>
           <td><?php echo $row["train_no"]; ?></td>
           <td><?php echo $row["train_name"]; ?></td>
           <td><?php echo $row["start_station"]; ?></td>
           <td><?php echo $row["end_station"]; ?></td>
           <td><?php echo $row["start_time"]; ?></td>
           <td><?php echo $row["end_time"]; ?></td>
           <td><?php echo $row["ticket_price"]; ?></td>
           <td><?php echo $row["total_seat"]; ?></td>
           <td><?php echo $row["class"]; ?></td>
       </tr>
       <?php
           $trains[] = $row;
       }
?>
       </table>
       </center>
   <script>
    const trains = <?php echo json_encode($trains); ?>;

    function trainOptions(trains)
    {
        let index = 0;
        function readNext() 
        {
            if (index < trains.length)
            {
                const train = trains[index];
                const utterance = new SpeechSynthesisUtterance(
                    `Option ${index + 1}: Train Number is ${train.train_no}, Train Name is ${train.train_name}, this train goes from ${train.start_station} 
                    to ${train.end_station}, starting at ${train.start_time} and arriving at ${train.end_time}, the class is ${train.class}, there are ${train.total_seat} seats available, and 
                    the price is ${train.ticket_price}.`
                );
                utterance.lang = "en-IN";
                speechSynthesis.speak(utterance);
                utterance.onend = () => 
                {
                    index++;
                    readNext();
                };
            }
            else 
            {
                const promptUtterance = new SpeechSynthesisUtterance("Please say the option number you want to book.");
                promptUtterance.lang = "en-IN";
                speechSynthesis.speak(promptUtterance);
                promptUtterance.onend = () => startRecognition();
            }
        }
        readNext();
    }
    trainOptions(trains);

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
            const speechResult = event.results[0][0].transcript.trim();
            const optionno = parseInt(speechResult);
            if (!isNaN(optionno) && optionno > 0 && optionno <= trains.length)
            {
                const train = trains[optionno - 1];
                const utterance1 = new SpeechSynthesisUtterance(
                    `Your selected option is ${optionno}: Train Number is ${train.train_no}, Train Name is ${train.train_name}, this train goes from ${train.start_station} 
                    to ${train.end_station}, starting at ${train.start_time} and arriving at ${train.end_time}, the class is ${train.class}. Is this correct? Say yes or no.`
                );
                utterance1.lang = "en-IN";
                speechSynthesis.speak(utterance1);
                utterance1.onend = () => startrecord(train);
            } 
            else 
            {
                const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that. Please say the option number again.");
                errorUtterance.lang = "en-IN";
                speechSynthesis.speak(errorUtterance);
                errorUtterance.onend = () => startRecognition();
            }
        };
    }

    function startrecord(train) 
    {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let rec1 = new SpeechRecognition();
        rec1.lang = "en-IN";
        rec1.interimResults = false;
        rec1.continuous = false;
        rec1.start();

        rec1.onresult = function(event)
        {
            const speechResult2 = event.results[0][0].transcript.toLowerCase();
            if (speechResult2.includes("yes"))
            {
                window.location.href = 'visualbooking.php';
                submitdetails(train);
            }
            else if (speechResult2.includes("no")) 
            {
                const errorUtterance2 = new SpeechSynthesisUtterance(" let's try again. Please say the option number you want to book.");
                errorUtterance2.lang = "en-IN";
                speechSynthesis.speak(errorUtterance2);
                errorUtterance2.onend = () => startRecognition();
            }
            else if (speechResult2.includes("repeat once more")|| speechResult2.includes("come again") || speechResult2.includes("say again")) 
            {
                const errorUtterance2 = new SpeechSynthesisUtterance("yeah...sure");
                errorUtterance2.lang = "en-IN";
                speechSynthesis.speak(errorUtterance2);
                errorUtterance2.onend = () => trainOptions(trains);
            }
            else 
            {
                const errorUtterance3 = new SpeechSynthesisUtterance("Sorry, I didn't catch that. Please say yes or no.");
                errorUtterance3.lang = "en-IN";
                speechSynthesis.speak(errorUtterance3);
                errorUtterance3.onend = () => startrecord();
            }
        };
    }
    function submitdetails(train) 
    {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'visualbooking.php'; 

    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'train_no';
    input.value = train.train_no;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'train_name';
    input.value = train.train_name;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'start_station';
    input.value = train.start_station;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'end_station';
    input.value = train.end_station;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ticket_price';
    input.value = train.ticket_price;
    form.appendChild(input);


    document.body.appendChild(form);
    form.submit();
}

</script>
<?php
} 
else
{
    echo "<div id='no-trains'><center><strong>We're sorry, no trains are available matching your criteria. Please try again with different details.</strong></center></div>";
    echo "<script>
        const text = document.getElementById('no-trains').textContent;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-IN';
        speechSynthesis.speak(utterance);
        window.location.href='./visualreservation.php';
    </script>";
}
?>
