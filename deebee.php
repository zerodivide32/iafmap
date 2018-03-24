<?php

/* 
 * MySQL database connection info
 */

$username = "mapuser";
$password = "mapusrpss";
$database = "iafmap";

// Opens a connection to a MySQL server
//$connection = mysqli_connect('localhost', $username, $password);
//if (mysqli_connect_errno()) {
//    die('Not connected : ' . mysqli_connect_error());
//}

$connection = pg_connect("host=localhost dbname=$database user=$username password=$password")
    or die('Could not connect: ' . pg_last_error());


// Set the active MySQL database
//$db_selected = mysqli_select_db($connection, $database);
//if (!$db_selected) {
//    die('Can\'t use db : ' . mysqli_error());
//}

