<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/contact.scss');";

include_once "$G_sRacine/model/Event.php";
$events = (new CEvents(true))->getEvents();
$pastEvents = (new CEvents(false))->getEvents(); // événements passés
?>
<style>

    section#event {
        padding: 60px 20px;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
        width: 100%;
    }

    .first-card {
        display: flex;
        justify-content: center;
    }

    .card-list {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 20px;
        width: 100%;
    }

    .card {
        background: white;
        border-radius: 10px;
        width: 230px;
        box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card img {
        width: 100px;
        height: 100px;
        border-radius: 1000px;
        object-fit: cover;
        margin: 20px auto 10px;
    }

    .card h3 {
        margin: 0;
        font-size: 1rem;
        letter-spacing: 1px;
    }

    .card p {
        font-size: 0.8rem;
        margin: 10px 0;
    }

    .card-footer {
        background-color: #a03333;
        color: white;
        font-size: 0.7rem;
        padding: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-footer span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn {
        color: white;
        border: none;
        background: none;
        font-size: 0.7rem;
        cursor: pointer;
    }

    .past-section {
        margin-top: 60px;
        text-align: center;
        color: white;
    }

    .past-section h2 {
        font-size: 1.2rem;
        margin-bottom: 10px;

        color: #000;

        &::after {
            content: '';
            display: block;
            width: 50px;
            height: 2px;
            background: #000;
            margin: 10px auto;
        }
    }

    .past-events {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .past-card {
        background: white;
        border-radius: 10px;
        width: 400px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
    }

    .past-card img {
        width: 40px;
        height: 40px;
        border-radius: 100px;
        object-fit: cover;
        margin-right: 10px;
    }

    .past-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 10px;
    }

    .past-title {
        font-weight: bold;
        font-size: 0.85rem;
        white-space: nowrap;

        color: #000;
    }

    .past-badges {
        display: flex;
        gap: 5px;
    }

    .badge {
        background: #43a047;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        white-space: nowrap;
    }
</style>

<section id="event">
    <div class="container">
        <h2>Nos évenement</h2>
        <?php if (!empty($events)): ?>
            <div class="first-card">
                <?php $event = array_shift($events); ?>
                <div class="card">
                <img src="<?= $G_sPath."/src/img/uploads/".htmlspecialchars($event->getImg()) ?>" alt="event">
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
                <div class="card">
                    <img src="<?= $G_sPath."/src/img/uploads/".htmlspecialchars($event->getImg()) ?>" alt="event">
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
        <div class="past-card">
            <div class="past-info">
                <img src="<?= $G_sPath."/src/img/uploads/".htmlspecialchars($event->getImg()) ?>" alt="event">
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