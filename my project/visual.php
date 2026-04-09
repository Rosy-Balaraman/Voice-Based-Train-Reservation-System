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
            background-color: #f4f4f4;
            color: #333;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            color: navy;
            padding-top:30px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 2px solid navy; /* Navy border for input */
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: blue; /* Change border color on focus */
        }

        button {
            background-color: blue; /* Blue background for button */
            color: white;           /* White text color */
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: darkblue; /* Darker blue on hover */
        }
    </style>
<body>
    <center><h1> Welcome to our  Cheerful Journey Website </h1></center>
    <input type="text" placeholder="Already registered or not " id="name" name="name" required>

    <script>
       
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        document.addEventListener("DOMContentLoaded", function()
        {    
            welcome();
        });
        function welcome()
        {
            const welcometext = "Welcome to our cheerful journey Website... ";
            const welcome= new SpeechSynthesisUtterance(welcometext);
            welcome.lang = "en-IN";
            speechSynthesis.speak(welcome);
       
        welcome.onend = function()
        {
            confirm();
        };
        }

        function confirm() 
        {
            const utterance = new SpeechSynthesisUtterance("If you are already registered, say 'yes'. If not, say 'no'.");
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function() 
            {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;
                let speechResult = "";
                recognition.start();

                recognition.onresult = function(event)
                {
                    const speechResult = event.results[0][0].transcript.trim().toLowerCase();
                    document.getElementById('name').value = speechResult;
                    if (speechResult.includes("yes"))
                    {
                        const utterance1 = new SpeechSynthesisUtterance("Let's get you login...");
                        utterance1.lang = "en-IN";
                        speechSynthesis.speak(utterance1);
                        window.location.href = 'visuallogin.php';
                    } 
                    else if(speechResult.includes("no"))
                    {
                        const utterance1 = new SpeechSynthesisUtterance("Let's get you registered...");
                        utterance1.lang = "en-IN";
                        speechSynthesis.speak(utterance1);

                        window.location.href = 'visualregister.php';
                    } 
                };

                recognition.onend = function() 
                {
                    if (speechResult=="") 
                    {
                        sayAgain();
                    }
                };
                function sayAgain() 
                    {
                    const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
                    retryUtterance.lang = "en-IN";
                    speechSynthesis.speak(retryUtterance);
                    retryUtterance.onend = function() 
                    {
                    confirm();
                    };
                }
            };
        }
    </script>
</body>
</html>
