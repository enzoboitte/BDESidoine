<?php 
global $G_sPath, $G_lPermission, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/membrecompte.css');";

require_once "$G_sRacine/model/Permission.php";
?>

<a href="<?= $G_sPath ?>/dashboard/" class="back-button">Retour</a>

<div class="membre-admin-container">
    <h2>Gestion des membres</h2>

    <a href="<?= $G_sPath ?>/admin/membrecomptes/add" class="btn btn-success">Ajouter un membre</a>

    <table class="table-membre">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($listeMembres)): ?>
            <?php foreach ($listeMembres as $membre): ?>
                <tr>
                    <td>
                        <img src="<?= $G_sPath ?>/assets/img/membres/<?= htmlspecialchars($membre['_image']) ?>" alt="Photo de <?= htmlspecialchars($membre['prenom']) ?>" class="img-profil">
                    </td>
                    <td><?= htmlspecialchars($membre['prenom']) ?></td>
                    <td><?= htmlspecialchars($membre['nom']) ?></td>
                    <td><?= htmlspecialchars($membre['role']) ?></td>
                    <td>
                        <a href="<?= $G_sPath ?>/admin/membrecomptes/edit&id=<?= $membre['idM'] ?>" class="btn btn-edit">Modifier</a>
                        <a href="<?= $G_sPath ?>/admin/membrecomptes/delete&id=<?= $membre['idM'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce membre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucun membre trouvé.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>