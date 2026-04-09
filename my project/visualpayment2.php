<?php
session_start();
include 'include.php';
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
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #162447, #1f4068);
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            font-size: 1.8rem;
            color: #162447;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 1.5rem;
            color: #1f4068;
            margin-bottom: 30px;
        }
        button {
            background-color: #1f4068;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #162447;
        }
    </style>
</head>
<body>   
    <?php   
    $start_station=$_SESSION['start_station'];
    $end_station=$_SESSION['end_station'];
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
                        echo $ref_id.' ';
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
                        echo $ref_id.' ';
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
    mysqli_close($con);    
    ?>
     <div class="container">
     <h1>Payment done...Ticket booked Successfully!....</h1>
    <h1>Your PNR Number <?php echo $pnr?></h1>

    <form action="" method="post">        
        <button type="button" onClick="ticket()">View Ticket</button>
    </form> 
</div>
    <script>
        const pnr = <?php echo json_encode($pnr); ?>;
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        function tell()
        {
            const utterance = new SpeechSynthesisUtterance(`Payment done...Ticket booked Successfully!..Your PNR Number is ${pnr}...if you want to know about your ticket say yes... if not no`);
            utterance.lang = "en-IN"; 
            speechSynthesis.speak(utterance);
            utterance.onend = function() 
            {
                console.log("Speech finished, starting recognition");
                let recognition3= new SpeechRecognition();
                recognition3.lang = "en-IN";
                recognition3.interimResults = false;
                recognition3.continuous = false;

                recognition3.start();

                recognition3.onresult = function(event)
                {
                    let speechResult3 = event.results[0][0].transcript.trim().toLowerCase();
                    if (speechResult3.includes("ok")||speechResult3.includes("yes")||speechResult3.includes("show the ticket")) 
                    {
                    window.location.href = 'visualticketgeneration.php';
                    } 
                    else if (speechResult3.includes("repeat again")||speechResult3.includes("say again")||speechResult3.includes("once more")) 
                    {
                        const noUtterance = new SpeechSynthesisUtterance("Okay");
                        noUtterance.lang = "en-IN";
                        speechSynthesis.speak(noUtterance);
                        noUtterance.onend = () => tell();
                    } 
                    else 
                    {
                    const errorUtterance = new SpeechSynthesisUtterance("Sorry, I didn't catch that...");
                    errorUtterance.lang = "en-IN";
                    speechSynthesis.speak(errorUtterance);
                    errorUtterance.onend = () => tell();
                    }
                }
            };

        }
        tell();
       
    </script>

</body>
</html>