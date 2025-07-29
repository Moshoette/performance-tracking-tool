<?php
$hostname = 'localhost:3306'; // or 'localhost:3307' if using that port
$username = 'root';
$password = '#Moshoette28'; // make sure this is correct
$database = 'smmes';

$con = mysqli_connect($hostname, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
