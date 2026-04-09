<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        form {
            display: inline-block;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width:100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            margin-top: 20px;
            width: 80%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        label {
            font-size: 17px;
            margin: 5px;
            display: block;
        }
        input[type="text"] {
            width: 30%;
            padding: 8px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"], button[type="submit"] {
            width: auto;
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005f6b;
        }
    </style>
</head>
<body>
<form action="" method="post">
            <center>
                <button type="button" Onclick="home()">Home</button>
                <button type="submit" name="view">View Train</button>
                <button type="submit" name="addtrain">Add Train</button>
                <button type="submit" name="edit">Edit Train</button>
                <button type="submit" name="delete">Delete Train</button><br><br>
            </center>
        </form>
        
    <script>
        function home()
        {
            window.location.href="admin.php";
        }
    </script>
<?php

if(isset($_POST['view']))
{
$s1="select * from train;";
$result2=mysqli_query($con,$s1);
if($result2->num_rows>0)
{
?>
<center>
<table border="2">
<tr>
<th>train no</th>
<th>train name</th>
<th>start station</th>
<th>end station</th>
<th>start time</th>
<th>end time</th>
</tr>
<?php 
while($row=$result2->fetch_assoc())
{?>
<tr>
<td><?php echo $row["train_no"];?></td>
<td><?php echo $row["train_name"];?></td>
<td><?php echo $row["start_station"];?></td>
<td><?php echo $row["end_station"];?></td>
<td><?php echo $row["start_time"];?></td>
<td><?php echo $row["end_time"];?></td>
<?php
}?>
</table>
</center>
<?php
}}


// Add Train Form
if (isset($_POST['addtrain'])) {
    echo '<form action="" method="post">
        <h2>Add New Train</h2>
        
        <label for="train_no">Train Number:</label>
        <input type="text" id="train_no" name="train_no" required>
        
        <label for="train_name">Train Name:</label>
        <input type="text" id="train_name" name="train_name" required>
        
        <label for="start_station">Start Station:</label>
        <input type="text" id="start_station" name="start_station" required>
        
        <label for="end_station">End Station:</label>
        <input type="text" id="end_station" name="end_station" required>
        
        <label for="start_time">Start Time (HH:MM:SS):</label>
        <input type="time" id="start_time" name="start_time" step="1" required>
        
        <label for="end_time">End Time (HH:MM:SS):</label>
        <input type="time" id="end_time" name="end_time" step="1" required>
        
        <input type="submit" name="add" value="Add Train">
    </form>';
}
 if(isset($_POST['add'])) {
    // Sanitize inputs
    $train_no = mysqli_real_escape_string($con, $_POST['train_no']);
    $train_name = mysqli_real_escape_string($con, $_POST['train_name']);
    $start_station = mysqli_real_escape_string($con, $_POST['start_station']);
    $end_station = mysqli_real_escape_string($con, $_POST['end_station']);
    
    // Format times (convert from browser format to MySQL format)
    $start_time = date("H:i:s", strtotime($_POST['start_time']));
    $end_time = date("H:i:s", strtotime($_POST['end_time']));
    
    // Check if train number already exists
    $check_query = "SELECT * FROM train WHERE train_no = '$train_no'";
    $check_result = mysqli_query($con, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        echo '<p class="error">Train with this number already exists!</p>';
    } else {
        // Insert new train
        $q2 = "INSERT INTO train(train_no, train_name, start_station, end_station, start_time, end_time)
               VALUES('$train_no', '$train_name', '$start_station', '$end_station', '$start_time', '$end_time')";
        
        if(mysqli_query($con, $q2)) {
            echo '<script>alert("Train added successfully!")</script>';
        } else {
            echo '<p class="error">Error: ' . mysqli_error($con) . '</p>';
        }
    }
}
   if(isset($_POST['edit']))
   {?>
   <form action="" method="post">
   <input type="text" placeholder="Enter the Train Number" name="train_no1">
   <button type="submit" name="search">search</button>
    </form>   
    <?php
   }
if(isset($_POST['search']))
{
    $train_no1 = mysqli_real_escape_string($con, $_POST['train_no1']);
    $q3="select * from train where train_no='$train_no1';";
    $res3=mysqli_query($con,$q3);
      if ($res3->num_rows > 0)
      {
        $row = $res3->fetch_assoc();?>
         <form action="" method="post">
         <label>Train Number: <input type="text" name="train_no" value="<?php echo htmlspecialchars($row['train_no']); ?>"></label><br>
            <label>Train Name: <input type="text" name="train_name" value="<?php echo htmlspecialchars($row['train_name']); ?>"></label><br>
            <label>Source Station: <input type="text" name="start_station" value="<?php echo htmlspecialchars($row['start_station']); ?>"></label><br>
            <label>Destination Station: <input type="text" name="end_station" value="<?php echo htmlspecialchars($row['end_station']); ?>"></label><br>
            <label>Start Time: <input type="text" name="start_time" value="<?php echo htmlspecialchars($row['start_time']); ?>"></label><br>
            <label>Arrival Time: <input type="text" name="end_time" value="<?php echo htmlspecialchars($row['end_time']); ?>"></label><br>
            <input type="hidden" name="original_train_no" value="<?php echo htmlspecialchars($train_no1); ?>">
            <button type="submit" name="update">Update</button>
        </form>
        <?php
    } else {
        echo '<h2><center>No Train Found!</center></h2>';
    }
}
   if(isset($_POST['update']))
    {
        $original_train_no = mysqli_real_escape_string($con, $_POST['original_train_no']);
        $train_no = mysqli_real_escape_string($con, $_POST['train_no']);
        $train_name = mysqli_real_escape_string($con, $_POST['train_name']);
        $start_station = mysqli_real_escape_string($con, $_POST['start_station']);
        $end_station = mysqli_real_escape_string($con, $_POST['end_station']);
        $start_time = mysqli_real_escape_string($con, $_POST['start_time']);
        $end_time = mysqli_real_escape_string($con, $_POST['end_time']);
     
        $up="update train set train_no='$train_no',train_name='$train_name',start_station='$start_station',end_station='$end_station',start_time='$start_time',end_time='$end_time' where train_no='$original_train_no';";
        if(mysqli_query($con,$up))
        {
            echo'<script>alert("values updated successfully")</script>';
        }
        else
        {
            echo'Something Wrong !...';
        }
    }
if (isset($_POST['delete']))
{
    ?>
   <form action="" method="post">
   <input type="text" placeholder="Enter the Train Number" name="train_no">
   <button type="submit" name="deletetrain">Delete</button>
    </form>   
    <?php
}
if(isset($_POST['deletetrain']))
{
    $train_no1 = $_POST['train_no'];
    $q4="delete from train where train_no='$train_no1';";
    if(mysqli_query($con,$q4))
    {
        echo'<script>alert("Deleted Train Successfully...")</script>';
    }
    else
    {
        echo'Something Wrong !...';
    }
}

?>
</body>
</html>