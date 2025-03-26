<?php
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = "";
  $db_name = "soccershoestore";


  $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
  if ($conn) {
    echo"Your database connection is successful";
  }
  else {
    echo"Your database connection is not successful";
  }
?>