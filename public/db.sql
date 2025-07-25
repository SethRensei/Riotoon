-- Active: 1752699055629@@127.0.0.1@3306@riotoon
CREATE DATABASE IF NOT EXISTS riotoon;
USE riotoon;

CREATE TABLE IF NOT EXISTS category(
    id INT AUTO_INCREMENT,
    label VARCHAR(50) NOT NULL UNIQUE,
    CONSTRAINT pk_cat PRIMARY KEY(id)
);

CREATE TABLE webtoon(
    id INT AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(120) NOT NULL,
    synopsis TEXT NOT NULL,
    cover VARCHAR(255) NOT NULL,
    status ENUM('ONGOING', 'FINISHED', 'SUSPENDED') DEFAULT 'ONGOING',
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_web PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS chapter(
    id INT AUTO_INCREMENT,
    ch_num INT NOT NULL,
    ch_path VARCHAR(255) NOT NULL,
    webtoon INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_ch PRIMARY KEY(id),
    CONSTRAINT fk_ch_web FOREIGN KEY(webtoon) REFERENCES webtoon(id)
    ON UPDATE RESTRICT
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS web_cat(
    category INT NOT NULL,
    webtoon INT NOT NULL,
    CONSTRAINT fk_cat FOREIGN KEY(category) REFERENCES category(id)
    ON UPDATE RESTRICT
    ON DELETE CASCADE,
    CONSTRAINT f_web FOREIGN KEY(webtoon) REFERENCES webtoon(id)
    ON UPDATE RESTRICT
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users
(
    id INT AUTO_INCREMENT,
    pseudo VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    picture VARCHAR(255) DEFAULT NULL,
    roles LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
    u_token INT NOT NULL,
    token_expire INT(12) NULL,
    is_verify BOOL DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_user PRIMARY KEY(id)
);

-- INSERT DATA
INSERT INTO category(label)
VALUES ('Action'),('Adulte'),('Arts Martiaux'),('Aventure'),('Biographie'),('Combat'),('Comédie'),('Cyberpunk'),('Drame'),('Famille'),('Fantaisie'),('Guerre'),('Historique'),('Horreur'),('Isekai'),('Josei'),('Magie'),('Musique'),('Mystère'),('Politique'),('Post-apocalyptique'),('Psycho'),('Romance'),('Sc-Fi'),('School life'),('Seinen'),('Shojo'),('Shonen'),('Slice of Life'),('Sport'),('Steampunk'),('Surnaturel'),('Thriller'),('Tragédie'),('Webcomic');