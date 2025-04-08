<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/contact.scss');";
?>
<section id ="cont" class="contact">
    <div class="content">
        <div class="info">
            <img src="<?= $G_sPath ?>/src/img/logo_bde_sidoine.svg">
            <p>1 Rue Henri Simon,</p></br>
            <p>63000 Clermont-Ferrand</p></br>
            <p>bdesidoineapollinaire@gmail.com</p></br>
        </div>
        <form method="POST" class="contact-form">
            <h3>Contactez-nous</h3>
            <div class="same-line">
                <input type="text" id="" name="name" placeholder="Nom">
                <input type="email" id="" name="email" placeholder="Adresse email" required="">
            </div>
            <input type="text" id="" name="phone" placeholder="Numéro de téléphone">
            <input type="text" id="" name="object" placeholder="Objet" required="">
            
            <textarea id="" name="content" placeholder="Message" required=""></textarea>
            <button type="submit" name="">Demander un appel</button>
        </form>
    </div>    
</section>