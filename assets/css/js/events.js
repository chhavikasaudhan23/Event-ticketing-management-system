// events.js - show list and details
document.addEventListener('DOMContentLoaded', () => {
  if(document.getElementById('events-container')) loadEvents();
  if(document.getElementById('details')) loadEventDetails();
});

function loadEvents() {
  fetch('/backend/fetch_events.php')
    .then(r=>r.json())
    .then(data => { if(Array.isArray(data) && data.length) renderEvents(data); else throw 'no backend'; })
    .catch(_ => {
      // fallback sample events
      const events = [
        { event_id:1, title:'Music Concert', description:'Live music', price:500, image:'assets/images/default-event.jpg', date:'2025-12-10' },
        { event_id:2, title:'Tech Conference', description:'Tech talks', price:300, image:'assets/images/default-event.jpg', date:'2026-01-15' },
      ];
      renderEvents(events);
    });
}

function renderEvents(events) {
  const container = document.getElementById('events-container');
  container.innerHTML = '';
  events.forEach(ev => {
    const div = document.createElement('div');
    div.className = 'event-card';
    const img = ev.image || 'assets/images/default-event.jpg';
    div.innerHTML = `
      <img src="${img}" alt="${ev.title}">
      <h3>${ev.title}</h3>
      <p>${ev.description || ''}</p>
      <p>Date: ${ev.date || ''}</p>
      <p>Price: Rs ${ev.price}</p>
      <a href="event-details.html?id=${ev.event_id}">Details</a>
      <a href="booking.html?id=${ev.event_id}">Book Now</a>
    `;
    container.appendChild(div);
  });
}

function loadEventDetails() {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');
  if(!id) return document.getElementById('details').innerText = 'No event selected';
  fetch(`/backend/event_details.php?id=${id}`)
    .then(r=>r.json())
    .then(ev => {
      if(ev && ev.event_id) {
        const img = ev.image || 'assets/images/default-event.jpg';
        const details = document.getElementById('details');
        details.innerHTML = `
          <div class="event-card">
            <img src="${img}" alt="${ev.title}">
            <h2>${ev.title}</h2>
            <p>${ev.description || ''}</p>
            <p>Date: ${ev.date} ${ev.time || ''}</p>
            <p>Venue: ${ev.venue || ''}</p>
            <p>Price: Rs ${ev.price}</p>
            <a href="booking.html?id=${ev.event_id}">Book Now</a>
          </div>
        `;
      } else document.getElementById('details').innerText = 'Event not found';
    })
    .catch(_ => document.getElementById('details').innerText = 'Cannot load details (no backend)');
}
