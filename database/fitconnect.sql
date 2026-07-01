-- =====================================================
-- Base de données FitConnect
-- Réseau de salles de sport - script de structure + jeu de données de test
-- Issu du MLD validé (Adherent, Abonnement, Seance, Salle, Activite, Equipement)
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `fitconnect` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fitconnect`;

-- --------------------------------------------------------
-- Table `salle`
-- --------------------------------------------------------
CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL AUTO_INCREMENT,
  `nom_salle` varchar(50) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `ville` varchar(50) NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `salle` (`id_salle`, `nom_salle`, `adresse`, `ville`) VALUES
(1, 'FitConnect Casablanca', '123 Boulevard Mohammed V', 'Casablanca'),
(2, 'FitConnect Rabat', '45 Avenue Hassan II', 'Rabat'),
(3, 'FitConnect Marrakech', '78 Rue de la Liberté', 'Marrakech'),
(4, 'FitConnect Tanger', '56 Place du Grand Socco', 'Tanger');

-- --------------------------------------------------------
-- Table `activite`
-- --------------------------------------------------------
CREATE TABLE `activite` (
  `id_activite` int(11) NOT NULL AUTO_INCREMENT,
  `nom_activite` varchar(50) NOT NULL,
  PRIMARY KEY (`id_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activite` (`id_activite`, `nom_activite`) VALUES
(1, 'Musculation'),
(2, 'Cardio'),
(3, 'Yoga'),
(4, 'CrossFit'),
(5, 'Natation'),
(6, 'Boxe');

-- --------------------------------------------------------
-- Table `equipement`
-- --------------------------------------------------------
CREATE TABLE `equipement` (
  `id_equipement` int(11) NOT NULL AUTO_INCREMENT,
  `nom_equipement` varchar(50) NOT NULL,
  PRIMARY KEY (`id_equipement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `equipement` (`id_equipement`, `nom_equipement`) VALUES
(1, 'Haltères'),
(2, 'Tapis de course'),
(3, 'Vélo elliptique'),
(4, 'Banc de musculation'),
(5, 'Corde à sauter'),
(6, 'Sac de frappe'),
(8, 'Rameur');

-- --------------------------------------------------------
-- Table `adherent`
-- Règle de gestion : un adhérent possède une salle d'inscription parmi les 4 salles.
-- --------------------------------------------------------
CREATE TABLE `adherent` (
  `id_adherent` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `id_salle` int(11) NOT NULL,
  PRIMARY KEY (`id_adherent`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_adherent_salle` (`id_salle`),
  CONSTRAINT `fk_adherent_salle` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `adherent` (`id_adherent`, `nom`, `prenom`, `email`, `telephone`, `date_inscription`, `id_salle`) VALUES
(1, 'Benslimane', 'Karim', 'karim.benslimane@email.com', '0612345678', '2026-01-15', 1),
(2, 'Alaoui', 'Fatima', 'fatima.alaoui@email.com', '0623456789', '2026-02-19', 2),
(3, 'El Amrani', 'Youssef', 'youssef.elamrani@email.com', '0634567890', '2026-03-10', 1),
(4, 'Benali', 'Sara', 'sara.benali@email.com', '0645678901', '2026-04-05', 3),
(5, 'Fassi', 'Omar', 'omar.fassi@email.com', '0656789012', '2026-05-12', 4),
(6, 'Idrissi', 'Laila', 'laila.idrissi@email.com', '0667890123', '2026-06-01', 2);

-- --------------------------------------------------------
-- Table `abonnement`
-- Règle de gestion : un adhérent ne détient qu'un seul abonnement actif à la fois.
-- --------------------------------------------------------
CREATE TABLE `abonnement` (
  `id_abonnement` int(11) NOT NULL AUTO_INCREMENT,
  `type_abonnement` varchar(50) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_adherent` int(11) NOT NULL,
  PRIMARY KEY (`id_abonnement`),
  KEY `fk_abonnement_adherent` (`id_adherent`),
  CONSTRAINT `fk_abonnement_adherent` FOREIGN KEY (`id_adherent`) REFERENCES `adherent` (`id_adherent`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `abonnement` (`id_abonnement`, `type_abonnement`, `date_debut`, `date_fin`, `id_adherent`) VALUES
(1, 'annuel', '2026-01-15', '2027-01-15', 1),
(2, 'trimestriel', '2026-02-20', '2026-05-20', 2),
(3, 'mensuel', '2026-03-10', '2026-04-10', 3),
(4, 'annuel', '2026-04-05', '2027-04-05', 4);

-- --------------------------------------------------------
-- Table `seance`
-- Règle de gestion : une séance ne peut être enregistrée que si l'abonnement
-- de l'adhérent est valide à la date du jour. Référence adhérent, salle, activité,
-- durée et, optionnellement, un équipement.
-- --------------------------------------------------------
CREATE TABLE `seance` (
  `id_seance` int(11) NOT NULL AUTO_INCREMENT,
  `date_seance` date NOT NULL,
  `duree` int(11) NOT NULL COMMENT 'Durée en minutes',
  `id_equipement` int(11) DEFAULT NULL,
  `id_activite` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `id_adherent` int(11) NOT NULL,
  PRIMARY KEY (`id_seance`),
  KEY `fk_seance_equipement` (`id_equipement`),
  KEY `fk_seance_activite` (`id_activite`),
  KEY `fk_seance_salle` (`id_salle`),
  KEY `fk_seance_adherent` (`id_adherent`),
  CONSTRAINT `fk_seance_activite` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`id_activite`) ON UPDATE CASCADE,
  CONSTRAINT `fk_seance_adherent` FOREIGN KEY (`id_adherent`) REFERENCES `adherent` (`id_adherent`) ON UPDATE CASCADE,
  CONSTRAINT `fk_seance_equipement` FOREIGN KEY (`id_equipement`) REFERENCES `equipement` (`id_equipement`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_seance_salle` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seance` (`id_seance`, `date_seance`, `duree`, `id_equipement`, `id_activite`, `id_salle`, `id_adherent`) VALUES
(1, '2026-06-20', 60, 1, 1, 1, 1),
(2, '2026-06-21', 45, 2, 2, 1, 1),
(3, '2026-06-20', 90, NULL, 3, 2, 2),
(4, '2026-06-19', 60, 4, 1, 1, 3),
(5, '2026-06-21', 30, 5, 2, 3, 4),
(6, '2026-06-20', 45, 6, 6, 4, 5),
(7, '2026-06-22', 60, NULL, 3, 2, 2),
(8, '2026-06-22', 75, 3, 2, 1, 1);

COMMIT;

-- =====================================================
-- Note : la table `adherent` et `seance` n'utilisent pas l'engine InnoDB
-- par défaut dans le dump phpMyAdmin original pour abonnement/seance ;
-- ce script les force en InnoDB pour garantir le respect des contraintes
-- FOREIGN KEY (intégrité référentielle obligatoire selon les règles de gestion).
-- =====================================================
