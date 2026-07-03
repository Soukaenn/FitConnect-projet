<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Modifier l'abonnement</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=abonnement&action=edit&id=<?= $abonnement->getIdAbonnement() ?>">
    <label for="type_abonnement">Type d'abonnement</label>
    <select id="type_abonnement" name="type_abonnement" required>
        <?php foreach (['mensuel', 'trimestriel', 'annuel'] as $type): ?>
            <option value="<?= $type ?>" <?= $abonnement->getTypeAbonnement() === $type ? 'selected' : '' ?>>
                <?= ucfirst($type) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="date_debut">Date de début</label>
    <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($abonnement->getDateDebut()) ?>" required>

    <label for="date_fin">Date de fin</label>
    <input type="date" id="date_fin" name="date_fin" value="<?= htmlspecialchars($abonnement->getDateFin()) ?>" required>

    <p>
        <button type="submit" class="btn">Enregistrer les modifications</button>
        <a class="btn btn-danger" href="index.php?controller=abonnement&action=index">Annuler</a>
    </p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
