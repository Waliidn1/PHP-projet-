<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Intervenant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #800080;
            color: #FFFFFF;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-edit {
            cursor: pointer;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        #form-edit-container {
            display: none;
            margin-top: 20px;
        }

         .logo, .my-profile {
             height: 50px;
             width: auto;
         }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #05659fff;
            color: white;
        }

        .header h1, .header h2 {
            margin: 0;
        }

        body{
        background-color: #fbd361;
        }

    </style>
</head>
<body>

<div class="header">
    <a href="index3.php">
        <img src="projet_dev_web_photos/logo.png" alt="Logo" class="logo">
    </a>
    <div class="date">
        <h2><?php echo date("d-m-Y"); ?></h2><br>
    </div>
    <a href="mesinfo.php" class="profile-photo">
        <img src="projet_dev_web_photos/mon_profil.png" alt="My Profile" class="my-profile">
    </a>
</div>


<div class="container">
    <h1>Espace Intervenant</h1>
    <!-- Formulaire de modification -->
    <div id="form-edit-container">
        <h2>Modifier Intervention</h2>
        <form id="form-edit" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" id="edit-id" name="id">
            <label for="edit-date">Date:</label>
            <input type="text" id="edit-date" name="date">
            <label for="edit-client">Client:</label>
            <input type="text" id="edit-client" name="client">
            <label for="edit-statut">Statut:</label>
            <input type="text" id="edit-statut" name="statut">
            <label for="edit-type">Type d'intervention:</label>
            <input type="text" id="edit-type" name="type">
            <label for="edit-adresse">Adresse:</label>
            <input type="text" id="edit-adresse" name="adresse">
            <label for="edit-codepostal">Code Postal:</label>
            <input type="text" id="edit-codepostal" name="codepostal">
            <label for="edit-numtel">Numéro Tel:</label>
            <input type="text" id="edit-numtel" name="numtel">
            <label for="edit-urgence">Degré d'urgence:</label>
            <input type="text" id="edit-urgence" name="urgence">
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
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
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dataweb";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Traitement de la mise à jour
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $id = $conn->real_escape_string($_POST['id']);
            // Collecte des données à jour
            $date = $conn->real_escape_string($_POST['date']);
            $client = $conn->real_escape_string($_POST['client']);
            $statut = $conn->real_escape_string($_POST['statut']);
            $type = $conn->real_escape_string($_POST['type']);
            $adresse = $conn->real_escape_string($_POST['adresse']);
            $codepostal = $conn->real_escape_string($_POST['codepostal']);
            $numtel = $conn->real_escape_string($_POST['numtel']);
            $urgence = $conn->real_escape_string($_POST['urgence']);

            // Mise à jour de l'enregistrement
            $sql = "UPDATE intervention SET date='$date', client='$client', statut='$statut', `type d'intervention`='$type', adresse='$adresse', `code postal`='$codepostal', `numero tel`='$numtel', `degré d'urgence`='$urgence' WHERE Identifiant='$id'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record updated successfully</p>";
            } else {
                echo "<p>Error updating record: " . $conn->error . "</p>";
            }
        }

        $sql = "SELECT Identifiant, date, client, statut, `type d'intervention`, adresse, `code postal`, `numero tel`, `degré d'urgence` FROM intervention";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['Identifiant'] . '</td>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['client'] . '</td>';
                echo '<td>' . $row['statut'] . '</td>';
                echo '<td>' . $row['type d\'intervention'] . '</td>';
                echo '<td>' . $row['adresse'] . '</td>';
                echo '<td>' . $row['code postal'] . '</td>';
                echo '<td>' . $row['numero tel'] . '</td>';
                echo '<td>' . $row['degré d\'urgence'] . '</td>';
                echo '<td><button class="btn-edit" data-id="' . $row['Identifiant'] . '" data-date="' . $row['date'] . '" data-client="' . $row['client'] . '" data-statut="' . $row['statut'] . '" data-type="' . $row['type d\'intervention'] . '" data-adresse="' . $row['adresse'] . '" data-codepostal="' . $row['code postal'] . '" data-numtel="' . $row['numero tel'] . '" data-urgence="' . $row['degré d\'urgence'] . '">Modifier</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="10">Aucune donnée trouvée</td></tr>';
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                document.getElementById('edit-id').value = id;

                // Ajout des valeurs aux champs du formulaire
                document.getElementById('edit-date').value = this.getAttribute('data-date');
                document.getElementById('edit-client').value = this.getAttribute('data-client');
                document.getElementById('edit-statut').value = this.getAttribute('data-statut');
                document.getElementById('edit-type').value = this.getAttribute('data-type');
                document.getElementById('edit-adresse').value = this.getAttribute('data-adresse');
                document.getElementById('edit-codepostal').value = this.getAttribute('data-codepostal');
                document.getElementById('edit-numtel').value = this.getAttribute('data-numtel');
                document.getElementById('edit-urgence').value = this.getAttribute('data-urgence');

                document.getElementById('form-edit-container').style.display = 'block';
            });
        });
    });
</script>
</body>
</html>
