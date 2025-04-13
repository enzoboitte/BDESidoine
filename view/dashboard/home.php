<?php 
global $G_sPath, $G_lPermission;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/home.scss');";

require_once "$G_sRacine/model/Permission.php";
?>

<div class="panel-container">
    <a href="<?= $G_sPath ?>/v1/account/logout">Déconnexion</a>
    <h1 class="panel-title">Panel Administrateur</h1>
    <h4 class="panel-subtitle">Vous êtes connecté avec le compte: <span><?= (new CAccount())->F_lGetInfo()["nom"].".".(new CAccount())->F_lGetInfo()["prenom"] ?></span></h4>
    <div class="panel-grid">
        <?php if((new CRegle)->F_bIsAutorise(ERegle::READ_EVENT, $G_lPermission)): ?>
        <div class="panel-card">
            <h3>Calendrier</h3>
            <a href="<?= $G_sPath ?>/dashboard/calendar" class="btn-circle"><i class="fa fa-arrow-right"></i></a>
        </div>
        <?php endif; ?>

        <div class="panel-card purple">
            <h3>Membres &<br>Comptes</h3>
            <a href="<?= $G_sPath ?>/dashboard/membrecomptes" class="btn-circle"><i class="fa fa-arrow-right"></i></a>
        </div>

        <?php if((new CRegle)->F_bIsAutorise(ERegle::READ_POST, $G_lPermission)): ?>
        <div class="panel-card brown">
            <h3>Blog</h3>
            <a href="<?= $G_sPath ?>/dashboard/blog" class="btn-circle"><i class="fa fa-arrow-right"></i></a>
        </div>
        <?php endif; ?>
    </div>
</div>