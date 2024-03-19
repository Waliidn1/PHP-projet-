<?php
// Replace these values with your database connection information
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

// Update user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $numero_de_tel = $_POST['numero_de_tel'];
  $code_postal = $_POST['code_postal'];
  $adresse = $_POST['adresse'];
  $user_id = $_POST['user_id']; 


  $sql = "UPDATE utilisateur SET nom=?, prenom=?, `numero de tel`=?, `code postal`=?, adresse=? WHERE id=?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssiisi", $nom, $prenom, $numero_de_tel, $code_postal, $adresse, $user_id);


  if ($stmt->execute()) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }

  $stmt->close();
}

header("Location: index3.php"); // Redirect to index3.php


$conn->close();
?>
