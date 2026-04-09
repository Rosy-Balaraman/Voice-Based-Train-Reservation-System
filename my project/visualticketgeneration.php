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
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #162447, #1f4068);
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: #162447;
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 1.1rem;
            margin-bottom: 20px;
            border: 2px solid #1f4068;
            border-radius: 5px;
        }

        button {
            background-color: #1f4068;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #162447;
        }

    </style>
</head>
<body>
<div class="container">
    <form action="" method=post>
        <input type="text" placeholder="Enter Your PNR Number" name="pnr" id="pnr" value="<?php echo isset($_POST['pnr']) ? htmlspecialchars($_POST['pnr']) : ''; ?>">
        <button type="submit" name="ticket">View Ticket</button>
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
    $current_date = date("Y-m-d");
    if(isset($_POST['pnr']))
    {   
        $pnr=$_POST['pnr'];
        $_SESSION['pnr']=$pnr;
        $query1="select journey_date from pnr where pnr='$pnr';";
        $result=mysqli_query($con,$query1);
        if($result->num_rows>0)
        {
        while($row=$result->fetch_assoc())   
        {
            $journey_date=$row["journey_date"];
        }
        if($current_date > $journey_date)
        {
            echo "<div id='date'><center><strong>journey date has passed</strong></center></div>";
            echo "<script>
            const text = document.getElementById('date').textContent;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-IN';
            speechSynthesis.speak(utterance);
            </script>";
        }
        else
        {
            echo'<script>window.location.href="visualviewticket.php";</script>';
        }
        }
        else 
        {
            echo "<div id='invalid'><center><strong>Invalid PNR Number...Please say correct PNR number</strong></center></div>";
            echo "<script>
            const text = document.getElementById('invalid').textContent;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-IN';
            speechSynthesis.speak(utterance);
            </script>";
        }
    }
       
    ?>
</body>
</html>