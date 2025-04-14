<?php 
global $G_sPath, $G_lPermission, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/blog.scss');";

require_once "$G_sRacine/model/Permission.php";
?>

<a href="<?= $G_sPath ?>/dashboard/" class="back-button">Retour</a>

<div class="dashboard-container">
    <h1>Articles du blog</h1>

    <div class="table-header">
        <span class="header-title">Liste des articles</span>


        <button onclick="openAddModal()" class="btn-add">
            <i class="fa fa-plus"></i> Ajouter un article
        </button>
    </div>


    <div id="addModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Ajouter un article</h2>
                <button onclick="closeAddModal()" class="modal-close">&times;</button>
            </div>
            <form action="<?= $G_sPath ?>/dashboard/blog" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">

                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>

                <label for="image">Image :</label>
                <input type="file" id="image" name="image">


                <button type="submit" class="modal-submit">Ajouter</button>
            </form>
        </div>
    </div>


    <div class="custom-table">
        <div class="table-head">
            <div class="row-left"><strong>Informations</strong></div>
            <div class="row-right"><strong>Actions</strong></div>
        </div>

        <?php if (empty($articles)): ?>
            <div class="table-row no-article">
                <div class="row-left">Aucun article trouvé.</div>
                <div class="row-right">–</div>
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="table-row">
                    <div class="row-left">
                        <div><strong>ID :</strong> <?= htmlspecialchars($article['idP']) ?></div>
                        <div><strong>Titre :</strong> <?= htmlspecialchars($article['titreP']) ?></div>
                        <div><strong>Date :</strong> <?= htmlspecialchars($article['dateP']) ?></div>
                        <div><strong>Description :</strong> <?= htmlspecialchars($article['descriptionP']) ?></div>
                        <?php if (!empty($article['_imageP'])): ?>
                            <img src="<?= $G_sPath ?>/assets/img/blog/<?= htmlspecialchars($article['_imageP']) ?>" class="img-miniature" alt="Image article">
                        <?php endif; ?>
                    </div>
                    <div class="row-right">
                        <a href="<?= $G_sPath ?>/dashboard/blog/edit?id=<?= $article['idP'] ?>" class="btn-action edit" title="Modifier">✏️</a>
                        <a href="<?= $G_sPath ?>/dashboard/blog/delete?id=<?= $article['idP'] ?>" class="btn-action delete" onclick="return confirm('Supprimer cet article ?')" title="Supprimer">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


<script>
function openAddModal() {
    document.getElementById("addModal").style.display = "flex";
}

function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("addModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>
