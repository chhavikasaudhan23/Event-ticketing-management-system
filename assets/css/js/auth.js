// auth.js - registration & login with backend fallback to localStorage
document.addEventListener('DOMContentLoaded', () => {
  const rb = document.getElementById('registerBtn');
  const lb = document.getElementById('loginBtn');
  if(rb) rb.addEventListener('click', registerHandler);
  if(lb) lb.addEventListener('click', loginHandler);
});

function registerHandler() {
  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const phone = document.getElementById('phone').value.trim();
  if(!name || !email || !password) { alert('Fill required fields'); return; }
  const payload = { name, email, password, phone };

  // Try backend
  fetch('/backend/register_user.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(r => r.json())
  .then(res => {
    if(res.success) { alert('Registered successfully'); window.location.href = 'login.html'; }
    else fallbackRegister(payload);
  })
  .catch(_ => fallbackRegister(payload));
}

function fallbackRegister(payload) {
  localStorage.setItem('user', JSON.stringify(payload));
  alert('Registered locally (no backend). Please login.');
  window.location.href = 'login.html';
}

function loginHandler() {
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  if(!email || !password) { alert('Enter credentials'); return; }

  fetch('/backend/login_user.php', {
    method:'POST',
    headers:{ 'Content-Type':'application/json' },
    body: JSON.stringify({ email, password })
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.success) {
      sessionStorage.setItem('user', JSON.stringify(res.user));
      window.location.href = 'events.html';
    } else fallbackLogin(email, password);
  })
  .catch(_ => fallbackLogin(email, password));
}

function fallbackLogin(email, password) {
  const saved = JSON.parse(localStorage.getItem('user') || 'null');
  if(saved && saved.email === email && saved.password === password) {
    sessionStorage.setItem('user', JSON.stringify(saved));
    window.location.href = 'events.html';
  } else {
    alert('Login failed (no backend). Check credentials.');
  }
}
