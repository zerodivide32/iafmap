<?php

/* 
 * MySQL database connection info
 */

$username = "dbuser";
$password = "dbpass";
$database = "fetchy";

// Opens a connection to a MySQL server
$connection = mysqli_connect('localhost', $username, $password);
if (mysqli_connect_errno()) {
    die('Not connected : ' . mysqli_connect_error());
}

// Set the active MySQL database
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
    die('Can\'t use db : ' . mysqli_error());
}

