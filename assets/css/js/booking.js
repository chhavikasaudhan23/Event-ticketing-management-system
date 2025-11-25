// booking.js
document.addEventListener('DOMContentLoaded', () => {
  if(document.getElementById('booking-info')) showBookingForm();
  if(document.getElementById('bookingList')) showBookingsList();
});

function showBookingForm() {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');
  if(!id) { document.getElementById('booking-info').innerHTML = '<p>No event selected</p>'; return; }

  fetch(`/backend/event_details.php?id=${id}`)
    .then(r=>r.json())
    .then(ev => { if(ev && ev.event_id) buildForm(ev); else buildForm({event_id:id, title:'Event', price:0}); })
    .catch(_ => buildForm({event_id:id, title:'Event', price:0}));
}

function buildForm(ev) {
  const container = document.getElementById('booking-info');
  container.innerHTML = `
    <h2>Book: ${ev.title}</h2>
    <p>Price per ticket: Rs ${ev.price}</p>
    <label>Quantity</label>
    <input id="qty" type="number" value="1" min="1" max="10">
    <p>Total: Rs <span id="total">${ev.price}</span></p>
    <button id="proceed">Proceed to Payment</button>
  `;
  const qty = document.getElementById('qty'), total = document.getElementById('total');
  qty.addEventListener('input', ()=> total.innerText = (Number(qty.value) * Number(ev.price || 0)));
  document.getElementById('proceed').addEventListener('click', () => {
    const q = Number(qty.value) || 1;
    const amount = q * Number(ev.price || 0);
    window.location.href = `payment.html?eventId=${encodeURIComponent(ev.event_id)}&amount=${encodeURIComponent(amount)}&qty=${q}`;
  });
}

function showBookingsList() {
  fetch('/backend/admin_fetch_bookings.php')
    .then(r=>r.json())
    .then(data => { if(Array.isArray(data) && data.length) renderBookings(data); else throw 'no backend'; })
    .catch(_ => {
      const bookings = JSON.parse(localStorage.getItem('bookings') || '[]');
      renderBookings(bookings);
    });
}

function renderBookings(bookings) {
  const list = document.getElementById('bookingList') || document.getElementById('adminBookings');
  if(!list) return;
  list.innerHTML = '';
  if(!bookings.length) return list.innerHTML = '<p>No bookings found</p>';
  bookings.forEach(b => {
    const div = document.createElement('div');
    div.className = 'event-card';
    div.innerHTML = `
      <p><strong>Event ID:</strong> ${b.eventId || b.event_id}</p>
      <p><strong>Amount:</strong> Rs ${b.amount || b.amount_paid}</p>
      <p><strong>Quantity:</strong> ${b.qty || b.quantity || 1}</p>
      <p><strong>Status:</strong> ${b.status || b.payment_status || 'Success'}</p>
      <p><strong>Date:</strong> ${b.date || ''}</p>
    `;
    list.appendChild(div);
  });
}
