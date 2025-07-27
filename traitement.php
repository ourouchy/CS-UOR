<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entreprise = $_POST['entreprise'];
    $poste = $_POST['poste'];
    $date = $_POST['date_candidature'];
    $statut = $_POST['statut'];
    $lien = $_POST['lien_annonce'];

    $sql = "INSERT INTO emplois (entreprise, poste, date_candidature, statut, lien_annonce)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$entreprise, $poste, $date, $statut, $lien]);

    echo "<h1>Candidature enregistrée</h1>";
    echo "<p><strong>Entreprise :</strong> $entreprise</p>";
    echo "<p><strong>Poste :</strong> $poste</p>";
    echo "<p><strong>Date :</strong> $date</p>";
    echo "<p><strong>Statut :</strong> $statut</p>";
    echo "<p><strong>Annonce :</strong> <a href='$lien'>$lien</a></p>";
    echo "<p><a href='formulaire.php'>← Retour au formulaire</a></p>";

    echo "<br><br>";
    echo "<form action='formulaire.php' method='GET'>
            <button type='submit'>Retour au formulaire</button>
          </form>";
}
?>
