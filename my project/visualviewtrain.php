<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Number Search</title>
    <style>
            body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            color: navy;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        input[type="text"]::placeholder {
            color: #999;
        }

        button {
            background-color: navy;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: blue;
        }

        button:focus, input:focus {
            outline: none;
            border-color: blue;
        }
    </style>
</head>
<body>        
<h1>Using the Train Number</h1>
    <form action="visualreadtrain.php" method="post" id="trainForm">
        <input type="text" placeholder="Train Number" name="train_no" id="trainno" value="<?php echo isset($train_no) ? htmlspecialchars($train_no) : ''; ?>">
        <button type="submit" name="btn1">Search</button>
    </form>

    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        document.addEventListener("DOMContentLoaded", function() 
        {    
        asktrain();
        });

        function asktrain() {
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
                    if (speechResult) 
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
