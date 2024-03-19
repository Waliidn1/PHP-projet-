<?php
include 'connexionBDD.php';

//Envoi des données à la BDD :
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Nom'])) {
    // Assignation des données du formulaire à des variables
    $dateIntervention = $_POST['date'];
    $client = $_POST['Nom'];
    $adresse = $_POST['Adresse'];
    $codePostal = $_POST['Code_Postal'];
    $numeroTel = $_POST['Telephone'];
    $typeIntervention = $_POST['type'];
    $superficie = $_POST['Superficie'];
    $nombreDAppareil = $_POST['Nombre_d_appareil'];
    $distanceALaRoute = $_POST['Distance_a_la_Route'];
    $statut = 'En Attente'; // Définition de la valeur par défaut pour 'statut'

    // Préparation de la requête SQL pour l'insertion des données dans la table 'intervention'
    $stmt = $mysqli->prepare("INSERT INTO intervention (date, client, adresse, `code postal`, `numero tel`, `type d'intervention`, superficie, nombre_d_appareil, distance_a_la_route, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Vérification si prepare() a réussi
    if ($stmt === false) {
        // Gestion de l'erreur si prepare() a échoué
        die("Prepare failed: " . $mysqli->error);
    }

    // Liaison des paramètres à la requête préparée
    $stmt->bind_param("ssssssiids", $dateIntervention, $client, $adresse, $codePostal, $numeroTel, $typeIntervention, $superficie, $nombreDAppareil, $distanceALaRoute, $statut);

    // Exécution de la requête préparée
    if ($stmt->execute()) {
        // Succès de l'insertion des enregistrements
    } else {
        // Affichage de l'erreur en cas d'échec de l'insertion
        echo "Error inserting records: " . $stmt->error;
    }

    // Fermeture du statement
    $stmt->close();
}
