<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/event.scss');";

include_once "$G_sRacine/model/Event.php";
$events = (new CEvents(true))->getEvents("event");
$pastEvents = (new CEvents(false))->getEvents("event"); // événements passés
?>

<section id="event">
    <div class="container">
        <h2>Nos évenement</h2>
        <?php if (!empty($events)): ?>
            <div class="first-card">
                <?php $event = array_shift($events); ?>
                <div class="card">
                <img src="<?= $G_sPath.htmlspecialchars($event->getImg()) ?>" alt="event">
                <h3><?= htmlspecialchars($event->getTitre()) ?></h3>
                <p><?= htmlspecialchars($event->getPhrase()) ?></p>
                <div class="card-footer">
                    <p class="btn">prochain événement</p>
                    <span><?= date('d/m H:i', strtotime($event->getDebut())) ?></span>
                </div>
                </div>
            </div>
            <div class="card-list">
                <?php foreach ($events as $event): ?>
                    <?php if($event->getType() != "event") continue; ?>
                    <div class="card">
                        <img src="<?= $G_sPath.htmlspecialchars($event->getImg()) ?>" alt="event">
                        <h3><?= htmlspecialchars($event->getTitre()) ?></h3>
                        <p><?= htmlspecialchars($event->getPhrase()) ?></p>
                        <div class="card-footer">
                        <p class="btn">prochain événement</p>
                        <span><?= date('d/m H:i', strtotime($event->getDebut())) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun événement à venir.</p>
        <?php endif; ?>
    </div>
</section>


<?php if (!empty($pastEvents)): ?>
<section class="past-section">
    <h2>Évènement Passé</h2>
    <div class="past-events">
        <?php foreach ($pastEvents as $event): ?>
            <?php if($event->getType() != "event") continue; ?>
            <div class="past-card">
                <div class="past-info">
                    <img src="<?= $G_sPath.htmlspecialchars($event->getImg()) ?>" alt="event">
                    <div class="past-title"><?= htmlspecialchars($event->getTitre()) ?></div>
                </div>
                <div class="past-badges">
                    <div class="badge"><?= date('d/m', strtotime($event->getDebut())) ?></div>
                    <div class="badge"><?= date('H:i', strtotime($event->getDebut())) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>