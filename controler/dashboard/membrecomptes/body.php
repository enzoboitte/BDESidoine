<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $G_sRacine = "../..";
}

require_once "$G_sRacine/model/CMembers.php";
require_once "$G_sRacine/model/Role.php";

$CMembers = new CMembers();
$membres = $CMembers->getMembersByYear("2024");

include "$G_sRacine/view/dashboard/membres.php";


?>
<h1><?php echo isset($membre) ? 'Modifier' : 'Ajouter'; ?> un Membre</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nom" placeholder="Nom" value="<?php echo isset($membre) ? htmlspecialchars($membre['nom']) : ''; ?>" required>
    <input type="text" name="prenom" placeholder="Prénom" value="<?php echo isset($membre) ? htmlspecialchars($membre['prenom']) : ''; ?>" required>
    <input type="email" name="mail" placeholder="Email" value="<?php echo isset($membre) ? htmlspecialchars($membre['mail']) : ''; ?>" required>
    <input type="tel" name="tel" placeholder="Téléphone" value="<?php echo isset($membre) ? htmlspecialchars($membre['tel']) : ''; ?>" required>
    <input type="file" name="_image" placeholder="Image" accept="image/*">
    <select name="idRo" required>
        <option value="">Sélectionner un rôle</option>
        

        <!-- Remplir les options avec les rôles disponibles -->


        <?php foreach ($roles as $role): ?>
            <option value="<?php echo $role['idRo']; ?>" <?php echo (isset($membre) && $membre['idRo'] == $role['idRo']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($role['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit"><?php echo isset($membre) ? 'Modifier' : 'Ajouter'; ?></button>
</form>