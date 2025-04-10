<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/section_apropos.scss');";
?>
<section id="apropos" class="apropos">
    <h1 class="title">Qui sommes-nous ?</h1>
    <div class="container">
        <div class="container__img">
            <img src="<?= $G_sPath ?>/src/img/background.png" alt="Logo de l'association" class="logo">
        </div>
        <p>Le Bureau des Étudiants (BDE) du lycée Sidoine Apollinaire a été créé durant l’année scolaire 2023-2024. Il a pour objectif principal de dynamiser la vie étudiante au sein de l’établissement en proposant divers événements, activités et projets tout au long de l’année.</p>
        <div class="container__img">
            <img src="<?= $G_sPath ?>/src/img/evenement.jpg" alt="Logo de l'association" class="logo">
        </div>
    </div>
</section>