<?php 
global $G_sPath, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/login.css');";


include_once "$G_sRacine/model/Account.php";
if((new CAccount())->F_bIsConnect())
    header("Location: $G_sPath/dashboard/home");
    
?>

<div class="cnx">
    <h1 >Connexion</h1></br>
    <div class="cnx-form">
        <form id="login-form" method="GET" action="<?= $G_sPath ?>/dashboard/login">
            <label for ="id">Identifiant:</label> 
            <input type="id" name="identifier" required placeholder="Identifiant"><br>

            <label for ="password">Mot de passe:</label> 
            <input type="password" name="passwd" required placeholder="Mot de passe"><br>

            <p class="msg"></p>

            <button type="submit" class="btn-envoyer">Se connecter</button>
        </form>
    </div>
</div>

<script>
    let login_form = document.getElementById("login-form");
    let msg_form   = document.querySelector("p.msg");

    login_form.onsubmit = async function (e)
    {
        e.preventDefault();

        const username = e.target.elements.identifier.value;
        const passwd = e.target.elements.passwd.value;

        try {
            const response = await fetch(`<?= $G_sPath ?>/v1/account/login/${encodeURIComponent(username)},${encodeURIComponent(hash(passwd))}`, {
                method: "GET",
            });
            let data = await response.json();

            console.table(data);

            if(data.code != 0)
                msg_form.innerText = data.msg;
            else
            {
                
                msg_form.innerText = "";
                window.location = `<?= $G_sPath ?>/dashboard/home`;
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }
</script>