<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/menu.scss');";
?>
<nav id="menu" class="menu">
    <ul>
        <!-- back button -->
        <li><a href="<?= $G_sPath ?>/"><img src="<?= $G_sPath ?>/src/img/back.svg" alt="back"></a></li>
        
        <li><a href="<?= $G_sPath ?>/">Nos membres</a></li>
        <li><a href="<?= $G_sPath ?>/evenement">Nos évenement</a></li>
        <li><a href="<?= $G_sPath ?>/blog">Les news</a></li>
    </ul>
</nav>