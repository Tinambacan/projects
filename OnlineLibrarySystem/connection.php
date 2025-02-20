<?php

    $db=mysqli_connect("localhost", "root", "", "online_library_management_system"); /* server name, username, password, database */ 

if(!$db)
    {
        die("Connecrtion failed: ". mysqli_connect_error());
    }

?>