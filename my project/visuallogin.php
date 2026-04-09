<?php
session_start();
include 'db.php';
$i=0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if($i<2)
    {        
        $mob = mysqli_real_escape_string($con, $_POST['mob']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
    
        $sql = "SELECT * FROM visuser WHERE phn_no = lower('$mob') AND password='$password'";
        $result = $con->query($sql);
    
        if ($result && $result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $_SESSION['name'] = $row['name'];
            $_SESSION['p_id'] = $row['p_id'];
            ?>
            <script>
                const utterance2 = new SpeechSynthesisUtterance("Login successfully...");
                utterance2.lang = "en-IN";
                speechSynthesis.speak(utterance2);
                window.location.href = 'visualperson.php';
            </script>  
            <?php
        } 
        else
        {
            ?>
            <script>
                const utterance3 = new SpeechSynthesisUtterance("Your credentials are incorrect. Please try again.");
                utterance3.lang = "en-IN";
                speechSynthesis.speak(utterance3);
                askNumber();
            </script>  
            <?php
        }
    }
}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        #loginForm {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
        }
        h1 {
            text-align: center;
            color: navy;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="text"]:focus {
            border-color: navy;
            outline: none;
        }
        button {
            background-color: navy;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <form action="" method="post" id="loginForm">
        <h1>Login</h1>
        <input type="text" placeholder="Enter your Mobile number" name="mob" id="mob">
        <input type="text" placeholder="Enter your password" name="password" id="password">
    </form>

    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        document.addEventListener("DOMContentLoaded", function() 
        {    
                askNumber();
        });

        function askNumber() {
            const utterance = new SpeechSynthesisUtterance("Please say your Mobile Number");
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function() {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;

                recognition.start();

                recognition.onresult = function(event) {
                    let speechResult = event.results[0][0].transcript.trim();
                    speechResult = speechResult.replace(/\D/g, '');
                    if(speechResult.length === 10 && speechResult.charAt(0) !== '0' && speechResult.charAt(0) !== '1') {
                        document.getElementById('mob').value = speechResult; 
                        console.log(speechResult);
                    }
                };
                recognition.onend = function() {
                    const mobField = document.getElementById('mob').value.trim();
                    if (!mobField) {
                        askMobAgain();
                    } else {
                        askPassword();
                    }
                };
            };
        }

        function askMobAgain() {
            const retryUtterance = new SpeechSynthesisUtterance("Number is invalid. The number should be 10 digits.");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() {
                askNumber();
            };
        }

        function askPassword() {
            const utterance2 = new SpeechSynthesisUtterance("Please say your password");
            utterance2.lang = "en-IN";
            speechSynthesis.speak(utterance2);

            utterance2.onend = function() {
                let recognition2 = new SpeechRecognition();
                recognition2.lang = "en-IN";
                recognition2.interimResults = false;
                recognition2.continuous = false;

                recognition2.start();

                recognition2.onresult = function(event) {
                    let speechResult2 = event.results[0][0].transcript.trim();
                    speechResult2 = speechResult2.replace(/\D/g, '');
                    if(speechResult2.length == 6) {
                        document.getElementById('password').value = speechResult2; 
                        console.log(speechResult2);
                    }
                };
                recognition2.onend = function() {
                    const passField = document.getElementById('password').value.trim();
                    if (!passField) {
                        askPasswordAgain();
                    } else {
                        document.getElementById('loginForm').submit();
                    }
                };
            };
        }

        function askPasswordAgain() {
            const retryUtterance2 = new SpeechSynthesisUtterance("Password is invalid. The password should be 6 digits.");
            retryUtterance2.lang = "en-IN";
            speechSynthesis.speak(retryUtterance2);
            retryUtterance2.onend = function() {
                askPassword();
            };
        }

        
    </script>  
</body>
</html>
