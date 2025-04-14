<footer class="footer">
    <div class="footer-left">
        <p>Pages :</p>
        <ul>
        <li><a href="<?= $G_sPath ?>/nos-membres">Nos membres</a></li>
        <li><a href="<?= $G_sPath ?>/evenement">Nos évenement</a></li>
        <li><a href="<?= $G_sPath ?>/blog">Les news</a></li>
        </ul>
    </div>

    <div class="footer-center">
        <h3>BDE SIDOINE APOLLINAIRE</h3>
        <img src="<?= URL ?>src/img/logo_bde_sidoine.svg" alt="Logo BDE">
        <p>© 2025 BDE SIDOINE APOLLINAIRE - Tous droits réservés.</p>
    </div>

    <div class="footer-right">
        <a href="https://www.instagram.com/bdesidoine/" target="_blank">
            <img src="<?= URL ?>src/img/instagram.png" alt="Instagram" class="insta-icon">
        </a>
        <a href="<?= $G_sPath ?>/mentions-legales">Mentions légales</a>
    </div>
</footer>

<style>.footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background-color: #616C6B;
    padding: 40px 60px;
    color: white;
    flex-wrap: wrap;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    gap: 20px;
}

.footer-left,
.footer-center,
.footer-right {
    flex: 1;
    min-width: 220px;
}

.footer-left p {
    font-weight: bold;
    margin-bottom: 12px;
    font-size: 1.2em;
}

.footer-left ul {
    list-style: none;
    padding: 0;
}

.footer-left li {
    margin-bottom: 8px;
}

.footer-left a {
    color: white;
    text-decoration: none;
    font-size: 1em;
    transition: color 0.3s ease;
}

.footer-left a:hover {
    color: #FFD700;
}

.footer-center {
    text-align: center;
}

.footer-center h3 {
    font-weight: bold;
    font-size: 1.3em;
    margin-bottom: 10px;
}

.footer-center img {
    height: 65px;
    margin: 15px 0;
}

.footer-center p {
    font-size: 0.9em;
    margin-top: 5px;
}

.footer-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.footer-right a.mention {
    color: white;
    font-size: 0.9em;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.footer-right a.mention:hover {
    color: #FFD700;
}

.footer-right .insta-icon {
    width: 30px;
    height: 30px;
    transition: transform 0.3s ease;
}

.footer-right .insta-icon:hover {
    transform: scale(1.2);
}

@media screen and (max-width: 768px) {
    .footer {
        flex-direction: column;
        align-items: center;
        padding: 30px 20px;
        text-align: center;
    }

    .footer-left,
    .footer-center,
    .footer-right {
        align-items: center;
        text-align: center;
    }

    .footer-right {
        flex-direction: row;
        justify-content: center;
    }

    .footer-right a.mention {
        margin-left: 15px;
    }
}

</style>
