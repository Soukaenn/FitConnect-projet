<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Détail séance</h1>

<table>
    <tr><th>Date</th><td><?= htmlspecialchars($seance['date_seance']) ?></td></tr>
    <tr><th>Adhérent</th><td><?= htmlspecialchars($seance['prenom_adherent'] . ' ' . $seance['nom_adherent']) ?></td></tr>
    <tr><th>Salle</th><td><?= htmlspecialchars($seance['nom_salle']) ?></td></tr>
    <tr><th>Activité</th><td><?= htmlspecialchars($seance['nom_activite']) ?></td></tr>
    <tr><th>Durée</th><td><?= (int) $seance['duree'] ?> min</td></tr>
    <tr><th>Équipement</th><td><?= htmlspecialchars($seance['nom_equipement'] ?? '—') ?></td></tr>
</table>

<p style="margin-top:16px;">
    <a class="btn" href="index.php?controller=seance&action=edit&id=<?= $seance['id_seance'] ?>">Modifier</a>
    <a class="btn" href="index.php?controller=seance&action=index">Retour à la liste</a>
</p>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
