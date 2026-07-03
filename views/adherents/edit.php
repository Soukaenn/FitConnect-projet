<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Modifier l'adhérent</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=adherent&action=edit&id=<?= $adherent->getIdAdherent() ?>">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($adherent->getNom()) ?>" required>

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($adherent->getPrenom()) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($adherent->getEmail()) ?>" required>

    <label for="telephone">Téléphone</label>
    <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($adherent->getTelephone() ?? '') ?>">

    <label for="id_salle">Salle</label>
    <select id="id_salle" name="id_salle" required>
        <?php foreach ($salles as $s): ?>
            <option value="<?= $s->getIdSalle() ?>" <?= $s->getIdSalle() === $adherent->getIdSalle() ? 'selected' : '' ?>>
                <?= htmlspecialchars($s->getNomSalle()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <p>
        <button type="submit" class="btn">Enregistrer les modifications</button>
        <a class="btn btn-danger" href="index.php?controller=adherent&action=index">Annuler</a>
    </p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
