<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Détail adhérent</h1>

<table>
    <tr><th>Nom</th><td><?= htmlspecialchars($adherent->getNom()) ?></td></tr>
    <tr><th>Prénom</th><td><?= htmlspecialchars($adherent->getPrenom()) ?></td></tr>
    <tr><th>Email</th><td><?= htmlspecialchars($adherent->getEmail()) ?></td></tr>
    <tr><th>Téléphone</th><td><?= htmlspecialchars($adherent->getTelephone() ?? '—') ?></td></tr>
    <tr><th>Date d'inscription</th><td><?= htmlspecialchars($adherent->getDateInscription()) ?></td></tr>
    <tr><th>Salle</th><td><?= $salle ? htmlspecialchars($salle->getNomSalle()) : '—' ?></td></tr>
</table>

<p style="margin-top:16px;">
    <a class="btn" href="index.php?controller=adherent&action=edit&id=<?= $adherent->getIdAdherent() ?>">Modifier</a>
    <a class="btn" href="index.php?controller=adherent&action=index">Retour à la liste</a>
</p>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
