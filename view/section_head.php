<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/section_head.scss');";
?>
<section class="header">
    <div class="top">
        <img src="<?= $G_sPath ?>/src/img/logo.png" alt="Logo de l'association" class="logo">
        <img src="<?= $G_sPath ?>/src/img/logo.png" alt="Logo de l'association" class="logo_account">
    </div>
    <div class="center">
        <div class="title">
            <h1>BDE</h1>
            <h2>Sidoine Apollinaire</h2>
        </div>

        <a href="<?= $G_sPath ?>/login" class="button">en savoir plus</a>
    </div>
    <img src="<?= $G_sPath ?>/src/img/background.png" alt="Logo de l'association" class="bg">
    <img src="<?= $G_sPath ?>/src/img/montain.svg" alt="Logo de l'association" class="montain">
</section>