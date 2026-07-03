<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Adhérents</h1>
<p><a class="btn" href="index.php?controller=adherent&action=create">+ Nouvel adhérent</a></p>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Inscription</th>
            <th>Salle (id)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($adherents as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a->getNom()) ?></td>
            <td><?= htmlspecialchars($a->getPrenom()) ?></td>
            <td><?= htmlspecialchars($a->getEmail()) ?></td>
            <td><?= htmlspecialchars($a->getTelephone() ?? '—') ?></td>
            <td><?= htmlspecialchars($a->getDateInscription()) ?></td>
            <td><?= (int) $a->getIdSalle() ?></td>
            <td>
                <a class="btn" href="index.php?controller=adherent&action=show&id=<?= $a->getIdAdherent() ?>">Voir</a>
                <a class="btn" href="index.php?controller=adherent&action=edit&id=<?= $a->getIdAdherent() ?>">Modifier</a>
                <a class="btn btn-danger"
                   href="index.php?controller=adherent&action=delete&id=<?= $a->getIdAdherent() ?>"
                   onclick="return confirm('Supprimer cet adhérent ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($adherents)): ?>
        <tr><td colspan="7">Aucun adhérent enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
