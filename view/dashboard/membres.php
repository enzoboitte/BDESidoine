<?php
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/membres.css');";

require_once "$G_sRacine/model/Members.php";


$CMembers = new CMembers();
$membres = $CMembers->getMembersByYear($G_sYear);
?>

<a href="<?= $G_sPath ?>/dashboard/" class="back-button">Retour</a>
<h2>Gestion des membres</h2>
<button class="btn-success" id="open-add-modal">Ajouter un membre</button>

<div class="membres-container">
    <?php foreach ($membres as $m): ?>
        <div class="carte-membre">
            <?php $img = $m->getImage() !== '' ? $m->getImage() : 'default.png'; ?>
            <img src="<?= $G_sPath ?>/assets/img/membres/<?= $img ?>" alt="Photo de <?= $m->getNom() ?>" class="photo-membre">
            <div class="infos">
                <h4><?= $m->getPrenom() ?> <?= $m->getNom() ?></h4>
                <p><?= $m->getRole()->getLibelle() ?></p>
                <p><?= $m->getMail() ?></p>
                <p><?= $m->getTel() ?></p>
            </div>
            <div class="actions">
                <a class="btn-edit" data-id="<?= $m->getIdM() ?>">✏️</a>
                <a class="btn-delete" data-id="<?= $m->getIdM() ?>">🗑️</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="modale-add" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-add-modal">&times;</span>
        <h3>Ajouter un membre</h3>

        
        <input type="hidden" name="action" value="add">
        <label>Prénom</label><input type="text" name="prenom" required>
        <label>Nom</label><input type="text" name="nom" required>
        <label>Mail</label><input type="email" name="mail">
        <label>Téléphone</label><input type="text" name="tel">
        <label>Rôle</label>
        <select name="role" required>
            <option value="">-- Choisir un rôle --</option>
            <?php foreach ((new CRoles())->getRoles() as $r): ?>
            <option value="<?= $r->getIdRo() ?>"><?= $r->getLibelle() ?></option>
            <?php endforeach; ?>
        </select>

        <label>Photo</label><input type="file" name="photo" accept="image/*">
        <div class="modale-actions">
            <button type="submit" class="btn-save">Ajouter</button>
            <button type="button" class="btn-cancel cancel-add">Annuler</button>
        </div>
    </div>
</div>


<div id="modale-edit" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-edit-modal">&times;</span>
        <h3>Modifier le membre</h3>
        
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="idM" id="edit-idM">
        <label>Prénom</label><input type="text" name="prenom" id="edit-prenom" required>
        <label>Nom</label><input type="text" name="nom" id="edit-nom" required>
        <label>Mail</label><input type="email" name="mail" id="edit-mail">
        <label>Téléphone</label><input type="text" name="tel" id="edit-tel">
        <label>Rôle</label>
        <select name="role" id="edit-role" required>
            <option value="">-- Choisir un rôle --</option>
            <?php foreach ((new CRoles())->getRoles() as $r): ?>
            <option value="<?= $r->getIdRo() ?>"><?= $r->getLibelle() ?></option>
            <?php endforeach; ?>
        </select>
        <label>Photo</label><input type="file" name="photo">
        <div class="modale-actions">
            <button type="submit" class="btn-save">Enregistrer</button>
            <button type="button" class="btn-cancel cancel-edit">Annuler</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('open-add-modal').addEventListener('click', () => {
            document.getElementById('modale-add').classList.remove('hidden');
        });
        document.getElementById('close-add-modal').addEventListener('click', () => {
            document.getElementById('modale-add').classList.add('hidden');
        });
        document.querySelectorAll('.cancel-add').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('modale-add').classList.add('hidden');
            });
        });
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('edit-idM').value = btn.dataset.id;
                document.getElementById('edit-prenom').value = btn.dataset.prenom;
                document.getElementById('edit-nom').value = btn.dataset.nom;
                document.getElementById('edit-mail').value = btn.dataset.mail;
                document.getElementById('edit-tel').value = btn.dataset.tel;
                document.getElementById('edit-role').value = btn.dataset.role;
                document.getElementById('modale-edit').classList.remove('hidden');
            });
        });
        document.getElementById('close-edit-modal').addEventListener('click', () => {
            document.getElementById('modale-edit').classList.add('hidden');
        });
        document.querySelectorAll('.cancel-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('modale-edit').classList.add('hidden');
            });
        });
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm(`Supprimer ${btn.dataset.prenom} ?`)) {
                    window.location.href = btn.href;
                }
            });
        });
    });
</script>