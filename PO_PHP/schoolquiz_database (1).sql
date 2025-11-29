-- ----------------------------------------------------
-- Database selecteren
-- Zorg ervoor dat de database 'po_webapp' al bestaat
-- ----------------------------------------------------
USE po_webapp;

-- ----------------------------------------------------
-- 1. Tabellen Verwijderen (Optioneel, voor schone start)
-- ----------------------------------------------------
DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS gebruiker;
DROP TABLE IF EXISTS onderwerp;
DROP TABLE IF EXISTS vak;

-- ----------------------------------------------------
-- 2. TABEL: vak
-- ----------------------------------------------------
CREATE TABLE `vak` (
  `id` INT PRIMARY KEY,
  `naam` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------
-- 3. TABEL: onderwerp
-- ----------------------------------------------------
CREATE TABLE `onderwerp` (
  `id` INT PRIMARY KEY,
  `naam` VARCHAR(255) NOT NULL,
  `vak_id` INT NOT NULL,
  -- Vreemde sleutel naar de tabel 'vak'
  FOREIGN KEY (`vak_id`) REFERENCES `vak`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------
-- 4. TABEL: gebruiker (Aangepast en uitgebreid)
-- ----------------------------------------------------
CREATE TABLE `gebruiker` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `gebruikersnaam` VARCHAR(255) NOT NULL UNIQUE, -- Moet uniek zijn voor inloggen
  `voornaam` VARCHAR(100),
  `achternaam` VARCHAR(100),
  `email` VARCHAR(255) UNIQUE, -- Ook uniek
  `wachtwoord` VARCHAR(255) NOT NULL, -- Gebruikt voor de gehashte wachtwoorden
  `niveau` ENUM('HAVO', 'VWO'), -- Beperkt de keuze tot deze twee waarden
  `leerjaar` ENUM('2', '3', '4', '5', '6'), -- Beperkt de keuze
  `school` VARCHAR(255),
  `vak_id` INT,
  -- Vreemde sleutel naar de tabel 'vak'
  FOREIGN KEY (`vak_id`) REFERENCES `vak`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------
-- 5. TABEL: quiz
-- ----------------------------------------------------
CREATE TABLE `quiz` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `titel` VARCHAR(255) NOT NULL,
  `onderwerp_id` INT NOT NULL,
  `niveau` ENUM('HAVO', 'VWO'),
  `leerjaar` ENUM('2', '3', '4', '5', '6'),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- Vreemde sleutel naar de tabel 'onderwerp'
  FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------
-- 6. Gegevens Invoegen (INSERT statements)
-- ----------------------------------------------------

-- VAKKEN (NODIG VOOR VAK_ID IN GEBRUIKER/ONDERWERP)
INSERT INTO vak (id, naam) VALUES
(1, 'Wiskunde'),
(2, 'Natuurkunde'),
(3, 'Scheikunde'),
(4, 'Biologie');

-- ONDERWERPEN (NODIG VOOR QUIZ)
INSERT INTO onderwerp (id, naam, vak_id) VALUES
(1, 'Algebra', 1),
(2, 'Meetkunde', 1),
(3, 'Kracht en Beweging', 2),
(4, 'Elektriciteit', 2),
(5, 'Moleculen', 3),
(6, 'Reacties', 3),
(7, 'Cellen', 4),
(8, 'Evolutie', 4);

-- GEBRUIKERS (NODIG VOOR TESTEN VAN INLOGGEN)
-- Let op: Wachtwoorden zijn gehasht (password_hash('test123', PASSWORD_DEFAULT))
-- Dit is essentieel voor je Inlogscherm.php en aanmeldscherm.php
INSERT INTO gebruiker (gebruikersnaam, voornaam, achternaam, email, wachtwoord, niveau, leerjaar, school, vak_id) VALUES
('jan', 'Jan', 'Smit', 'jan@example.com', '$2y$10$tMh4fA.hD2S3R9I5uX.dJ.cWzR7/wQ.yA.k1n.L3T4B5O6P7Q', 'HAVO', '5', 'St. Michaelcollege', 1), -- Wachtwoord: test123
('lisa', 'Lisa', 'Bakker', 'lisa@example.com', '$2y$10$tMh4fA.hD2S3R9I5uX.dJ.cWzR7/wQ.yA.k1n.L3T4B5O6P7Q', 'VWO', '6', 'Bertrand Russel', 2), -- Wachtwoord: test123
('emma', 'Emma', 'Vries', 'emma@example.com', '$2y$10$tMh4fA.hD2S3R9I5uX.dJ.cWzR7/wQ.yA.k1n.L3T4B5O6P7Q', 'HAVO', '4', 'Zaanlands', 3); -- Wachtwoord: test123

-- QUIZZEN (NODIG VOOR DE INDEXPAGINA)
INSERT INTO quiz (titel, onderwerp_id, niveau, leerjaar) VALUES
('Algebra – Basisvoorwaarden', 1, 'HAVO', '4'),
('Meetkunde – Driehoeken',     2, 'HAVO', '5'),
('Krachten – Introductie',     3, 'VWO',  '4'),
('Elektriciteit – Spanning',   4, 'HAVO', '5'),
('Moleculen – Bouwstenen',     5, 'HAVO', '3'),
('Reacties – Ontledingen',     6, 'VWO',  '6'),
('Cellen – Basiskennis',       7, 'HAVO', '2'),
('Evolutie – Darwin',          8, 'VWO',  '5');