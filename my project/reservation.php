<?php
session_start();
include 'db.php';
$loginSuccess = false; // Initialize the loginSuccess variable
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT f_name, l_name, email FROM user WHERE email='$email' AND password='$password';";
    $result = mysqli_query($con, $query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['f_name'] = $row['f_name'];
        $_SESSION['l_name'] = $row['l_name'];
        $_SESSION['email'] = $row['email'];
        $loginSuccess = true; 
    } 
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #003366;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 25px;
            color: black;
            padding-left: 10px;
        }

        #popup {
            display: <?php echo $loginSuccess ? 'none' : 'flex'; ?>;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;        
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            justify-content: center;
            align-items: center;
        }    

        #pop-content {
            background-color: rgba(255, 192, 203, 0.9);
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            width: 350px;
            height: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #pop-content h1 {
            margin: 0;
            font-size: 1.8em;
            color: #003366;
        }

        input[type="email"], 
        input[type="password"], 
        input[type="text"], 
        input[type="date"], 
        select {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
            width: 80%;
        }

        button:hover {
            background-color: #00509e;
        }

        .button-container {
            margin-top: 20px;
        }

        .reg {
            color: #ff6600;
            text-decoration: none;
        }

        .reg:hover {
            text-decoration: underline;
        }

        .booking-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        #error {
            color: red;
            font-size: 15px;
            display: <?php echo ($_SERVER["REQUEST_METHOD"] == "POST" && !$loginSuccess) ? 'block' : 'none'; ?>;
            margin-top: -8px; 
        }

    </style>
</head>
<body>

<div id="popup">
    <div id="pop-content">
        <form action="" method="post">
            <h1>LOGIN</h1>
            <input type="email" name="email" placeholder="Enter Your Email" required>
            <input type="password" name="password" placeholder="Enter Your Password" required> 
            <p id="error">Invalid Email or Password</p>
            <button type="submit">SIGN IN</button>
            <p>Don't have an account? <a href="register.php" class="reg">REGISTER</a></p>      
        </form>
    </div>
</div>

<div class="booking-container">
    <h1>BOOK TICKET</h1>
    <form action="search.php" method="post">        
        <input type="text" name="from" placeholder="From" required>
        <input type="date" name="journey_date" id="journey_date" placeholder="Date" required>
        <input type="text" name="to" placeholder="To" required> 
        <select name="class" id="class" required>         
            <option value="AC">AC</option>
            <option value="AC 2 Tier">AC 2 Tier</option>
            <option value="AC 3 Tier">AC 3 Tier</option>
            <option value="ladies">Ladies</option>
            <option value="Sleeper">Sleeper</option>
        </select>
        <button name="btn" id="search">Search</button>
    </form>
</div>

<script>
    let today = new Date().toISOString().split('T')[0];
    document.getElementById('journey_date').setAttribute('min', today);
</script>
</body>
</html>
