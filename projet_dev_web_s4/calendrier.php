<?php
include 'connexionBDD.php';

// Fonction pour construire un calendrier HTML pour un mois et une année donnés
function construire_calendrier($mois, $annee) {
    // Définition des jours de la semaine
    $joursSemaine = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    
    // Récupération du timestamp du premier jour du mois
    $premierJour = mktime(0, 0, 0, $mois, 1, $annee);
    
    // Nombre de jours dans le mois
    $nombreJours = date('t', $premierJour);
    
    // Récupération des composants de la date du premier jour du mois
    $composantsDate = getdate($premierJour);
    
    // Nom du mois
    $nomMois = $composantsDate['month'];
    
    // Jour de la semaine du premier jour du mois
    $jourSemaine = $composantsDate['wday'];
    
    // Initialisation du calendrier avec la structure de base en HTML
    $calendrier = "<table class='table table-bordered'>";
    $calendrier .= "<header><h2>$nomMois $annee</h2></header><tr>";
    
    // Ajout des en-têtes de jours de la semaine
    foreach($joursSemaine as $jour) {
        $calendrier .= "<th class=''>$jour</th>";
    }

    // Construction des cases vides pour les jours avant le premier jour du mois
    $calendrier .= "</tr><tr>";
    if ($jourSemaine > 0) { 
        for($k = 0; $k < $jourSemaine; $k++){
            $calendrier .= "<td></td>"; 
        }
    }

    // Formatage du mois pour qu'il ait toujours 2 chiffres
    $mois = str_pad($mois, 2, "0", STR_PAD_LEFT); //str_pad permet de formater une chaine de caractère
    
    // Boucle pour ajouter les jours du mois dans le calendrier
    $jourCourant = 1;
    while ($jourCourant <= $nombreJours) {
        // Si on arrive à la fin de la semaine, on passe à la ligne suivante
        if ($jourSemaine == 7) {
            $jourSemaine = 0;
            $calendrier .= "</tr><tr>";
        }
        
        // Formatage du jour pour qu'il ait toujours 2 chiffres
        $jourCourantRel = str_pad($jourCourant, 2, "0", STR_PAD_LEFT);
        
        // Construction de la date au format YYYY-MM-DD pour chaque case du calendrier
        $date = "$annee-$mois-$jourCourantRel";
        
        // Ajout du jour dans une case du calendrier
        $calendrier .= "<td class='day' rel='$date'>$jourCourant</td>";
        
        // Passage au jour suivant
        $jourCourant++;
        $jourSemaine++;
    }
    
    // Ajout des cases vides pour compléter la dernière semaine du mois si nécessaire
    if ($jourSemaine != 7) { 
        $joursRestants = 7 - $jourSemaine;
        for($l = 0; $l < $joursRestants; $l++){
            $calendrier .= "<td></td>"; 
        }
    }
    
    // Fermeture du tableau HTML
    $calendrier .= "</tr></table>";
    
    // Renvoi du calendrier HTML construit
    return $calendrier;
}

// Construction du calendrier pour le mois et l'année actuels et stockage dans une variable
ob_start();
echo construire_calendrier(date('m'), date('Y'));
$calendarHtml = ob_get_clean();
