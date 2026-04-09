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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9; /* Light background for a clean look */
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background-color: white;
            width: 50%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: navy;
            text-align: center;
            margin-bottom: 30px;
        }

        h3 {
            font-size: 18px;
            margin: 10px 0;
            padding-left: 15px;
            color: #555;
        }

        /* Profile details */
        .profile-details {
            border-left: 5px solid navy;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <?php
    $p_id = $_SESSION['p_id'];
    $query="select name,gender,phn_no from visuser where p_id='$p_id';";
    $result=mysqli_query($con,$query);
     if($result->num_rows>0)
     {
        $details = $result->fetch_assoc();
        
        $name = $details["name"];
        $gender = $details["gender"];
        $phn_no = $details["phn_no"];
    }
    ?>
   <div class="profile-container">
        <h2>PROFILE</h2>
        <div class="profile-details">
            <h3>Passenger Name: <?php echo htmlspecialchars($name); ?></h3>
            <h3>Gender: <?php echo htmlspecialchars($gender); ?></h3>
            <h3>Mobile: <?php echo htmlspecialchars($phn_no); ?></h3>
        </div>
    </div>

    <script>
    const details = <?php echo json_encode($details); ?>;
    function read()
    {
        const utterance = new SpeechSynthesisUtterance(`Your name is ${details.name}, gender is ${details.gender},mobile number is ${details.phn_no}...can i repeat it`);
        utterance.lang = "en-IN";
        speechSynthesis.speak(utterance);
        utterance.onend = () => 
        {
            record();
        };
    }
    function record()
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
            if (speechResult.includes("repeat")||speechResult.includes("say again")||speechResult.includes("come again")) 
            {
            const repeatUtterance = new SpeechSynthesisUtterance("ok...let me repeat");
            repeatUtterance.lang = "en-IN";
            speechSynthesis.speak(repeatUtterance);
            repeatUtterance.onend = () => read();
            }
            else if (speechResult.includes("no")||speechResult.includes("dont want")) 
            {
            const repeatUtterance = new SpeechSynthesisUtterance("ok");
            repeatUtterance.lang = "en-IN";
            speechSynthesis.speak(repeatUtterance);
            repeatUtterance.onend = () => window.location.href="visualperson.php";
            }
            else 
            {
            const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that.");
            errorUtterance.lang = "en-IN";
            speechSynthesis.speak(errorUtterance);
            errorUtterance.onend = () => record();
            }
        };
    }
    read();
    </script>

</body>
</html>