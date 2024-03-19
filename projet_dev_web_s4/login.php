<?php
session_start();

// Message d'erreur initial vide
$message = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "dataweb");

    // Vérifie la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupère et nettoie les données du formulaire
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $password = $conn->real_escape_string($_POST['password']);

    // Prépare la requête SQL pour trouver l'utilisateur
    // Assurez-vous que les noms de colonnes correspondent exactement à ceux de votre base de données
    $sql = "SELECT `id`, `nom`, `mot de passe` FROM utilisateur WHERE `numero de tel` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $telephone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Récupère les données de l'utilisateur
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['mot de passe'])) {
            // Mot de passe correct, démarrage de la session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nom'];
            // Redirige vers client_page.php
            header("TypeCompte.php");
            exit;
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Utilisateur non trouvé.";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            background-color: #fbd661;
            margin: 0;
            font-family: 'Arial', sans-serif;
            padding-top: 50px; /* added padding to push the content down */
        }

        h1 {
            width: 100%;
            height: 50px; /* adjusted height */
            text-align: center;
            background-color: #05659fff;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            margin: 0;
            /* removed position: fixed to allow the bar to scroll with the page */
        }

        form {
            width: 80%;
            margin: 20px auto; /* added some margin to the top and bottom */
        }

        .Question-Box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 6px;
            margin-bottom: 6px;
            border-radius: 5px;
        }

        label {
            flex: 1;
        }

        input {
            flex: 2;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .Question-Box:last-child {
            text-align: center;
            margin-top: 16px;
        }

        input[type="submit"] {
            padding: 12px 24px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 16px;
        }

        p a {
            font-size: 16px;
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }

        p a:hover {
            color: #2980b9;
        }

        .message {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>Connexion</h1>
<?php if (!empty($message)): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>
<form action="TypeCompte.php" method="post">
    <div class="Question-Box">
        <label for="telephone">Téléphone</label>
        <input type="tel" id="telephone" name="telephone" required>
    </div>
    <div class="Question-Box">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <input type="submit" value="Se connecter">
    </div>
</form>
<p>Pas encore inscrit? <a href="register.php">Inscrivez-vous ici</a></p>
</body>
</html>
