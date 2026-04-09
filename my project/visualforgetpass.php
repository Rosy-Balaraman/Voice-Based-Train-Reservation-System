<?php
session_start();
include 'db.php';
 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
 {        

     $question = mysqli_real_escape_string($con, $_POST['question']);
     $answer = mysqli_real_escape_string($con, $_POST['answer']);
 
     if(!empty($question))
     {
        $sql = "SELECT * FROM visuser WHERE `$question` = lower('$answer')";
        $result = $con->query($sql);
    
        if ($result && $result->num_rows > 0)
        {
        ?>
        <script>
            askpassword();
        </script>  
        <?php
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
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Forget Password</h2>
    <form action="" method="post">
    <input type="text" placeholder="security question" name="answer" id="answer">
    <input type="hidden" name="question" id="question">
    </form>
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
                        document.getElementById('loginForm').submit();
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

        function askpassword()
        {
            const utterance9 = new SpeechSynthesisUtterance("Say your new password...Please say 6 digit Number");
            utterance9.lang = "en-IN";
            speechSynthesis.speak(utterance9);

            utterance9.onend = function() 
            {
                let recognition9= new SpeechRecognition();
                recognition9.lang = "en-IN";
                recognition9.interimResults = false;
                recognition9.continuous = false;

                recognition9.start();

                recognition9.onresult = function(event)
                {
                    let speechResult9 = event.results[0][0].transcript.trim();
                    speechResult9 = speechResult9.replace(/\D/g, '');
                    if(speechResult9.length === 6 )
                    {
                        document.getElementById('password').value = speechResult9; 
                        passwordValue = speechResult9; 
                    }
                };
                recognition9.onend = function() 
                {
                    const passwordField = document.getElementById('password').value.trim();
                    if (!passwordField) 
                    {
                        askpasswordAgain();
                    }
                    else
                    {
                        askconfirmpass();
                    }
                };
            };
        }
        function askpasswordAgain() 
        {
            const retryUtterance6 = new SpeechSynthesisUtterance("I didn't catch that...");
            retryUtterance6.lang = "en-IN";
            speechSynthesis.speak(retryUtterance6);
            retryUtterance6.onend = function() 
            {
            askpassword();
            };
        }

        function askconfirmpass()
        {
            const utterance10 = new SpeechSynthesisUtterance("could you Please say again say your password");
            utterance10.lang = "en-IN";
            speechSynthesis.speak(utterance10);

            utterance10.onend = function() 
            {
                let recognition10= new SpeechRecognition();
                recognition10.lang = "en-IN";
                recognition10.interimResults = false;
                recognition10.continuous = false;

                recognition10.start();

                recognition10.onresult = function(event)
                {
                    let speechResult10 = event.results[0][0].transcript.trim();
                    speechResult10 = speechResult10.replace(/\D/g, '');
                    if(speechResult10 === passwordValue)
                    {
                        document.getElementById('confirm_pass').value = speechResult10; 
                    }
                    else 
                    {
                        const retryUtterance61 = new SpeechSynthesisUtterance("password and confirm password do not match");
                        retryUtterance61.lang = "en-IN";
                        speechSynthesis.speak(retryUtterance61);
                        retryUtterance61.onend = function() 
                        {
                        askconfirmpass();
                        };
                    }
                };
                recognition10.onend = function() 
                {
                    const con_passField = document.getElementById('confirm_pass').value.trim();
                    if (!con_passField) 
                    {
                        askconfirmpassAgain();
                    }
                    else
                    {
                        window.location.href="visuallogin";
                    }
                };
            };
        }
        function askconfirmpassAgain() 
        {
            const retryUtterance10 = new SpeechSynthesisUtterance("Number is not Match");
            retryUtterance10.lang = "en-IN";
            speechSynthesis.speak(retryUtterance10);
            retryUtterance10.onend = function() 
            {
            askpassword();
            };
        }

        </script>
</body>
</html>