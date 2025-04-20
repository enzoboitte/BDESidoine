<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/menu.scss');";
?>

<nav id="menu" class="menu">
    <ul>
        <!-- back button <i class="fa fa-arrow-left"></i> -->
        <?php if(isset($G_sBackBtn) && $G_sBackBtn): ?>
        <li><a href="<?= $G_sPath ?>/">Accueil</a></li>
        <?php endif; ?>
        
        <li><a href="<?= $G_sPath ?>/nos-membres">Nos membres</a></li>
        <li><a href="<?= $G_sPath ?>/evenement">Nos évenements</a></li>
        <li><a href="<?= $G_sPath ?>/blog">Les news</a></li>
    </ul>
</nav>