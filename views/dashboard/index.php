<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Tableau de bord — Réseau FitConnect</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="value"><?= $stats['total_adherents'] ?></div>
        <div>Adhérents</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $stats['total_abonnements'] ?></div>
        <div>Abonnements</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $stats['total_seances'] ?></div>
        <div>Séances</div>
    </div>
    <div class="stat-card">
        <div class="value"><?= $stats['total_salles'] ?></div>
        <div>Salles</div>
    </div>
</div>

<h2 style="margin-top:32px;">Dernières séances</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Adhérent</th>
            <th>Salle</th>
            <th>Activité</th>
            <th>Durée (min)</th>
            <th>Équipement</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (array_slice($seances, 0, 8) as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['date_seance']) ?></td>
            <td><?= htmlspecialchars($s['prenom_adherent'] . ' ' . $s['nom_adherent']) ?></td>
            <td><?= htmlspecialchars($s['nom_salle']) ?></td>
            <td><?= htmlspecialchars($s['nom_activite']) ?></td>
            <td><?= (int) $s['duree'] ?></td>
            <td><?= htmlspecialchars($s['nom_equipement'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($seances)): ?>
        <tr><td colspan="6">Aucune séance enregistrée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
