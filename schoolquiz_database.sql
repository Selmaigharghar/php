
INSERT INTO vak (id, naam) VALUES
(1, 'Wiskunde'),
(2, 'Natuurkunde'),
(3, 'Scheikunde'),
(4, 'Biologie');


INSERT INTO onderwerp (id, naam, vak_id) VALUES
(1, 'Algebra', 1),
(2, 'Meetkunde', 1),
(3, 'Kracht en Beweging', 2),
(4, 'Elektriciteit', 2),
(5, 'Moleculen', 3),
(6, 'Reacties', 3),
(7, 'Cellen', 4),
(8, 'Evolutie', 4);

INSERT INTO gebruiker (gebruikersnaam, wachtwoord, niveau, leerjaar, school, vak_id) VALUES
('Jan',   'test123', 'HAVO', '5', 'St. Michaelcollege', 1),
('Lisa',  'test123', 'VWO',  '6', 'Bertrand Russel',    2),
('Emma',  'test123', 'HAVO', '5', 'Zaanlands',          3);

INSERT INTO quiz (titel, onderwerp_id, niveau, leerjaar) VALUES
('Algebra – Basisvoorwaarden', 1, 'HAVO', '4'),
('Meetkunde – Driehoeken',     2, 'HAVO', '5'),
('Krachten – Introductie',     3, 'VWO',  '4'),
('Elektriciteit – Spanning',   4, 'HAVO', '5'),
('Moleculen – Bouwstenen',     5, 'HAVO', '3'),
('Reacties – Ontledingen',     6, 'VWO',  '6'),
('Cellen – Basiskennis',       7, 'HAVO', '2'),
('Evolutie – Darwin',          8, 'VWO',  '5');