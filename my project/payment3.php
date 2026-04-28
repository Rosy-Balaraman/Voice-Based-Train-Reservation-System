<?php
session_start();
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
            background-color: white;
            color: black;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: navy;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        button {
            background-color: navy;
            color: white;
            font-size: 1.2em;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: blue;
        }
        form {
            margin-top: 40px;
        }
        .reward-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            text-align: center;
            display: block; /* Make it visible when session value is set */
        }
        .reward-popup h2 {
            color: darkorange;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .reward-popup button {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .reward-popup button:hover {
            background-color: darkorange;
        }
    </style>
<body>   
    <?php
    $start_station=$_SESSION['start_station'];
    $end_station=$_SESSION['end_station'];
    $start_time=$_SESSION['start_time'];
    $end_time=$_SESSION['end_time'];
    $no_of_pas= $_SESSION['no_of_pas'];
    $class= $_SESSION['class'];
    $pnr=$_SESSION['booking_id']; 
  
        $query2="update booking set payment_status='paid' where booking_id='$pnr';";
        if (mysqli_query($con, $query2))
        {
            $query3="select booking_id,p_id,train_no,journey_date,booking_date from booking where booking_id='$pnr' and payment_status='paid';";
            $result3=mysqli_query($con,$query3);
            if($result3)
            {
            while($row=$result3->fetch_assoc())   
            {
               $pnr=$row["booking_id"];
               $p=$row["p_id"];
               $tn=$row["train_no"];
               $jd=$row["journey_date"];
               $bd=$row["booking_date"];
            }
            }
        } 

    $query4="insert into pnr (pnr,p_id,train_no,journey_date,booking_date,source,destination,no_of_passenger) values ('$pnr','$p','$tn','$jd','$bd','$start_station','$end_station',$no_of_pas);";
    if(mysqli_query($con,$query4))
    {
        if ($class == 'AC' )
        {
            $query51="select compartment from compartment where train_no='$tn' and class='AC';";
            $result51=mysqli_query($con,$query51);
            if ($result51->num_rows > 0) 
            {                
                while ($row = $result51->fetch_assoc())
                {
                    $compartments[] = $row['compartment'];
                }
                $comp1 = isset($compartments[0]) ? $compartments[0] : null;
                $comp2 = isset($compartments[1]) ? $compartments[1] : null;         
            }
            $query5="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp1';";
            $result5=mysqli_query($con,$query5);
                if ($result5->num_rows > 0) 
                {                
                $row=$result5->fetch_assoc();  
                $booked_seat=$row["booked_seat"];
                $Max_seat=$row["Max_seat"];
                $avail_seat=$row["avail_seat"];
                
                if($booked_seat < $Max_seat)
                {
                $remain_pas = $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                $n_of_pas = $no_of_pas-$remain_pas;
                $n_booked_seat = $booked_seat+$n_of_pas;
                $n_avail_seat = $Max_seat-$n_booked_seat;
                $query6="update compartment set booked_seat='$n_booked_seat', avail_seat= '$n_avail_seat' where train_no='$tn' and compartment='$comp1';";
                if(mysqli_query($con,$query6))
                {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                        $row = $result31->fetch_assoc();
                        $ref_id = $row["ref_id"];    
                        $query12 = "UPDATE booking SET booking_status='booked',compartment='$comp1' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query12))
                        {
                        $query20="select seat_no from seat where compartment='$comp1'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp1',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp1',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                        }
                }
                if($remain_pas>0)
                {
                $query7="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp2';";
                $result7=(mysqli_query($con,$query7));
                if ($result7->num_rows > 0) 
                {
                 $row=$result7->fetch_assoc();
                 $c2_booked_seat=$row["booked_seat"];
                 $c2_Max_seat=$row["Max_seat"];
                 $c2_avail_seat=$row["avail_seat"];
                           
                if($c2_booked_seat < $c2_Max_seat)
                {
                 $remain_pas1= $c2_avail_seat > $remain_pas ? 0 : ($remain_pas - $c2_avail_seat);
                 $n_of_pas1 = $remain_pas-$remain_pas1;
                 $c2n_booked_seat=$n_of_pas1+$c2_booked_seat;
                 $c2n_avail_seat=$c2_Max_seat-$c2n_booked_seat;
                 $query8="update compartment set booked_seat='$c2n_booked_seat', avail_seat= '$c2n_avail_seat' where train_no='$tn' and compartment='$comp2';";
                 if(mysqli_query($con,$query8))
                 {
                    for ($i = 0; $i < $n_of_pas1; $i++) 
                    { 
                        $row = $result31->fetch_assoc();
                        $ref_id = $row["ref_id"];
                        $query13 = "UPDATE booking SET booking_status='booked',compartment='$comp2' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query13))
                        {
                        $query20="select seat_no from seat where compartment='$comp2'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                    }
                 }
                 if($remain_pas1>0)
                 { 
                    for ($i = 0; $i < $remain_pas1; $i++) 
                    { 
                    $ref_id = $ref_ids[$i];
                    $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                    mysqli_query($con, $query12);
                    }
                 }
                }
                }
                }
                } 
                elseif($booked_seat == $Max_seat)
                {
                $query9="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp2';";
                $result9=(mysqli_query($con,$query9));
                    if ($result9 && $result9->num_rows > 0) 
                    {
                    $row=$result9->fetch_assoc();   
                    $booked_seat=$row["booked_seat"];
                    $Max_seat=$row["Max_seat"];
                    $avail_seat=$row["avail_seat"];
        
                    if($booked_seat < $Max_seat)
                    {                    
                    $remain_pas= $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                    $n_of_pas = $no_of_pas-$remain_pas;
                    $n_booked_seat=$booked_seat+$n_of_pas;
                    $n_avail_seat=$Max_seat-$n_booked_seat;
                    $query10="update compartment set booked_seat='$n_booked_seat', avail_seat= '$n_avail_seat' where train_no='$tn' and compartment='$comp2';";
                    if(mysqli_query($con,$query10))
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='booked',compartment='$comp2' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query12))
                        {
                        $query20="select seat_no from seat where compartment='$comp2'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                        }
                    }
                    if($remain_pas>0)
                    {  
                        for ($i = 0; $i < $remain_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                        }
                    }
                    }
                    else
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $no_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                        }
                    }
                    }
                }
            }
                    
        } 

        elseif($class == 'AC 2 Tier') 
        {
            $query51 = "SELECT compartment FROM compartment WHERE train_no='$tn' AND class='AC 2 Tier';";
            $result51 = mysqli_query($con, $query51);
            
            if ($result51->num_rows > 0) 
            {                
                while ($row = $result51->fetch_assoc())
                {
                    $compartments[] = $row['compartment'];
                }
                $comp1 = isset($compartments[0]) ? $compartments[0] : null;   
            }
            
            $query5 = "SELECT booked_seat, Max_seat, avail_seat FROM compartment WHERE train_no='$tn' AND compartment='$comp1';";
            $result5 = mysqli_query($con, $query5);
            
            if ($result5->num_rows > 0) 
            {                
                $row = $result5->fetch_assoc();  
                $booked_seat = $row["booked_seat"];
                $Max_seat = $row["Max_seat"];
                $avail_seat = $row["avail_seat"];
                
                $remain_pas = 0; 
        
                if ($booked_seat < $Max_seat) 
                {
                    $remain_pas = $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                    $n_of_pas = $no_of_pas - $remain_pas;
                    $n_booked_seat = $booked_seat + $n_of_pas;
                    $n_avail_seat = $Max_seat - $n_booked_seat;
                    $query6 = "UPDATE compartment SET booked_seat='$n_booked_seat', avail_seat='$n_avail_seat' WHERE train_no='$tn' AND compartment='$comp1';";
                    
                    if (mysqli_query($con, $query6))
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];    
                            $query12 = "UPDATE booking SET booking_status='booked', compartment='$comp1' WHERE ref_id='$ref_id';";
                            
                            if (mysqli_query($con, $query12)) 
                            {
                                $query20 = "SELECT seat_no FROM seat WHERE compartment='$comp1' AND train_no='$tn' ORDER BY seat_no DESC LIMIT 1;";
                                $result20 = mysqli_query($con, $query20);
                                
                                if ($result20->num_rows > 0) 
                                {                
                                    $row = $result20->fetch_assoc();  
                                    $seat_no = $row["seat_no"] + 1; 
                                    $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                    
                                    if (mysqli_query($con, $query21)) 
                                    {
                                        $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                        mysqli_query($con, $query22);
                                    }
                                } 
                                elseif ($result20->num_rows == 0) 
                                {                                
                                    $seat_no = 1;
                                    $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                    
                                    if (mysqli_query($con, $query21)) 
                                    {
                                        $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                        mysqli_query($con, $query22);
                                    }
                                }
                            }
                        }
                        
                        if ($remain_pas > 0) 
                        { 
                            for ($i = 0; $i < $remain_pas; $i++) 
                            { 
                                $row = $result31->fetch_assoc();
                                $ref_id = $row["ref_id"];
                                $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                                mysqli_query($con, $query12);
                            }
                        }
                    }
                } 
                elseif ($booked_seat == $Max_seat) 
                {
                    $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                    $result31 = mysqli_query($con, $query31);
                    while ($row = $result31->fetch_assoc()) 
                    { 
                        $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                    }
                } 
            }
        } 

                elseif($class == 'AC 3 Tier') 
                {
                    $query51 = "SELECT compartment FROM compartment WHERE train_no='$tn' AND class='AC 3 Tier';";
                    $result51 = mysqli_query($con, $query51);
                    
                    if ($result51->num_rows > 0) 
                    {                
                        while ($row = $result51->fetch_assoc())
                        {
                            $compartments[] = $row['compartment'];
                        }
                        $comp1 = isset($compartments[0]) ? $compartments[0] : null;                         
                    }
                    
                    $query5 = "SELECT booked_seat, Max_seat, avail_seat FROM compartment WHERE train_no='$tn' AND compartment='$comp1';";
                    $result5 = mysqli_query($con, $query5);
                    
                    if ($result5->num_rows > 0) 
                    {                
                        $row = $result5->fetch_assoc();  
                        $booked_seat = $row["booked_seat"];
                        $Max_seat = $row["Max_seat"];
                        $avail_seat = $row["avail_seat"];
                        
                        $remain_pas = 0; 
                
                        if ($booked_seat < $Max_seat) 
                        {
                            $remain_pas = $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                            $n_of_pas = $no_of_pas - $remain_pas;
                            $n_booked_seat = $booked_seat + $n_of_pas;
                            $n_avail_seat = $Max_seat - $n_booked_seat;
                            $query6 = "UPDATE compartment SET booked_seat='$n_booked_seat', avail_seat='$n_avail_seat' WHERE train_no='$tn' AND compartment='$comp1';";
                            
                            if (mysqli_query($con, $query6))
                            {
                                $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                                $result31 = mysqli_query($con, $query31);
                                for ($i = 0; $i < $n_of_pas; $i++) 
                                {
                                    $row = $result31->fetch_assoc();
                                    $ref_id = $row["ref_id"];    
                                    $query12 = "UPDATE booking SET booking_status='booked', compartment='$comp1' WHERE ref_id='$ref_id';";
                                    
                                    if (mysqli_query($con, $query12)) 
                                    {
                                        $query20 = "SELECT seat_no FROM seat WHERE compartment='$comp1' AND train_no='$tn' ORDER BY seat_no DESC LIMIT 1;";
                                        $result20 = mysqli_query($con, $query20);
                                        
                                        if ($result20->num_rows > 0) 
                                        {                
                                            $row = $result20->fetch_assoc();  
                                            $seat_no = $row["seat_no"] + 1; 
                                            $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                            
                                            if (mysqli_query($con, $query21)) 
                                            {
                                                $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                                mysqli_query($con, $query22);
                                            }
                                        } 
                                        elseif ($result20->num_rows == 0) 
                                        {                                
                                            $seat_no = 1;
                                            $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                            
                                            if (mysqli_query($con, $query21)) 
                                            {
                                                $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                                mysqli_query($con, $query22);
                                            }
                                        }
                                    }
                                }
                                
                                if ($remain_pas > 0) 
                                { 
                                    for ($i = 0; $i < $remain_pas; $i++) 
                                    { 
                                        $row = $result31->fetch_assoc();
                                        $ref_id = $row["ref_id"];
                                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                                        mysqli_query($con, $query12);
                                    }
                                }
                            }
                        } 
                        elseif ($booked_seat == $Max_seat) 
                        {
                            $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                            $result31 = mysqli_query($con, $query31);
                            while ($row = $result31->fetch_assoc()) 
                            { 
                                $ref_id = $row["ref_id"];
                                $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                                mysqli_query($con, $query12);
                            }
                        } 
                    }
                }

        elseif($class=='sleeper')
        {
            $query51="select compartment from compartment where train_no='$tn' and class='sleeper';";
            $result51=mysqli_query($con,$query51);
            if ($result51->num_rows > 0) 
            {                
                while ($row = $result51->fetch_assoc())
                {
                    $compartments[] = $row['compartment'];
                }
                $comp1 = isset($compartments[0]) ? $compartments[0] : null;
                $comp2 = isset($compartments[1]) ? $compartments[1] : null;    
            }
            $query5="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp1';";
            $result5=mysqli_query($con,$query5);
                if ($result5->num_rows > 0) 
                {                
                $row=$result5->fetch_assoc();  
                $booked_seat=$row["booked_seat"];
                $Max_seat=$row["Max_seat"];
                $avail_seat=$row["avail_seat"];
                
                if($booked_seat < $Max_seat)
                {
                $remain_pas = $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                $n_of_pas = $no_of_pas-$remain_pas;
                $n_booked_seat = $booked_seat+$n_of_pas;
                $n_avail_seat = $Max_seat-$n_booked_seat;
                $query6="update compartment set booked_seat='$n_booked_seat', avail_seat= '$n_avail_seat' where train_no='$tn' and compartment='$comp1';";
                if(mysqli_query($con,$query6))
                {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                        $row = $result31->fetch_assoc();
                        $ref_id = $row["ref_id"];    
                        $query12 = "UPDATE booking SET booking_status='booked',compartment='$comp1' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query12))
                        {
                        $query20="select seat_no from seat where compartment='$comp1'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp1',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp1',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                        }
                }
                if($remain_pas>0)
                {
                $query7="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp2';";
                $result7=(mysqli_query($con,$query7));
                if ($result7->num_rows > 0) 
                {
                 $row=$result7->fetch_assoc();
                 $c2_booked_seat=$row["booked_seat"];
                 $c2_Max_seat=$row["Max_seat"];
                 $c2_avail_seat=$row["avail_seat"];
                           
                if($c2_booked_seat < $c2_Max_seat)
                {
                 $remain_pas1= $c2_avail_seat > $remain_pas ? 0 : ($remain_pas - $c2_avail_seat);
                 $n_of_pas1 = $remain_pas-$remain_pas1;
                 $c2n_booked_seat=$n_of_pas1+$c2_booked_seat;
                 $c2n_avail_seat=$c2_Max_seat-$c2n_booked_seat;
                 $query8="update compartment set booked_seat='$c2n_booked_seat', avail_seat= '$c2n_avail_seat' where train_no='$tn' and compartment='$comp2';";
                 if(mysqli_query($con,$query8))
                 {
                    for ($i = 0; $i < $n_of_pas1; $i++) 
                    { 
                        $row = $result31->fetch_assoc();
                        $ref_id = $row["ref_id"];
                        $query13 = "UPDATE booking SET booking_status='booked',compartment='$comp2' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query13))
                        {
                        $query20="select seat_no from seat where compartment='$comp2'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                    }
                 }
                 if($remain_pas1>0)
                 { 
                    for ($i = 0; $i < $remain_pas1; $i++) 
                    { 
                    $ref_id = $ref_ids[$i];
                    $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                    mysqli_query($con, $query12);
                    }
                 }
                }
                }
                }
                } 
                elseif($booked_seat == $Max_seat)
                {
                $query9="select booked_seat,Max_seat,avail_seat from compartment where train_no='$tn' and compartment='$comp2';";
                $result9=(mysqli_query($con,$query9));
                    if ($result9 && $result9->num_rows > 0) 
                    {
                    $row=$result9->fetch_assoc();   
                    $booked_seat=$row["booked_seat"];
                    $Max_seat=$row["Max_seat"];
                    $avail_seat=$row["avail_seat"];
        
                    if($booked_seat < $Max_seat)
                    {                    
                    $remain_pas= $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);

                    $n_of_pas = $no_of_pas-$remain_pas;
                    $n_booked_seat=$booked_seat+$n_of_pas;
                    $n_avail_seat=$Max_seat-$n_booked_seat;
                    $query10="update compartment set booked_seat='$n_booked_seat', avail_seat= '$n_avail_seat' where train_no='$tn' and compartment='$comp2';";
                    if(mysqli_query($con,$query10))
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='booked',compartment='$comp2' WHERE ref_id='$ref_id';";
                        if(mysqli_query($con, $query12))
                        {
                        $query20="select seat_no from seat where compartment='$comp2'and train_no='$tn' order by seat_no desc limit 1;";
                        $result20=mysqli_query($con, $query20);
                            if ($result20->num_rows > 0) 
                            {                
                            $row=$result20->fetch_assoc();  
                            $seat_no=$row["seat_no"];
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                            elseif($result20->num_rows == 0)
                            {                                
                                $seat_no= 0;
                                $seat_no=$seat_no+1;
                                $query21="insert seat (ref_id,p_id,train_no,class,compartment,seat_no)value('$ref_id','$p','$tn','$class','$comp2',$seat_no);";
                                if(mysqli_query($con, $query21))
                                {
                                    $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                    mysqli_query($con, $query22);
                                }
                            }
                        }
                        }
                    }
                    if($remain_pas>0)
                    {  
                        for ($i = 0; $i < $remain_pas; $i++) 
                        {
                        $row = $result31->fetch_assoc();
                        $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                        }
                    }
                    }
                    else
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $no_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                        }
                    }
                    }
                }
            }
          
        }   
        elseif($class == 'ladies') 
        {
            $query51 = "SELECT compartment FROM compartment WHERE train_no='$tn' AND class='ladies';";
            $result51 = mysqli_query($con, $query51);
            
            if ($result51->num_rows > 0) 
            {                
                while ($row = $result51->fetch_assoc())
                {
                    $compartments[] = $row['compartment'];
                }
                $comp1 = isset($compartments[0]) ? $compartments[0] : null;   
            }
            
            $query5 = "SELECT booked_seat, Max_seat, avail_seat FROM compartment WHERE train_no='$tn' AND compartment='$comp1';";
            $result5 = mysqli_query($con, $query5);
            
            if ($result5->num_rows > 0) 
            {                
                $row = $result5->fetch_assoc();  
                $booked_seat = $row["booked_seat"];
                $Max_seat = $row["Max_seat"];
                $avail_seat = $row["avail_seat"];
                
                $remain_pas = 0; 
        
                if ($booked_seat < $Max_seat) 
                {
                    $remain_pas = $avail_seat > $no_of_pas ? 0 : ($no_of_pas - $avail_seat);
                    $n_of_pas = $no_of_pas - $remain_pas;
                    $n_booked_seat = $booked_seat + $n_of_pas;
                    $n_avail_seat = $Max_seat - $n_booked_seat;
                    $query6 = "UPDATE compartment SET booked_seat='$n_booked_seat', avail_seat='$n_avail_seat' WHERE train_no='$tn' AND compartment='$comp1';";
                    
                    if (mysqli_query($con, $query6))
                    {
                        $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                        $result31 = mysqli_query($con, $query31);
                        for ($i = 0; $i < $n_of_pas; $i++) 
                        {
                            $row = $result31->fetch_assoc();
                            $ref_id = $row["ref_id"];    
                            $query12 = "UPDATE booking SET booking_status='booked', compartment='$comp1' WHERE ref_id='$ref_id';";
                            
                            if (mysqli_query($con, $query12)) 
                            {
                                $query20 = "SELECT seat_no FROM seat WHERE compartment='$comp1' AND train_no='$tn' ORDER BY seat_no DESC LIMIT 1;";
                                $result20 = mysqli_query($con, $query20);
                                
                                if ($result20->num_rows > 0) 
                                {                
                                    $row = $result20->fetch_assoc();  
                                    $seat_no = $row["seat_no"] + 1; 
                                    $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                    
                                    if (mysqli_query($con, $query21)) 
                                    {
                                        $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                        mysqli_query($con, $query22);
                                    }
                                } 
                                elseif ($result20->num_rows == 0) 
                                {                                
                                    $seat_no = 1;
                                    $query21 = "INSERT INTO seat (ref_id, p_id, train_no, class, compartment, seat_no) VALUES ('$ref_id', '$p', '$tn', '$class', '$comp1', $seat_no);";
                                    
                                    if (mysqli_query($con, $query21)) 
                                    {
                                        $query22 = "UPDATE booking SET seat=$seat_no WHERE ref_id='$ref_id';";
                                        mysqli_query($con, $query22);
                                    }
                                }
                            }
                        }
                        
                        if ($remain_pas > 0) 
                        { 
                            for ($i = 0; $i < $remain_pas; $i++) 
                            { 
                                $row = $result31->fetch_assoc();
                                $ref_id = $row["ref_id"];
                                $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                                mysqli_query($con, $query12);
                            }
                        }
                    }
                } 
                elseif ($booked_seat == $Max_seat) 
                {
                    $query31 = "SELECT ref_id FROM booking WHERE booking_id='$pnr';";
                    $result31 = mysqli_query($con, $query31);
                    while ($row = $result31->fetch_assoc()) 
                    { 
                        $ref_id = $row["ref_id"];
                        $query12 = "UPDATE booking SET booking_status='waiting' WHERE ref_id='$ref_id';";
                        mysqli_query($con, $query12);
                    }
                } 
            }
        }  
    }            
    ?>
   <h1>Payment Done...</h1>   
    <h1>Your PNR Number: <?php echo $pnr; ?></h1>

    <?php
    // Check if reward points were calculated and stored in the session
    if (isset($_SESSION['cal_reward'])) {
        $cal_reward = $_SESSION['cal_reward'];
        // Display the reward popup when reward points are available
        echo '<div id="rewardPopup" class="reward-popup">
                <h2>Congratulations!</h2>
                <p>You earned ' . $cal_reward . ' reward points.</p>
                <button onclick="closePopup()">OK</button>
              </div>';
    }
    ?>

    <form action="" method="post">        
        <button type="button" onClick="ticket()">View Ticket</button>
        <button type="button" onClick="back()">Home</button>
    </form>

    <script>
         window.onload = function () {
        const successSound = document.getElementById('successSound');
        successSound.play().catch((error) => {
            console.log('Audio playback error:', error);
        });
    };
        function ticket() {
            window.location.href = "ticketgeneration.php";
        }

        function back() {
            window.location.href = "passenger.php";
        }

        function closePopup() {
            document.getElementById('rewardPopup').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
$email = $_SESSION['email'];
$f_name = $_SESSION['f_name'];
$start_station = $_SESSION['start_station'];
$end_station = $_SESSION['end_station'];
$start_time = $_SESSION['start_time'];
$end_time = $_SESSION['end_time'];
$train_name = $_SESSION['train_name'];
$class = $_SESSION['class'];
$journey_date = $_SESSION['journey_date']; 
$total_price = $_SESSION['total_price'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        // For security reasons, the email and password will be hidden.
        $mail->Username = ''; 
        $mail->Password = ''; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        // For security reasons, the email and password will be hidden.
        $mail->setFrom('', 'Cheerful Journey');
        $mail->addAddress($email, $f_name); // The recipient email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation on Cheerful Journey, Train: ' . $tn . ', ' . $journey_date . ', ' . $class . ', ' . $start_station . ' to ' . $end_station;

        // Fetch passenger details from the database
        $query = "SELECT name, age, gender, seat, compartment, booking_status FROM booking WHERE booking_id = '$pnr';";
        $result = mysqli_query($con, $query);
        
        // Initialize the body with the booking details
        $mailBody = '
            <h3>Ticket Confirmation</h3>
            <h4>Dear ' . $f_name . ',</h4>
            <p>Thank you for using Cheerful Journey\'s online rail reservation facility. Your booking details are indicated below:</p>
            <ul>
                <li><strong>PNR Number:</strong> ' . $pnr . '</li>
                <li><strong>Train Number:</strong> ' . $tn . '</li>
                <li><strong>Train Name:</strong> ' . $train_name . '</li>
                <li><strong>From:</strong> ' . $start_station . '</li>
                <li><strong>To:</strong> ' . $end_station . '</li>
                <li><strong>Start Time:</strong> ' . $start_time . '</li>
                <li><strong>Arrival Time:</strong> ' . $end_time . '</li>
                <li><strong>Date of Journey:</strong> ' . $journey_date . '</li>
                <li><strong>Class:</strong> ' . $class . '</li>
            </ul>';

        if ($result->num_rows > 0) {
            $mailBody .= '<table border="1" cellpadding="5" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Seat No</th>
                                    <th>Compartment</th>
                                    <th>Booking Status</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($row = $result->fetch_assoc()) {
                $mailBody .= '<tr>
                                <td>' . $row['name'] . '</td>
                                <td>' . $row['age'] . '</td>
                                <td>' . $row['gender'] . '</td>
                                <td>' . $row['seat'] . '</td>
                                <td>' . $row['compartment'] . '</td>
                                <td>' . $row['booking_status'] . '</td>
                            </tr>';
            }
            $mailBody .= '</tbody></table>';
        }

        $mailBody .= '<h3>Ticket Fare: ₹' . $total_price . '</h3>';
        
        $mail->Body = $mailBody;

        $mail->send();
        echo '<script>alert("Email has been sent")</script>';
    } 
    catch (Exception $e)
    {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $con->close();


?>
