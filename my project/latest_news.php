<?php 
    session_start();
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="admin.php">Back</a>
    <form action="" method="post">
        <textarea name="first_news" id="first_news" rows="4" cols="50">
        </textarea><br><br>
        <button name="submit">Submit</button>
    </form>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $first_news=$_POST['first_news'];
        $_SESSION['first_news'] = $first_news;
    }
    ?>
</body>
</html>