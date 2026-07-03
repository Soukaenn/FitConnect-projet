<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Modifier la séance</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=seance&action=edit&id=<?= $seance->getIdSeance() ?>">
    <label for="id_adherent">Adhérent</label>
    <select id="id_adherent" name="id_adherent" required>
        <?php foreach ($adherents as $a): ?>
            <option value="<?= $a->getIdAdherent() ?>" <?= $a->getIdAdherent() === $seance->getIdAdherent() ? 'selected' : '' ?>>
                <?= htmlspecialchars($a->getNomComplet()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="id_salle">Salle</label>
    <select id="id_salle" name="id_salle" required>
        <?php foreach ($salles as $s): ?>
            <option value="<?= $s->getIdSalle() ?>" <?= $s->getIdSalle() === $seance->getIdSalle() ? 'selected' : '' ?>>
                <?= htmlspecialchars($s->getNomSalle()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="id_activite">Activité</label>
    <select id="id_activite" name="id_activite" required>
        <?php foreach ($activites as $act): ?>
            <option value="<?= $act->getIdActivite() ?>" <?= $act->getIdActivite() === $seance->getIdActivite() ? 'selected' : '' ?>>
                <?= htmlspecialchars($act->getNomActivite()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="duree">Durée (minutes)</label>
    <input type="number" id="duree" name="duree" min="1" value="<?= $seance->getDuree() ?>" required>

    <label for="id_equipement">Équipement (optionnel — id)</label>
    <input type="number" id="id_equipement" name="id_equipement" min="1" value="<?= $seance->getIdEquipement() ?? '' ?>">

    <label for="date_seance">Date de la séance</label>
    <input type="date" id="date_seance" name="date_seance" value="<?= htmlspecialchars($seance->getDateSeance()) ?>" required>

    <p>
        <button type="submit" class="btn">Enregistrer les modifications</button>
        <a class="btn btn-danger" href="index.php?controller=seance&action=index">Annuler</a>
    </p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
