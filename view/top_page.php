<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/pagetopbottom.scss');";
?>
<div class="top_page">
    <?php $G_sBackBtn = true; include "$G_sRacine/view/menu.php"; ?>
    <img src="<?= $G_sPath ?>/src/img/Polygon_top.svg" alt="section_head" class="section_head">
</div>

<?= "<div style='height: 15dvh;'></div>"; ?>