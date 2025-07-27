<?php
$file = "candidatures.json";

// Charger les candidatures existantes
$candidatures = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Nouvelle candidature
$nouvelle = [
    "entreprise" => $_POST['entreprise'],
    "poste" => $_POST['poste'],
    "date" => $_POST['date_candidature'],
    "statut" => $_POST['statut'],
    "lien" => $_POST['lien_annonce']
];

// Ajouter et sauvegarder
$candidatures[] = $nouvelle;
file_put_contents($file, json_encode($candidatures, JSON_PRETTY_PRINT));

// Affichage de confirmation
echo "<h1>Candidature enregistrée (JSON)</h1>";
echo "<p><strong>Entreprise :</strong> {$nouvelle['entreprise']}</p>";
echo "<p><strong>Poste :</strong> {$nouvelle['poste']}</p>";
echo "<p><strong>Date :</strong> {$nouvelle['date']}</p>";
echo "<p><strong>Statut :</strong> {$nouvelle['statut']}</p>";
echo "<p><strong>Annonce :</strong> <a href='{$nouvelle['lien']}'>{$nouvelle['lien']}</a></p>";

// Bouton retour
echo "<br><br><form action='formulaire_nosql.php' method='GET'>
        <button type='submit'>Retour au formulaire</button>
      </form>";
?>
