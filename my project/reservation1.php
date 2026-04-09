<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2; /* Light gray background */
        }
        h1 {
            text-align: center;
            color: navy;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 400px;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: 100%; /* Full width */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
        }
        button {
            width: 100%; /* Full width */
            padding: 10px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: darkblue;
        }
        #home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 18px;
            text-decoration: none;           
            background-color: #000080; 
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <a href="passenger.php" id="home">Back</a>
    <h1>BOOK TICKET</h1>
    <form action="search.php" method="post">        
        <input type="text" name="from" placeholder="From" class="from" required><br>
        <input type="date" name="journey_date" id="journey_date" class="date" required><br>
        <input type="text" name="to" placeholder="To" class="from" required><br> 
        <select name="class" id="class" class="date" required>
            <option value="AC">AC</option>
            <option value="AC 2 Tier">AC 2 Tier</option>
            <option value="AC 3 Tier">AC 3 Tier</option>
            <option value="ladies">Ladies</option>
            <option value="Sleeper">Sleeper</option>
        </select><br>
        <button name="btn" id="search">Search</button>
    </form>
    <script>
        let today = new Date().toISOString().split('T')[0];
        document.getElementById('journey_date').setAttribute('min', today);
    </script>
</body>
</html>
