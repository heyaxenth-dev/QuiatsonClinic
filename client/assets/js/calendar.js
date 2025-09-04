import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

// Import FullCalendar CSS
import '@fullcalendar/core/index.css';
import '@fullcalendar/daygrid/index.css';
import '@fullcalendar/timegrid/index.css';
import '@fullcalendar/list/index.css';

document.addEventListener('DOMContentLoaded', function () {
	let calendarEl = document.getElementById('calendar');

	let calendar = new Calendar(calendarEl, {
		plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
		initialView: 'dayGridMonth',
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
		},
		events: '/QuiatsonClinic/get-appointments.php', // fetch from PHP backend
	});

	calendar.render();
});
