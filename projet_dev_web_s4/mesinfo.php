<!DOCTYPE html>
<html>
<head>
    <title>Update User Information</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<style>

body{
    background-color: #fbd361;

}

.logo, .my-profile {
    height: 50px;
    width: auto;
}

.date h2 {
    font-size: 1rem;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #05659fff;
    color: white;
}


footer {
    text-align: center;
    padding: 20px;
    background-color: #05659fff;
    color: #333333;
}

.footer-content {
    display: flex;
    justify-content: space-around;
    max-width: 100%;
    margin: 0 auto;
    color: black;
}

.footer-column {
    flex: 1;
    padding: 10px;
}

.footer-column h3 {
    font-size: 16px;
    margin-bottom: 10px;
}

.footer-column p {
    font-size: 14px;
    margin-bottom: 5px;
}

.footer-bottom {
    font-size: 12px;
    margin-top: 20px;
}
</style>
<div class="header">
            <a href="index3.php">
                <img src="projet_dev_web_photos/logo.png" alt="Logo" class="logo">
            </a>
            <h1>Service</h1>
            <div class="date">
                <h2><?php echo date("d-m-Y"); ?></h2><br>
            </div>
            <a href="login.php" class="profile-photo">
                <img src="projet_dev_web_photos/mon_profil.png" alt="My Profile" class="my-profile">
            </a>
        </div>

<?php
session_start(); // Start the session at the beginning
require_once "connexionBDD.php";

// Initialize variables
$userInfo = [];

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
//echo '<pre>'; print_r($userInfo); echo '</pre>';

?>


<div class="container">
    <h2>Update User Information</h2>

    <form action="updateMesInfo.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userInfo['id'] ?? '') ?>">

        <div class="form-row">
            <div class="form-group col-md-6">
                Nom: <input type="text" class="form-control" name="nom" required value="<?php echo htmlspecialchars($userInfo['nom'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                Prenom: <input type="text" class="form-control" name="prenom" required value="<?php echo htmlspecialchars($userInfo['prenom'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                Numero de Tel: <input type="text" class="form-control" name="numero_de_tel" required value="<?php echo htmlspecialchars($userInfo['numero de tel'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                Code Postal: <input type="text" class="form-control" name="code_postal" required value="<?php echo htmlspecialchars($userInfo['code postal'] ?? '') ?>">
            </div>
            <div class="form-group col-md-6">
                Adresse: <input type="text" class="form-control" name="adresse" required value="<?php echo htmlspecialchars($userInfo['adresse'] ?? '') ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="update">Update</button>
    </form>



    <!-- Separate Form for Password Update -->
    <h2>Change Password</h2>
    <form action="changePassword.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userInfo['id'] ?? '') ?>">

        <div class="form-group">
            New Mot de Passe: <input type="password" class="form-control" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-warning" name="change_password">CHANGER MDP</button>
    </form>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



<footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>Données personelles</h3>
                <h3>Cookies</h3>
                <h3>Conditions d'utilisation</h3>
            </div>
            <div class="footer-column">
                <h3>Service client :</h3>
                <p>Email: info@example.com</p>
                <p>Phone: +1 123-456-7890</p>
            </div>
            <div class="footer-column">
                <h3>Rejoignez nous sur :</h3>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
        </div>
        <p class="footer-bottom">© 2024 My Website. All rights reserved.</p>
    </footer>

</body>
</html>
