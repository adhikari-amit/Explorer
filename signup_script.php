<?php

require("includes/common.php");

  // Getting the values from the signup page using $_POST[] and cleaning the data submitted by the user.
  $name = $_POST['name'];
  $name = mysqli_real_escape_string($con, $name);

  $email = $_POST['email'];
  $email = mysqli_real_escape_string($con, $email);

  $password = $_POST['password'];
  $password = mysqli_real_escape_string($con, $password);
  $password = MD5($password);

  $phone = $_POST['phone'];
  $phone = mysqli_real_escape_string($con, $phone);

  $dob = $_POST['dob'];
  $dob = mysqli_real_escape_string($con, $dob);

  $query = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  $num = mysqli_num_rows($result);
  
  if ($num != 0) {

  } else {
    
    $query = "INSERT INTO users(name,email,password,phone,dob)VALUES('" . $name . "','" . $email . "','" . $password . "','" . $phone . "','" . $dob. "')";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $user_id = mysqli_insert_id($con);
    $_SESSION['email'] = $email;
    header('location: index.php');
   }
?>