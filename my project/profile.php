<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            background-color: #f0f8ff;
            color: #000;
            margin: 0;
            padding: 20px;
            font-family: 'Verdana', sans-serif; 
            text-align: center; 
        }

        h2 {
            color: #000080;
            margin-bottom: 20px;
        }
        
        h3 {
            margin: 10px 0;
            font-size: 22px;
            color: #333;
        }

        .profile-container {
            background-color: white;
            border: 2px solid #000080;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
            max-width: 600px;
            width: 100%;
            margin-top: 20px;
            text-align: left; 
            word-wrap: break-word;
        }

        .address {
            font-size: 20px;
            color: #555;
        }

        #home, #edit {
            width:200px;
            color: white;
            font-size: 20px;
            text-decoration: none;           
            background-color: #000080; 
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block; 
            margin: 10px;
        }

        .button-container {
            text-align: center; 
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <h2>PROFILE</h2>
    <div class="profile-container">
        <?php    
        $email = $_SESSION['email'];
        $query = "SELECT f_name, l_name, dob, gender, door_no, area, city, state, country, ph_no, email FROM user WHERE email='$email';";
        $result = mysqli_query($con, $query);
        
        if ($result && $result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()) 
            {   
                $f_name = htmlspecialchars($row["f_name"]);
                $l_name = htmlspecialchars($row["l_name"]);
                $dob = htmlspecialchars($row["dob"]);
                $gender = htmlspecialchars($row["gender"]);
                $door_no = htmlspecialchars($row["door_no"]);
                $area = htmlspecialchars($row["area"]);
                $city = htmlspecialchars($row["city"]);
                $state = htmlspecialchars($row["state"]);
                $country = htmlspecialchars($row["country"]);
                $ph_no = htmlspecialchars($row["ph_no"]);
                $email = htmlspecialchars($row["email"]);
            }
        }
        ?>
        <pre><h3>Passenger Name      : <?php echo ucfirst(strtolower($f_name)); ?></h3></pre>
        <pre><h3>Last Name           : <?php echo ucfirst(strtolower($l_name)); ?></h3></pre>
        <pre><h3>Gender              : <?php echo ucfirst(strtolower($gender)); ?></h3></pre>
        <pre><h3>Date of Birth       : <?php echo $dob; ?></h3></pre>
        <pre><h3>Mobile              : <?php echo $ph_no; ?></h3></pre>
        <pre><h3>Country             : <?php echo ucfirst(strtolower($country)); ?></h3></pre>
        <pre><h3>Email               : <?php echo $email; ?></h3></pre>
        <pre><h3>Address             : <?php echo $door_no . ", " . $area; ?></h3></pre> 
        <pre><h3>                      <?php echo ucfirst(strtolower($city)) . "," . ucfirst(strtolower($state));?></h3></pre>
    </div>

    <div class="button-container">
        <a href="passenger.php" id="home">Back</a>
        <a href="updatepro.php" id="edit">Edit</a>
    </div>

</body>
</html>
