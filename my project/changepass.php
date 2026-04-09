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
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .container {
            background-color: #fff;
            border: 2px solid #000080;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 380px;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #000080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
        }

        button:hover {
            background-color: #003366;
        }

        button[type="button"] {
            background-color: #ff6666;
        }

        button[type="button"]:hover {
            background-color: #cc0000;
        }

        .form-group {
            text-align: left;
        }

        .form-group p {
            margin: 5px 0;
            font-size: 16px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        #error {
            color: red;
            font-size: 15px;
            display: none;
            margin-top: 10px; /* Give it space from the form */
        }
    </style>
</head>
<body>

    <h2>Change Password</h2>

    <div class="container">
        <form action="" method="post">
            <div class="form-group">
                <p>Old Password</p>
                <input type="password" placeholder="Enter Old Password" name="old_pass" value="<?php if(isset($_POST['old_pass'])) { echo $_POST['old_pass']; } ?>">
            </div>
            <div class="form-group">
                <p>New Password</p>
                <input type="password" placeholder="New Password" name="new_pass" value="<?php if(isset($_POST['new_pass'])) { echo $_POST['new_pass']; } ?>">
            </div>
            <div class="form-group">
                <p>Confirm Password</p>
                <input type="password" placeholder="Confirm Password" name="confirm_pass" value="<?php if(isset($_POST['confirm_pass'])) { echo $_POST['confirm_pass']; } ?>">
            </div>
            
            <p id="error"></p> <!-- Error message moved here below all inputs -->

            <div class="button-container">
                <button type="button" onclick="back()">Cancel</button>
                <button name="change">Change</button>
            </div>
        </form>
    </div>

    <script>
        function back() {
            window.location.href = "passenger.php";
        }
    </script>

    <?php 
     $email = $_SESSION['email'];
    if(isset($_POST['change'])) {
        $old_pass = $_POST['old_pass'];
        $query = "SELECT password FROM user WHERE email='$email';";
        $result = mysqli_query($con, $query);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $password = $row["password"];
                if($password == $old_pass) {
                    $new_pass = $_POST['new_pass'];
                    $confirm_pass = $_POST['confirm_pass'];
                    if($new_pass == $confirm_pass) {
                        $query1 = "UPDATE user SET password='$new_pass' WHERE email='$email';";
                        if(mysqli_query($con, $query1)) {
                            echo '<script>alert("Password changed successfully!");
                            window.location.href="passenger.php"</script>';
                        }
                    } else {
                        echo '<script>document.getElementById("error").style.display="block";
                            document.getElementById("error").innerHTML="New Password and Confirm Password do not match."</script>';
                    }
                } else {
                    echo '<script>document.getElementById("error").style.display="block";
                            document.getElementById("error").innerHTML="Your old password is incorrect.";</script>';
                }
            }
        }
    }
    ?>
</body>
</html>
