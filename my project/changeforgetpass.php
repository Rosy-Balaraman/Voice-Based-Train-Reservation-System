<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="password"] {
            width: 75%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
            background-color: #f9f9f9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .button-container {
            display: flex; 
            justify-content: space-between; 
            width: 75%; 
            margin: 20px auto; 
        }

        button {
            width: 48%; 
            padding: 15px;
            background-color: #0072ff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #005bb5;
        }

        .error-message {
            color: red;
            margin-bottom: 10px; 
            font-size: 14px;
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <form action="" method="post">
            <?php
            $email=$_SESSION['email'];
            if(isset($_POST['change']))
            {
                $new_pass=$_POST['new_pass'];
                $confirm_pass=$_POST['confirm_pass'];
                if($new_pass==$confirm_pass)
                {
                    $query1="update user set password='$new_pass' where email='$email';";
                    if(mysqli_query($con,$query1))
                    {
                        echo'<div class="error-message">Successfully Changed.</div>';
                        header("Location: login.php");
                        exit();
                    }
                }
                else
                {
                    echo'<div class="error-message">New Password and Confirm Password do not match.</div>';
                }
            }
            ?>
            <input type="password" placeholder="New Password" name="new_pass" value="<?php if(isset($_POST['new_pass'])) { echo $_POST['new_pass']; } ?>">
            <input type="password" placeholder="Confirm Password" name="confirm_pass" value="<?php if(isset($_POST['confirm_pass'])) { echo $_POST['confirm_pass']; } ?>"><br><br>
            <div class="button-container">
                <button type="button" onclick="back()">Cancel</button>
                <button name="change">Change</button>
            </div>
        </form>
        <script>
            function back()
            {
                window.location.href="passenger.php";
            }
        </script>
  
    </div>
</body>
</html>
