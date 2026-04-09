<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            margin: 0;
            padding: 0;
        }
        #box {
            width: 750px;
            border: 2px solid #000;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #333; 
        }
        .ticket, .train {
            display: flex;
            justify-content: space-between; 
            padding: 0 30px;
            margin-bottom: 20px;
        }
        .passenger {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; 
        }        
        td {
            text-align: center;
            padding: 10px; 
        }
        th {
            background-color: transparent; 
            color: #000; 
            padding: 10px; 
        }
        th, td {
            border: 1px solid #ddd; 
        }
        th {
            font-weight: bold; 
        }
        p {
            text-transform: uppercase;
            margin: 5px 0; 
        }
        .blue-text {
            color: blue; 
            font-weight: bold; 
        }
        button {
            background-color: navy; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px;
            cursor: pointer; 
            margin: 20px 0; 
            display: block; 
            width: 150px;
            text-align: center; 
        }
        button:hover {
            background-color: darkblue;
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

<?php
$pnr=$_SESSION['pnr'];
$query="SELECT p.train_no, p.source, p.destination, p.journey_date, p.booking_date,t.train_name, t.start_time, t.end_time,b.class FROM pnr p JOIN train t ON p.train_no = t.train_no 
      JOIN booking b ON p.pnr = b.booking_id WHERE p.pnr = '$pnr';";
$result=mysqli_query($con,$query);
if($result->num_rows>0)
{
    while($row=$result->fetch_assoc())   
    {
        $train_no=$row["train_no"];
        $source=$row["source"];
        $destination=$row["destination"];
        $journey_date=$row["journey_date"];
        $booking_date=$row["booking_date"];
        $train_name=$row["train_name"];
        $start_time=$row["start_time"];
        $end_time=$row["end_time"];
        $class=$row["class"];
    }
}
?>  
  <a href="passenger.php" id="home">Back</a>

<div id="box">
    <h1>TICKET</h1>
    <div class="ticket">
        <div>
            <h3>From</h3>
            <p><?php echo $source; ?></p>
            <p><?php echo 'Start Time: '. $start_time; ?></p>
        </div>
        <div>
            <h3>To</h3>
            <p><?php echo $destination; ?></p>
            <p><?php echo 'Arrival Time: '.$end_time; ?></p>
        </div>
    </div>
    <hr>
    <div class="train">
        <div>
            <h3>PNR</h3>
            <p class="blue-text"><?php echo $pnr; ?></p>
            <h3>Booking Date</h3>
            <p><?php echo $booking_date; ?></p>
        </div>
        <div>
            <h3>Train No/Name</h3>
            <p class="blue-text"><?php echo $train_no.'/'.$train_name; ?></p>
        </div>
        <div>            
            <h3>Class</h3>
            <p class="blue-text"><?php echo $class; ?></p>
            <h3>Journey Date</h3>
            <p><?php echo $journey_date; ?></p>
        </div>
    </div>
    <hr>
    <h3>Passenger Details</h3>
    <div>
        <?php
        $query="SELECT name, age, gender, seat, compartment, booking_status FROM booking WHERE booking_id = '$pnr';";
        $result=mysqli_query($con,$query);
        if($result->num_rows>0) { ?>
            <center><br><br>
                <table class="passenger">
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Seat No</th>
                        <th>Compartment</th>
                        <th>Booking Status</th>
                    </tr>
                    <?php
                    while($row=$result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["name"];?></td>
                            <td><?php echo $row["age"];?></td>
                            <td><?php echo $row["gender"];?></td>
                            <td><?php echo $row["seat"];?></td>
                            <td><?php echo $row["compartment"];?></td>
                            <td><?php echo $row["booking_status"];?></td>
                        </tr>   
                    <?php } ?>
                </table>
            </center>    
        <?php } ?>
    </div>
</div>

</body>
</html>
