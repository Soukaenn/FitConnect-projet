<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Nouvelle séance</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=seance&action=create">
    <label for="id_adherent">Adhérent</label>
    <select id="id_adherent" name="id_adherent" required>
        <?php foreach ($adherents as $a): ?>
            <option value="<?= $a->getIdAdherent() ?>">
                <?= htmlspecialchars($a->getNomComplet()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="id_salle">Salle</label>
    <select id="id_salle" name="id_salle" required>
        <?php foreach ($salles as $s): ?>
            <option value="<?= $s->getIdSalle() ?>"><?= htmlspecialchars($s->getNomSalle()) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="id_activite">Activité</label>
    <select id="id_activite" name="id_activite" required>
        <?php foreach ($activites as $act): ?>
            <option value="<?= $act->getIdActivite() ?>"><?= htmlspecialchars($act->getNomActivite()) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="duree">Durée (minutes)</label>
    <input type="number" id="duree" name="duree" min="1" required>

    <label for="id_equipement">Équipement (optionnel — id)</label>
    <input type="number" id="id_equipement" name="id_equipement" min="1">

    <label for="date_seance">Date de la séance</label>
    <input type="date" id="date_seance" name="date_seance" value="<?= date('Y-m-d') ?>" required>

    <p><button type="submit" class="btn">Enregistrer la séance</button></p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
