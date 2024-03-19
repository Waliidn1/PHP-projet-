<?php 

$mysqli = new mysqli('localhost', 'root', '', 'dataweb');

if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dataweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>