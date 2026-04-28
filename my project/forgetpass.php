<?php
session_start();
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$showVerificationForm = false;
$error = "";

if (isset($_POST['send_email']))
{
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $randomnum = rand(100000, 999999); 

        $_SESSION['randomnum'] = $randomnum;
        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);

        try
        {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // For security reasons, the email and password will be hidden.
            $mail->Username = '';
            $mail->Password = ''; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('cheerfulljourney.rrs@gmail.com', 'Cheerful Journey');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Email for reset Password';
            $mail->Body = 'Your Verification code is: ' . $randomnum . ' Don\'t share your code with anyone.';

            $mail->send();
            $showVerificationForm = true; 
        } 
        catch (Exception $e)
        {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } 
    else
    {
        $error = "Invalid email address.";
    }
}

if (isset($_POST['change']))
{
    $code = $_POST['code'];

    if (isset($_SESSION['randomnum']) && $_SESSION['randomnum'] == $code)
    {
        header("Location: changeforgetpass.php");
        exit();
    }
    else 
    {
        $error = 'Your Verification Code is wrong';  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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

        input[type="email"], input[type="text"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
            background-color: #f9f9f9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
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

        .hidden {
            display: none;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        #back {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <a href="login.php" id="back"><img src="back2.webp" alt="Back" width="100px" height="100px"></a>
    
    <div class="container">
        <h1>Reset Password</h1>

        <form action="" method="post" id="emailForm" class="<?= $showVerificationForm ? 'hidden' : '' ?>">
            <input type="email" placeholder="Enter your email" name="email" required>
            <button name="send_email">Send Verification Code</button>
        </form>

        <form action="" method="post" id="verificationForm" class="<?= $showVerificationForm ? '' : 'hidden' ?>">
            <input type="text" placeholder="Enter Verification code" name="code" required>
            <button type="submit" name="change">Change Password</button>
        </form>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?= $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
