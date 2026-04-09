<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>BOOKED HISTORY</h1>
    <?php   
     $p_id=$_SESSION['p_id'];
     echo $p_id ;
      $query1="select b.train_no,b.booking_id,t.train_name,t.start_station,t.end_station,t.start_time,t.end_time,b.journey_date,b.booking_status from booking b join train t 
      on t.train_no=b.train_no where booking_status='cancelled' and p_id='$p_id' order by journey_date;";
      $result1=mysqli_query($con,$query1);
      $trains = [];  
      if($result1->num_rows>0)
      {
        ?>
         <center><br><br>
            <table border="5" width="500">
            <tr>
                <th>Train_no</th>
                <th>Train_name</th>
                <th>Start station</th>
                <th>end station</th>
                <th>Start Time</th>
                <th>Arriving Time</th>
                <th>Journey Date</th>
                <th>PNR</th>
                <th>Booking Status</th>
            </tr>
        <?php
        while($row=$result1->fetch_assoc())   
        {
           $trains[] = $row;
         ?>
         <tr>
            <td ><?php echo $row["train_no"];?></td>
            <td ><?php echo $row["train_name"];?></td>
            <td ><?php echo $row["start_station"];?></td>
            <td ><?php echo $row["end_station"];?></td>
            <td ><?php echo $row["start_time"];?></td>
            <td ><?php echo $row["end_time"];?></td>
            <td><?php echo $row["journey_date"];?></td>
            <td><?php echo $row["booking_id"];?></td>
            <td><?php echo $row["booking_status"];?></td>
        </tr>   
        <?php
        }?>
        </table>
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
                    `Train Number is ${train.train_no}, Train Name is ${train.train_name}, this train goes from ${train.start_station} 
                    to ${train.end_station}, starting at ${train.start_time} and arriving at ${train.end_time}, journey date is ${train.journey_date}, your PNR number is ${train.booking_id}
                    and the booking status is ${train.booking_status}.`
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
                const promptUtterance = new SpeechSynthesisUtterance("can i repeat it");
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
            const speechResult = event.results[0][0].transcript.toLowerCase().trim();
            if (speechResult.includes("yes")||speechResult.includes("say again")||speechResult.includes("repeat again")) 
            {
                trainOptions(trains);
            } 
            else if (speechResult.includes("no")||speechResult.includes("don't want")||speechResult.includes("no need")) 
            {
                window.location.href = 'visualperson.php';
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

    </script>
    <?php
} 
else
{
    echo "<div id='no-trains'><center><strong>No train has been cancelled.</strong></center></div>";
    echo "<script>
        const text = document.getElementById('no-trains').textContent;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-IN';
        speechSynthesis.speak(utterance);
        window.location.href='./visualperson.php';
    </script>";
}
?>
</body>
</html>