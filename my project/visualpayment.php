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
            background: linear-gradient(to right, #3a3a52, #1f4068);
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #1f4068;
        }
        h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #162447;
        }
        button {
            background-color: #1f4068;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #162447;
        }
        input[type="hidden"] {
            display: none;
        }
        .wallet-info {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Payment Details</h1>
    <input type="hidden" id="opinion" > <br><br>

    <?php
    $total_price = $_SESSION['total_price'];
    $p_id=$_SESSION['p_id'];

    $query="select wallet from rewards where p_id='$p_id';";
    $result=mysqli_query($con,$query);
     if($result->num_rows>0)
     {
     while($row=$result->fetch_assoc())   
     {
        $wallet=$row["wallet"];
     }
    }
    $_SESSION['total_price']=$total_price;
    $_SESSION['wallet']=$wallet;
    echo '<h2>Total amount to be Paid: Rs ' . $total_price . '</h2>';
        echo '<div class="wallet-info">Your wallet balance: Rs ' . $wallet . '</div>';
    ?>
</div>
    <script>
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const wallet = <?php echo json_encode($wallet); ?>;
    const total_price = <?php echo json_encode($total_price); ?>;
    if(wallet > total_price)
    {
        ask();
    }
    else
    {
        const retryUtterance3 = new SpeechSynthesisUtterance(`Your Ticket price is ${total_price}... Insufficient wallet Balance to make payment`);
        retryUtterance3.lang = "en-IN";
        speechSynthesis.speak(retryUtterance3);
        retryUtterance3.onend = function() 
        {
        window.location.href="visualperson.php";
        }; 
    }

        function ask()
        {
            const utterance3 = new SpeechSynthesisUtterance(`Your Ticket price is ${total_price}... Proceed the payment...Please say yes or no`);
            utterance3.lang = "en-IN";
            speechSynthesis.speak(utterance3);

            utterance3.onend = function() 
            {
                let recognition3= new SpeechRecognition();
                recognition3.lang = "en-IN";
                recognition3.interimResults = false;
                recognition3.continuous = false;

                recognition3.start();

                recognition3.onresult = function(event)
                {
                    let speechResult3 = event.results[0][0].transcript.trim().toLowerCase();
                    document.getElementById('opinion').value = speechResult3; 
                    if (speechResult3.includes("yes")) 
                    {
                    window.location.href = 'visualpayment2.php';
                    } 
                    else if (speechResult3.includes("no")) 
                    {
                        const noUtterance = new SpeechSynthesisUtterance("Okay...");
                        noUtterance.lang = "en-IN";
                        speechSynthesis.speak(noUtterance);
                        noUtterance.onend = () => window.location.href = 'visualperson.php';
                    } 
                    else 
                    {
                    const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that...");
                    errorUtterance.lang = "en-IN";
                    speechSynthesis.speak(errorUtterance);
                    errorUtterance.onend = () => ask();
                    }
                };
            };
        }
        function askAgain() 
        {
            const retryUtterance3 = new SpeechSynthesisUtterance("I did not catch that...");
            retryUtterance3.lang = "en-IN";
            speechSynthesis.speak(retryUtterance3);
            retryUtterance3.onend = function() 
            {
            ask();
            };
        }
        
    </script>
</body>
</html>