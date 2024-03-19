<?php

// Include database connection
require_once "connexionBDD.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $newPassword = $_POST['new_password'];
    $userId = $_POST['user_id']; 

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL to update the user's password
    $sql = "UPDATE utilisateur SET `mot de passe` = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("si", $hashedPassword, $userId);
    
    if ($stmt->execute()) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
