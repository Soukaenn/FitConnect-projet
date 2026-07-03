<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Nouvel adhérent</h1>

<?php if (!empty($erreur)): ?>
    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form class="form-box" method="post" action="index.php?controller=adherent&action=create">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="telephone">Téléphone</label>
    <input type="text" id="telephone" name="telephone">

    <label for="id_salle">Salle</label>
    <select id="id_salle" name="id_salle" required>
        <?php foreach ($salles as $s): ?>
            <option value="<?= $s->getIdSalle() ?>"><?= htmlspecialchars($s->getNomSalle()) ?></option>
        <?php endforeach; ?>
    </select>

    <p><button type="submit" class="btn">Enregistrer</button></p>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
