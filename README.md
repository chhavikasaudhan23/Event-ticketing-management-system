# Event Ticketing Management System

## Overview
Web app for booking event tickets with a fake payment simulator. Frontend is static HTML/CSS/JS. Backend is PHP + MySQL (optional).

## How to run (frontend-only)
Open `index.html` in vscode.dev or any static host. App uses localStorage for register/login/bookings.

## How to run (full backend)
1. Install XAMPP/MAMP/LAMP.
2. Put `EventTicketingSystem` in web root (htdocs).
3. Import `database/event_ticketing.sql` and `database/sample_data.sql` using phpMyAdmin.
4. Edit `backend/db.php` DB credentials if needed.
5. Start Apache & MySQL, then open `http://localhost/EventTicketingSystem/index.html`.

## Notes
- For production secure passwords, input validation, HTTPS required.
- Image upload requires write permission to `assets/images/uploads/`.
