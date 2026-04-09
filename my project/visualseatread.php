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
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        h1 {
            color: navy;
            font-size: 28px;
            text-align: center;
            padding-top: 20px;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
        }

        table th {
            background-color: navy;
            color: white;
            font-size: 18px;
        }

        table td {
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        #no-trains {
            font-size: 18px;
            color: red;
            text-align: center;
            margin-top: 50px;
        }

        button {
            background-color: navy;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: blue;
        }

        button:focus {
            outline: none;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container table {
            width: 80%;
        }

        .container button {
            margin: 20px auto;
        }
    </style>
<body>
    
</body>
</html>

<?php
$train_no = isset($_POST['train_no']) ? trim($_POST['train_no'], " .") : '';
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
    $train_no = getClosestMatch($train_no, ["A001", "B001", "C001"]);
    
    $trains = [];
    $index = 0;

    $query =  "SELECT t.train_no, t.train_name, t.start_station, t.end_station, t.start_time, t.end_time, SUM(c.avail_seat) AS total_seat FROM train t JOIN compartment c ON t.train_no = c.train_no 
                 WHERE t.train_no='$train_no'GROUP BY t.train_no, t.train_name, t.start_station, t.end_station, t.start_time, t.end_time;";
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
               <th>Available Seat</th>
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
           <td><?php echo $row["total_seat"]; ?></td>
       </tr>
       <?php
           $trains[] = $row;
       }
?>
       </table>
       </center>
   <script>
    const trains = <?php echo json_encode($trains); ?>;

        let index = 0;
        function readNext() 
        {
                const train = trains[index];
                const utterance = new SpeechSynthesisUtterance(
                    ` Train Number is ${train.train_no}, Train Name is ${train.train_name}, this train goes from ${train.start_station} 
                    to ${train.end_station}, starting at ${train.start_time}, arriving at ${train.end_time} and total available Seat is ${train.total_seat}...can i repeat once again ?...`);
                utterance.lang = "en-IN";
                speechSynthesis.speak(utterance);
                utterance.onend = () => 
                {
                   startrecord();
                };
        }
        readNext();

    function startrecord() 
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
            if (speechResult2.includes("repeat once more")|| speechResult2.includes("come again") || speechResult2.includes("say again")|| speechResult2.includes("yes"))
            {
                const errorUtterance2 = new SpeechSynthesisUtterance("yeah...sure");
                errorUtterance2.lang = "en-IN";
                speechSynthesis.speak(errorUtterance2);
                errorUtterance2.onend = () => readNext();
            }
            else if (speechResult2.includes("no")|| speechResult2.includes("don't want") ) 
            {
                window.location.href = 'visualperson.php';
            }
            else if (speechResult2.includes("go to home page")|| speechResult2.includes("home")) 
            {
                window.location.href = 'visualperson.php';
            }
            else if (speechResult2.includes("book")|| speechResult2.includes("book a ticket")) 
            {
                window.location.href = 'visualreservation.php';
            }
            else 
            {
                const errorUtterance3 = new SpeechSynthesisUtterance("Sorry, I didn't catch that.");
                errorUtterance3.lang = "en-IN";
                speechSynthesis.speak(errorUtterance3);
                errorUtterance3.onend = () => startrecord();
            }
        };
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
