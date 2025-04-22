<?php global $G_sPath; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $G_sHeader ?>

        <link rel="icon" type="image/png" href="<?= $G_sPath ?>/src/image/icon.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?= $G_sPath?>/src/css/style.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
        <script>
            const G_sPath = "<?= $G_sPath ?>";
        </script>
        <script src="<?= $G_sPath ?>/src/js/var_env.js"></script>

        <?php 
        if(isset($G_lJsPathH))
            foreach($G_lJsPathH as $l_sJsPath) {
                echo "<script src='$G_sPath/src/js/$l_sJsPath'></script>";
            }
        ?>
    </head>
    <body class="">
        <?= $G_sBody ?>

        <style>
            <?php
                echo $G_sCss;
            ?>
        </style>

        <?php
        if(isset($G_lJsPathB))
            foreach($G_lJsPathB as $l_sJsPath) {
                echo "<script src='$G_sPath/src/js/$l_sJsPath'></script>";
            }
        ?>
    </body>

    <script src="<?= $G_sPath ?>/src/js/button_home.js"></script>
</html>
