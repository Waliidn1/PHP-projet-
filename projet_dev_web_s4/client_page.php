<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace client</title>
    <style>
        /* Styles pour la page */
        body body {
            background-color: #fbd661;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh; /* Assure que le body prend toute la hauteur de la fenêtre */
            display: flex;
            justify-content: center; /* Centre le contenu horizontalement */
            align-items: center; /* Centre le contenu verticalement */
        }

        h1 {
            position: absolute; /* Positionnement absolu pour ne pas perturber le centrage du formulaire */
            top: 0; /* Placé en haut de la page */
            width: 100%;
            background-color: #05659fff;
            color: white;
            text-align: center;
            margin: 0;
            padding: 15px 0;
        }

        form {
            width: 80%;
            max-width: 500px; /* Limite la largeur maximale du formulaire */
            background: white; /* Fond blanc pour le formulaire */
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombre pour une meilleure distinction du formulaire */
            display: flex;
            flex-direction: column; /* Organisation des éléments du formulaire en colonne */
        }

        .Question-Box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%; /* Prend toute la largeur du formulaire */
            margin-bottom: 10px; /* Espacement entre les éléments du formulaire */
        }

        label, input {
            width: 48%; /* Label et input prennent presque la moitié de la largeur du conteneur */
        }

        input[type="submit"] {
            width: auto; /* Adaptation automatique à la largeur du contenu */
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px; /* Espacement avant le bouton de soumission */
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Changement de couleur au survol */
        }

        p {
            text-align: center;
            margin-top: 16px;
        }

        p a {
            font-size: 16px;
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s; /* Effet de transition pour le survol des liens */
        }

        p a:hover {
            color: #2980b9; /* Changement de couleur au survol des liens */
        }

        .message {
            text-align: center;
            color: red;
            margin-top: 20px; /* Espacement avant le message d'erreur */
        }

    </style>
</head>
<body>
<div class="container">
    <h1>Espace client</h1>
    <table border="1">
        <thead>
        <tr>
            <th>Identifiant</th>
            <th>Date</th>
            <th>Client</th>
            <th>Statut</th>
            <th>Type d'intervention</th>
            <th>Adresse</th>
            <th>Code Postal</th>
            <th>Numero Tel</th>
            <th>Degré d'urgence</th>
        </tr>
        </thead>
        <tbody>
        <?php
        session_start();
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dataweb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ensure the phone number is stored in $_SESSION['phone_number'] during user login
        $phoneNumber = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : die("Veuillez vous connecter pour voir les interventions.");

        // Modify the SQL query to filter interventions by the phone number associated with the currently connected user
        $sql = "SELECT intervention.Identifiant, intervention.date, intervention.client, intervention.statut, intervention.`type d'intervention`, intervention.adresse, intervention.`code postal`, intervention.`numero tel`, intervention.`degré d'urgence` FROM intervention JOIN utilisateur ON intervention.userId = utilisateur.Id WHERE utilisateur.`numero tel` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $phoneNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statut_class = $row['statut'] === 'En cours' ? 'en-cours' : 'en-attente';
                echo '<tr>';
                echo '<td>' . $row['Identifiant'] . '</td>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['client'] . '</td>';
                echo '<td class="' . $statut_class . '">' . $row['statut'] . '</td>';
                echo '<td>' . $row['type d\'intervention'] . '</td>';
                echo '<td>' . $row['adresse'] . '</td>';
                echo '<td>' . $row['code postal'] . '</td>';
                echo '<td class="bleu">' . $row['numero tel'] . '</td>';
                echo '<td class="jaune">' . $row['degré d\'urgence'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="9">Aucune donnée trouvée pour cet utilisateur.</td></tr>';
        }

        $stmt->close();
        $conn->close();
        ?>

        </tbody>
    </table>
</div>
</body>
</html>

