<?php 
global $G_sPath;
$G_sCss .= "@import url('$G_sPath/src/css/dashboard/calendar.scss');";
?>
<div class="calendar-container">
    <div class="calendar-header">
        <button onclick="changeWeek(-1)">&lt;</button>
        <h3 id="weekDisplay"></h3>
        <button onclick="changeWeek(1)">&gt;</button>
    </div>
    <button onclick="toggleForm()">Ajouter un événement</button>
    <div class="event-form" id="eventForm">
        <input type="text" id="eventTitle" placeholder="Titre de l'événement">
        <input type="date" id="eventDate">
        <!--<select id="eventHour"></select>-->
        <input type="time" id="eventHour" placeholder="Heure de l'événement">
        <select id="eventType">
            <option value="event">Événement</option>
            <option value="reunion">Réunion</option>
            <option value="rdv">Rendez-vous</option>
            <option value="tache">Tâche</option>
        </select>
        <button onclick="submitEvent()">Ajouter</button>
    </div>
    <div class="calendar-grid" id="calendarGrid"></div>
</div>

<script>
    let currentDate = new Date();
    let events = [];

    // recuperer les événements de l'API
    function getEvents() {
        // et une version pour utiliser la fonction CallApi
        CallApi("POST", "/v1/event/get/all", {}).then(response => {
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
        renderWeek(new Date(currentDate));
    }

    function toggleForm() {
        const form = document.getElementById('eventForm');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }

    function submitEvent() {
        const title = document.getElementById('eventTitle').value;
        const date = document.getElementById('eventDate').value;
        const hour = document.getElementById('eventHour').value;
        const type = document.getElementById('eventType').value;
        if (title && date && hour) {
            //events.push({ title, date, hour, type });
            //renderWeek(currentDate);
            toggleForm();

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
</script>