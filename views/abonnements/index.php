<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Abonnements</h1>
<p><a class="btn" href="index.php?controller=abonnement&action=create">+ Nouvel abonnement</a></p>

<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Adhérent (id)</th>
            <th>Statut</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($abonnements as $ab): ?>
        <tr>
            <td><?= htmlspecialchars($ab->getTypeAbonnement()) ?></td>
            <td><?= htmlspecialchars($ab->getDateDebut()) ?></td>
            <td><?= htmlspecialchars($ab->getDateFin()) ?></td>
            <td><?= (int) $ab->getIdAdherent() ?></td>
            <td><?= $ab->estValide() ? '✅ Actif' : '❌ Expiré' ?></td>
            <td>
                <a class="btn" href="index.php?controller=abonnement&action=show&id=<?= $ab->getIdAbonnement() ?>">Voir</a>
                <a class="btn" href="index.php?controller=abonnement&action=edit&id=<?= $ab->getIdAbonnement() ?>">Modifier</a>
                <a class="btn btn-danger"
                   href="index.php?controller=abonnement&action=delete&id=<?= $ab->getIdAbonnement() ?>"
                   onclick="return confirm('Supprimer cet abonnement ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($abonnements)): ?>
        <tr><td colspan="6">Aucun abonnement enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
