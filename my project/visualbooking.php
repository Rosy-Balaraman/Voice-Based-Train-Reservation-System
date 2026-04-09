<?php
ob_start();
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Booking</title>
</head>
<style>
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
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    text-align: center;
    color: navy;
}

input[type="text"] {
    width: 20%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

input[type="hidden"] {
    display: none;
}

.input-row {
    margin-bottom: 15px;
}

input[type="submit"], button {
    background-color: navy;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover, button:hover {
    background-color: darkblue;
}

#container {
    margin-top: 20px;
}

</style>
<body>
    <?php
    $train_no = $_POST['train_no'] ?? '';
    $train_name = $_POST['train_name'] ?? '';
    $start_station = $_POST['start_station'] ?? '';
    $end_station = $_POST['end_station'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $ticket_price = $_POST['ticket_price'] ?? 0;
    $journey_date = $_SESSION['date'];
    $class = strtoupper($_SESSION['class']);
  ?> 
  <div class="container">
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
</div>
    <form id="passenger-form" method="post">
        <div id="container"></div>
        <input type="hidden" name="form_submitted" value="true">
        <input type="hidden" name="train_no" value="<?php echo htmlspecialchars($train_no); ?>">
        <input type="hidden" name="train_name" value="<?php echo htmlspecialchars($train_name); ?>">
        <input type="hidden" name="start_station" value="<?php echo htmlspecialchars($start_station); ?>">
        <input type="hidden" name="end_station" value="<?php echo htmlspecialchars($end_station); ?>">
        <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>">
        <input type="hidden" name="end_time" value="<?php echo htmlspecialchars($end_time); ?>">
        <input type="hidden" name="ticket_price" value="<?php echo htmlspecialchars($ticket_price); ?>">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($journey_date); ?>">
        <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
    </form>  

    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let totalpass = 0;
        let currentPassenger = 1;

        document.addEventListener("DOMContentLoaded", function() 
        {
            askPassenger();
        });

        function askPassenger() 
        {
            const utterance = new SpeechSynthesisUtterance("How many passengers would you like to book tickets for?");
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function() 
            {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;

                recognition.start();

                recognition.onresult = function(event)
                {
                    let speechResult = event.results[0][0].transcript.trim();
                    if (speechResult > 0 && speechResult < 5) 
                    {
                        totalpass = parseInt(speechResult);
                        createInputFields();
                        askNameForPassenger(currentPassenger);
                    }
                    else 
                    {
                        const retryUtterance = new SpeechSynthesisUtterance("You can only book up to 4 tickets.");
                        retryUtterance.lang = "en-IN";
                        speechSynthesis.speak(retryUtterance);
                        retryUtterance.onend = function()
                        {
                            askPassenger();
                        };
                    }
                    
                };

                recognition.onend = function()
                {
                    if (totalpass === 0) {
                        askPassenger();
                    }
                };
            }
        }

        function createInputFields()
        {
            const container = document.getElementById('container');
            container.innerHTML = ''; // Clear previous inputs

            for (let i = 1; i <= totalpass; i++)
            {
                
                const row = document.createElement('div');
                row.className = 'input-row';

                const form = document.getElementById('passenger-form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'form_submitted';
                hiddenInput.value = 'true';
                form.appendChild(hiddenInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = 'name[]';
                nameInput.id = `name[${i}]`;
                nameInput.placeholder = `Passenger ${i} Name`;
                row.appendChild(nameInput);

                const ageInput = document.createElement('input');
                ageInput.type = 'text';
                ageInput.name = 'age[]';
                ageInput.id = `age[${i}]`;
                ageInput.placeholder =`Passenger ${i} Age`;
                row.appendChild(ageInput);

                const genderInput = document.createElement('input');
                genderInput.type = 'text';
                genderInput.name = 'gender[]';
                genderInput.id = `gender[${i}]`;
                genderInput.placeholder =`Passenger ${i} Gender`;
                row.appendChild(genderInput);

                container.appendChild(row);
            }
        }

        function askNameForPassenger(passengerNumber)
        {
            if (passengerNumber > totalpass)
            {
                console.log("All passengers' details have been collected.");
                document.getElementById('passenger-form').submit();
                return;
            }

            const utterance = new SpeechSynthesisUtterance(`Please say the name of passenger ${passengerNumber} and spell it out.`);
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function()
            {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;

                recognition.start();

                recognition.onresult = function(event) 
                {
                    let speechResult = event.results[0][0].transcript.trim();
                    speechResult = speechResult.replace(/\.$/, '');
                    const nameInput = document.querySelector(`input[id='name[${passengerNumber}]']`);
                    if (nameInput) 
                    {
                        nameInput.value = speechResult;
                        askAgeForPassenger(passengerNumber);
                    } 
                    else 
                    {
                        askNameAgain(passengerNumber);
                    }
                };

                recognition.onend = function()
                {
                    const nameInput = document.querySelector(`input[id='name[${passengerNumber}]']`);
                    if (!nameInput || !nameInput.value.trim())
                    {
                        askNameAgain(passengerNumber);
                    }
                };
            }
        }

        function askNameAgain(passengerNumber) 
        {
            const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that. ");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() 
            {
                askNameForPassenger(passengerNumber);
            };
        }

        function askAgeForPassenger(passengerNumber) 
        {
            const utterance = new SpeechSynthesisUtterance(`Please say the age of passenger ${passengerNumber}.`);
            utterance.lang = "en-IN";
            speechSynthesis.speak(utterance);

            utterance.onend = function()
            {
                let recognition = new SpeechRecognition();
                recognition.lang = "en-IN";
                recognition.interimResults = false;
                recognition.continuous = false;

                recognition.start();

                recognition.onresult = function(event) 
                {
                    let speechResult = event.results[0][0].transcript.trim();
                    if (!isNaN(speechResult) && Number.isInteger(Number(speechResult))) 
                    {
                        const ageInput = document.querySelector(`input[id='age[${passengerNumber}]']`);
                        if (ageInput) 
                        {
                            ageInput.value = speechResult;
                            askGenderForPassenger(passengerNumber);
                        } 
                        else 
                        {
                            askAgeAgain(passengerNumber);
                        }
                    } 
                    else 
                    {
                        askAgeAgain(passengerNumber);
                    }
                };

                recognition.onend = function() 
                {
                    const ageInput = document.querySelector(`input[id='age[${passengerNumber}]']`);
                    if (!ageInput || !ageInput.value.trim()) 
                    {
                        askAgeAgain(passengerNumber);
                    }
                };
            };
        }

        function askAgeAgain(passengerNumber) {
            const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() 
            {
                askAgeForPassenger(passengerNumber);
            };
        }

        
        function askGenderForPassenger(passengerNumber)
        {
             const utterance2 = new SpeechSynthesisUtterance(`Please say the  gender of passenger ${passengerNumber}.`);
            utterance2.lang = "en-IN";
            speechSynthesis.speak(utterance2);

            utterance2.onend = function() 
            {
                let recognition2= new SpeechRecognition();
                recognition2.lang = "en-IN";
                recognition2.interimResults = false;
                recognition2.continuous = false;

                recognition2.start();

                recognition2.onresult = function(event)
                {
                    let speechResult2 = event.results[0][0].transcript.trim();
                    speechResult2 = speechResult2.replace(/\.$/, '');
                    if (speechResult2.toLowerCase() === 'mail')
                    {
                    speechResult2 = 'male';
                    }
                    if (speechResult2.toLowerCase() === 'email')
                    {
                    speechResult2 = 'female';
                    }
                    if (speechResult2) 
                    { 
                        const genderInput = document.querySelector(`input[id='gender[${passengerNumber}]']`);
                    if (genderInput) 
                    {
                        genderInput.value = speechResult2;
                        currentPassenger++;
                        askNameForPassenger(currentPassenger);
                    } 
                    else 
                    {
                        askGenderAgain(passengerNumber);
                    }
                    }
                    else 
                    {
                    askGenderAgain(passengerNumber);
                    }
                };
                recognition2.onend = function() 
                {
                    const genderInput = document.querySelector(`input[id='gender[${passengerNumber}]']`);
                    if (!genderInput || !genderInput.value.trim())
                     {
                        askGenderAgain(passengerNumber);
                    }
                };
            };
        }
        function askGenderAgain(passengerNumber) 
        {
            const retryUtterance2 = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance2.lang = "en-IN";
            speechSynthesis.speak(retryUtterance2);
            retryUtterance2.onend = function() 
            {
                askGenderForPassenger(passengerNumber);
            };
        }

    </script>

<?php
if (isset($_POST['form_submitted'])) 
{
$p_id = $_SESSION['p_id'];

    $query="select booking_id from booking order by booking_id desc limit 1;";
     $result=mysqli_query($con,$query);
     if($result->num_rows>0)
     {
     while($row=$result->fetch_assoc())   
     {
        $r=$row["booking_id"];
        $ans= $r+1;
     }
    }
    
    $p=[$p_id];
    $names = $_POST['name'] ?? [];
    $ages = $_POST['age'] ?? [];
    $genders = $_POST['gender'] ?? [];

    $t_no =[$_POST['train_no']] ?? '';
    $cls = [$_POST['class']] ?? '';
    $jd = [$_POST['date']] ?? '';

    $passenger_details = [];
    $values = [];
    $calculated_prices = [];

    $b_id[0]=$ans; 
    $d = date('Y-m-d');
    $bd=[$d];
    $no_of_pas=count($names);
    $disable[]='yes';
    
  
    $_SESSION['no_of_pas'] = $no_of_pas;
    $_SESSION['p_id']=$p_id;

    for ($i = 0; $i < count($names); $i++) 
    {
        $name = mysqli_real_escape_string($con, $names[$i]); 
        $age = intval(mysqli_real_escape_string($con, $ages[$i]));
        $gender = mysqli_real_escape_string($con, $genders[$i]);
        $booking_id=mysqli_real_escape_string($con, $b_id[0]);
        $train_no=mysqli_real_escape_string($con, $t_no[0]);
        $class=mysqli_real_escape_string($con, $cls[0]);
        $journey_date=mysqli_real_escape_string($con, $jd[0]);
        $booking_date = mysqli_real_escape_string($con, $bd[0]);
        $p_id=mysqli_real_escape_string($con, $p[0]);
        $dis=mysqli_real_escape_string($con, $disable[0]);
        if ($age <= 5) 
        {
            $calculated_prices[$i] = 0;
        } 
        else 
        {
            if ($class == 'AC' || $class == 'SLEEPER' || $class == 'LADIES')
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
        $values[] = "('$name', '$age', '$gender', '$calculated_price','$booking_id','$train_no','$journey_date','$p_id','$booking_date','$class','$dis')";
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
        echo $calculated_price;
    }
    if (!empty($values))
    {
        $valuesStr = implode(", ", $values);
        $ins = "INSERT INTO booking (name, age, gender, ticket_price,booking_id,train_no,journey_date,p_id,booking_date,class,disabled) VALUES $valuesStr";
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

        echo "Name: $name, Age: $age, Gender: $gender, Calculated Price: $calculated_price<br>";


            session_write_close();
            header('Location: visualconfirm.php');
            exit();
        }
        else
        {
            echo '<script>window.location.href="visualperson.php";</script>';
        }
    }
}
if (isset($con) && $con) 
{
    mysqli_close($con);
}
?>
</body>
</html>
