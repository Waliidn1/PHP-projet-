<?php
// Start a session
session_start();

// Define variables for database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dataweb";

// Initialize message variable
$message = "";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    // Assuming `email` field is supposed to be `numero de tel` based on database structure
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $password = $conn->real_escape_string($_POST['password']);
    $password_cfg = $conn->real_escape_string($_POST['password_cfg']);
    $adresse = $conn->real_escape_string($_POST['adresse']);
    $code_postal = $conn->real_escape_string($_POST['code_postal']);
    // `ville` field is not present in your database schema
    // Assuming `type de compte` and `role` fields have predefined values or are collected from the form
    $type_de_compte = "client"; // Example value, adjust as necessary
    $role = "utilisateur"; // Example value, adjust as necessary

    // Check if passwords match
    if ($password !== $password_cfg) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, `mot de passe`, `type de compte`, `numero de tel`, `code postal`, adresse, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nom, $prenom, $hashed_password, $type_de_compte, $telephone, $code_postal, $adresse, $role);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            // Redirect or do something upon success
        } else {
            $message = "Erreur lors de l'inscription: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
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
<h1>Inscription</h1>
<?php if (!empty($message)): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>
<form action="register.php" method="post">
    <!-- Add all your form fields here -->
    <div class="Question-Box">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Votre nom" required />
    </div>
    <div class="Question-Box">
        <label for="prenom">Prenom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Votre prenom" required />
    </div>
    <div class="Question-Box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required />
    </div>
    <div class="Question-Box">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required />
    </div>
    <div class="Question-Box">
        <label for="password_cfg">Confirmation du mot de passe</label>
        <input type="password" id="password_cfg" name="password_cfg" placeholder="Confirmez votre mot de passe" required />
    </div>
    <div class="Question-Box">
        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" placeholder="Votre adresse" required />
    </div>
    <div class="Question-Box">
        <label for="code_postal">Code postal</label>
        <input type="text" id="code_postal" name="code_postal" pattern="\d{5}" placeholder="Votre code postal (max. 5 chiffres)" required />
    </div>
    <div class="Question-Box">
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" placeholder="Votre ville" required />
    </div>
    <div class="Question-Box">
        <label for="telephone">Telephone</label>
        <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" placeholder="Votre numéro de téléphone" required />
    </div>
    <div>
        <input type="submit" value="S'inscrire" />
    </div>
</form>
<p>Déjà inscrit? <a href="login.php">Connexion</a></p>
</body>
</html>
