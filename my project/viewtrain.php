<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Train by Number</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; 
            color: #000;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin-top:80px;
            color: #000080; 
        }

        form {
            margin-top: 20px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 2px solid #000080;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #000080; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #4169e1; 
        }

        table {
            width: 60%;
            border-collapse: collapse;
            margin: 20px auto;
            border: 2px solid navy;
        }

        table, th, td {
            border: 1px solid navy;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #000080;
            font-size:20px; 
            color: white;
        }

        td {
            background-color: #fff;
            font-weight:bold;
            font-size:20px; 
        }

        .error-message {
            color: red;
            font-size: 18px;
        }

        a {
            color: #000080;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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

<a href="passenger.php" id="home">Back</a>

    <center>
        <h1>Search By Train Number</h1><br>
        <form action="" method="post" id="trainForm">
            <input type="text" placeholder="Train Number" name="train_no" id="train_no" required>
            <button type="submit" name="btn1">Search</button>
        </form>
        <div id="trainDetails"></div>
    </center>

    <?php
    if (isset($_POST['train_no'])) {
        $train_no = strtoupper(trim($_POST['train_no']));  
        $query = "SELECT train_no, train_name, start_station, end_station, start_time, end_time 
                  FROM train WHERE train_no = '$train_no'";
        $result = mysqli_query($con, $query);

        if ($result->num_rows > 0) {
            echo "<center><br><br>
                  <table>
                  <tr>
                      <th>Train No</th>
                      <th>Train Name</th>
                      <th>From Station</th>
                      <th>Destination Station</th>
                      <th>Arrival Time</th>
                      <th>Departure Time</th>
                  </tr>";

            while ($row = $result->fetch_assoc())
            {
                echo '<tr>
                      <td>' . ucfirst(strtolower($row['train_no'])). '</td>
                      <td>' . ucfirst(strtolower($row['train_name'])) . '</td>
                      <td>' . ucfirst(strtolower($row['start_station'])). '</td>
                      <td>' . ucfirst(strtolower($row['end_station'])) . '</td>
                      <td>' . $row['start_time'] . '</td>
                      <td>' . $row['end_time']. '</td>
                      </tr>';
            }
            echo "</table></center>";
        } else {
            echo "<center><h2 class='error-message'>Invalid Train Number: $train_no</h2></center>";
        }

        mysqli_close($con);
    }
    ?>
</body>
</html>
