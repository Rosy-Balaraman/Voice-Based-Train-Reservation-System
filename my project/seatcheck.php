<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Available Seats</title>
    <style>
        body {
            margin-top: 100px;
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #000; 
            text-align: center;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000080; 
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        a:hover {
            background-color: #4169e1;
        }

        form {
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px; 
            border: 2px solid #000080; 
            border-radius: 5px;
            outline: none;
            font-size: 20px;
            color: black;
        }

        button {
            padding: 10px 20px;
            background-color: orange; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer; 
            margin-top: 30px; 
            font-size: 24px; 
            font-weight: bold; 
        }

        button:hover {
            background-color: #4169e1; 
        }

        h1 {
            color: #000080;
            margin-top: 30px; 
        }

        .error-message {
            color: red; 
            font-size: 18px;
            margin-top: 20px;
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

        table {
            width: 80%; 
            margin: 30px auto; 
            border-collapse: collapse; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }

        th, td {
            padding: 12px; 
            text-align: center; 
            font-size: 20px;
            border: 1px solid #000080; 
        }

        td {
            font-weight: bold;
            font-size: 20px;
        }

        th {
            background-color: #000080; 
            color: white; 
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; 
        }

        tr:hover {
            background-color: #d1e7dd; 
        }
    </style>
</head>
<body>
    <a href="passenger.php" id="home">Back</a>
    <h1>Search By Train Number</h1>
    <form action="" method="post">
        <input type="text" placeholder="Enter Train Number" name="train_no" value="<?php echo isset($_POST['train_no']) ? htmlspecialchars($_POST['train_no']) : ''; ?>" required>
        <br><br>
        <button name="search">Search</button>
    </form>

<?php   
if (isset($_POST['search'])) 
{
    $train_no = $_POST['train_no'];

    $query = "SELECT class, compartment, avail_seat FROM compartment WHERE train_no='$train_no';";
    $result = mysqli_query($con, $query);

    if ($result) 
    {
        if ($result->num_rows > 0) 
        {
            ?>
            <table>
                <tr>
                    <th>Class</th>
                    <th>Compartment</th>
                    <th>Available Seats</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) 
                {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["class"]); ?></td>
                        <td><?php echo htmlspecialchars($row["compartment"]); ?></td>
                        <td><?php echo htmlspecialchars($row['avail_seat']); ?></td>
                    </tr>   
                    <?php
                }
                ?>
            </table>
            <?php
        } 
        else 
        {
            echo '<div class="error-message">No available seats for Train Number: ' . htmlspecialchars($train_no) . '</div>';
        }
    } 
    else 
    {
        echo '<h1 class="error-message">Error executing query: ' . mysqli_error($con) . '</h1>';  
    }
}
mysqli_close($con);
?>
</body>
</html>
