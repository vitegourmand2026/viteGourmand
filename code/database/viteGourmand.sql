-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 11 jan. 2026 à 22:26
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `viteGourmand`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergenes`
--

CREATE TABLE `allergenes` (
  `id` int NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `allergenes`
--

INSERT INTO `allergenes` (`id`, `nom`) VALUES
(1, 'Gluten'),
(2, 'Oeuf'),
(3, 'Lactose'),
(4, 'Arachides'),
(5, 'Crustacés'),
(6, 'Moutarde'),
(7, 'Soja');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `commentaire` text NOT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `user_id`, `commentaire`, `statut`) VALUES
(9, 1, 'Le menu reception était excellent. Merci à vous!', 'validé'),
(10, 1, 'Au top ! Mention spéciale pour le menu dinatoire à partager entre amis ;)', 'validé'),
(11, 1, 'Trop bon, nous nous sommes régalés.', 'validé'),
(13, 2, 'Nous avons pris le menu Marrakech, il était vraiment bon , Bravo!', 'validé');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `ordre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `ordre`) VALUES
(1, 'entree', 1),
(2, 'plat', 2),
(3, 'dessert', 3);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `nb_personnes` int NOT NULL,
  `date_livraison` date NOT NULL,
  `heure_livraison` time NOT NULL,
  `sous_total` float NOT NULL,
  `frais_livraison` float NOT NULL,
  `total` float NOT NULL,
  `statut` varchar(50) DEFAULT 'en_attente',
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `adresse_livraison` varchar(255) NOT NULL,
  `code_postal` int NOT NULL,
  `ville` varchar(255) NOT NULL,
  `motif_annulation` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `menu_id`, `nb_personnes`, `date_livraison`, `heure_livraison`, `sous_total`, `frais_livraison`, `total`, `statut`, `date_commande`, `adresse_livraison`, `code_postal`, `ville`, `motif_annulation`) VALUES
(34, 2, 2, 9, '2026-02-19', '18:45:00', 225, 5, 230, 'annulee', '2025-12-19 01:49:36', '23 rue Georges Raules 33000 Bordeaux', 0, '0', 'Fermeture exceptionnelle le 19/02. Cliente contactée par mail.'),
(35, 2, 3, 9, '2026-02-06', '11:12:00', 180, 5, 185, 'annulee', '2025-12-28 22:42:49', '23 rue Georges Raules 33000 Bordeaux', 0, '0', 'bqdljkbnld'),
(36, 2, 5, 8, '2026-02-06', '14:26:00', 224, 5, 229, 'livree', '2025-12-28 23:21:31', '10 rue de la paix', 33000, 'Bordeaux', NULL),
(37, 1, 8, 4, '2026-01-23', '12:00:00', 104, 5, 109, 'terminee', '2026-01-06 14:56:29', '6 rue Lucie Aubrac', 33000, 'Bordeaux', NULL),
(38, 28, 9, 2, '2026-02-14', '14:00:00', 60, 5, 65, 'terminee', '2026-01-06 15:19:53', '4 rue moujeau', 33000, 'Bordeaux', NULL),
(39, 2, 7, 3, '2026-02-27', '15:00:00', 75, 5, 80, 'en_attente', '2026-01-07 13:45:53', '10 rue de la paix', 33000, 'Bordeaux', NULL),
(40, 36, 4, 5, '2026-03-18', '11:30:00', 125, 5, 130, 'terminee', '2026-01-09 23:29:23', '35, Rue de Bibonne', 33370, 'Tresses', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `nom`, `prenom`, `email`, `message`) VALUES
(3, 'Laurent', 'Martin', 'martinlaurent@gmail.com', 'Bonjour j\'ai un problème de connexion'),
(5, 'bernard', 'paul', 'herty@hotmail.fr', 'bonjour peut on payer par carte bancaire ?');

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

CREATE TABLE `horaires` (
  `id` int NOT NULL,
  `texte` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `horaires`
--

INSERT INTO `horaires` (`id`, `texte`) VALUES
(1, 'Nous sommes ouverts du mardi 9H00 au Dimanche 23H00.');

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int NOT NULL,
  `theme` varchar(255) NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `personne` int NOT NULL,
  `prix` int NOT NULL,
  `regime` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `menus`
--

INSERT INTO `menus` (`menu_id`, `theme`, `titre`, `personne`, `prix`, `regime`, `description`, `image`) VALUES
(1, 'Gastronomie', 'Le Réception', 2, 36, 'Classique', 'Un menu parfait pour accompagner vos réceptions, vos événements.', 'images/menus/reception.png'),
(2, 'Bistronomie', 'Le Bistro', 2, 25, 'Classique', 'Un menu simple à déguster entre amis ou en famille.', 'images/menus/bistro.png'),
(3, 'Bistronomie', 'Le Dinatoire', 6, 20, 'Classique', 'Un apéritif dinatoire complet et varié pour toutes les envies.', 'images/menus/dînatoire.png'),
(4, 'Cuisine du monde', 'L\'Athènes', 2, 25, 'Classique', 'Voyagez aux confins de la cuisine grecque.', 'images/menus/athenes.png'),
(5, 'Cuisine du monde', 'Le Marrakech', 4, 28, 'Sans porc', 'Un menu traditionnel à partager, qui respecte le régime hallal.', 'images/menus/marrakech.png'),
(6, 'Cuisine du monde', 'Le Barcelone', 2, 25, 'Classique', 'Découvrez la cuisine des tapaserias catalanes.', 'images/menus/barcelona.png'),
(7, 'Cuisine du monde', 'Le Roma', 2, 25, 'Végétarien', 'Tous les plats de ce menu mènent à Rome.', 'images/menus/roma.png'),
(8, 'Végétarien', 'Le Champêtre', 2, 26, 'Végétarien', 'Un menu élaboré avec des plats inédits dans la cuisine végétarienne.', 'images/menus/champêtre.png'),
(9, 'Événements', 'Le St Valentin', 2, 30, 'Classique', 'Le menu des amoureux à partager sans modération.', 'images/menus/valentin.png');

-- --------------------------------------------------------

--
-- Structure de la table `menu_allergenes`
--

CREATE TABLE `menu_allergenes` (
  `id` int NOT NULL,
  `menu_id` int NOT NULL,
  `allergenes_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `menu_allergenes`
--

INSERT INTO `menu_allergenes` (`id`, `menu_id`, `allergenes_id`) VALUES
(8, 4, 1),
(9, 4, 3),
(10, 5, 4),
(11, 5, 3),
(12, 5, 1),
(13, 6, 3),
(14, 6, 1),
(17, 8, 1),
(18, 8, 3),
(19, 8, 4),
(28, 9, 1),
(29, 9, 3),
(30, 9, 2),
(31, 3, 4),
(32, 3, 3),
(33, 7, 2),
(34, 7, 1),
(39, 1, 5),
(40, 1, 1),
(41, 1, 3),
(42, 2, 4),
(43, 2, 1),
(44, 2, 2),
(46, 8, 6);

-- --------------------------------------------------------

--
-- Structure de la table `plates`
--

CREATE TABLE `plates` (
  `plate_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `categorie_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `plates`
--

INSERT INTO `plates` (`plate_id`, `name`, `categorie_id`, `menu_id`, `image`) VALUES
(1, 'Sashimi de saumon, \r\nbetteraves et fleurs de sureau', 1, 1, 'images/plates/entree gastro.png'),
(2, 'Fondant de boeuf \r\net son écrasé de panais', 2, 1, 'images/plates/plat gastro.png'),
(3, 'Entremet pistache \r\net sa crème vanille', 3, 1, 'images/plates/dessert gastro.png'),
(4, 'La véritable salade niçoise : thon, oeuf et olives noires ', 1, 2, 'images/plates/entree bistro.png'),
(5, 'Sauté de veau et ses pommes de terre grenailles', 2, 2, 'images/plates/plat bistro.png'),
(6, 'Soupe de fraise et son moelleux au citron', 3, 2, 'images/plates/dessert bistro.png'),
(7, 'Toast avocat crevettes et ses tomates cerises ', 1, 3, 'images/plates/entree dinatoire.png'),
(8, 'Plateau de charcuterie, fromages et légumes croquants.', 2, 3, 'images/plates/plat dînatoire.png'),
(9, 'Assortiment de fruits exotiques et tropicaux.', 3, 3, 'images/plates/dessert dinatoire.png'),
(10, 'Salade grecque et ses tomates anciennes, feta et olives noires                                        ', 1, 4, 'images/plates/entree athenes.png'),
(11, 'Moussaka aubergines et boeuf, sauce béchamel ', 2, 4, 'images/plates/plat athenes.png'),
(12, 'Mousse de cerises sur moelleux aux amandes', 3, 4, 'images/plates/dessert athenes.png'),
(13, 'Salade marocaine tomates, poivrons, menthe et pois chiches', 1, 5, 'images/plates/entree marrakech.png'),
(14, 'Tajine de poulet aux olives vertes et citrons confits', 2, 5, 'images/plates/plat marrakech.png'),
(15, 'Assortiment de pâtisseries orientales faites par nos soins', 3, 5, 'images/plates/dessert marrakech.png'),
(16, 'Assortiment de tapas catalans, typiques de Barcelone', 1, 6, 'images/plates/entree barcelona.png'),
(17, 'Empanadas au poulet, thon et boeuf et leur sauce chimichuri', 2, 6, 'images/plates/plat barcelona.png'),
(18, 'Tujon maison aux amandes et noix torrifiées', 3, 6, 'images/plates/dessert barcelona.png'),
(19, 'Mozzarella di buffala et ses tomates cerises.', 1, 7, 'images/plates/entree roma.png'),
(20, 'Risotto d\'asperges blanches au safran et parmesan AOC.', 2, 7, 'images/plates/plat roma.png'),
(21, 'Véritable tiramisu au mascarpone AOC et son café romain.', 3, 7, 'images/plates/dessert roma.png'),
(22, 'Salade de roquette et ses légumes croustillants', 1, 8, 'images/plates/entree vegan.png'),
(23, 'Croustillants de légumes et falafels assortis de légumes de saison', 2, 8, 'images/plates/plat vegan.png'),
(24, 'Entremet pistache \r\net sa crème vanille', 3, 8, 'images/plates/dessert vegan.png'),
(25, 'Asperges rôties au bacon, sauce hollandaise', 1, 9, 'images/plates/entree valentin.png'),
(26, 'Filet mignon et ses légumes rôtis en sauce forestière', 2, 9, 'images/plates/plat valentin.png'),
(27, 'Coeur moelleux citron yuzu et fraise des bois', 3, 9, 'images/plates/dessert valentin.png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('user','employee','admin') DEFAULT 'user',
  `telephone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `reset_code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `role`, `telephone`, `adresse`, `code_postal`, `ville`, `mot_de_passe`, `reset_code`) VALUES
(1, 'Dubois', 'Laurent', 'laurentDubois1437868@hotmail.fr', 'user', '0755443323', '6 rue Lucie Aubrac', '33000 ', 'Bordeaux', '$2y$10$KiCD1PheEU6j/qYrhTXR2.31ZtSYJTQBwEV72OXFvTKc/KQ.f0ESu', NULL),
(2, 'Belkari', 'Amina', 'aminaBelkari23567@hotmail.fr', 'user', '0677889921', '10 rue de la paix', '33000', 'Bordeaux', '$2y$10$6L.1lBI6AiQ1koI7SY/Ma.BtxHNx3GDNO8hAUdb82SP/Woodem60S', NULL),
(5, 'Rezoul', 'Maxime', 'maximeRezoul345346@hotmail.fr', 'employee', '06 12 35 47 65', '12 rue pasteur', '33000', 'Bordeaux', '$2y$10$mDq5wvx2QC7M58uxdd5s3.ph6aGeJ4GZXiyFWp.cn8/blhsE20vpW', NULL),
(8, 'Dussol', 'José', 'joseDussol6576875453@hotmail.fr', 'admin', '06 26 22 22 61', '132, rue des violettes ', '33000', 'Bordeaux', '$2y$10$4Yo86tE7NN7wiNfHpD48Fu0rjO46bFPvgnZ5PeaoablJne477NCjK', NULL),
(28, 'jean', 'Bernard', '', 'user', '0655776655', '4 rue moujeau', '33000', 'Bordeaux', '$2y$10$JmFItlytOh76ryMsY0Ao.eZExj01Zg3rQFBWZS0a1DtfD1Y5dpaOG', NULL),
(33, 'Delors', 'Sylvain', 'sdelors@hotmail.fr', 'employee', '0677558899', '13 rue des papillons 33000 Bordeaux', NULL, NULL, '$2y$10$VT/RXmp4e0OgeAyA4O1rCeW/4QkRM4HX9KV0vn.75h0bWyaCxZWBa', NULL),
(34, 'Rancin', 'julie', 'jrancin@gmail.com', 'employee', '0677008811', '45, boulevard du jeu de paume 33000 Bordeaux', NULL, NULL, '$2y$10$StG8PXYjbINLcA1v4l9qteIhW/PJbFp10ZQl1y0EVNiNhyd/uVgUm', NULL),
(35, 'Salari', 'Ahmed', 'ahmedsalari@hotmail.fr', 'user', '0634674634', '13 Rue de Canteret', '33290', 'Blanquefort', '$2y$10$tmX.1sNyzN6TzYJG716TQO0fH26lpEKiii.j2lLklg7T9C6bCuNPi', NULL),
(36, 'Belfort', 'Martin', 'martinbelfort@proton.me', 'user', '0677121443', '35, Rue de Bibonne', '33370', 'Tresses', '$2y$10$izH9RKWerlf4Zi8dBIyPNe4o8g6O/1LMkWijF63uZ3p7hkYkec.Fy', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergenes`
--
ALTER TABLE `allergenes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `horaires`
--
ALTER TABLE `horaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Index pour la table `menu_allergenes`
--
ALTER TABLE `menu_allergenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `allergenes_id` (`allergenes_id`);

--
-- Index pour la table `plates`
--
ALTER TABLE `plates`
  ADD PRIMARY KEY (`plate_id`),
  ADD KEY `idx_categories` (`categorie_id`),
  ADD KEY `idx_menu` (`menu_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergenes`
--
ALTER TABLE `allergenes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `horaires`
--
ALTER TABLE `horaires`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `menu_allergenes`
--
ALTER TABLE `menu_allergenes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `plates`
--
ALTER TABLE `plates`
  MODIFY `plate_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`);

--
-- Contraintes pour la table `menu_allergenes`
--
ALTER TABLE `menu_allergenes`
  ADD CONSTRAINT `fk_menu_allergenes_allergene` FOREIGN KEY (`allergenes_id`) REFERENCES `allergenes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_menu_allergenes_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `plates`
--
ALTER TABLE `plates`
  ADD CONSTRAINT `fk_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
