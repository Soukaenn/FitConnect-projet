<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Nouvel abonnement</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=abonnement&action=create">
    <label for="id_adherent">Adhérent</label>
    <select id="id_adherent" name="id_adherent" required>
        <?php foreach ($adherents as $a): ?>
            <option value="<?= $a->getIdAdherent() ?>">
                <?= htmlspecialchars($a->getNomComplet()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="type_abonnement">Type d'abonnement</label>
    <select id="type_abonnement" name="type_abonnement" required>
        <option value="mensuel">Mensuel</option>
        <option value="trimestriel">Trimestriel</option>
        <option value="annuel">Annuel</option>
    </select>

    <label for="date_debut">Date de début</label>
    <input type="date" id="date_debut" name="date_debut" value="<?= date('Y-m-d') ?>" required>

    <p><button type="submit" class="btn">Souscrire</button></p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
