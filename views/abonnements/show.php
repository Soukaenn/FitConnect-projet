<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Détail abonnement</h1>

<table>
    <tr><th>Type</th><td><?= htmlspecialchars($abonnement->getTypeAbonnement()) ?></td></tr>
    <tr><th>Date de début</th><td><?= htmlspecialchars($abonnement->getDateDebut()) ?></td></tr>
    <tr><th>Date de fin</th><td><?= htmlspecialchars($abonnement->getDateFin()) ?></td></tr>
    <tr><th>Adhérent</th><td><?= $adherent ? htmlspecialchars($adherent->getNomComplet()) : '—' ?></td></tr>
    <tr><th>Statut</th><td><?= $abonnement->estValide() ? '✅ Actif' : '❌ Expiré' ?></td></tr>
</table>

<p style="margin-top:16px;">
    <a class="btn" href="index.php?controller=abonnement&action=edit&id=<?= $abonnement->getIdAbonnement() ?>">Modifier</a>
    <a class="btn" href="index.php?controller=abonnement&action=index">Retour à la liste</a>
</p>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
