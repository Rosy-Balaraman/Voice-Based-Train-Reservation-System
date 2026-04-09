<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        form {
            margin: 20px 0;
        }

        button {
            background-color: navy;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: blue;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 2px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
            background-color: #fff;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #dcdcdc;
        }

        h2 {
            color: navy;
            margin-top: 20px;
        }
    </style>
<body>
    <form action="" method="post">
        <button type="button" onClick="home()">Home</button>
        <button name="book">Booked Ticket</button>
        <button name="cancel">Cancelled Ticket</button>
        <button name="refund">Refund Details</button>
    </form>
    <script>
        function home()
        {
            window.location.href="admin.php";
        }
    </script>
<?php
    if(isset($_POST['book']))
    {
        $q1="select p_id,train_no,name,gender,booking_id,booking_date,journey_date,class,compartment from booking where booking_status='booked' and payment_status='paid';";
        $result1=mysqli_query($con,$q1);
        if($result1->num_rows>0)
        {
        ?>
        <center>
        <table border="2">
        <tr>
        <th>Passenger Id</th>
        <th>Train No</th>
        <th>Name</th>
        <th>Gender</th>
        <th>PNR</th>
        <th>Booking Date</th>
        <th>journey Date</th>
        <th>Class</th>
        <th>Compartment</th>
        </tr>
        <?php 
        while($row=$result1->fetch_assoc())
        {?>
        <tr>
        <td><?php echo $row["p_id"];?></td>
        <td><?php echo $row["train_no"];?></td>
        <td><?php echo $row["name"];?></td>
        <td><?php echo $row["gender"];?></td>
        <td><?php echo $row["booking_id"];?></td>
        <td><?php echo $row["booking_date"];?></td>
        <td><?php echo $row["journey_date"];?></td>
        <td><?php echo $row["class"];?></td>
        <td><?php echo $row["compartment"];?></td>
        <?php
        }?>
        </table>
        </center>
        <?php
        }
        else
        {
            echo'No Reslts found';
        }
    }

    if(isset($_POST['cancel']))
    {
        $q2="select p_id,train_no,name,gender,booking_id,booking_date,journey_date,class,compartment from booking where booking_status='cancelled' and payment_status='refund';";
        $result2=mysqli_query($con,$q2);
        if($result2->num_rows>0)
        {
        ?>
        <center>
        <table border="2">
        <tr>
        <th>Passenger Id</th>
        <th>Train No</th>
        <th>Name</th>
        <th>Gender</th>
        <th>PNR</th>
        <th>Booking Date</th>
        <th>journey Date</th>
        <th>Class</th>
        <th>Compartment</th>
        </tr>
        <?php 
        while($row=$result2->fetch_assoc())
        {?>
        <tr>
        <td><?php echo $row["p_id"];?></td>
        <td><?php echo $row["train_no"];?></td>
        <td><?php echo $row["name"];?></td>
        <td><?php echo $row["gender"];?></td>
        <td><?php echo $row["booking_id"];?></td>
        <td><?php echo $row["booking_date"];?></td>
        <td><?php echo $row["journey_date"];?></td>
        <td><?php echo $row["class"];?></td>
        <td><?php echo $row["compartment"];?></td>
        <?php
        }?>
        </table>
        </center>
        <?php
        }
        else
        {
            echo'No Reslts found';
        }
    }
    if(isset($_POST['refund']))
    {
        $q1="select p_id,train_no,name,gender,booking_id,booking_date,journey_date,class,compartment from booking where payment_status='refund';";
        $result1=mysqli_query($con,$q1);
        if($result1->num_rows>0)
        {
        ?>
        <center>
        <table border="2">
        <tr>
        <th>Passenger Id</th>
        <th>Train No</th>
        <th>Name</th>
        <th>Gender</th>
        <th>PNR</th>
        <th>Booking Date</th>
        <th>journey Date</th>
        <th>Class</th>
        <th>Compartment</th>
        </tr>
        <?php 
        while($row=$result1->fetch_assoc())
        {?>
        <tr>
        <td><?php echo $row["p_id"];?></td>
        <td><?php echo $row["train_no"];?></td>
        <td><?php echo $row["name"];?></td>
        <td><?php echo $row["gender"];?></td>
        <td><?php echo $row["booking_id"];?></td>
        <td><?php echo $row["booking_date"];?></td>
        <td><?php echo $row["journey_date"];?></td>
        <td><?php echo $row["class"];?></td>
        <td><?php echo $row["compartment"];?></td>
        <?php
        }?>
        </table>
        </center>
        <?php
        }
        else
        {
            echo'No Reslts found';
        }
    }
        ?>
</body>
</html>