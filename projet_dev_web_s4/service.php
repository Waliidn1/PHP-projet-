<?php
$mysqli = new mysqli('localhost', 'root', '', 'dataweb');

if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

$dateOrder = 'ASC'; 
$typeFilter = '';
$statusFilter = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sort_date'])) {
        $dateOrder = $_POST['sort_date'] === 'ASC' ? 'DESC' : 'ASC';
    }
    
    if (isset($_POST["type"]) && in_array($_POST["type"], ['Wifi', 'Plomberie', 'Electrique'])) {
        $typeFilter = $mysqli->real_escape_string($_POST["type"]);
    }
    
    if (isset($_POST['status']) && in_array($_POST['status'], ['En cours', 'En Attente', 'Terminé'])) {
        $statusFilter = $mysqli->real_escape_string($_POST['status']);
    }
}

$query = "SELECT * FROM intervention WHERE 1=1";

if ($typeFilter !== '') {
    $query .= " AND `type d'intervention` = '$typeFilter'";
}

if ($statusFilter !== '') {
    $query .= " AND statut = '$statusFilter'";
}

$query .= " ORDER BY date $dateOrder";

$result = $mysqli->query($query);





// Fonction pour construire un calendrier
function construire_calendrier($mois, $annee) {
    // Jours de la semaine
    $joursSemaine = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    // Premier jour du mois
    $premierJour = mktime(0, 0, 0, $mois, 1, $annee);
    // Nombre de jours dans le mois
    $nombreJours = date('t', $premierJour);
    // Composants de la date pour le premier jour
    $composantsDate = getdate($premierJour);
    // Nom du mois
    $nomMois = $composantsDate['month'];
    // Jour de la semaine pour le 1er jour
    $jourSemaine = $composantsDate['wday'];
    
    // Début du calendrier
    $calendrier = "<table class='table table-bordered'>";
    // En-tête avec nom du mois et année
    $calendrier .= "<header><h2>$nomMois $annee</h2></header><tr>";
    // Jours de la semaine en en-tête
    foreach($joursSemaine as $jour) {
        $calendrier .= "<th class='header'>$jour</th>";
    }

    // Initialisation du jour courant
    $jourCourant = 1;
    $calendrier .= "</tr><tr>";
    // Ajout de cases vides si nécessaire avant le premier jour du mois
    if ($jourSemaine > 0) { 
        for($k = 0; $k < $jourSemaine; $k++){
            $calendrier .= "<td></td>"; 
        }
    }

    // Formatage du mois pour l'affichage
    $mois = str_pad($mois, 2, "0", STR_PAD_LEFT);
    // Boucle sur tous les jours du mois
    while ($jourCourant <= $nombreJours) {
        // Passage à la ligne suivante en fin de semaine
        if ($jourSemaine == 7) {
            $jourSemaine = 0;
            $calendrier .= "</tr><tr>";
        }
        
        // Formatage du jour courant pour l'affichage
        $jourCourantRel = str_pad($jourCourant, 2, "0", STR_PAD_LEFT);
        $date = "$annee-$mois-$jourCourantRel";
        // Ajout du jour courant au calendrier
        $calendrier .= "<td class='day' rel='$date'>$jourCourant</td>";
        $jourCourant++;
        $jourSemaine++;
    }
    // Remplissage avec des cases vides en fin de mois si nécessaire
    if ($jourSemaine != 7) { 
        $joursRestants = 7 - $jourSemaine;
        for($l = 0; $l < $joursRestants; $l++){
            $calendrier .= "<td></td>"; 
        }
    }
    // Fin du calendrier
    $calendrier .= "</tr></table>";
    return $calendrier;
}

// Démarrage de la mise en mémoire tampon
// Cette fonction démarre la mise en mémoire tampon. Cela signifie que tout ce qui est normalement envoyé directement au navigateur est plutôt stocké en mémoire temporairement.
ob_start(); 

// Affichage du calendrier pour le mois et l'année courants
echo construire_calendrier(date('m'), date('Y'));

// Récupération du contenu du calendrier depuis la mémoire tampon
//  Cette fonction récupère le contenu actuel de la mémoire tampon (dans ce cas, le HTML du calendrier) et l'assigne à la variable $calendrierHtml. La mémoire tampon est ensuite nettoyée et fermée.
$calendarHtml = ob_get_clean();


//Envoi des données à la BDD :
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Nom'])) {
    // Assign form data to variables
    $dateIntervention = $_POST['date'];
    $client = $_POST['Nom'];
    $adresse = $_POST['Adresse'];
    $codePostal = $_POST['Code_Postal'];
    $numeroTel = $_POST['Telephone'];
    $typeIntervention = $_POST['type'];
    $superficie = $_POST['Superficie'];
    $nombreDAppareil = $_POST['Nombre_d_appareil'];
    $distanceALaRoute = $_POST['Distance_a_la_Route'];
    $statut = 'En Attente'; // Define default value for 'statut'

    $stmt = $mysqli->prepare("INSERT INTO intervention (date, client, adresse, `code postal`, `numero tel`, `type d'intervention`, superficie, nombre_d_appareil, distance_a_la_route, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if prepare() was successful
    if ($stmt === false) {
        // Handle error, prepare() failed
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("ssssssiids", $dateIntervention, $client, $adresse, $codePostal, $numeroTel, $typeIntervention, $superficie, $nombreDAppareil, $distanceALaRoute, $statut);

    if ($stmt->execute()) {
    } else {
        echo "Error inserting records: " . $stmt->error;
    }

    $stmt->close();
}



//Fin PHP
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="C:\wamp64\www\B2\Full Stack\service.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fbd361;
        }
        .head {
            position: fixed;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 15%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: #05659fff;
        }
        .head h1 {
            font-size: 500%;
        }
        .head h2 {
            font-size: 200%
        }
        .head .logo {
            position: fixed;
            top: 2%;
            left: 2%;
            width: 7%;
        }
        .head .date {
            position: fixed;
            top: 2%;
            right: 10%
        }
        .head .Mon_profil {
            position: fixed;
            top: 2%;
            right: 2%;
            width: 5%;
        }
        .left_column {
            position: fixed;
            top: 15%;
            left: 0%;
            width: 25%;
            height: 100%;
            background-color: #b867c4;
            display: flex;
            flex-direction : column;
            box-shadow: 5px 0px 10px 0px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            padding: 2%
            justify-content: center;
        }


        .left_column table {
        width: 100%; 
        border-collapse: collapse; 
    
        }

        .left_column th, .left_column td {
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }

        .left_column th {
            background-color: #f2f2f2; 
            color: #333; 
        }

        .left_column tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        .left_column tr:hover {
            background-color: #e8e8e8;
        }


        .tarif {
            width: 30%;
            height: 70%;
            position: fixed;
            right: 40%;
            top: 25%;
            padding: 10px;
            background-color: #b867c4;
            border-radius: 7%;
            box-shadow: 10px 10px 10px 0px rgba(0, 0, 0, 0.5);
        }

        form{
            width: 100%;
            height: 100%;
        }

        .tarif .tableau_tarif {
            border-collapse: collapse;
            width: 100%;
            height: 100%;
        }

        .tarif .tableau_tarif th,
        .tarif .tableau_tarif td {
            text-align: center;
            padding: 5px;
            border: 1px solid white;
        }

        .tarif .tableau_tarif tr:last-child {
            border: none;
        }
        
        button {
            width: 100%;
            height: 100%;
            background-color: #05659fff;
            color: white; 
            padding: 10px 20px;
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
        }


    

        .Calendrier {
            position: fixed;
            right: 1%;
            top: 25%;
            width: 30%;
            overflow: hidden;
            padding: 10px;
            background-color: #b867c4;
            border-radius: 10%;
            box-shadow: 10px 10px 10px 0px rgba(0, 0, 0, 0.5);

        }
        table.table.table-bordered {
            width: 100%;
            max-width: 100%;
            table-layout: fixed;
        }
        table.table.table-bordered td,
        table.table.table-bordered th {
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="head">
        <h1>Service</h1>
        <a href="/*Lien de l'espace client ou intervenant en fonction*/">
            <img src="projet_dev_web_photos/logo.png" alt="Logo" class="logo">
        </a>
        <div class="date">        
            <h2><?php echo date("d-m-Y"); ?></h2><br>
        </div>
        <a href="/*Lien de l'espace info perso*/" class="photo_profil">
            <img src="projet_dev_web_photos/mon_profil.png" alt="Mon profil" class="Mon_profil">
        </a>
    </div>
    <div class="left_column">
        <h1>Intervention</h1>
        <form action="" method="post">
            <table border='1'>
                <tr>
                    <th>
                        Date
                        <button name="sort_date" value="<?php echo $dateOrder; ?>" type="submit">Sort</button>
                    </th>
                    <th>
                        Type
                        <select name="type" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="Wifi">Wifi</option>
                            <option value="Plomberie">Plomberie</option>
                            <option value="Electrique">Electrique</option>
                        </select>
                    </th>
                    <th>
                        Status
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="En cours">En cours</option>
                            <option value="En Attente">En Attente</option>
                            <option value="Terminé">Terminé</option>
                        </select>
                    </th>
                </tr>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row["type d'intervention"]); ?></td>
                            <td><?php echo htmlspecialchars($row['statut']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No results found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </form>
    </div>

        <div class="tarif">
            <form action="" method="post">
                <table class="tableau_tarif">
                    <tr><th>Spécification</th>          <th>Quantité</th></tr>

                    <tr><td>Nom du Client</td><td><input type="text" name="Nom" required></td></tr>
                    <tr><td>Adresse</td><td><input type="text" name="Adresse" required></td></tr>
                    <tr><td>Code Postal</td><td><input type="text" name="Code_Postal" required></td></tr>
                    <tr><td>Téléphone</td><td><input type="text" name="Telephone" required></td></tr>

                    <tr><td>Type d'intervention</td>    <td><select name="type" required>
                                                            <option value="">All</option>
                                                            <option value="Wifi">Wifi</option>
                                                            <option value="Plomberie">Plomberie</option>
                                                            <option value="Electrique">Electrique</option>
                                                        </select><br></td></tr>
                    <tr><td>Superficie</td>          <td><input type="number" name="Superficie" required><br>M²</td></tr>
                    <tr><td>Nombre d'appareils</td> <td><input type="number" name="Nombre_d_appareil" required></td></tr>
                    <tr><td>Distance à la Route</td> <td><input type="number" name="Distance_a_la_Route" required> <br>M</td></tr>
                    <tr><td>Date</td><td><input type="text" name="date" placeholder="jj/mm/aaaa" required></td></tr>

                    <tr><td colspan=2><button type="submit">Validé</button></td></tr> 
                </table>
            </form>
        </div>


    <div class="Calendrier">
            <div class="col-md-12">
                <div id="calendar">
                    <?php echo $calendarHtml; ?>
                </div>
            </div>
    </div>

    <script>
        // Set the selected option based on the current filter
        document.querySelector('select[name="type"]').value = '<?php echo $typeFilter; ?>';
        document.querySelector('select[name="status"]').value = '<?php echo $statusFilter; ?>';
    </script>
</body>
</html>
