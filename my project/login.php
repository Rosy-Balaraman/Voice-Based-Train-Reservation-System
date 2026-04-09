<?php
session_start();
include 'db.php';
    $error = ''; // Variable to store error message
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $_SESSION['email'] = $email;  
        if ($con) {
            $query = "SELECT f_name, l_name FROM user WHERE email = '$email' AND password = '$password';";
            $result = mysqli_query($con, $query);
            if ($result->num_rows > 0) {            
                $row = $result->fetch_assoc();
                $_SESSION['f_name'] = $row['f_name'];
                $_SESSION['l_name'] = $row['l_name'];
                header("Location: passenger.php");
                exit();
            } else {
                $error = 'Invalid Email or Password';
            }
        } else {
            $error = 'Database connection failed';
        }
        $con->close();
    }
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('train pic.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 400px;
            height:420px;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="email"], input[type="password"] {
            width: 70%;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 20px;
            background-color: #f9f9f9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 70%;
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

        .links {
            margin-top: 30px;
        }

        .links a {
            color: #0072ff;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .container p {
            margin: 0;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 18px;
            text-decoration: none;
            background-color: blue;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .forget-password {
            text-align: left;
            padding-left: 50px;
            margin: 10px 0;
            display: block;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="index.php" class="home-link">Home</a>
    <div class="container">
        <form action="" method="post">
            <h1>LOGIN</h1>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Enter Your Email" required>
            <input type="password" name="password" placeholder="Enter Your Password" required>
            <a href="forgetpass.php" class="forget-password">Forget Password?</a><br>
            <button type="submit">SIGN IN</button>
            <div class="links">
                <p>Don't have an account? <a href="register.php">REGISTER</a></p>
            </div>
        </form>
    </div>
</body>
</html>
