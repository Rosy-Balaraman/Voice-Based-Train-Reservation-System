<?php
session_start();
include 'db.php';
$p_id = htmlspecialchars($_SESSION['p_id']);
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

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="password"]:focus {
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
</head>
<body>
<div class="container">
        <h2>Set Password</h2>
        <form action="" method="post">
            <input type="password" placeholder="Set a password..." id="password" name="password" required>
            <input type="password" placeholder="Confirm your password..." id="confirm_pass" name="confirm_pass" required>
            <button type="submit">Submit</button>
        </form>
    </div>
    
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        
        document.addEventListener("DOMContentLoaded", function() {    
            askpassword();
        });

        function askpassword() {
            const utterance9 = new SpeechSynthesisUtterance("Set the password... Please say a 6 digit Number");
            utterance9.lang = "en-IN";
            speechSynthesis.speak(utterance9);

            utterance9.onend = function() {
                let recognition9 = new SpeechRecognition();
                recognition9.lang = "en-IN";
                recognition9.interimResults = false;
                recognition9.continuous = false;

                recognition9.start();

                recognition9.onresult = function(event) {
                    let speechResult9 = event.results[0][0].transcript.trim();
                    speechResult9 = speechResult9.replace(/\D/g, '');
                    if (speechResult9.length === 6) {
                        document.getElementById('password').value = speechResult9; 
                        passwordValue = speechResult9; 
                    }
                };

                recognition9.onend = function() {
                    const passwordField = document.getElementById('password').value.trim();
                    if (!passwordField) {
                        askpasswordAgain();
                    } else {
                        askconfirmpass();
                    }
                };
            };
        }

        function askpasswordAgain() {
            const retryUtterance6 = new SpeechSynthesisUtterance("I didn't catch that...");
            retryUtterance6.lang = "en-IN";
            speechSynthesis.speak(retryUtterance6);
            retryUtterance6.onend = function() {
                askpassword();
            };
        }

        function askconfirmpass() {
            const utterance10 = new SpeechSynthesisUtterance("Could you please say your password again?");
            utterance10.lang = "en-IN";
            speechSynthesis.speak(utterance10);

            utterance10.onend = function() {
                let recognition10 = new SpeechRecognition();
                recognition10.lang = "en-IN";
                recognition10.interimResults = false;
                recognition10.continuous = false;

                recognition10.start();

                recognition10.onresult = function(event) {
                    let speechResult10 = event.results[0][0].transcript.trim();
                    speechResult10 = speechResult10.replace(/\D/g, '');
                    if (speechResult10 === passwordValue) {
                        document.getElementById('confirm_pass').value = speechResult10; 
                    } else {
                        const retryUtterance61 = new SpeechSynthesisUtterance("Password and confirm password do not match");
                        retryUtterance61.lang = "en-IN";
                        speechSynthesis.speak(retryUtterance61);
                        retryUtterance61.onend = function() {
                            askconfirmpass();
                        };
                    }
                };

                recognition10.onend = function() {
                    const con_passField = document.getElementById('confirm_pass').value.trim();
                    if (!con_passField) {
                        askconfirmpassAgain();
                    } else {
                        document.querySelector('form').submit(); 
                    }
                };
            };
        }

        function askconfirmpassAgain() {
            const retryUtterance10 = new SpeechSynthesisUtterance("Numbers do not match");
            retryUtterance10.lang = "en-IN";
            speechSynthesis.speak(retryUtterance10);
            retryUtterance10.onend = function() {
                askconfirmpass();
            };
        }
    </script>
    
    <?php
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {       
        $password = htmlspecialchars($_POST['password']);
        $ins = "UPDATE visuser SET password='$password', confirm_password='$password' WHERE p_id='$p_id';";
        $result_ins = mysqli_query($con, $ins);

        if ($result_ins) {
            echo '<script>
                const retryUtterance10 = new SpeechSynthesisUtterance("Updated successfully");
                retryUtterance10.lang = "en-IN";
                speechSynthesis.speak(retryUtterance10);
                retryUtterance10.onend = function() {            
                    window.location="./visualperson.php";
                };
                </script>';   
        } else {
            echo '<script>
                const retryUtterance10 = new SpeechSynthesisUtterance("Something went wrong");
                retryUtterance10.lang = "en-IN";
                speechSynthesis.speak(retryUtterance10);
                retryUtterance10.onend = function() {            
                    askpassword();
                };
                </script>';   
        }

        mysqli_close($con);
    }
    ?>
</body>
</html>
