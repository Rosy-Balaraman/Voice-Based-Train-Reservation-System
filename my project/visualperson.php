<?php
session_start();
include 'include.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: navy;
            margin-bottom: 20px;
            font-size: 36px;
        }
        nav {
            margin-bottom: 20px;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: navy;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px;
            font-size: 18px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: darkblue;
        }
        #paragraph {
            width: 70%;
            margin: 30px auto;
            background-color: #e6e6f7; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top:100px;
        }

        #paragraph p {
            font-size: 18px;
            line-height: 1.6;
            text-align: justify;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['name'])) {
            $name = htmlspecialchars($_SESSION['name']);
            $p_id = htmlspecialchars($_SESSION['p_id']);
            echo '<h1>Welcome ' . $name . '</h1>';
            echo '<script>
                const utterance = new SpeechSynthesisUtterance("login Successfully...Welcome ' . $name . ', how can I help you?");
                utterance.lang = "en-IN";
                utterance.onend = function() {
                    rec(); 
                };            
                speechSynthesis.speak(utterance);
            </script>';
        }
        ?>
        
        <nav>
            <a href="index1.php">Home</a>
            <a href="visualreservation.php">Reservation</a>
            <a href="visualviewtrain.php">View Train Info</a>
            <a href="visualhistory.php">Booking History</a>
            <a href="visualseat.php">Seat Availability</a>             
            <a href="visualwallet.php">Wallet</a>
            <a href="visualreward.php">Reward Points</a>
            <a href="visualticketgeneration.php">View Ticket</a>
            <a href="visualProfile.php">Profile</a>  
            <a href="visualpasschange.php">Change Password</a> 
            <a href="visualcancelticket.php">Cancel Ticket</a>
            <a href="Logout.php">Logout</a>
        </nav>
    </div>
    
    <div id="paragraph">
        <p>Welcome to our Train Ticket Reservation System, designed to provide a seamless and user-friendly experience for all passengers. Whether you are booking a ticket, checking seat availability, or managing your reservations, our platform is here to assist you every step of the way. With a focus on accessibility, we ensure that visually impaired individuals can navigate our services with ease. Enjoy hassle-free travel planning, and let us help you reach your destination comfortably and efficiently!</p>
    </div>
  
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        function rec() {
            let recognition = new SpeechRecognition();
            recognition.lang = "en-IN";
            recognition.interimResults = false;
            recognition.continuous = false;

            recognition.start();

            recognition.onresult = function(event) {
                const speechResult = event.results[0][0].transcript.toLowerCase();
                console.log("Recognized speech:", speechResult); 

                if (speechResult.includes("booking history") || speechResult.includes("history") || speechResult.includes("see booking history") || speechResult.includes("i want to see booking history")) {
                    window.location.href = 'visualhistory.php';
                } else if (speechResult.includes("book a ticket") || speechResult.includes("booking ticket") || speechResult.includes("i want to book a ticket")) {
                    window.location.href = 'visualreservation.php';
                } else if (speechResult.includes("search train") || speechResult.includes("train search") || speechResult.includes("view train")) {
                    window.location.href = 'visualviewtrain.php';
                } else if (speechResult.includes("check seat availability") || speechResult.includes("seat") || speechResult.includes("check seat")) {
                    window.location.href = 'visualseat.php';
                } else if (speechResult.includes("see my profile") || speechResult.includes("my profile") || speechResult.includes("profile")) {
                    window.location.href = 'visualprofile.php';
                }
                else if (speechResult.includes("change my password") || speechResult.includes("change password") || speechResult.includes("i want to change my password")) {
                    window.location.href = 'visualpasschange.php';
                }
                else if (speechResult.includes("logout") || speechResult.includes("i want to logout")) {
                    window.location.href = 'logout.php';
                } else if (speechResult.includes("wallet") || speechResult.includes("see my wallet") || speechResult.includes("i want to see my wallet") || speechResult.includes("i want to know my wallet account")) {
                    window.location.href = 'visualwallet.php';
                }
                else if (speechResult.includes("reward points") || speechResult.includes("see my reward points") || speechResult.includes("i want to see my rewards") || speechResult.includes("i want to know my reward points")) {
                    window.location.href = 'visualwallet.php';
                }
                else if (speechResult.includes("View ticket") || speechResult.includes("see my ticket") || speechResult.includes("i want to see my ticket") || speechResult.includes("i want to know my ticket")) {
                    window.location.href = 'visualwallet.php';
                }
            };
        }
    </script>
</body>
</html>
