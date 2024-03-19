<?php
include 'connexionBDD.php';

// Définition des valeurs par défaut pour les filtres de tri et de statut
$dateOrder = 'ASC'; 
$typeFilter = '';
$statusFilter = '';

// Vérification si la méthode de requête HTTP est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si l'utilisateur a demandé un changement de tri par date
    if (isset($_POST['sort_date'])) {
        // Inversion de l'ordre de tri selon l'ordre actuel (ASC ou DESC)
        $dateOrder = $_POST['sort_date'] === 'ASC' ? 'DESC' : 'ASC';
    }
    
    // Vérification et récupération du filtre par type d'intervention
    if (isset($_POST["type"]) && in_array($_POST["type"], ['Wifi', 'Plomberie', 'Electrique'])) {
        // Échappement des caractères spéciaux pour la sécurité et la prévention des injections SQL
        $typeFilter = $mysqli->real_escape_string($_POST["type"]);
    }
    
    // Vérification et récupération du filtre par statut
    if (isset($_POST['status']) && in_array($_POST['status'], ['En cours', 'En Attente', 'Terminé'])) {
        // Échappement des caractères spéciaux pour la sécurité et la prévention des injections SQL
        $statusFilter = $mysqli->real_escape_string($_POST['status']);
    }
}

// Construction de la requête SQL de sélection des interventions
$query = "SELECT * FROM intervention WHERE 1=1";

// Ajout des conditions de filtrage, si des filtres ont été définis
if ($typeFilter !== '') {
    $query .= " AND `type d'intervention` = '$typeFilter'";
}

if ($statusFilter !== '') {
    $query .= " AND statut = '$statusFilter'";
}

// Ajout de l'ordre de tri par date avec l'ordre spécifié (ASC ou DESC)
$query .= " ORDER BY date $dateOrder";

// Exécution de la requête SQL
$result = $mysqli->query($query);
