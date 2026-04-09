<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: navy;
            text-align: center;
        }
        table {
            width: 100%; 
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            color: navy; 
            font-weight: bold; 
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; 
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #FF1493;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100px;
            font-size:15px;
        }
        button:hover {
            background-color: darkblue;
        }
        #home {
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
<a href="reservation1.php" id="home">Back</a>
    <h1>WELCOME</h1>
    <?php
        if(isset($_POST['btn'])) {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $class = $_POST['class'];
            $journey_date = $_POST['journey_date'];

            $query = "SELECT t.train_no, t.train_name, t.start_station, t.end_station, 
                      t.start_time, t.end_time, c.ticket_price, SUM(c.avail_seat) AS total_seat, 
                      c.class 
                      FROM train t 
                      JOIN compartment c ON t.train_no = c.train_no 
                      WHERE t.start_station = '$from' AND t.end_station = '$to' AND c.class = '$class' 
                      GROUP BY t.train_no, t.train_name, t.start_station, t.end_station, 
                      t.start_time, t.end_time, c.ticket_price;";

            $result = mysqli_query($con, $query);
            if($result->num_rows > 0) {
                ?>
                <center>
                    <table>
                        <tr>
                            <th>Train No</th>
                            <th>Train Name</th>
                            <th>Start Station</th>
                            <th>End Station</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Ticket Price</th>
                            <th>Available Seats</th>
                            <th>Class</th>
                            <th>Booking</th>
                        </tr>
                        <?php
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $row["train_no"]; ?></td>
                                <td><?php echo ucfirst(strtolower($row['train_name'])); ?></td>
                                <td><?php echo ucfirst(strtolower($row['start_station'])); ?></td>
                                <td><?php echo ucfirst(strtolower($row['end_station'])); ?></td>
                                <td><?php echo $row["start_time"]; ?></td>
                                <td><?php echo $row["end_time"]; ?></td>
                                <td><?php echo $row["ticket_price"]; ?></td>
                                <td><?php echo $row["total_seat"]; ?></td>
                                <td><?php echo $row["class"]; ?></td>
                                <td>
                                    <form action="booking.php" method="post">
                                        <input type="hidden" name="train_no" value="<?php echo $row['train_no']; ?>">
                                        <input type="hidden" name="train_name" value="<?php echo $row['train_name']; ?>">
                                        <input type="hidden" name="start_station" value="<?php echo $row['start_station']; ?>">
                                        <input type="hidden" name="end_station" value="<?php echo $row['end_station']; ?>">
                                        <input type="hidden" name="start_time" value="<?php echo $row['start_time']; ?>">
                                        <input type="hidden" name="end_time" value="<?php echo $row['end_time']; ?>">
                                        <input type="hidden" name="journey_date" value="<?php echo $journey_date; ?>">
                                        <input type="hidden" name="ticket_price" value="<?php echo $row['ticket_price']; ?>">
                                        <input type="hidden" name="class" value="<?php echo $row['class']; ?>">
                                        <button type="submit">Book Now</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </center>  
                <?php
            } else {
                echo "<p style='text-align:center;'>No Train Available</p>";
            }
        }
    ?>
</body>
</html>
