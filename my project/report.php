<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Report</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f7;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            color: #003366;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #003366;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #002244;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            text-align: left;
            padding: 12px;
            background-color: #fff;
        }

        th {
            background-color: #003366;
            color: white;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #003366;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }

        a:hover {
            background-color: #002244;
        }

        p {
            font-size: 18px;
            color: #cc0000;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
        }

        .download-btn {
            text-align: center;
        }
    </style>
<body>
<a href="admin.php">Home</a>

<form action="" method="post">
    <input type="text" placeholder="Enter Train Number" name="train_no" required>
    <input type="date" placeholder="Enter Journey Date" name="journey_date" required>
    <button name="report">Search</button>
</form>

<?php
if (isset($_POST['report'])) 
{
    $train_no = $_POST['train_no'];
    $journey_date = $_POST['journey_date'];
    
    $_SESSION['train_no'] = $train_no;
    $_SESSION['journey_date'] = $journey_date;
    if (!$con)
    {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT class, COUNT(class) as class_count FROM compartment WHERE train_no='$train_no' GROUP BY class;";
    $result = mysqli_query($con, $query);

    if ($result->num_rows > 0) 
    {
        while ($row = $result->fetch_assoc()) 
        {
            $class = $row['class'];

            $query1 = "SELECT compartment, COUNT(compartment) as comp_count FROM compartment WHERE train_no='$train_no' AND class='$class' GROUP BY compartment;";
            $result1 = mysqli_query($con, $query1);

            if ($result1->num_rows > 0) 
            {
                echo "<h2>Class: $class</h2>";

                while ($row1 = $result1->fetch_assoc()) 
                {
                    $compartment = $row1['compartment'];
                    $query2 = "SELECT booking_id, name, gender, class, compartment, seat FROM booking WHERE train_no='$train_no' AND journey_date='$journey_date' AND class='$class' AND compartment='$compartment' AND booking_status='booked';";
                    $result2 = mysqli_query($con, $query2);

                    if ($result2->num_rows > 0) {
                        echo "<h3>Compartment: $compartment</h3>";
                        ?>
                        <table border="1" width="600">
                            <tr>
                                <th>Passenger Name</th>
                                <th>Gender</th>
                                <th>PNR</th>
                                <th>Class</th>
                                <th>Compartment</th>
                                <th>Seat Number</th>
                            </tr>
                        <?php
                        while ($row2 = $result2->fetch_assoc()) 
                        {
                            ?>
                            <tr>
                                <td><?php echo $row2["name"]; ?></td>
                                <td><?php echo $row2["gender"]; ?></td>
                                <td><?php echo $row2["booking_id"]; ?></td>
                                <td><?php echo $row2["class"]; ?></td>
                                <td><?php echo $row2["compartment"]; ?></td>
                                <td><?php echo $row2["seat"]; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </table>
                        <?php
                    } 
                    else 
                    {
                        echo "<p class='no-data'>No passengers found for compartment: $compartment</p>";
                    }
                }
            }
        }
    } else {
        echo "<p class='no-data'>No data found for this train number and journey date.</p>";
    }

    mysqli_close($con);
}
?>

<?php if (isset($_POST['report'])): ?>
    <div class="download-btn">
    <form action="downloadreport.php" method="post">
        <button name="download_pdf">Download Report</button>
    </form>
    </div>
<?php endif; ?>

</body>
</html>
