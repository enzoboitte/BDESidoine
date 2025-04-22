DROP DATABASE IF exists `bdbdesidoine`;
CREATE DATABASE IF NOT exists `bdbdesidoine`;

use `bdbdesidoine`;

CREATE TABLE role(
	idRo INT,
	libelle VARCHAR(50),
	PRIMARY KEY(idRo)
) Engine=InnoDB;

CREATE TABLE membre(
	idM INT auto_increment,
	nom VARCHAR(50),
	prenom VARCHAR(50),
	mail VARCHAR(150),
	tel VARCHAR(10),
	image VARCHAR(254),
	idRo INT NOT NULL,
	FOREIGN KEY(idRo) REFERENCES role(idRo),
	PRIMARY KEY(idM)
) Engine=InnoDB;

CREATE TABLE regle(
	idR INT,
	libelle VARCHAR(50),
	action VARCHAR(50),
	PRIMARY KEY(idR)
) Engine=InnoDB;

CREATE TABLE annee(
	idA DATE,
	libelle VARCHAR(50),
	PRIMARY KEY(idA)
) Engine=InnoDB;

CREATE TABLE type_event(
	code varchar(50),
    libelle varchar(150),
    
    primary key(code)
) Engine=InnoDB;

CREATE TABLE event(
	idE INT auto_increment,
	titre VARCHAR(150),
	phrase VARCHAR(250),
	debut DATETIME,
	img VARCHAR(254),
    type varchar(50),
	PRIMARY KEY(idE),
    FOREIGN KEY(type) REFERENCES type_event(code)
) Engine=InnoDB;

CREATE TABLE compte(
	idC INT auto_increment,
	mdp VARCHAR(150),
	tmpkey varchar(300) DEFAULT '',
	idM INT NOT NULL,
	PRIMARY KEY(idC),
	UNIQUE(idM),
	FOREIGN KEY(idM) REFERENCES membre(idM) ON DELETE CASCADE
) Engine=InnoDB;

CREATE TABLE poste(
	idP INT auto_increment,
	dateP DATE,
	titreP VARCHAR(50),
	descriptionP TEXT,
	_imageP TEXT,
	idC INT NOT NULL,
	PRIMARY KEY(idP)
	-- FOREIGN KEY(idC) REFERENCES compte(idC)
) Engine=InnoDB;

CREATE TABLE image(
	idI INT auto_increment,
	idP INT,
	path TEXT,
	PRIMARY KEY(idI, idP),
	FOREIGN KEY(idP) REFERENCES poste(idP)
) Engine=InnoDB;

CREATE TABLE contient(
	idRo INT,
	idR INT,
	PRIMARY KEY(idRo, idR),
	FOREIGN KEY(idRo) REFERENCES role(idRo),
	FOREIGN KEY(idR) REFERENCES regle(idR)
) Engine=InnoDB;

CREATE TABLE nommer(
	idM INT,
	idRo INT,
	idA DATE,
	PRIMARY KEY(idM, idA),
	FOREIGN KEY(idM) REFERENCES membre(idM) ON DELETE CASCADE,
	FOREIGN KEY(idRo) REFERENCES role(idRo),
	FOREIGN KEY(idA) REFERENCES annee(idA)
) Engine=InnoDB;


-- Inserts table role
INSERT INTO role VALUES
(1, 'Président'),
(2, 'Trésorier'),
(3, 'Vice-président'),
(4, 'Secrétaire'),
(5, 'Responsable Communication'),
(6, 'Responsable Événementiel'),
(7, 'Responsable Partenariats'),
(8, 'Community Manager');

-- Inserts table membre
INSERT INTO membre VALUES
(1, 'boitte', 'enzo', 'jean.dupont@mail.com', '3', '', 1),
(2, 'Martin', 'Alice', 'alice.martin@mail.com', '4', '', 2),
(3, 'Durand', 'Louis', 'louis.durand@mail.com', '2', '', 3);

-- Inserts table regle
INSERT INTO regle VALUES
(1, 'Créer un post', 'CREATE_POST'),
(2, 'Supprimer un post', 'DELETE_POST'),
(3, 'Modifier un post', 'UPDATE_POST'),
(4, 'Lire un post', 'READ_POST'),
(9, 'Créer un événement', 'CREATE_EVENT'),
(10, 'Supprimer un événement', 'DELETE_EVENT'),
(11, 'Modifier un événement', 'UPDATE_EVENT'),
(12, 'Lire un événement', 'READ_EVENT'),

(13, 'Créer un compte', 'CREATE_ACCOUNT'),
(14, 'Supprimer un compte', 'DELETE_ACCOUNT'),
(15, 'Modifier un compte', 'UPDATE_ACCOUNT'),
(16, 'Lire un compte', 'READ_ACCOUNT');

-- Inserts table annee
INSERT INTO annee VALUES
('2024-01-01', '2024'),
('2025-01-01', '2025');

-- Inserts table type event
INSERT INTO type_event VALUES
("event", "Événement"),
("reunion", "Réunion"), 
("rdv", "Rendez-vous"),
("tache", "Tâche");

-- Inserts table event
INSERT INTO event VALUES
(1, 'Fête Annuelle', 'Grande soirée étudiante', '2025-05-01 20:00:00', 'fete.jpg', 'event'),
(2, 'Réunion Mensuelle', 'Réunion BDE', '2025-04-15 18:00:00', 'reunion.jpg', 'reunion');

-- Inserts table compte
INSERT INTO compte VALUES
(1, '906b87053567bb4a8a4038832323c5bf4dccf881', '', 1),
(2, 'hashed_password2', '', 2);

-- Inserts table poste
INSERT INTO poste VALUES
(1, '2025-03-27', 'Bienvenue', 'Premier post de bienvenue', 'image_post1.jpg', 1),
(2, '2025-03-28', 'Événement à venir', 'Annonce fête annuelle', 'image_post2.jpg', 2);

-- Inserts table image
INSERT INTO image (idP, path) VALUES
(1, 'Photo_groupe.jpg'),
(1, 'Photo_accueil.jpg');

-- Inserts table contient
INSERT INTO contient VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16);

-- Inserts table nommer
INSERT INTO nommer VALUES
(1, 1, '2024-01-01'),
(2, 2, '2024-01-01'),
(3, 6, '2024-01-01');



-- TABLE ACCOUNT --
delimiter $

DROP PROCEDURE IF EXISTS procLogin$
CREATE PROCEDURE procLogin(p_nom VARCHAR(50), p_pren VARCHAR(50), p_passwd VARCHAR(300))
BEGIN
  declare response varchar(50) default "ERROR";
  declare tmpKey   varchar(300) default "null";
  declare code     int(1) default -1;
    
  declare count_id int default 0;
  declare res_identifier VARCHAR(100) default null;
  declare res_passwd     VARCHAR(300) default null;
  declare res_email      VARCHAR(150) default null;
  
  SET p_passwd := SHA(concat(p_passwd, "bdesid_service"));
  
  SELECT COUNT(`nom`) INTO count_id FROM `membre` WHERE `nom` = p_nom AND `prenom` = p_pren;
  
  if count_id = 1 then
    -- SELECT `name`, `passwd`, `email` INTO res_identifier, res_passwd, res_email FROM `admin` WHERE `name` = p_id AND `passwd` = p_passwd;
    select c.idC, c.mdp, m.mail INTO res_identifier, res_passwd, res_email FROM membre m inner join compte c on c.idM = m.idM WHERE m.`nom` = p_nom AND m.`prenom` = p_pren AND c.mdp = p_passwd;
    -- select p_id, p_passwd, res_identifier, res_passwd, res_email;
    
    if res_passwd = p_passwd then
      SET response := "SUCCEFUL";
      SET code     := 0;
      SET tmpKey   := SHA(concat(res_passwd, NOW(), res_email));
            
      UPDATE `compte` SET `tmpKey` = tmpKey WHERE `idC` = res_identifier AND `mdp` = res_passwd;
    else
      SET response := CONCAT(response, ": incorrect password.");
      SET code     := 2;
    end if;
  else
    SET response := CONCAT(response, ": incorrect identifier.");
    SET code     := 1;
  end if;
  
  SELECT response as 'response', code as 'code', tmpKey as 'tmpKey', p_passwd as 'pw';
END;$
