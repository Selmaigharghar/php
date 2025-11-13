INSERT INTO gebruiker (id, gebruikersnaam, wachtwoord, niveau, leerjaar, school) VALUES
(1, 'emma01', 'wachtwoord123', 'HAVO', '4', 'Stedelijk College Eindhoven'),
(2, 'dylan02', 'quizmaster', 'VWO', '5', 'Gymnasium Apeldoorn'),
(3, 'sara03', 'lerenisleuk', 'VMBO-T', '3', 'Carmel College'),
(4, 'lucas04', 'pi314', 'VWO', '6', 'Lyceum Rotterdam');

INSERT INTO vak (id, naam) VALUES
(1, 'Wiskunde A'),
(2, 'Wiskunde B'),
(3, 'Wiskunde C'),
(4, 'Wiskunde D'),
(5, 'Natuurkunde'),
(6, 'Scheikunde'),
(7, 'Biologie'),
(8, 'Informatica');

INSERT INTO onderwerp (id, naam, vak_id) VALUES
(1, 'Statistiek', 1),
(2, 'Primitieven', 2),
(3, 'Afgeleiden', 2),
(4, 'Logica', 3),
(5, 'Vectoren', 4),
(6, 'Kracht en Beweging', 5),
(7, 'Atomen en Moleculen', 6),
(8, 'Celbiologie', 7),
(9, 'Algoritmen', 8);

INSERT INTO quiz (id, titel, onderwerp_id, niveau, leerjaar, created_at) VALUES
(1, 'Quiz Statistiek HAVO 4', 1, 'HAVO', '4', '2025-01-10'),
(2, 'Quiz Primitieven VWO 5', 2, 'VWO', '5', '2025-02-15'),
(3, 'Quiz Afgeleiden VWO 5', 3, 'VWO', '5', '2025-03-01'),
(4, 'Quiz Logica HAVO 4', 4, 'HAVO', '4', '2025-01-25'),
(5, 'Quiz Vectoren VWO 6', 5, 'VWO', '6', '2025-03-20'),
(6, 'Quiz Kracht en Beweging', 6, 'HAVO', '5', '2025-04-05'),
(7, 'Quiz Atomen en Moleculen', 7, 'HAVO', '4', '2025-04-10'),
(8, 'Quiz Celbiologie', 8, 'VWO', '4', '2025-02-22'),
(9, 'Quiz Algoritmen', 9, 'VWO', '5', '2025-05-01');

INSERT INTO flashcard (id, vraag, antwoord, onderwerp_id) VALUES
(1, 'Wat is de som van alle kansen in een kansverdeling?', 'Altijd 1.', 1),
(2, 'Wat is de primitieve van f(x) = 2x?', 'F(x) = x² + C', 2),
(3, 'Wat is de afgeleide van sin(x)?', 'cos(x)', 3),
(4, 'Wat betekent “¬p” in de logica?', 'Niet p (ontkenning van p)', 4),
(5, 'Wat is de richting van een vector (3,4)?', 'tan⁻¹(4/3)', 5),
(6, 'Wat is de formule van de tweede wet van Newton?', 'F = m × a', 6),
(7, 'Wat is een molecuul?', 'Een groep atomen die samen één deeltje vormen.', 7),
(8, 'Wat doet het celmembraan?', 'Regelt wat er in en uit de cel gaat.', 8),
(9, 'Wat is een algoritme?', 'Een reeks stappen om een probleem op te lossen.', 9);

INSERT INTO dictee (id, titel, onderwerp_id, niveau, leerjaar, created_at) VALUES
(1, 'Oefentoets Statistiek', 1, 'HAVO', '4', '2025-03-01'),
(2, 'Oefentoets Primitieven', 2, 'VWO', '5', '2025-03-12'),
(3, 'Oefentoets Afgeleiden', 3, 'VWO', '5', '2025-04-01'),
(4, 'Oefentoets Logica', 4, 'HAVO', '4', '2025-02-28'),
(5, 'Oefentoets Vectoren', 5, 'VWO', '6', '2025-04-15'),
(6, 'Oefentoets Kracht en Beweging', 6, 'HAVO', '5', '2025-05-10'),
(7, 'Oefentoets Atomen en Moleculen', 7, 'HAVO', '4', '2025-05-20'),
(8, 'Oefentoets Celbiologie', 8, 'VWO', '4', '2025-03-30'),
(9, 'Oefentoets Algoritmen', 9, 'VWO', '5', '2025-06-01');

INSERT INTO gebruiker_quiz (id, gebruiker_id, quiz_id, score, datum_gemaakt) VALUES
(1, 1, 1, 85, '2025-03-02'),
(2, 1, 2, 70, '2025-03-10'),
(3, 2, 3, 92, '2025-03-12'),
(4, 3, 4, 60, '2025-03-15'),
(5, 4, 5, 88, '2025-03-22'),
(6, 2, 6, 74, '2025-04-07'),
(7, 1, 7, 81, '2025-04-11'),
(8, 3, 8, 77, '2025-04-25'),
(9, 4, 9, 95, '2025-05-03');
