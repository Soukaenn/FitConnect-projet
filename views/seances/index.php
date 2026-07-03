<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Séances</h1>
<p><a class="btn" href="index.php?controller=seance&action=create">+ Nouvelle séance</a></p>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Adhérent</th>
            <th>Salle</th>
            <th>Activité</th>
            <th>Durée (min)</th>
            <th>Équipement</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($seances as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['date_seance']) ?></td>
            <td><?= htmlspecialchars($s['prenom_adherent'] . ' ' . $s['nom_adherent']) ?></td>
            <td><?= htmlspecialchars($s['nom_salle']) ?></td>
            <td><?= htmlspecialchars($s['nom_activite']) ?></td>
            <td><?= (int) $s['duree'] ?></td>
            <td><?= htmlspecialchars($s['nom_equipement'] ?? '—') ?></td>
            <td>
                <a class="btn" href="index.php?controller=seance&action=show&id=<?= $s['id_seance'] ?>">Voir</a>
                <a class="btn" href="index.php?controller=seance&action=edit&id=<?= $s['id_seance'] ?>">Modifier</a>
                <a class="btn btn-danger"
                   href="index.php?controller=seance&action=delete&id=<?= $s['id_seance'] ?>"
                   onclick="return confirm('Supprimer cette séance ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($seances)): ?>
        <tr><td colspan="7">Aucune séance enregistrée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
