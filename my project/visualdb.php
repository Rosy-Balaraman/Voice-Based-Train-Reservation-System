<?php
session_start();
include 'db.php';

// Get the last `p_id` from the `visuser` table and generate a new one
$query = "SELECT p_id FROM visuser ORDER BY p_id DESC LIMIT 1;";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $p_id = $row["p_id"];
    $p_id = $p_id + 1;
    $p_id = $p_id . 'V';
} else {
    $p_id = 1; // Start from 1 if there is no previous record
    $p_id = $p_id . 'V';
}

// Prepare the user data for insertion
$name = strtolower($_POST['name']);
$gender = strtolower($_POST['gender']);
$mob = strtolower($_POST['mob']);
$cer_id = strtolower($_POST['cer_id']);
$aadhar = strtolower($_POST['aadhar']);
$password = strtolower($_POST['password']);
$confirm_pass = strtolower($_POST['confirm_pass']);
$pet = strtolower($_POST['pet']);
$birth = strtolower($_POST['birth']);

// Insert into the `visuser` table
$ins = "INSERT INTO visuser(p_id, name, gender, phn_no, cer_id, aadhar, password, confirm_password, pet, birth) 
        VALUES('$p_id', '$name', '$gender', '$mob', '$cer_id', '$aadhar', '$password', '$confirm_pass', '$pet', '$birth');";
$result_ins = mysqli_query($con, $ins);

if ($result_ins) {
    $query33 = "SELECT p_id FROM user ORDER BY p_id DESC LIMIT 1;";
    $result33 = mysqli_query($con, $query33);

    if ($result33 && mysqli_num_rows($result33) > 0) {
        $row = mysqli_fetch_assoc($result33);
        $p_id = $row["p_id"];

        $query = "INSERT INTO rewards(p_id) VALUES('$p_id');";
        $result_ins = mysqli_query($con, $query);

        if ($result_ins) {
            echo '<script> window.location="./visuallogin.php";</script>';
        } else {
            echo "Error inserting into rewards: " . mysqli_error($con);
        }
    } else {
        echo "Error fetching p_id from user table: " . mysqli_error($con);
    }
} else {
    echo "Error inserting into visuser: " . mysqli_error($con);
}

mysqli_close($con);
?>