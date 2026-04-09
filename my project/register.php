<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            padding-left:100px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }

        h1 {
            font-size: 28px;
            color: navy;
            text-align: center;
        }

        input[type="text"], input[type="date"], input[type="password"], input[type="email"] {
            width: 80%;
            padding: 10px;
            justify-content:center;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        label {
            font-size: 16px;
            color: #333;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        .check {
            margin-top: 10px;
        }

        .check + span {
            font-size: 14px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            width: 80%;
            padding: 12px;
            font-size: 18px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-home {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-home a {
            font-size: 16px;
            color: #333;
            text-decoration: none;
        }

        .back-home a:hover {
            color: navy;
        }

        .radio-group 
        {
            display: flex;
            margin-bottom: 10px;
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
        .error {
            color: red;
            display: none;
            margin-top: -5px; 
            text-align:center;
        }
    </style>
</head>
<body>

<a href="index.php" id="home">Home</a>

<h1>REGISTER</h1>
<div class="container">
<h2 id="regerror" class="error"  style="display:none"></h2>

    <div class="back-home">
    </div>
    <form action="" method="post">
        <input type="text" name="f_name" placeholder="First Name" required value="<?php echo isset($_POST['f_name']) ? $_POST['f_name'] : ''; ?>">
        <input type="text" name="l_name" placeholder="Last Name" value="<?php echo isset($_POST['l_name']) ? $_POST['l_name'] : ''; ?>">
        <input type="date" name="dob" id="dob" placeholder="Date of Birth" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ''; ?>">
        
        <div class="radio-group">
            <label><input type="radio" name="gender" value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Male') echo 'checked'; ?>> Male</label>
            <label><input type="radio" name="gender" value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Female') echo 'checked'; ?>> Female</label>
            <label><input type="radio" name="gender" value="Transgender" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Transgender') echo 'checked'; ?>> Transgender</label>
        </div>

        <input type="text" name="door no" placeholder="Door No" required value="<?php echo isset($_POST['door_no']) ? $_POST['door_no'] : ''; ?>">
        <input type="text" name="area" placeholder="Area" required value="<?php echo isset($_POST['area']) ? $_POST['area'] : ''; ?>">
        <input type="text" name="city" placeholder="City" required value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''; ?>">
        <input type="text" name="state" placeholder="State" required value="<?php echo isset($_POST['state']) ? $_POST['state'] : ''; ?>">
        <input type="text" name="country" placeholder="Country" required value="<?php echo isset($_POST['country']) ? $_POST['country'] : ''; ?>">
        <input type="text" name="aadhar" placeholder="Aadhar Number" required value="<?php echo isset($_POST['aadhar']) ? $_POST['aadhar'] : ''; ?>">
        <p id="aadharerror" class="error" style="display:none"></p>
        <input type="text" name="ph_no" placeholder="Mobile No" required value="<?php echo isset($_POST['ph_no']) ? $_POST['ph_no'] : ''; ?>">
        <p id="phnerror" class="error" style="display:none"></p>
        <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        <input type="password" name="password" placeholder="Password" required value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
        <input type="password" name="confirmpassword" placeholder="Confirm Password" required value="<?php echo isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : ''; ?>">
        <p id="conerror"class="error"  style="display:none"></p>
        <br>
        <input class="check" type="checkbox" name="request" required <?php if (isset($_POST['request'])) echo 'checked'; ?>>
        <span>I agree to the <a href="terms.html">terms and conditions</a></span>

        <br><br>
        <button type="submit" name="signin">SIGN IN</button>
    </form>
</div>

</body>
</html>


<?php
if(isset($_POST['signin']))
{
   
    $f_name=$_POST['f_name'];
    $l_name=$_POST['l_name'];
    $dob=$_POST['dob'];
    $gender=$_POST['gender'];
    $door_no=$_POST['door_no'];
    $area=$_POST['area'];
    $city=$_POST['city'];
    $state=$_POST['state'];
    $country=$_POST['country'];
    $aadhar=$_POST['aadhar'];
    $ph_no=$_POST['ph_no'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirmpassword=$_POST['confirmpassword'];
    if(strlen($aadhar)===12 && is_numeric($aadhar))
    {
        if($password==$confirmpassword)
        {
           if(strlen($ph_no)===10 && is_numeric($ph_no)) 
           {
           $phn_no=$ph_no;
           $q="select u.email,u.ph_no,v.phn_no from user u join visuser v  WHERE u.email = '$email' OR u.ph_no = '$phn_no';";
           $r=mysqli_query($con,$q);
                if($r->num_rows>0)
                {  
                    echo '<script>
                    document.getElementById("regerror").style.display="block";
                    document.getElementById("regerror").innerHTML="Already Registered";</script>';                     
                }
                else
                {
                  $query="insert into user(f_name,l_name,dob,gender,door_no,area,city,state,country,ph_no,email,password,aadhar_no) 
                  values('$f_name','$l_name','$dob','$gender',' $door_no','$area','$city','$state','$country','$phn_no','$email','$password','$aadhar');";
                  $result_ins=mysqli_query($con,$query);
                  if($result_ins)
                  {
                    $query33="select p_id from user order by p_id desc limit 1;";
                    $result33=mysqli_query($con,$query33);
                    if ($result33->num_rows > 0)
                    {
                        while ($row = $result33->fetch_assoc()) 
                        {
                            $p_id = $row["p_id"];?>
                            <script>alert("Registered successfully")
                            window.location.href="login.php";
                            </script>
                            <?php
                        }
                    }
                  }
                  else 
                  {
                  echo '<h2>Registration failed. Please try again.</h2>';
                  }
                }
            }
            else
            {
            echo '<script>
            document.getElementById("phnerror").style.display="block";
            document.getElementById("phnerror").innerHTML="Enter a valid 10-digit phone number";</script>';            
            }
        }
        else
        {
            echo '<script>
            document.getElementById("conerror").style.display="block";
            document.getElementById("conerror").innerHTML="Password and Confirm Password do not match";</script>';
        }
    }
    else
    {
        echo '<script>
        document.getElementById("aadharerror").style.display="block";
        document.getElementById("aadharerror").innerHTML="Please Enter 12 Aadhar Digit Number";</script>';
    }
       
}
 
?>

</body>
</html>