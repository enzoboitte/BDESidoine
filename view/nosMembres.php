<?php 
global $G_sPath, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/membres.css');"; 
?>

<h1 class="titre-page">Nos membres</h1>

<form method="get" class="form-annee">
    <label for="annee">Choisissez l'année :</label>
    <select name="annee" id="annee" onchange="this.form.submit()">
        <option value="2024-2025" <?= $year == '2024-2025' ? 'selected' : '' ?>>2024/2025</option>
    </select>
</form>

<div class="organigramme-wrapper">

    <!-- PRÉSIDENCE -->
    <h2>Présidence</h2>
    <div class="niveau">
        <?php foreach ($members as $m) :
            if (in_array($m['role'], ['Présidente', 'Président', 'Vice-Présidente', 'Vice-président'])) : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <!-- TRÉSORERIE -->
    <h2>Trésorerie</h2>
    <div class="niveau">
        <?php foreach ($members as $m) :
            if (in_array($m['role'], ['Trésorier', 'Vice-trésorier'])) : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <!-- SECRÉTARIAT -->
    <h2>Secrétariat</h2>
    <div class="niveau">
        <?php foreach ($members as $m) :
            if (in_array($m['role'], ['Secrétaire', 'Vice-secrétaire'])) : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <div class="pole">
    <h2>Pôle Événementiel</h2>
    
    <!-- Responsable -->
    <div class="niveau-responsable">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Responsable Événementiel') : ?>
                <div class="membre responsable">
                    <img src="<?= URL ?>src/img/membres/<?= $m['photo'] ?? 'default.png' ?>" alt="Photo de <?= $m['prenom'] ?>">
                    <strong><?= $m['prenom'] ?></strong>
                    <span><?= $m['role'] ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <!-- Membres -->
    <div class="niveau-membres">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Membre Événementiel') : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= $m['photo'] ?? 'default.png' ?>" alt="Photo de <?= $m['prenom'] ?>">
                    <strong><?= $m['prenom'] ?></strong>
                    <span><?= $m['role'] ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>
</div>


   <!-- PÔLE COMMUNICATION -->
<div class="pole">
    <h2>Pôle Communication</h2>

    <!-- Responsable -->
    <div class="niveau-responsable">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Responsable Communication') : ?>
                <div class="membre responsable">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
        <?php endif;
        endforeach; ?>
    </div>

    <!-- Membres -->
    <div class="niveau-membres">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Membre Communication') : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
        <?php endif;
        endforeach; ?>
    </div>
</div>


    <!-- PÔLE SÉCURITÉ -->
<div class="pole">
    <h2>Pôle Sécurité</h2>

    <!-- Responsable -->
    <div class="niveau-responsable">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Responsable Sécurité') : ?>
                <div class="membre responsable">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
        <?php endif;
        endforeach; ?>
    </div>

    <!-- Membres -->
    <div class="niveau-membres">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Membre Sécurité') : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
        <?php endif;
        endforeach; ?>
    </div>
</div>


    <!-- ADMINISTRATEURS -->
    <h2>Administrateurs</h2>
    <div class="niveau">
        <?php foreach ($members as $m) :
            if ($m['role'] === 'Administrateur') : ?>
                <div class="membre">
                    <img src="<?= URL ?>src/img/membres/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" alt="Photo de <?= htmlspecialchars($m['prenom']) ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?></strong>
                    <span><?= htmlspecialchars($m['role']) ?></span>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

</div>
