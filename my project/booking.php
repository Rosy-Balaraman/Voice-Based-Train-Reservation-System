
<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
    <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

.container {
    width: 70%;
    margin: 20px auto;
    border: 1px solid #ddd;
    padding: 20px;
    background-color: white;
    position: relative;
    border-radius:15px;
    margin-top:100px;
}

h2 {
    color: #333;
}

.journey-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.journey-info div {
    flex: 1;
    padding: 10px;
}

h3 {
    text-align: center;
    color: #007bff;
}

.input-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.input-container .input-box {
    flex: 1;
    margin: 0 5px;
}

.input-container input, 
.input-container select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size:17px;
    box-sizing: border-box;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.input-container input:focus, 
.input-container select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0px 2px 8px rgba(0, 123, 255, 0.5);
}

.input-container button {
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
}

.input-container button:hover {
    background-color: #0056b3;
}

.nav-btn {
    margin-left: 10px;
    background-color: #28a745;
}

.nav-btn:hover {
    background-color: #218838;
}

#container {
    margin-top: 20px;
}

#container .input-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f1f1f1;
    border: 1px solid #ddd;
    border-radius: 8px;
}

#container .input-row input,
#container .input-row select {
    width: 30%;
    padding: 12px;
    font-size:17px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
}

#limit {
    color: red;
    margin-top: 10px;
}

.cancel-btn {
    cursor: pointer;
    width: 30px;
    height: 20px;
    position:absolute;
    align-self: center;
    margin-bottom:50px;
    margin-left:94%;
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
    .uppercase
    {
        text-transform:uppercase;
    }
    </style>
</head>
<body>
<a href="reservation1.php" id="home">Back</a>
<div class="container">
<?php
    $train_no = '';
    $train_name = '';
    $start_station = '';
    $end_station = '';
    $date = '';
    $ticket_price = 0;
    $class = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action']))
    {
        $train_no = $_POST['train_no'] ?? '';
        $train_name = $_POST['train_name'] ?? '';
        $start_station = $_POST['start_station'] ?? '';
        $end_station = $_POST['end_station'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $journey_date = $_POST['journey_date'] ?? '';
        $ticket_price = $_POST['ticket_price'] ?? 0;  
        $class = $_POST['class'] ?? '';
    }
?>
 <h2><?php echo$train_name . "(" .$train_no. ")";?></h2>

<div class="journey-info">
    <div class="source">
        <h2 class="uppercase" style="text-align: left;"><?php echo$start_station;?></h2>
        <p class="uppercase" style="text-align: left;"><?php echo$start_time;?></p>
    </div>

    <div class="class">
        <h3>Journey Date: <?php echo$journey_date;?></h3>
        <h3>Ticket Price: <?php echo$ticket_price;?></h3>
    </div>

    <div class="destination">
        <h2 class="uppercase" style="text-align: right;"><?php echo $end_station;?></h2>
        <p class="uppercase" style="text-align: right;"><?php echo $end_time;?></p>
    </div>
</div>


<h1>PASSENGER DETAILS</h1>

<form action="" method="post">
    <input type="hidden" name="train_no" value="<?php echo htmlspecialchars($train_no); ?>">
    <input type="hidden" name="train_name" value="<?php echo htmlspecialchars($train_name); ?>">
    <input type="hidden" name="start_station" value="<?php echo htmlspecialchars($start_station); ?>">
    <input type="hidden" name="end_station" value="<?php echo htmlspecialchars($end_station); ?>">
    <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>">
    <input type="hidden" name="end_time" value="<?php echo htmlspecialchars($end_time); ?>">
    <input type="hidden" name="ticket_price" value="<?php echo htmlspecialchars($ticket_price); ?>">
    <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
    <input type="hidden" name="journey_date" value="<?php echo htmlspecialchars($journey_date); ?>">
    
    <div id="input">
            <div class="input-container">
                <div class="input-box">
                    <input type="text" placeholder="Passenger Name" name="name[]" required>
                </div>
                <div class="input-box">
                    <input type="number" placeholder="Age" name="age[]" required>
                </div>
                <div class="input-box">
                    <select name="gender[]" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="container"></div>
        <div id="limit"></div><br>

        <div class="input-container">
            <button id="addbtn" type="submit" name="action" value="add">ADD</button>
            <button type="submit" class="nav-btn">Next</button>
        </div>
        
</div>
<script>
    var i = 0;
    var btn = document.getElementById('addbtn');
    btn.addEventListener('click', function(event) {
        if (i < 3)
        {
            event.preventDefault();
            var container = document.getElementById('container');
            var row = document.createElement('div');
            row.className = 'input-row';

            var n1 = document.createElement('input');
            n1.type = 'text';
            n1.name = 'name[]';
            n1.placeholder = 'Passenger Name';
            row.appendChild(n1);

            var n2 = document.createElement('input');
            n2.type = 'text';
            n2.name = 'age[]';
            n2.placeholder = 'Age';
            row.appendChild(n2);

            var genderSelect = document.createElement('select');
            genderSelect.name = 'gender[]';
            genderSelect.innerHTML = `
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>`;
            row.appendChild(genderSelect);

            var cancelBtn = document.createElement('img');
                cancelBtn.src = 'cancel.png'; 
                cancelBtn.className = 'cancel-btn';
                cancelBtn.alt = 'Cancel';
                cancelBtn.addEventListener('click', function() {
                container.removeChild(row); 
                i--; 
                btn.disabled = false; 
                document.getElementById('limit').innerText = "";
        });
        row.appendChild(cancelBtn);

            container.appendChild(row);
            i++;
        } 
        else {
            document.getElementById('limit').innerText = "Reached number of passenger limit";
            btn.disabled = true;
        }
    });
</script>

<?php
ob_start();
$email= $_SESSION['email'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action'])) 
{
     $query="select * from booking order by booking_id desc limit 1;";
     $result=mysqli_query($con,$query);
     if($result->num_rows>0)
     {
     while($row=$result->fetch_assoc())   
     {
        $r=$row["booking_id"];
        $ans= $r+1;
     }
     $query2="select p_id from user where email='$email';";
     $result1=mysqli_query($con,$query2);
     if($result1->num_rows>0)
     {
     while($row=$result1->fetch_assoc())   
     {
        $p_id=$row["p_id"];
     }
    }
    }

    $_SESSION['p_id']=$p_id;

    $names = $_POST['name'] ?? [];
    $ages = $_POST['age'] ?? [];
    $genders = $_POST['gender'] ?? [];
    $passenger_details = [];
    $values = [];
    $calculated_prices = [];
    $b_id=[];
    $b_id[0]=$ans;
    $t_no=[$train_no];
    $cls=[$class];
    $jd=[$journey_date];
    $pi=[$p_id];
    $d = date('Y-m-d');
    $bd=[$d];
    $no_of_pas=count($names);
    $_SESSION['no_of_pas'] = $no_of_pas;

    for ($i = 0; $i < count($names); $i++) 
    {
        $name = mysqli_real_escape_string($con, $names[$i]);
        $age = mysqli_real_escape_string($con, $ages[$i]);
        $gender = mysqli_real_escape_string($con, $genders[$i]);
        $booking_id=mysqli_real_escape_string($con, $b_id[0]);
        $train_no=mysqli_real_escape_string($con, $t_no[0]);
        $class=mysqli_real_escape_string($con, $cls[0]);
        $journey_date=mysqli_real_escape_string($con, $jd[0]);
        $booking_date = mysqli_real_escape_string($con, $bd[0]);
        $p_id=mysqli_real_escape_string($con, $pi[0]);

        if ($age <= 5) 
        {
            $calculated_prices[$i] = 0;
        } 
        else 
        {
            if ($class == 'AC' || $class == 'sleeper' || $class == 'ladies')
            {
                if ($age >= 6 && $age <= 14)
                {
                    $calculated_prices[$i] = $ticket_price / 2;
                } 
                else 
                {
                    $calculated_prices[$i] = $ticket_price;
                }
            } 
            else
            {
                $calculated_prices[$i] = $ticket_price;
            }
        }

        $calculated_price = mysqli_real_escape_string($con, $calculated_prices[$i]);
        $values[] = "('$name', '$age', '$gender', '$calculated_price','$booking_id','$train_no','$journey_date','$p_id','$booking_date','$class')";
        $passenger_details[] = [
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'ticket_price' => $calculated_price,
            'booking_id'=>$booking_id,
            'train_no'=>$train_no,
            'journey_date'=>$journey_date,
            'p_id'=>$p_id,
            'booking_date'=>$booking_date
        ];
    }

    if (!empty($values))
    {
        $valuesStr = implode(", ", $values);
        $ins = "INSERT INTO booking (name, age, gender, ticket_price,booking_id,train_no,journey_date,p_id,booking_date,class) VALUES $valuesStr";
        if (mysqli_query($con, $ins)) 
        {
            $_SESSION['passenger_details'] = $passenger_details;
            $_SESSION['train_details'] = [
                'train_no' => $train_no,
                'train_name' => $train_name,
                'start_station' => $start_station,
                'end_station' => $end_station,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'journey_date' => $journey_date,
                'class' => $class
            ];
            session_write_close();
           echo'<script>window.location.href="confirm.php";</script>';
        }
        else
        {
            echo '<pre>Error: ' . mysqli_error($con) . '</pre>';
        }
    }
}

mysqli_close($con);
ob_end_flush();
?>
</body>
</html>
