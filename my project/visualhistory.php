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
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: navy;
            margin-top: 30px;
        }

        nav {
            background-color: navy;
            padding: 15px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            padding: 10px;
        }

        nav a:hover {
            background-color: #333;
            border-radius: 5px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        </style>
<body>
    <h1>Booking History</h1>
        <nav>
            <a href="visualbookedhistory.php">Booking History</a>
            <a href="visualcancelledhistory.php">Cancelled History</a>
            <a href="visualrefundhistory.php">Refunded History</a>
        </nav>
    <script>
  document.addEventListener("DOMContentLoaded",function(){read();});

    function read()
    {
            const utterance = new SpeechSynthesisUtterance("What would you like to see: booked history, refund history, cancelled history, or Pending History?");
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
            if (speechResult.includes("booked history")||speechResult.includes("i want to see booking history")||speechResult.includes("booking history")) 
            {
            window.location.href = 'visualbookedhistory.php';
            } 
            else if (speechResult.includes("cancelled history")||speechResult.includes("i want to see cancelled history")) 
            {
               window.location.href = 'visualcancelledhistory.php';
            } 
            else if (speechResult.includes("refund history")||speechResult.includes("i want to see refund history")) 
            {
                window.location.href = 'visualrefundhistory.php';
            } 
            else 
            {
            const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that...");
            errorUtterance.lang = "en-IN";
            speechSynthesis.speak(errorUtterance);
            errorUtterance.onend = () => record();
            }
        };
    }
    </script>
</body>
</html>