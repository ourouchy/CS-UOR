<?php
include 'config.php';

// Récupérer toutes les candidatures déjà enregistrées
$sql = "SELECT * FROM emplois ORDER BY id DESC";
$stmt = $pdo->query($sql);
$candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Formulaire Candidature</title>
</head>
<body>
 <nav>
  <ul>
    <li><a href="index.html">Page HTML (chap. 2)</a></li>
    <li><a href="cv-css.html" aria-current="page">Page stylée (chap. 3)</a></li>
    <li><a href="cv-tailwind.html">Page responsive (bonus)</a></li>
    <li><a href="formulaire_nosql.php">Formulaire NoSql (Bonus)</a></li>

  </ul>
</nav>
 

<h1>Ajouter une candidature</h1>

  <form action="traitement.php" method="POST">
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

  <h2>Candidatures enregistrées</h2>

  <?php if (count($candidatures) > 0): ?>
    <table border="1" cellpadding="5">
      <tr>
        <th>ID</th>
        <th>Entreprise</th>
        <th>Poste</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Annonce</th>
      </tr>
      <?php foreach ($candidatures as $c): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['entreprise']) ?></td>
        <td><?= htmlspecialchars($c['poste']) ?></td>
        <td><?= $c['date_candidature'] ?></td>
        <td><?= $c['statut'] ?></td>
        <td><a href="<?= htmlspecialchars($c['lien_annonce']) ?>" target="_blank">Lien</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>Aucune candidature enregistrée.</p>
  <?php endif; ?>

</body>
</html>

