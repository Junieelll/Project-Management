<?php 
    $conn  = mysqli_connect("localhost", "root", "", "devsphere");
    if ($conn == false){
        die("connection error". mysqli_connect_error());
    }
?>