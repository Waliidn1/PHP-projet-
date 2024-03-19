<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: index3.php");
    exit;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "dataweb");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL to fetch the user's role
$sql = "SELECT `role` FROM utilisateur WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userRole = $row['role'];

    // Redirect based on role
    if ($userRole === 'Intervenant') {
        header("Location: index3.php");
    } elseif ($userRole === 'utilisateur') {
        header("Location: index3.php");
    } else {
        // Handle unexpected role
        echo "Role non reconnu.";
    }
} else {
    echo "Utilisateur non trouvé.";
}



if (isset($_SESSION['user_id'])) { // Check if session variable exists
    $userId = $_SESSION['user_id']; // Use session variable

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?"); // Query user by session user_id
    $stmt->bind_param("i", $userId); // Bind userId as integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userInfo = $result->fetch_assoc(); // Fetch user information based on session user_id
    }

    $stmt->close();
}

$stmt->close();
$conn->close();
?>
