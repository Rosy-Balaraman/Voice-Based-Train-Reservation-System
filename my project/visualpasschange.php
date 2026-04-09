<?php
session_start();
include 'db.php';
 $p_id = htmlspecialchars($_SESSION['p_id']);
 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
 {        

     $question = mysqli_real_escape_string($con, $_POST['question']);
     $answer = mysqli_real_escape_string($con, $_POST['answer']);
 
     if(!empty($question))
     {
        $sql = "SELECT * FROM visuser WHERE `$question` = lower('$answer') and p_id='$p_id';";
        $result = $con->query($sql);
    
        if ($result && $result->num_rows > 0)
        {
        ?>
        <script>
            window.location.href="visualpassupdate.php";
        </script>  
        <?php
        }
        else
        {
            ?>
            <script>
                const utterance3 = new SpeechSynthesisUtterance("Your answer is wrong...Please try again");
                utterance3.lang = "en-IN";
                speechSynthesis.speak(utterance3);
                question();
            </script>  
            <?php
        }
     }
     $con->close();
 }
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
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #4A90E2;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #4A90E2;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #357ABD;
        }

    </style>
<body>
<div class="container">
        <h2>Forget Password</h2>
        <form action="" method="post" id="securityQuestionForm">
            <input type="text" placeholder="Security question answer" name="answer" id="answer" required>
            <input type="hidden" name="question" id="question" value="Your predefined question here">
            <button type="submit">Submit</button>
        </form>
    </div>
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

         document.addEventListener("DOMContentLoaded", function()
        {    
            question();
        });

        function question()
        {
        const questions = {
            "pet": "What is the name of your first pet or favorite animal?",
            "birth": "What is the name of your birth place?"
        };
        let selectedkey = Object.keys(questions)[Math.floor(Math.random() * Object.keys(questions).length)];
        let selectedQuestion=questions[selectedkey];

        document.getElementById('answer').placeholder = selectedQuestion;
        document.getElementById('question').value = selectedkey;

            const utterance1 = new SpeechSynthesisUtterance(selectedQuestion);
            utterance1.lang = "en-IN";
            speechSynthesis.speak(utterance1);

            utterance1.onend = function() 
            {
                let recognition1 = new SpeechRecognition();
                recognition1.lang = "en-IN";
                recognition1.interimResults = false;
                recognition1.continuous = false;

                recognition1.start();

                recognition1.onresult = function(event)
                {
                    let speechResult1 = event.results[0][0].transcript.trim();
                    speechResult1 = speechResult1.replace(/\.$/, '');
                    if(speechResult1)
                    {
                        document.getElementById('answer').value = speechResult1; 
                        console.log(speechResult);
                    }
                };
                recognition1.onend = function() 
                {
                    const Field = document.getElementById('answer').value.trim();
                    if (!Field) 
                    {
                        askquestionAgain();
                    }
                    else 
                    {
                        document.getElementById('securityQuestionForm').submit();
                    }
                };
            };
        }
        function askquestionAgain() 
        {
            const retryUtterance1 = new SpeechSynthesisUtterance("I didn't catch that...");
            retryUtterance1.lang = "en-IN";
            speechSynthesis.speak(retryUtterance1);
            retryUtterance1.onend = function() 
            {
            question();
            };
        }

       
        </script>
</body>
</html>