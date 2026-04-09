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
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            color: navy;
        }

        /* Centering form container */
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 250px;
            margin: 10px 0;
            border: 2px solid navy;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: navy;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

    </style>
<body>
    <center>
    <h1>Using Train number</h1>
    <form action="visualseatread.php" method="post"  id="trainForm">
    <input type="text" placeholder="Train Number" name="train_no" id="trainno" value="<?php echo isset($train_no) ? htmlspecialchars($train_no) : ''; ?>">
    <br><button type="submit" name="btn1" >search</button>
    </form>
    </center>
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

document.addEventListener("DOMContentLoaded", function()
{    
    asktrain();
});

function asktrain() 
{
    const utterance = new SpeechSynthesisUtterance("Please say Train Number.");
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
            speechResult = speechResult.replace(/\.$/, '').replace(/\s+/g, '');
            if(speechResult)
            {
                document.getElementById('trainno').value = speechResult.toUpperCase();
                document.getElementById('trainForm').submit();
            }
            else
            {
                asktrainAgain();
            }
            
        };
        recognition.onend = function() 
        {
            const noField = document.getElementById('trainno').value.trim();
            if (!noField) 
            {
                asktrainAgain();
            }
        };
    };
}
function asktrainAgain() 
{
    const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
    retryUtterance.lang = "en-IN";
    speechSynthesis.speak(retryUtterance);
    retryUtterance.onend = function() 
    {
    asktrain();
    };
}
    </script>
</body>
</html>