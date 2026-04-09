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
    $p_id = $_SESSION['p_id'];       
    
    $query="select wallet from visuser where p_id='$p_id';";
     $result=mysqli_query($con,$query);
     if($result->num_rows>0)
     {
     while($row=$result->fetch_assoc())   
     {
        $wallet=$row["wallet"];
     }
    }
    echo '<div class="container">';
    echo '<h2>Your Wallet Amount</h2>';
    echo '<div class="reward-points">₹' . $wallet . ' Rupees</div>';
    echo '<div class="button-container">';
    echo '</div>';
    echo '</div>';
    ?>
    <script>
    const wallet = <?php echo $wallet ?>;

    function readwallet()
    {
            const utterance = new SpeechSynthesisUtterance(`Your Wallet amount is ${wallet} rupees...can i repeat it`);
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);
            utterance.onend = () => record();
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
            if (speechResult.includes("no")||speechResult.includes("dont want")) 
            {
            window.location.href = 'visualperson.php';
            } 
            else if ((speechResult.includes("add money") || speechResult.includes("how to add money to wallet"))) 
            {
                const noUtterance = new SpeechSynthesisUtterance("Go to any railway station nearby you. In the railway station, go to the help center to add money to your wallet.");
                noUtterance.lang = "en-IN";
                speechSynthesis.speak(noUtterance);
                noUtterance.onend = () => window.location.href = 'visualperson.php';
            } 
            else if (speechResult.includes("repeat")||speechResult.includes("say again")||speechResult.includes("come again")) 
            {
            const repeatUtterance = new SpeechSynthesisUtterance("ok...let me repeat");
            repeatUtterance.lang = "en-IN";
            speechSynthesis.speak(repeatUtterance);
            repeatUtterance.onend = () => readwallet();
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
    readwallet();
    </script>

</body>
</html>