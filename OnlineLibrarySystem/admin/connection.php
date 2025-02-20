<?php

    $db=mysqli_connect("localhost", "root", "", "online_library_management_system"); /* server name, username, password, database */ 

if(!$db)
    {
        die("Connection failed: ". mysqli_connect_error());
    }

?>