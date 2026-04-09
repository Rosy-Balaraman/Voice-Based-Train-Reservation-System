<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            background-color: #f0f8ff;
            color: #000;
            margin: 0;
            padding: 20px;
            font-family: 'Verdana', sans-serif; 
            text-align: center; 
        }

        h3 {
            color: #333;
            margin-bottom: 10px;
            font-size:20px;
        }

        form {
            background-color: white;
            border: 2px solid #000080;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
            max-width: 600px;
            width: 100%;
            margin: 20px auto;
            text-align: left; 
        }

        input[type="text"], input[type="email"] 
        {
            width:200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size:20px;
        }

        button {
            background-color: #000080;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px; 
        }

        button:hover {
            background-color: #003366;
        }

        .button-container {
            text-align: right; 
        }
        .not {
            cursor: not-allowed;
        }
        #error {
            color: red;
            font-size: 15px;
            display: none;
            margin-top: -15px; 
            margin-left: 240px;
        }


    </style>
</head>
<body>
<?php  
    $email1 = $_SESSION['email'];
    $query = "SELECT f_name, l_name, dob, gender, door_no, area, city, state, country, ph_no, email FROM user WHERE email='$email1';";
    $result = mysqli_query($con, $query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) 
        {
            $f_name = $row["f_name"];
            $l_name = $row["l_name"];
            $dob = $row["dob"];
            $gender = $row["gender"];
            $door_no = $row["door_no"];
            $area = $row["area"];
            $city = $row["city"];
            $state = $row["state"];
            $country = $row["country"];
            $ph_no = $row["ph_no"];
            $email = $row["email"];
        }
    }
?>
<h1>PROFILE EDIT</h1>
    <form action="" method="post">
        <h3><pre>Passenger Name     : <input type="text" class="not" value="<?php echo htmlspecialchars($f_name); ?>" readonly></pre></h3>
        <h3><pre>Last Name          : <input type="text" class="not" value="<?php echo htmlspecialchars($l_name); ?>" readonly></pre></h3>
        <h3><pre>Gender             : <input type="text" class="not" value="<?php echo htmlspecialchars($gender); ?>" readonly></pre></h3>
        <h3><pre>Date of Birth      : <input type="text" class="not" value="<?php echo htmlspecialchars($dob); ?>" readonly></pre></h3>
        <h3><pre>Door No            : <input type="text" name="door_no" value="<?php echo htmlspecialchars($door_no); ?>"></pre></h3>
        <h3><pre>Area               : <input type="text" name="area" value="<?php echo htmlspecialchars($area); ?>"></pre></h3>
        <h3><pre>City               : <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>"></pre></h3>
        <h3><pre>State              : <input type="text" name="state" value="<?php echo htmlspecialchars($state); ?>"></pre></h3>
        <h3><pre>Country            : <input type="text" name="country" value="<?php echo htmlspecialchars($country); ?>"></pre></h3>
        <h3><pre>Mobile             : <input type="text" name="ph_no" value="<?php echo htmlspecialchars($ph_no); ?>"></pre></h3>
                                      <p id="error" style="display:none"></p>
        <h3><pre>Email              : <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"></pre></h3>
        
        <div class="button-container">
            <button type="button" onclick="back()">Cancel</button>
            <button name="update">Update</button>
        </div>
    </form>
    
    <script>
        function back() {
            window.location.href = "passenger.php";
        }
    </script>

    <?php 
    if (isset($_POST['update'])) {
        $door_no = $_POST['door_no'];
        $area = $_POST['area'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $ph_no = $_POST['ph_no'];
        $email = $_POST['email'];
        
        if (strlen($ph_no) === 10 && is_numeric($ph_no)) 
        {
            $up = "UPDATE user SET door_no='$door_no', area='$area', city='$city', state='$state', country='$country', ph_no='$ph_no', email='$email' WHERE email='$email1';";
            $result_up = mysqli_query($con, $up);
            if ($result_up)
            {
                echo "<script>alert('Values updated successfully');</script>";
            } 
            else 
            {
                echo "<script>alert('Oops... Something went wrong');</script>";
            }
        } 
        else 
        {
            echo '<script>
            document.getElementById("error").style.display="block";
            document.getElementById("error").innerHTML="Please Enter 10 Digit Number";</script>';
        }
    }
    ?>
</body>
</html>
