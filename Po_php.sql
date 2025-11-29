DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS gebruiker;
DROP TABLE IF EXISTS onderwerp;
DROP TABLE IF EXISTS vak;

-- ---------------------------------------------
-- TABEL: vak
-- ---------------------------------------------
CREATE TABLE `vak` (
  `id` INT PRIMARY KEY,
  `naam` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------
-- TABEL: onderwerp
-- ---------------------------------------------
CREATE TABLE `onderwerp` (
  `id` INT PRIMARY KEY,
  `naam` VARCHAR(255) NOT NULL,
  `vak_id` INT NOT NULL,
  FOREIGN KEY (`vak_id`) REFERENCES `vak`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------
-- TABEL: gebruiker
-- ---------------------------------------------
CREATE TABLE `gebruiker` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `gebruikersnaam` VARCHAR(255) NOT NULL,
  `wachtwoord` VARCHAR(255) NOT NULL,
  `niveau` VARCHAR(255),
  `leerjaar` VARCHAR(255),
  `school` VARCHAR(255),
  `vak_id` INT,
  FOREIGN KEY (`vak_id`) REFERENCES `vak`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------
-- TABEL: quiz
-- ---------------------------------------------
CREATE TABLE `quiz` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `titel` VARCHAR(255) NOT NULL,
  `onderwerp_id` INT NOT NULL,
  `niveau` VARCHAR(255),
  `leerjaar` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;