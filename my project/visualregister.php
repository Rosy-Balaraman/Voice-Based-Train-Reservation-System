<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-text {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Register</h1>
        <form action="visualdb.php" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Please say or spell your name..." id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" placeholder="Please say your gender..." id="gender" name="gender" required>
            </div>
            <div class="form-group">
                <label for="mob">Mobile Number</label>
                <input type="text" placeholder="Please say your mobile number..." id="mob" name="mob" required>
            </div>
            <div class="form-group">
                <label for="cer_id">Certificate Number</label>
                <input type="text" placeholder="Please say your certificate number..." id="cer_id" name="cer_id" required>
            </div>
            <div class="form-group">
                <label for="aadhar">Aadhar Number</label>
                <input type="text" placeholder="Please say your Aadhar number..." id="aadhar" name="aadhar" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" placeholder="Set a password..." id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_pass">Confirm Password</label>
                <input type="password" placeholder="Confirm your password..." id="confirm_pass" name="confirm_pass" required>
            </div>
            <div class="form-group">
                <label for="pet">Pet Animal Name</label>
                <input type="text" placeholder="Say your pet's name..." id="pet" name="pet" required>
            </div>
            <div class="form-group">
                <label for="birth">Birthplace Name</label>
                <input type="text" placeholder="Say your birthplace..." id="birth" name="birth" required>
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
<script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        document.addEventListener("DOMContentLoaded", function()
        {    
            askName();
        });

        function askName() 
        {
            const utterance = new SpeechSynthesisUtterance("Please say your name and spell it out.");
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
                    if(speechResult)
                    {
                        document.getElementById('name').value = speechResult;
                    }
                    else
                    {
                        askNameAgain();
                    }
                    
                };
                recognition.onend = function() 
                {
                    const nameField = document.getElementById('name').value.trim();
                    if (!nameField) 
                    {
                        askNameAgain();
                    }
                    else
                    {
                        askgender();
                    }
                };
            };
        }
        function askNameAgain() 
        {
            const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() 
            {
            askName();
            };
        }

        function askgender()
        {
             const utterance2 = new SpeechSynthesisUtterance("Please say your gender");
            utterance2.lang = "en-IN";
            speechSynthesis.speak(utterance2);

            utterance2.onend = function() 
            {
                let recognition2= new SpeechRecognition();
                recognition2.lang = "en-IN";
                recognition2.interimResults = false;
                recognition2.continuous = false;

                recognition2.start();

                recognition2.onresult = function(event)
                {
                    let speechResult2 = event.results[0][0].transcript.trim();
                    speechResult2 = speechResult2.replace(/\.$/, '');
                    if (speechResult2.toLowerCase() === 'mail') {
                    speechResult2 = 'male';
                    }
                    if (speechResult2) {
                    document.getElementById('gender').value = speechResult2;
                    }
                    else 
                    {
                    askgenderAgain();
                    }
                };
                recognition2.onend = function() {
                    const genderField = document.getElementById('gender').value.trim();
                    if (!genderField) 
                    {
                        askgenderAgain();
                    }
                    else
                    {
                        askNumber();
                    }
                };
            };
        }
        function askgenderAgain() 
        {
            const retryUtterance2 = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance2.lang = "en-IN";
            speechSynthesis.speak(retryUtterance2);
            retryUtterance2.onend = function() 
            {
            askgender();
            };
        }

        function askNumber()
        {
            const utterance3 = new SpeechSynthesisUtterance("Please say your Mobile Number");
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
                    let speechResult3 = event.results[0][0].transcript.trim();
                    speechResult3 = speechResult3.replace(/\D/g, '');
                    if(speechResult3.length === 10 && speechResult3.charAt(0) !== '0' && speechResult3.charAt(0) !== '1')
                    {
                        document.getElementById('mob').value = speechResult3; 
                    }
                };
                recognition3.onend = function() 
                {
                    const mobField = document.getElementById('mob').value.trim();
                    if (!mobField) 
                    {
                        askMobAgain();
                    }
                    else
                    {
                        askcernum();
                    }
                };
            };
        }
        function askMobAgain() 
        {
            const retryUtterance3 = new SpeechSynthesisUtterance("Number is invalid...the number should be 10 digit");
            retryUtterance3.lang = "en-IN";
            speechSynthesis.speak(retryUtterance3);
            retryUtterance3.onend = function() 
            {
            askNumber();
            };
        }
        
        function askcernum()
        {
            const utterance4 = new SpeechSynthesisUtterance("Please say your Certificate Number");
            utterance4.lang = "en-IN";
            speechSynthesis.speak(utterance4);

            utterance4.onend = function() 
            {
                let recognition4= new SpeechRecognition();
                recognition4.lang = "en-IN";
                recognition4.interimResults = false;
                recognition4.continuous = false;

                recognition4.start();

                recognition4.onresult = function(event)
                {
                    let speechResult4 = event.results[0][0].transcript.trim();
                    speechResult4 = speechResult4.replace(/\D/g, '');
                    if(speechResult4)
                    {
                        document.getElementById('cer_id').value = speechResult4; 
                    }
                };
                recognition4.onend = function() 
                {
                    const cerField = document.getElementById('cer_id').value.trim();
                    if (!cerField) 
                    {
                        askCerAgain();
                    }
                    else
                    {
                        askAadhar();
                    }
                };
            };
        }
        function askCerAgain() 
        {
            const retryUtterance4 = new SpeechSynthesisUtterance("Number is invalid...");
            retryUtterance4.lang = "en-IN";
            speechSynthesis.speak(retryUtterance4);
            retryUtterance4.onend = function() 
            {
            askcernum();
            };
        }

        function askAadhar()
        {
            const utterance5 = new SpeechSynthesisUtterance("Please say your Aadhar card Number");
            utterance5.lang = "en-IN";
            speechSynthesis.speak(utterance5);

            utterance5.onend = function() 
            {
                let recognition5= new SpeechRecognition();
                recognition5.lang = "en-IN";
                recognition5.interimResults = false;
                recognition5.continuous = false;

                recognition5.start();

                recognition5.onresult = function(event)
                {
                    let speechResult5 = event.results[0][0].transcript.trim();
                    speechResult5 = speechResult5.replace(/\D/g, '');
                    if(speechResult5.length === 12)
                        document.getElementById('aadhar').value = speechResult5; 
                };
                recognition5.onend = function() 
                {
                    const aadharField = document.getElementById('aadhar').value.trim();
                    if (!aadharField) 
                    {
                        askaadharAgain();
                    }
                    else
                    {
                       askpassword();
                    }
                };
            };
        }
        function askaadharAgain() 
        {
            const retryUtterance5 = new SpeechSynthesisUtterance("Number is invalid...");
            retryUtterance5.lang = "en-IN";
            speechSynthesis.speak(retryUtterance5);
            retryUtterance5.onend = function() 
            {
            askAadhar();
            };
        }
        let passwordValue = "";
        function askpassword()
        {
            const utterance9 = new SpeechSynthesisUtterance("Set the password...Please say 6 digit Number");
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
                        askpet();
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


        
        function askpet()
        {
            const utterance6 = new SpeechSynthesisUtterance("What is the name of your first pet or favorite animal?");
            utterance6.lang = "en-IN";
            speechSynthesis.speak(utterance6);

            utterance6.onend = function() 
            {
                let recognition6= new SpeechRecognition();
                recognition6.lang = "en-IN";
                recognition6.interimResults = false;
                recognition6.continuous = false;

                recognition6.start();

                recognition6.onresult = function(event)
                {
                    let speechResult6 = event.results[0][0].transcript.trim();
                    speechResult6 = speechResult6.replace(/\.$/, '');
                    if(speechResult6)
                    {
                        document.getElementById('pet').value = speechResult6; 
                    }
                };
                recognition6.onend = function() 
                {
                    const petField = document.getElementById('pet').value.trim();
                    if (!petField) 
                    {
                        askpetAgain();
                    }
                    else
                    {
                        askbirth();
                    }
                };
            };
        }
        function askpetAgain() 
        {
            const retryUtterance6 = new SpeechSynthesisUtterance("I didn't catch that...");
            retryUtterance6.lang = "en-IN";
            speechSynthesis.speak(retryUtterance6);
            retryUtterance6.onend = function() 
            {
            askpet();
            };
        }
        
        function askbirth()
        {
            const utterance8 = new SpeechSynthesisUtterance("What is the name of your birth place ?");
            utterance8.lang = "en-IN";
            speechSynthesis.speak(utterance8);

            utterance8.onend = function() 
            {
                let recognition8 = new SpeechRecognition();
                recognition8.lang = "en-IN";
                recognition8.interimResults = false;
                recognition8.continuous = false;

                recognition8.start();

                recognition8.onresult = function(event)
                {
                    let speechResult8 = event.results[0][0].transcript.trim();
                    speechResult8 = speechResult8.replace(/\.$/, '');
                    if(speechResult8)
                    {
                        document.getElementById('birth').value = speechResult8; 
                    }
                };
                recognition8.onend = function() 
                {
                    const birthField = document.getElementById('birth').value.trim();
                    if (!birthField) 
                    {
                        askbirthAgain();
                    }
                    else
                    {
                       register();
                    }
                };
            };
        }
        function askbirthAgain() 
        {
            const retryUtterance8 = new SpeechSynthesisUtterance("I didn't catch that...");
            retryUtterance8.lang = "en-IN";
            speechSynthesis.speak(retryUtterance8);
            retryUtterance8.onend = function() 
            {
            askbirth();
            };
        }

        function register()
        {
            const voice=new SpeechSynthesisUtterance("Register Successfully...");
            voice.lang="en-IN";
            speechSynthesis.speak(voice);
            voice.onend = function() {
            document.querySelector('form').submit(); 
            };
        }
    
    </script>
</body>
</html>