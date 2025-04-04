<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/menu.scss');";
?>
<nav class="menu">
    <ul>
        <li><a href="<?= $G_sPath ?>/">Nos membres</a></li>
        <li><a href="<?= $G_sPath ?>/event">Nos évenement</a></li>
        <li><a href="<?= $G_sPath ?>/blog">Les news</a></li>
    </ul>
</nav>