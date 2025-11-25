USE event_ticketing;

INSERT INTO admins (name,email,password) VALUES ('Admin','admin@site.com','admin123');

INSERT INTO events (title,description,date,time,venue,category,price,total_seats,available_seats,image_path)
VALUES 
('Music Concert','Popular artist live','2025-12-10','18:00:00','City Arena','Music',500,500,500,'assets/images/default-event.jpg'),
('Tech Conference','Latest tech talks','2026-01-15','09:00:00','Convention Center','Tech',300,300,300,'assets/images/default-event.jpg');
