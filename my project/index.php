<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Railway Reservation System</title>
</head>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background-image:url('train pic.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        nav {
            background-color: transperant;
            backdrop-filter:blur(40px);
            padding: 15px;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            text-shadow: 0 0 5px black;
            font-weight: bold;
            font-size: 20px;
            transition: 0.5s;
        }

        nav ul li a:hover {
            background-color: white;
            color: rgb(0,1,32);
            padding: 15px;
            font-size: 22px;
            text-shadow: none;
            border-radius: 30px;
        }

        h1 {
            text-align: center;
            margin: 30px 0;
            color: rgb(0,1,32);
        }

        #paragraph {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: transperant;
            backdrop-filter:blur(40px);
            box-shadow: 0 10px 20px black;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #paragraph p {
            text-align: justify;
            color: rgb(0,1,32);
            text-shadow: 0 0 1px black;
            background-color: transperant;
            backdrop-filter:blur(40px);
        }
        #popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        #pop-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #pop-content h1 {
            color: #ff6b81; 
        }

        #pop-content p {
            margin-bottom: 20px;
            color: #333;
        }

        #pop-content button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            background-color: #ff6b81;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        #pop-content button:hover {
            background-color: #ff4d6d;
        }
    </style>
<body>
<nav>
  <ul>
    <li>
    <a href="index.php" id="Home" >Home</a>     
    </li>
    <li>
    <a href="login.php" id="login" >login</a>    
    </li>
    <li>
    <a href="register.php" id="register" >Register</a>    
    </li>
    <li>
    <a href="reservation.php" id="reservation" >Reservation</a> 
    </li>
    <li>
    <a href="visual.php" id="visual" >visual</a>   
    </li>
    <li>
    <a href="adminlogin.php" id="agent" >Admin</a>   
    </li>
  </ul>                      
</nav>
<h1> CHEERFULL JOURNEY </h1>
<div id="paragraph">   
  <p>Welcome to our Train Ticket Reservation System, an inclusive platform designed for both regular and visually impaired users. Our system allows easy ticket booking with features like voice-assisted navigation for the visually impaired, real-time seat availability checks, and secure booking management. Whether you’re booking tickets, checking schedules, or canceling reservations, our platform ensures a seamless and accessible experience for all travelers.
    </p>
    </div>

<div id="popup">
  <div id="pop-content">
            <h1>CHEERFULL JOURNEY</h1><br><br>
            <p id="t">are you visually impaired person?..Please say yes or no</p><br>
            <button onclick="otherpage()" id="con">yes</button>
            <button onclick="closepopup()" id="go">no</button>
</div>
</div>
<script>
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  document.addEventListener("DOMContentLoaded",function(){show();});

  let rec = new SpeechRecognition();
  const synth=window.speechSynthesis;

  function show()
  {
    const utterance = new SpeechSynthesisUtterance("are you visually impaired person?..Please say yes or no");
    utterance.lang = "en-IN";     
    speechSynthesis.speak(utterance);

    utterance.onend = function() 
    {
      console.log("Speech ended.");
      rec.start();
    };

    utterance.onerror = function(event) 
    {
      console.error("SpeechSynthesis error:", event.error);
    };
    
    document.getElementById("popup").style.display="flex";
   
  }
  rec.onresult = function(event) 
  {
      const speechResult = event.results[0][0].transcript.toLowerCase();
      if (speechResult.includes("no"))
      {
        closepopup();
      }
      else if(speechResult.includes("yes")) 
      {
          window.location.href = 'visual.php';
      }
  };
  rec.onerror = function(event) 
  {
    console.error("SpeechRecognition error:", event.error);
};
  function closepopup()
  {
    synth.cancel();
    rec.stop();    
    document.getElementById("popup").style.display="none";
  }
  function otherpage()
  {
    synth.cancel();
    rec.stop(); 
    window.location.href='visual.php';
  }
</script>   

</body>
</html>

