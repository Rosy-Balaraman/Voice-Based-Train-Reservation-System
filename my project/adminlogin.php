<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            color: navy;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: blue;
        }
        .login-container a {
            display: inline-block;
            margin-top: 15px;
            color: navy;
            text-decoration: none;
        }
        .login-container p {
            color: red;
        }
        
        #home{
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
<a href="home.php" id="home">Home</a>
    <div class="login-container">
        <h1>Sign In</h1>
        <form action="" method="post">
            <input type="text" placeholder="Enter Your Username" name="username" required>
            <input type="password" placeholder="Enter Your Password" name="password" required>
            <button name="check">Sign In</button>
        </form>
        <?php
        if(isset($_POST['check'])) 
        {
            $username=$_POST['username'];
            $password=$_POST['password'];
            $q = "SELECT username, password FROM admin WHERE username = '$username' AND password = '$password';";
            $res=mysqli_query($con,$q);
            if($res->num_rows>0) 
            {            
                echo "<script>alert('successfully logged in');</script>";
                header("Location: admin.php");
                exit();
            } 
            else 
            {
                echo '<p>Invalid Email or Password</p>';
            }
        }
        ?>
    </div>
</body>
</html>
