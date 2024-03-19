<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    
        <div class="header">
            <a href="netd.php">
                <img src="projet_dev_web_photos/logo.png" alt="Logo" class="logo">
            </a>
            <h1>Service</h1>
            <div class="date">
                <h2><?php echo date("d-m-Y"); ?></h2><br>
            </div>
            <a href="mesinfo.php" class="profile-photo">
                <img src="projet_dev_web_photos/mon_profil.png" alt="My Profile" class="my-profile">
            </a>
        </div>

        <!-- Intégration des fichiers -->

        <?php
        session_start();

            require_once 'functions.php';
            require_once 'filtre.php';
            require_once 'calendrier.php';

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
        ?>



<div class="container-fluid">
<div class="left-column">
                <!-- Intervention Table -->
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





        <div class="row main-content">
            
            <div class="col-md-6 tarif">
                <!-- Tarif Form -->
                <form action="" method="post">
            <table class="col-md-12 tableau_tarif">
                <tr><th>Spécification</th><th>Quantité</th></tr>
                <tr><td>Nom du Client</td><td><input type="text" name="Nom" required></td></tr>
                <tr><td>Adresse</td><td><input type="text" name="Adresse" required></td></tr>
                <tr><td>Code Postal</td><td><input type="text" name="Code_Postal" required></td></tr>
                <tr><td>Téléphone</td><td><input type="text" name="Telephone" required></td></tr>
                <tr>
                    <td>Type d'intervention</td>
                    <td>
                        <select name="type" required>
                            <option value="">Select</option>
                            <option value="Wifi">Wifi</option>
                            <option value="Plomberie">Plomberie</option>
                            <option value="Electrique">Electrique</option>
                        </select>
                    </td>
                </tr>
                <tr><td>Superficie</td><td><input type="number" name="Superficie" required> M²</td></tr>
                <tr><td>Nombre d'appareils</td><td><input type="number" name="Nombre_d_appareil" required></td></tr>
                <tr><td>Distance à la Route</td><td><input type="number" name="Distance_a_la_Route" required> M</td></tr>
                <tr><td>Date</td><td><input type="text" name="date" placeholder="jj/mm/aaaa" required></td></tr>
                <tr><td colspan=2><button type="submit">Validé</button></td></tr>
            </table>
        </form>
            </div>

            <div class="col-md-12 calendar">
                <!-- Calendar Display -->
                <div id="calendar">
                    <?php echo $calendarHtml; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('select[name="type"]').value = '<?php echo $typeFilter; ?>';
        document.querySelector('select[name="status"]').value = '<?php echo $statusFilter; ?>';
    </script>


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
