// payment.js - fake payment
document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const amount = params.get('amount') || '0';
  const eventId = params.get('eventId') || '';
  const qty = params.get('qty') || 1;
  document.getElementById('amountSpan').innerText = 'Rs ' + amount;
  const payBtn = document.getElementById('payBtn');
  if(payBtn) payBtn.addEventListener('click', () => {
    const method = document.getElementById('paymentMethod').value;
    const number = document.getElementById('number').value.trim();
    const holder = document.getElementById('holder').value.trim();
    const cvv = document.getElementById('cvv').value.trim();
    if(!number || !holder || !cvv) { alert('Please fill payment fields'); return; }
    payBtn.disabled = true; payBtn.innerText = 'Processing...';
    setTimeout(() => {
      const success = Math.random() > 0.05;
      const bookingObj = { eventId, qty, amount, method, status: success ? 'Success' : 'Failed', date: new Date().toISOString() };
      fetch('/backend/fake_payment.php', {
        method:'POST', headers:{ 'Content-Type':'application/json' }, body: JSON.stringify(bookingObj)
      })
      .then(r=>r.json())
      .then(res=> { localSave(bookingObj); if(success) window.location.href = 'payment-success.html'; else { alert('Payment failed'); window.location.href = 'payment.html?amount='+amount+'&eventId='+eventId; } })
      .catch(_ => { localSave(bookingObj); if(success) window.location.href = 'payment-success.html'; else { alert('Payment failed'); window.location.href = 'payment.html?amount='+amount+'&eventId='+eventId; } });
    }, 1200);
  });
});

function localSave(obj) {
  const arr = JSON.parse(localStorage.getItem('bookings') || '[]'); arr.push(obj); localStorage.setItem('bookings', JSON.stringify(arr));
}
