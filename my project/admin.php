<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .navbar {
            background-color: navy;
            padding: 10px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: blue;
            color: white;
        }
        form {
            margin: 20px 0;
            text-align: center;
        }
        button {
            border: none;
            background-color: navy;
            color: white;
            cursor: pointer;
            padding: 10px 15px;
            margin: 10px;
            font-size: 16px;
        }
        button:hover {
            background-color: blue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: navy;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
        }
        .no-passenger {
            text-align: center;
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="admintrain.php">Train Details</a>
        <a href="adminbookingdetails.php">Booking Details</a>
        <a href="checkdetails.php">Check Details</a>
        <a href="report.php">Report</a>
    </div>

    <form action="" method="post">
        <button type="submit" name="user" >User Details</button>
        <button type="submit" name="visualuser">Visually Impaired Passenger Details</button>
    </form>

    <?php
    if(isset($_POST['user'])) {

        $s = "SELECT * FROM user;";
        $result1 = mysqli_query($con, $s);
        if ($result1->num_rows > 0) {
            echo '<table border="2">
                <tr>
                    <th>Passenger ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Door No</th>
                    <th>Area</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>';
            while($row = $result1->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['p_id']}</td>
                    <td>{$row['f_name']}</td>
                    <td>{$row['l_name']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['door_no']}</td>
                    <td>{$row['area']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['state']}</td>
                    <td>{$row['country']}</td>
                    <td>{$row['ph_no']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['password']}</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<p class="no-passenger">No Passenger found</p>';
        }
    }

    if(isset($_POST['visualuser'])) {
        $con = mysqli_connect("localhost:3306", "root", "system", "cheerfuljourney");
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $s = "SELECT * FROM visuser;";
        $result1 = mysqli_query($con, $s);
        if ($result1->num_rows > 0) {
            echo '<table border="2">
                <tr>
                    <th>Passenger ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Phone Number</th>
                    <th>Certificate Number</th>
                    <th>Aadhar Number</th>
                    <th>Favourite Pet</th>
                    <th>Birth Place</th>
                    <th>Password</th>
                </tr>';
            while($row = $result1->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['p_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['phn_no']}</td>
                    <td>{$row['cer_id']}</td>
                    <td>{$row['aadhar']}</td>
                    <td>{$row['pet']}</td>
                    <td>{$row['birth']}</td>
                    <td>{$row['password']}</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<p class="no-passenger">No Passenger found</p>';
        }
    }
    ?>
</body>
</html>
