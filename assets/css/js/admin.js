// admin.js
document.addEventListener('DOMContentLoaded', () => {
  if(document.getElementById('adminLoginBtn')) document.getElementById('adminLoginBtn').addEventListener('click', adminLogin);
  if(document.getElementById('addEventBtn')) {
    document.getElementById('addEventBtn').addEventListener('click', addEvent);
    loadAdminEvents(); loadAdminBookings();
  }
});

function adminLogin() {
  const email = document.getElementById('adminEmail').value;
  const pass = document.getElementById('adminPass').value;
  fetch('/backend/admin_login.php', {
    method:'POST', headers:{ 'Content-Type':'application/json' }, body: JSON.stringify({ email, password: pass })
  })
  .then(r=>r.json())
  .then(res => { if(res.success) { sessionStorage.setItem('admin', JSON.stringify(res.admin)); window.location.href = 'admin-dashboard.html'; } else alert('Admin login failed'); })
  .catch(_ => alert('Admin login failed (no backend)'));
}

function addEvent() {
  const title = document.getElementById('evTitle').value;
  const desc = document.getElementById('evDesc').value;
  const date = document.getElementById('evDate').value;
  const time = document.getElementById('evTime').value;
  const venue = document.getElementById('evVenue').value;
  const category = document.getElementById('evCategory').value;
  const price = document.getElementById('evPrice').value;
  const totalSeats = document.getElementById('evTotalSeats').value;
  const imageFile = document.getElementById('evImage').files[0];
  if(!title || !date) { alert('Title and Date required'); return; }

  const form = new FormData();
  form.append('title', title);
  form.append('description', desc);
  form.append('date', date);
  form.append('time', time);
  form.append('venue', venue);
  form.append('category', category);
  form.append('price', price);
  form.append('total_seats', totalSeats);
  if(imageFile) form.append('image', imageFile);

  fetch('/backend/admin_add_event.php', { method:'POST', body: form })
    .then(r=>r.json())
    .then(res=> { if(res.success) { alert('Event added'); loadAdminEvents(); } else alert('Add failed'); })
    .catch(_ => alert('No backend - cannot add event'));
}

function loadAdminEvents() {
  const container = document.getElementById('adminEvents'); if(!container) return;
  fetch('/backend/fetch_events.php').then(r=>r.json()).then(events=> {
    container.innerHTML = ''; events.forEach(ev => { const div = document.createElement('div'); div.className='ev'; div.innerHTML = `<strong>${ev.title}</strong> - ${ev.date} - Rs ${ev.price}`; container.appendChild(div); });
  }).catch(_ => container.innerHTML = '<p>No backend or no events</p>');
}

function loadAdminBookings() {
  const container = document.getElementById('adminBookings'); if(!container) return;
  fetch('/backend/admin_fetch_bookings.php').then(r=>r.json()).then(bookings=> {
    container.innerHTML = ''; bookings.forEach(b=> { const d = document.createElement('div'); d.innerHTML = `<p>Event:${b.event_id} Amount:Rs${b.amount_paid || b.amount}</p>`; container.appendChild(d); });
  }).catch(_ => container.innerHTML = '<p>No bookings or no backend</p>');
}
