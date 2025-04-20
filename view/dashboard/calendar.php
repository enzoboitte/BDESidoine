<?php 
global $G_sPath, $G_lPermission, $G_sRacine;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/calendar.scss');";

require_once "$G_sRacine/model/Permission.php";
?>
<!-- back button -->
<a href="<?= $G_sPath ?>/dashboard/" class="back-button">Retour</a>

<div class="calendar-container">
    <div class="calendar-header">
        <button onclick="changeWeek(-1)">&lt;</button>
        <h3 id="weekDisplay"></h3>
        <button onclick="changeWeek(1)">&gt;</button>
    </div>
    <?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_EVENT, $G_lPermission)): ?>
    <button class="btn-add">Ajouter un événement</button>
    <?php endif; ?>
    <div class="calendar-grid" id="calendarGrid"></div>
</div>

<?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_EVENT, $G_lPermission)): ?>
<div id="modale-add" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-add-modal">&times;</span>
        <h3>Ajout d'un événement</h3>
        
        <label>Titre</label><input type="text" id="eventTitle" placeholder="Titre de l'événement">
        <label>Date</label><input type="date" id="eventDate">
        
        <label>Heure</label><input type="time" id="eventHour" placeholder="Heure de l'événement">
        <label>Type</label><select id="eventType">
            <option value="event">Événement</option>
            <option value="reunion">Réunion</option>
            <option value="rdv">Rendez-vous</option>
            <option value="tache">Tâche</option>
        </select>
        <div class="modale-actions">
            <button onclick="submitEvent()" class="btn-save">Ajouter</button>
            <button type="button" class="btn-cancel cancel-add">Annuler</button>
        </div>        
    </div>
</div>
<?php endif; ?>

<?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_EVENT, $G_lPermission) || 
    (new CRegle)->F_bIsAutorise(ERegle::DELETE_EVENT, $G_lPermission)): ?>
<div id="modale-edit" class="modale hidden">
    <div class="modale-contenu">
        <span class="modale-fermer" id="close-edit-modal">&times;</span>
        <h3>Modifier un événement</h3>
        
        <label>Titre</label><input type="text" id="eventTitle" placeholder="Titre de l'événement">
        <label>Date</label><input type="date" id="eventDate">
        
        <label>Heure</label><input type="time" id="eventHour" placeholder="Heure de l'événement">
        <label>Type</label><select id="eventType">
            <option value="event">Événement</option>
            <option value="reunion">Réunion</option>
            <option value="rdv">Rendez-vous</option>
            <option value="tache">Tâche</option>
        </select>
        <div class="modale-actions">
            <?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_EVENT, $G_lPermission)): ?>
            <button onclick="modifyEvent()" class="btn-save">Modifier</button>
            <?php endif; ?>
            <?php if((new CRegle)->F_bIsAutorise(ERegle::DELETE_EVENT, $G_lPermission)): ?>
            <button onclick="removeEvent()" class="btn-remove">Supprimer</button>
            <?php endif; ?>
            <button type="button" class="btn-cancel cancel-edit">Annuler</button>
        </div>        
    </div>
</div>
<?php endif; ?>

<script>
    let currentDate = new Date();
    let events = [];

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }

    // recuperer les événements de l'API
    function getEvents() {
        //const date = formatDate(new Date(currentDate));
        const first = currentDate.getDate() - currentDate.getDay() + (currentDate.getDay() === 0 ? -6 : 1);
        const date = formatDate(new Date(currentDate.setDate(first)));
        // et une version pour utiliser la fonction CallApi
        CallApi("POST", `/v1/event/get/week/`, {date: `${date} 00:00`}).then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok');
            }
        }).then(data => {
            let res = data.code;

            events = [];

            for (let i = 0; i < res.length; i++) {
                events.push({
                    id: res[i].idE,
                    titre: res[i].titre,
                    date: res[i].debut,
                    type: res[i].type
                });
            }
            
            renderWeek(currentDate);
        }).catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

    }
    getEvents();

    function renderWeek(date) {
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '<div class="time-slot"></div>';

        const first = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 1);
        const firstDayOfWeek = new Date(date.setDate(first));

        document.getElementById('weekDisplay').textContent =
            `Semaine du ${firstDayOfWeek.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })}`;

        for (let i = 0; i < 7; i++) {
            let day = new Date(firstDayOfWeek);
            day.setDate(firstDayOfWeek.getDate() + i);
            grid.innerHTML += `<div>${day.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'numeric' })}</div>`;
        }

        for (let h = 0; h < 24; h++) {
            grid.innerHTML += `<div class="time-slot">${h.toString().padStart(2, '0')}:00</div>`;
            for (let d = 0; d < 7; d++) {
                grid.innerHTML += `<div id="cell-${d}-${h}"></div>`;
            }
        }

        displayEvents(firstDayOfWeek);
    }

    function changeWeek(direction) {
        currentDate.setDate(currentDate.getDate() + direction * 7);
        getEvents();
        renderWeek(new Date(currentDate));
    }


    <?php if((new CRegle)->F_bIsAutorise(ERegle::CREATE_EVENT, $G_lPermission)): ?>
    function submitEvent() {
        const title = document.getElementById('eventTitle').value;
        const date = document.getElementById('eventDate').value;
        const hour = document.getElementById('eventHour').value;
        const type = document.getElementById('eventType').value;
        if (title && date && hour) {
            //events.push({ title, date, hour, type });
            //renderWeek(currentDate);
            //toggleForm();
            document.getElementById('modale-add').classList.add('hidden');

            // appel API pour ajouter l'événement
            CallApi("POST", "/v1/event/add", { title, date, hour, type })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(data => {
                    let res = data.msg;
                    if (res == "ok") {
                        console.log("Event added successfully");

                        // recuperation de tous les événements de l'API
                        getEvents(); // refresh events after adding
                    } else {
                        console.error("Failed to add event");
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });

            // vider le formulaire
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDate').value = '';
            document.getElementById('eventHour').value = '';
            document.getElementById('eventType').value = 'event';
        } else {
            alert('Merci de remplir tous les champs.');
        }
    }
    <?php endif; ?>

    <?php if((new CRegle)->F_bIsAutorise(ERegle::UPDATE_EVENT, $G_lPermission)): ?>
    function modifyEvent() {
        const eventId = document.getElementById('modale-edit').dataset.id;
        const title = document.querySelector('#modale-edit #eventTitle').value;
        const date = document.querySelector('#modale-edit #eventDate').value;
        const hour = document.querySelector('#modale-edit #eventHour').value;
        const type = document.querySelector('#modale-edit #eventType').value;
        if (eventId && title && date && hour && type) {
            //events.push({ title, date, hour, type });
            //renderWeek(currentDate);
            //toggleForm();
            document.getElementById('modale-edit').classList.add('hidden');

            // appel API pour modifier l'événement
            CallApi("PUT", "/v1/event/update", { id: eventId, title: title, date: date, hour: hour, type: type })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(data => {
                    let res = data.msg;
                    if (res == "ok") {
                        console.log("Event updated successfully");

                        // recuperation de tous les événements de l'API
                        getEvents(); // refresh events after updating
                    } else {
                        console.error("Failed to update event");
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });

            // vider le formulaire
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDate').value = '';
            document.getElementById('eventHour').value = '';
            document.getElementById('eventType').value = 'event';
        } else {
            alert('Merci de remplir tous les champs.');
        }
    }
    <?php endif; ?>

    <?php if((new CRegle)->F_bIsAutorise(ERegle::DELETE_EVENT, $G_lPermission)): ?>
    function removeEvent() {
        const eventId = document.getElementById('modale-edit').dataset.id;
        if (eventId) {
            document.getElementById('modale-edit').classList.add('hidden');
            // appel API pour supprimer l'événement
            CallApi("DELETE", `/v1/event/rm/${eventId}`)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(data => {
                    let res = data.msg;
                    if (res == "ok") {
                        console.log("Event removed successfully");

                        // recuperation de tous les événements de l'API
                        getEvents(); // refresh events after removing
                    } else {
                        console.error("Failed to remove event");
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });

            // vider le formulaire
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDate').value = '';
            document.getElementById('eventHour').value = '';
            document.getElementById('eventType').value = 'event';
        } else {
            alert('Merci de sélectionner un événement à supprimer.');
        }
    }
    <?php endif; ?>

    function displayEvents(firstDayOfWeek) {
        const start = new Date(firstDayOfWeek);
        start.setHours(0, 0, 0, 0);

        const end = new Date(start);
        end.setDate(end.getDate() + 6);
        end.setHours(23, 59, 59, 999);

        events.forEach(event => {
            const eventDate = new Date(`${event.date}`);

            if (eventDate >= start && eventDate <= end) {
                const jsDay = eventDate.getDay();
                const dayIndex = (jsDay === 0) ? 6 : jsDay - 1;
                const hour = parseInt(event.date.split(" ")[1].split(':')[0]);
                const cell = document.getElementById(`cell-${dayIndex}-${hour}`);
                if (cell) {
                    cell.innerHTML += `<div data-id="${event.id}" class="event ${event.type}"><p>${event.titre}</p><p class="hour">${event.date.split(" ")[1].substring(0, 5)}</p></div>`;
                }

                // ajout d'un handler click sur les events pour ceux qui on l'attribut data-id
                const eventElement = cell.querySelector(`.event[data-id="${event.id}"]`);
                if (eventElement) 
                {
                    eventElement.addEventListener('click', function() {
                        const eventId = this.getAttribute('data-id');
                        console.log(`Event ID: ${eventId}`);
                        
                        // ici on peut faire un appel API pour recuperer les infos de l'événement et les afficher dans le formulaire
                        CallApi("POST", `/v1/event/get/${eventId}`)
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    throw new Error('Network response was not ok');
                                }
                            })
                            .then(data => {
                                let res = data.code;
                                if (res) {
                                    document.querySelector('#modale-edit #eventTitle').value = res.titre;
                                    document.querySelector('#modale-edit #eventDate').value = res.debut.split(" ")[0];
                                    document.querySelector('#modale-edit #eventHour').value = res.debut.split(" ")[1].substring(0, 5);
                                    document.querySelector('#modale-edit #eventType').value = res.type;
                                    document.getElementById('modale-edit').classList.remove('hidden');
                                    document.getElementById('modale-edit').dataset.id = eventId;
                                }
                            })
                            .catch(error => {
                                console.error('There was a problem with the fetch operation:', error);
                            });
                    });
                }
            }
        });
    }
    //renderWeek(currentDate);

    // fonction d'appel de lien api /v1/event/add/...,...,..... | /v1/event/rm/... | /v1/event/update/...,...,..... pour ajouter/supprimer/modifier les événements 
    function CallApi(sMethod, sUrl, oData) {
        return fetch(`<?= $G_sPath ?>${sUrl}`, {
            method: sMethod,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(oData)
        })
    }
    // CallApi("POST", "/v1/event/add", {title: "test", date: "2023-10-01", hour: "12:00", type: "event"});
    // CallApi("DELETE", "/v1/event/rm", {id: 1});
    // CallApi("PUT", "/v1/event/update", {id: 1, title: "test", date: "2023-10-01", hour: "12:00", type: "event"});
    // CallApi("GET", "/v1/event/get", {id: 1});
    // CallApi("GET", "/v1/event/get/all", {});

    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modale-add').classList.remove('hidden');
        });
    });
    document.getElementById('close-add-modal').addEventListener('click', () => {
        document.getElementById('modale-add').classList.add('hidden');
    });

    document.querySelectorAll('.cancel-add, .cancel-edit, #close-edit-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modale-add').classList.add('hidden');
            document.getElementById('modale-edit').classList.add('hidden');
        });
    });
</script>