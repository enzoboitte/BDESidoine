<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/section_head.scss');";
?>
<section class="header">
    <div class="top">
        <img src="<?= $G_sPath ?>/src/img/logo_bde_sidoine.svg" alt="Logo de l'association" class="logo">
        <a href="<?= $G_sPath ?>/dashboard"><img src="<?= $G_sPath ?>/src/img/login.svg" alt="Logo de l'association" class="logo_account"></a>
    </div>
    <div class="center">
        <div class="title">
            <h1>BDE</h1>
            <h2>Sidoine Apollinaire</h2>
        </div>

        <a class="button" id="btn_aboutmore">en savoir plus</a>
    </div>
    <!--<img src="<?= $G_sPath ?>/src/img/background.png" alt="Logo de l'association" class="bg">-->
    <div class="bg"><img src="<?= $G_sPath ?>/src/img/background.png"></div>
    <img src="<?= $G_sPath ?>/src/img/montain.svg" alt="Logo de l'association" class="montain">
</section>