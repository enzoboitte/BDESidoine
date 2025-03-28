<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/contact.scss');";
?>
<section id ="cont" class="contact">
    <div class="content">
        <div class="info">
            <img src="<?= $G_sPath ?>/src/img/f1_logo.png">
            <p>3 Rue Ray Charles,63000 Clermont-Ferrand</p></br>
            <p>contact@mageeris.fr</p></br>
            <p href="tel:0473638902">+33 7 xx xx xx xx</p>
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