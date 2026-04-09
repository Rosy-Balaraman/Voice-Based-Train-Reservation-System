<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelled Ticket History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #000; /* Black text */
            margin: 0;
            padding: 0;
            text-align: center; /* Center text */
        }

        h1 {
            color: #000080; /* Navy blue for the title */
            margin: 20px 0;
        }

        a {
            display: inline-block; /* Make link look like a button */
            padding: 10px 20px;
            background-color: #000080; /* Navy blue button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        a:hover {
            background-color: #4169e1; /* Lighter blue on hover */
        }

        table {
            width: 80%; /* Full width for the table */
            margin: 20px auto; /* Center the table */
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 2px solid #000080; /* Navy blue border */
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #000080; /* Navy blue header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light gray for even rows */
        }

        tr:hover {
            background-color: #e6e6fa; /* Light lavender on hover */
        }

        .error-message {
            color: red;
            font-size: 18px;
            margin-top: 20px;
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
<h1>Cancelled Ticket History</h1>
<?php 
$email = $_SESSION['email'];
$query = "SELECT p_id FROM user WHERE email='$email';";
$result = mysqli_query($con, $query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $p_id = $row["p_id"];
    }

    $query1 = "SELECT b.train_no, b.booking_id, t.train_name, t.start_station, t.end_station, b.journey_date, b.booking_status 
                FROM booking b 
                JOIN train t ON t.train_no = b.train_no 
                WHERE booking_status = 'cancelled' AND p_id = '$p_id' 
                ORDER BY journey_date;";
    
    $result1 = mysqli_query($con, $query1);
    
    if ($result1->num_rows > 0) {
        ?>
        <table>
            <tr>
                <th>Train No</th>
                <th>Train Name</th>
                <th>Start Station</th>
                <th>End Station</th>
                <th>Journey Date</th>
                <th>PNR</th>
                <th>Booking Status</th>
            </tr>
            <?php
            while ($row = $result1->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row["train_no"]; ?></td>
                    <td><?php echo $row["train_name"]; ?></td>
                    <td><?php echo ucfirst(strtolower($row['start_station'])); ?></td>
                    <td><?php echo ucfirst(strtolower($row['end_station'])); ?></td>
                    <td><?php echo $row["journey_date"]; ?></td>
                    <td><?php echo $row["booking_id"]; ?></td>
                    <td><?php echo ucfirst(strtolower($row['booking_status'])); ?></td>
                </tr>   
                <?php
            }
            ?>
        </table>
        <?php
    } else {
        echo '<div class="error-message">No Ticket Cancelled</div>';
    }
} else {
    echo '<div class="error-message">User not found.</div>';
}
?>
</body>
</html>
