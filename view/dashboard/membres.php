<?php
global $G_sPath, $G_lPermission;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/membres.css');";

require_once "$G_sRacine/model/Members.php";
require_once "$G_sRacine/model/Permission.php";

$CMembers = new CMembers();
$membres = $CMembers->F_lMembersByYear($G_sYear);
?>

<a href="<?= $G_sPath ?>/dashboard/" class="back-button">Retour</a>
<h2>Gestion des membres</h2>

<?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission)): ?>
<button class="btn-success" id="open-add-modal">Ajouter un membre</button>
<?php endif; ?>

<div class="membres-container">
    <?php foreach ($membres as $m): ?>
        <div class="carte-membre">
            <?php $img = $m->getImage() !== '' ? $m->getImage() : 'default.png'; ?>
            <img src="<?= $G_sPath.$img ?>" alt="Photo de <?= $m->getNom() ?>" class="photo-membre">
            <div class="infos">
                <h4><?= $m->getPrenom() ?> <?= strtoupper($m->getNom()) ?></h4>
                <p><?= $m->getRole()->getLibelle() ?></p>
                <p><?= $m->getMail() ?></p>
                <p><?= $m->getTel() ?></p>
            </div>
            <div class="actions">
            <?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_MEMBER, $G_lPermission)): ?>
                <a class="btn-edit" data-id="<?= $m->getIdM() ?>">✏️</a>
            <?php endif; ?>

            <?php if((new CRegle)->F_bIsAutorise(ERegle::DELETE_MEMBER, $G_lPermission)): ?>
                <a class="btn-delete" data-id="<?= $m->getIdM() ?>">🗑️</a>
            <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission)): ?>
<div id="modale-add" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-add-modal">&times;</span>
        <h3>Ajouter un membre</h3>
        
        <label>Prénom</label><input type="text" name="prenom" id="add-prenom" required>
        <label>Nom</label><input type="text" name="nom" id="add-nom" required>
        <label>Mail</label><input type="email" name="mail" id="add-mail" required>
        <label>Téléphone</label><input type="text" name="tel" id="add-tel" required>
        <label>Rôle</label>
        <select name="role" id="add-role" required>
            <option value="">-- Choisir un rôle --</option>
            <?php foreach ((new CRoles())->getRoles() as $r): ?>
            <option value="<?= $r->getIdRo() ?>"><?= $r->getLibelle() ?></option>
            <?php endforeach; ?>
        </select>

        <label>Photo</label><input type="file" name="photo" id="add-photo" accept="image/*">
        <div class="modale-actions">
            <button onclick="addMember()" class="btn-save">Ajouter</button>
            <button class="btn-cancel cancel-add">Annuler</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_MEMBER, $G_lPermission)): ?>
<div id="modale-edit" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-edit-modal">&times;</span>
        <h3>Modifier le membre</h3>
        
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
        <label>Photo</label><input type="file" name="photo" id="edit-photo" accept="image/*">
        <div class="modale-actions">
            <button onclick="modifyMember()" class="btn-save">Enregistrer</button>
            <button class="btn-cancel cancel-edit">Annuler</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    <?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission)): ?>
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
    <?php endif; ?>

    <?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_MEMBER, $G_lPermission)): ?>
    document.getElementById('close-edit-modal').addEventListener('click', () => {
        document.getElementById('modale-edit').classList.add('hidden');
    });
    document.querySelectorAll('.cancel-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modale-edit').classList.add('hidden');
        });
    });
    <?php endif; ?>
    
    <?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission)): ?>
    document.querySelectorAll(`a.btn-edit[data-id]`).forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            console.log(id);
            CallApi('POST', `/v1/members/get/${id}`,  {})
                .then(res => {
                    return res.json();
                })
                .then(res => {
                    if (res.code) {
                        const m = res.code;
                        idEdit = m.idM;
                        document.getElementById('edit-prenom').value = m.prenom;
                        document.getElementById('edit-nom').value = m.nom;
                        document.getElementById('edit-mail').value = m.mail;
                        document.getElementById('edit-tel').value = m.tel;
                        document.getElementById('edit-role').value = m.role.idRo;
                        document.getElementById('modale-edit').classList.remove('hidden');
                        document.getElementById('modale-edit').dataset.id = id;
                    } else {
                        alert("Erreur lors de la récupération des données du membre.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Erreur lors de la récupération des données du membre.");
                });
        });
    });
    <?php endif; ?>

    <?php if((new CRegle)->F_bIsAutorise(ERegle::DELETE_MEMBER, $G_lPermission)): ?>
    // delete member
    document.querySelectorAll(`a.btn-delete[data-id]`).forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            if (confirm("Êtes-vous sûr de vouloir supprimer ce membre ?")) {
                CallApi('DELETE', `/v1/members/rm/${id}`, {})
                    .then(res => {
                        return res.json();
                    })
                    .then(res => {
                        if (res.code) {
                            location.reload();
                        } else {
                            alert("Erreur lors de la suppression du membre.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Erreur lors de la suppression du membre.");
                    });
            }
        });
    });
    <?php endif; ?>

    <?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_MEMBER, $G_lPermission)): ?>
    function modifyMember()
    {
        const id = document.getElementById('modale-edit').dataset.id;
        const prenom = document.querySelector('#modale-edit #edit-prenom').value;
        const nom = document.querySelector('#modale-edit #edit-nom').value;
        const mail = document.querySelector('#modale-edit #edit-mail').value;
        const tel = document.querySelector('#modale-edit #edit-tel').value;
        const role = document.querySelector('#modale-edit #edit-role').value;
        const photo = document.querySelector('#modale-edit #edit-photo').files[0];

        let data = {id: id, prenom: prenom, nom: nom, mail: mail, tel: tel, role: role};

        if (photo) {
            data.photo = photo;
        }

        CallApi('PUT', '/v1/members/update/', data, formatText="formData")
            .then(res => {
                return res.json();
            })
            .then(res => {
                if (res.code === 0) {
                    location.reload();
                } else {
                    alert("Erreur lors de la modification du membre.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur lors de la modification du membre.");
            });
    }
    <?php endif; ?>
    
    <?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission)): ?>
    // add member
    function addMember() {
        const prenom = document.querySelector('#modale-add #add-prenom').value;
        const nom = document.querySelector('#modale-add #add-nom').value;
        const mail = document.querySelector('#modale-add #add-mail').value;
        const tel = document.querySelector('#modale-add #add-tel').value;
        const role = document.querySelector('#modale-add #add-role').value;
        const photo = document.querySelector('#modale-add #add-photo').files[0];

        let data = {prenom: prenom, nom: nom, mail: mail, tel: tel, role: role};

        if (photo) {
            data.photo = photo;
        }

        CallApi('POST', '/v1/members/add/', data, formatText="formData")
            .then(res => {
                return res.json();
            })
            .then(res => {
                if (res.code === 0) {
                    location.reload();
                } else {
                    alert("Erreur lors de l'ajout du membre.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur lors de l'ajout du membre.");
            });
    }
    <?php endif; ?>    
</script>