<?php
    //Connection to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "evangelion";

    $conn = new mysqli($servername, $username, $password, $db_name);

    if ($conn->connect_error) {
        die("Error connecting to database". $conn->connect_error);  
    }

