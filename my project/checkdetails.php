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
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: navy;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        h2 {
            color: navy;
        }
        form {
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        form input[type="text"], form input[type="date"] {
            background-color: #f0f0f0;
        }
        form button {
            background-color: navy;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #004080;
        }
        .results {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #004080;
            color: white;
        }
        td {
            background-color: #f9f9f9;
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
<body>
<a href="admin.php" id="home">Home</a>

<header>
        <h1>Train Passenger Details</h1>
    </header>

    <form action="" method="post">
        <input type="text" placeholder="Enter Train Number" name="train_no">
        <input type="date" placeholder="Enter the date" name="date">
        <button name="search">Search</button>
</form>
      <div class="results">
    <?php
   if(isset($_POST['search']))
    {
        $train_no = $_POST['train_no'];
        $date = $_POST['date'];
        $q1="select p_id,train_no,name,gender,booking_id,booking_date,journey_date,class,compartment,booking_status from booking where train_no='$train_no' and journey_date='$date';";
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
        <th>Booking Status</th>
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
        <td><?php echo $row["booking_status"];?></td>
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

        $query2="select count(ref_id) as booked from booking where journey_date='$date' and train_no='$train_no' and booking_status='booked' and payment_status='paid';";
        $result2=mysqli_query($con,$query2);
        if($result2->num_rows>0)
        {
        while($row=$result2->fetch_assoc())
        {
         $book_pass_count=$row["booked"];    
        }
        }
        else
        {
            $book_pass_count=0;   
        }

        $query3="select count(ref_id) as cancelled from booking where journey_date='$date' and train_no='$train_no' and booking_status='cancelled';";
        $result3=mysqli_query($con,$query3);
        if($result3->num_rows>0)
        {
        while($row=$result3->fetch_assoc())
        {
         $cancel_pass_count=$row["cancelled"];    
        }
        }
        else
        {
            $cancel_pass_count=0;   
        }

        $query4="select count(ref_id) as refund from booking where journey_date='$date' and train_no='$train_no' and payment_status='refund';";
        $result4=mysqli_query($con,$query4);
        if($result4->num_rows>0)
        {
        while($row=$result4->fetch_assoc())
        {
         $refund_pass_count=$row["refund"];    
        }
        }
        else
        {
            $refund_pass_count=0;   
        }
    
        echo'<h2>Total Number Of Passenger travelled in this Date : '. $book_pass_count; 
        echo'<h2>Total Number Of Passenger Cancelled in this Date : '. $cancel_pass_count; 
        echo'<h2>Total Number Of Passenger refunded in this Date : '. $refund_pass_count; 
    
    }
?>
    </div>
</body>
</html>