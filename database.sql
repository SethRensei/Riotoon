CREATE DATABASE IF NOT EXISTS `riotoon`;

USE `riotoon`;

CREATE TABLE IF NOT EXISTS `genre` (
    `id` INT AUTO_INCREMENT,
    `label` VARCHAR(50) NOT NULL UNIQUE,
    CONSTRAINT `pk_genre` PRIMARY KEY (`id`)
)

TRUNCATE TABLE `genre`;

CREATE TABLE IF NOT EXISTS `webtoon` (
    `id` INT AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `author` VARCHAR(150) NOT NULL,
    `synopsis` LONGTEXT NOT NULL,
    `cover` VARCHAR(255) NOT NULL,
    `release_year` YEAR NOT NULL,
    `status` ENUM('En cours', 'Terminé') NOT NULL,
    `modified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `pk_web` PRIMARY KEY (`id`)
)

TRUNCATE TABLE `webtoon`;

CREATE TABLE IF NOT EXISTS `webtoon_genres` (
    `id` INT AUTO_INCREMENT,
    `webtoon` INT NOT NULL,
    `genre` INT NOT NULL,
    CONSTRAINT `pk_web_gen` PRIMARY KEY (`id`),
    CONSTRAINT `fk_web_gen1`
        Foreign Key (`webtoon`) REFERENCES `webtoon` (`id`)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    CONSTRAINT `fk_web_gen2`
        Foreign Key (`genre`) REFERENCES `genre` (`id`)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

CREATE TABLE IF NOT EXISTS chapter(
    `id` INT AUTO_INCREMENT,
    `ch_num` VARCHAR(50) NOT NULL,
    `ch_path` VARCHAR(255) NOT NULL,
    `webtoon` INT NOT NULL,
    `modified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `pk_chapter` PRIMARY KEY(`id`),
    CONSTRAINT `fk_web_chap`
        Foreign Key (`webtoon`) REFERENCES `webtoon` (`id`)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

INSERT INTO `genre` (`label`)
VALUES ('Action'),
    ('Adulte'),
    ('Arts Martiaux'),
    ('Aventure'),
    ('Biographie'),
    ('Combat'),
    ('Comédie'),
    ('Cyberpunk'),
    ('Drame'),
    ('Famille'),
    ('Fantaisie'),
    ('Guerre'),
    ('Historique'),
    ('Horreur'),
    ('Isekai'),
    ('Josei'),
    ('Magie'),
    ('Musique'),
    ('Mystère'),
    ('Politique'),
    ('Post-apocalyptique'),
    ('Psycho'),
    ('Romance'),
    ('Sc-Fi'),
    ('School life'),
    ('Seinen'),
    ('Shojo'),
    ('Shonen'),
    ('Slice of Life'),
    ('Sport'),
    ('Steampunk'),
    ('Surnaturel'),
    ('Thriller'),
    ('Tragédie'),
    ('Webcomic');