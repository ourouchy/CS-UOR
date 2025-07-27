<?php
$file = "candidatures.json";

// Charger les candidatures existantes
$candidatures = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Formulaire Candidature (JSON)</title>
</head>
<body>

<nav>
  <ul>
    <li><a href="index.html">Page HTML (chap. 2)</a></li>
    <li><a href="cv-css.html">Page stylée (chap. 3)</a></li>
    <li><a href="cv-tailwind.html">Page responsive (bonus)</a></li>
    <li><a href="formulaire.php">Formulaire MySQL (chap. 5)</a></li>
    <li><a href="formulaire_nosql.php" aria-current="page">Formulaire JSON (bonus)</a></li>
  </ul>
</nav>

<h1>Ajouter une candidature (NoSQL)</h1>

<form action="traitement_nosql.php" method="POST">
  <label>Entreprise :</label><br>
  <input type="text" name="entreprise" required><br><br>

  <label>Poste :</label><br>
  <input type="text" name="poste" required><br><br>

  <label>Date de candidature :</label><br>
  <input type="date" name="date_candidature" required><br><br>

  <label>Statut :</label><br>
  <select name="statut" required>
    <option value="En attente">En attente</option>
    <option value="Sélectionné">Sélectionné</option>
    <option value="Refusé">Refusé</option>
  </select><br><br>

  <label>Lien de l'annonce :</label><br>
  <input type="url" name="lien_annonce" required><br><br>

  <button type="submit">Envoyer</button>
</form>

<hr>

<h2>Candidatures enregistrées (JSON)</h2>

<?php if (count($candidatures) > 0): ?>
  <table border="1" cellpadding="5">
    <tr>
      <th>#</th>
      <th>Entreprise</th>
      <th>Poste</th>
      <th>Date</th>
      <th>Statut</th>
      <th>Annonce</th>
    </tr>
    <?php foreach ($candidatures as $index => $c): ?>
    <tr>
      <td><?= $index + 1 ?></td>
      <td><?= htmlspecialchars($c['entreprise']) ?></td>
      <td><?= htmlspecialchars($c['poste']) ?></td>
      <td><?= htmlspecialchars($c['date']) ?></td>
      <td><?= htmlspecialchars($c['statut']) ?></td>
      <td><a href="<?= htmlspecialchars($c['lien']) ?>" target="_blank">Lien</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>Aucune candidature enregistrée.</p>
<?php endif; ?>

</body>
</html>
