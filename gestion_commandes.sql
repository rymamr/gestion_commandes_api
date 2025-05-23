-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 23 mai 2025 à 14:35
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_commandes`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `codeClient` varchar(50) NOT NULL,
  `nomClient` varchar(100) NOT NULL,
  `prenomClient` varchar(100) NOT NULL,
  `dateNaissance` date DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`codeClient`, `nomClient`, `prenomClient`, `dateNaissance`, `email`, `telephone`) VALUES
('C001', 'Dupont', 'Alice', '1990-05-12', 'alice.dupont@example.com', '0612345678'),
('C002', 'Martin', 'Bob', '1985-09-23', 'bob.martin@example.com', '0698765432'),
('C003', 'Durand', 'Charlie', '1978-02-14', 'charlie.durand@example.com', '0678123456');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCommande` int(11) NOT NULL,
  `codeClient` varchar(50) NOT NULL,
  `dateCommande` date NOT NULL,
  `totalHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `totalTTC` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TVA` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idCommande`, `codeClient`, `dateCommande`, `totalHT`, `totalTTC`, `TVA`) VALUES
(1, 'C001', '2025-03-08', 999.00, 1080.00, 180.00),
(2, 'C002', '2025-03-08', 1200.00, 1440.00, 240.00);

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `idCommande` int(11) NOT NULL,
  `codeProduit` varchar(50) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prixUnitaireHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TVA` decimal(10,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`idCommande`, `codeProduit`, `quantite`, `prixUnitaireHT`, `TVA`) VALUES
(1, 'P001', 2, 900.00, 20.00),
(1, 'P002', 2, 1200.00, 20.00),
(2, 'P003', 4, 250.00, 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `codeProduit` varchar(50) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `suite` varchar(100) DEFAULT NULL,
  `modele` varchar(100) DEFAULT NULL,
  `unite` varchar(50) DEFAULT NULL,
  `prixAchatHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `totalHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TVA` decimal(4,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`codeProduit`, `designation`, `suite`, `modele`, `unite`, `prixAchatHT`, `totalHT`, `TVA`) VALUES
('P001', 'Ordinateur Portable', 'EliteBook', '840 G5', 'Pièce', 600.00, 900.00, 20.00),
('P002', 'Smartphone', 'Galaxy', 'S23 Ultra', 'Pièce', 800.00, 1200.00, 20.00),
('P003', 'Imprimante', 'LaserJet', 'Pro MFP', 'Pièce', 150.00, 250.00, 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `proforma`
--

CREATE TABLE `proforma` (
  `idProforma` int(11) NOT NULL,
  `codeClient` varchar(50) NOT NULL,
  `dateProforma` date NOT NULL,
  `totalHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `totalTTC` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TVA` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `proforma`
--

INSERT INTO `proforma` (`idProforma`, `codeClient`, `dateProforma`, `totalHT`, `totalTTC`, `TVA`) VALUES
(1, 'C003', '2025-03-10', 250.00, 300.00, 50.00),
(2, 'C002', '2025-03-03', 900.00, 1080.00, 180.00),
(4, 'C002', '2025-03-27', 12600.00, 14994.00, 2394.00);

-- --------------------------------------------------------

--
-- Structure de la table `proforma_produit`
--

CREATE TABLE `proforma_produit` (
  `idProforma` int(11) NOT NULL,
  `codeProduit` varchar(50) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prixUnitaireHT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TVA` decimal(4,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `proforma_produit`
--

INSERT INTO `proforma_produit` (`idProforma`, `codeProduit`, `quantite`, `prixUnitaireHT`, `TVA`) VALUES
(1, 'P003', 2, 250.00, 20.00),
(2, 'P001', 1, 900.00, 20.00),
(4, 'P001', 6, 900.00, 19.00),
(4, 'P002', 6, 1200.00, 19.00);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(2, 'rymaraima@gmail.com', '$2y$10$VSAiVB1J.x6VH3zV070dh.ZK/rYdtBsJpjs0tygt8n1nVFRodprpy'),
(3, 'testo@gmail.com', '$2y$10$Ju0U5HBVt6fD783q0DmT/ue/gamDPtu7jDZQYZD1EFClIZmaApszq');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`codeClient`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `fk_commande_client` (`codeClient`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`idCommande`,`codeProduit`),
  ADD KEY `fk_cp_produit` (`codeProduit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`codeProduit`);

--
-- Index pour la table `proforma`
--
ALTER TABLE `proforma`
  ADD PRIMARY KEY (`idProforma`),
  ADD KEY `fk_proforma_client` (`codeClient`);

--
-- Index pour la table `proforma_produit`
--
ALTER TABLE `proforma_produit`
  ADD PRIMARY KEY (`idProforma`,`codeProduit`),
  ADD KEY `fk_pp_produit` (`codeProduit`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `proforma`
--
ALTER TABLE `proforma`
  MODIFY `idProforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_client` FOREIGN KEY (`codeClient`) REFERENCES `client` (`codeClient`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `fk_cp_commande` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cp_produit` FOREIGN KEY (`codeProduit`) REFERENCES `produit` (`codeProduit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `proforma`
--
ALTER TABLE `proforma`
  ADD CONSTRAINT `fk_proforma_client` FOREIGN KEY (`codeClient`) REFERENCES `client` (`codeClient`) ON DELETE CASCADE;

--
-- Contraintes pour la table `proforma_produit`
--
ALTER TABLE `proforma_produit`
  ADD CONSTRAINT `fk_pp_produit` FOREIGN KEY (`codeProduit`) REFERENCES `produit` (`codeProduit`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pp_proforma` FOREIGN KEY (`idProforma`) REFERENCES `proforma` (`idProforma`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
