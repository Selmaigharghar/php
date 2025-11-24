CREATE TABLE `gebruiker` (
  `id` integer PRIMARY KEY,
  `gebruikersnaam` varchar(255),
  `wachtwoord` varchar(255),
  `niveau` varchar(255),
  `leerjaar` varchar(255),
  `school` varchar(255)
);

CREATE TABLE `vak` (
  `id` integer PRIMARY KEY,
  `naam` varchar(255)
);

CREATE TABLE `onderwerp` (
  `id` integer PRIMARY KEY,
  `naam` varchar(255),
  `vak_id` integer NOT NULL
);

CREATE TABLE `quiz` (
  `id` integer PRIMARY KEY,
  `titel` varchar(255),
  `onderwerp_id` integer NOT NULL,
  `niveau` varchar(255),
  `leerjaar` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `flashcard` (
  `id` integer PRIMARY KEY,
  `vraag` text,
  `antwoord` text,
  `onderwerp_id` integer NOT NULL
);

CREATE TABLE `dictee` (
  `id` integer PRIMARY KEY,
  `titel` varchar(255),
  `onderwerp_id` integer NOT NULL,
  `niveau` varchar(255),
  `leerjaar` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `gebruiker_quiz` (
  `id` integer PRIMARY KEY,
  `gebruiker_id` integer NOT NULL,
  `quiz_id` integer NOT NULL,
  `score` integer,
  `datum_gemaakt` timestamp
);

ALTER TABLE `onderwerp` ADD FOREIGN KEY (`vak_id`) REFERENCES `vak` (`id`);

ALTER TABLE `quiz` ADD FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp` (`id`);

ALTER TABLE `flashcard` ADD FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp` (`id`);

ALTER TABLE `dictee` ADD FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp` (`id`);

ALTER TABLE `gebruiker_quiz` ADD FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`);

ALTER TABLE `gebruiker_quiz` ADD FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`);
