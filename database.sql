-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  mar. 12 déc. 2017 à 20:29
-- Version du serveur :  5.6.35
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `wallpaper_hd4k`
--

-- --------------------------------------------------------

--
-- Structure de la table `category_table`
--

CREATE TABLE `category_table` (
  `id` int(11) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `color_table`
--

CREATE TABLE `color_table` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comment_table`
--

CREATE TABLE `comment_table` (
  `id` int(11) NOT NULL,
  `wallpaper_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `device_table`
--

CREATE TABLE `device_table` (
  `id` int(11) NOT NULL,
  `token` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fos_user_table`
--

CREATE TABLE `fos_user_table` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` longtext COLLATE utf8_unicode_ci,
  `image` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `fos_user_table`
--

INSERT INTO `fos_user_table` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `name`, `facebook`, `instagram`, `twitter`, `emailo`, `type`, `token`, `image`) VALUES
(1, 'ADMIN', 'admin', 'ADMIN', 'admin', 1, 'djtfgbufxr4gwk4k0gss4sgs4k48wc4', '$2y$13$djtfgbufxr4gwk4k0gss4ekodAwfJ3IP01OyKvMD.stoxgr6MMa2S', '2017-12-12 19:48:51', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 0, NULL, 'Wallpaper 4K', NULL, NULL, NULL, NULL, 'email', NULL, '');

-- --------------------------------------------------------

--
-- Structure de la table `gallery_table`
--

CREATE TABLE `gallery_table` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medias_gallerys_table`
--

CREATE TABLE `medias_gallerys_table` (
  `gallery_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media_table`
--

CREATE TABLE `media_table` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rate_table`
--

CREATE TABLE `rate_table` (
  `id` int(11) NOT NULL,
  `wallpaper_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `review` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `report_table`
--

CREATE TABLE `report_table` (
  `id` int(11) NOT NULL,
  `wallpaper_id` int(11) NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `section_table`
--

CREATE TABLE `section_table` (
  `id` int(11) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `slide_table`
--

CREATE TABLE `slide_table` (
  `id` int(11) NOT NULL,
  `wallpaper_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_table`
--

CREATE TABLE `support_table` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_followers_table`
--

CREATE TABLE `user_followers_table` (
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `version_table`
--

CREATE TABLE `version_table` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `features` longtext COLLATE utf8_unicode_ci NOT NULL,
  `code` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wallpapers_categories_table`
--

CREATE TABLE `wallpapers_categories_table` (
  `wallpaper_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wallpapers_colors_table`
--

CREATE TABLE `wallpapers_colors_table` (
  `wallpaper_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wallpaper_table`
--

CREATE TABLE `wallpaper_table` (
  `id` int(11) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `downloads` int(11) NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resolution` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `sets` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `review` tinyint(1) NOT NULL,
  `wall` tinyint(1) NOT NULL,
  `comment` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category_table`
--
ALTER TABLE `category_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1E1AC00FEA9FDD75` (`media_id`),
  ADD KEY `IDX_1E1AC00FD823E37A` (`section_id`);

--
-- Index pour la table `color_table`
--
ALTER TABLE `color_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment_table`
--
ALTER TABLE `comment_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5FB317B7488626AA` (`wallpaper_id`),
  ADD KEY `IDX_5FB317B7A76ED395` (`user_id`);

--
-- Index pour la table `device_table`
--
ALTER TABLE `device_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fos_user_table`
--
ALTER TABLE `fos_user_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C3D4D4BD92FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_C3D4D4BDA0D96FBF` (`email_canonical`);

--
-- Index pour la table `gallery_table`
--
ALTER TABLE `gallery_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `medias_gallerys_table`
--
ALTER TABLE `medias_gallerys_table`
  ADD PRIMARY KEY (`gallery_id`,`media_id`),
  ADD KEY `IDX_CC965DCE4E7AF8F` (`gallery_id`),
  ADD KEY `IDX_CC965DCEEA9FDD75` (`media_id`);

--
-- Index pour la table `media_table`
--
ALTER TABLE `media_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rate_table`
--
ALTER TABLE `rate_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_66699665488626AA` (`wallpaper_id`),
  ADD KEY `IDX_66699665A76ED395` (`user_id`);

--
-- Index pour la table `report_table`
--
ALTER TABLE `report_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DC35883F488626AA` (`wallpaper_id`);

--
-- Index pour la table `section_table`
--
ALTER TABLE `section_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_617EE8222B36786B` (`title`),
  ADD KEY `IDX_617EE822EA9FDD75` (`media_id`);

--
-- Index pour la table `slide_table`
--
ALTER TABLE `slide_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_77A059652B36786B` (`title`),
  ADD KEY `IDX_77A05965488626AA` (`wallpaper_id`),
  ADD KEY `IDX_77A0596512469DE2` (`category_id`),
  ADD KEY `IDX_77A05965EA9FDD75` (`media_id`);

--
-- Index pour la table `support_table`
--
ALTER TABLE `support_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_followers_table`
--
ALTER TABLE `user_followers_table`
  ADD PRIMARY KEY (`user_id`,`follower_id`),
  ADD KEY `IDX_F9F8215CA76ED395` (`user_id`),
  ADD KEY `IDX_F9F8215CAC24F853` (`follower_id`);

--
-- Index pour la table `version_table`
--
ALTER TABLE `version_table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wallpapers_categories_table`
--
ALTER TABLE `wallpapers_categories_table`
  ADD PRIMARY KEY (`wallpaper_id`,`category_id`),
  ADD KEY `IDX_59407595488626AA` (`wallpaper_id`),
  ADD KEY `IDX_5940759512469DE2` (`category_id`);

--
-- Index pour la table `wallpapers_colors_table`
--
ALTER TABLE `wallpapers_colors_table`
  ADD PRIMARY KEY (`wallpaper_id`,`color_id`),
  ADD KEY `IDX_94BFE4F5488626AA` (`wallpaper_id`),
  ADD KEY `IDX_94BFE4F57ADA1FB5` (`color_id`);

--
-- Index pour la table `wallpaper_table`
--
ALTER TABLE `wallpaper_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FCC6A9EEEA9FDD75` (`media_id`),
  ADD KEY `IDX_FCC6A9EEA76ED395` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category_table`
--
ALTER TABLE `category_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `color_table`
--
ALTER TABLE `color_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `comment_table`
--
ALTER TABLE `comment_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `device_table`
--
ALTER TABLE `device_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `fos_user_table`
--
ALTER TABLE `fos_user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `gallery_table`
--
ALTER TABLE `gallery_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `media_table`
--
ALTER TABLE `media_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `rate_table`
--
ALTER TABLE `rate_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `report_table`
--
ALTER TABLE `report_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `section_table`
--
ALTER TABLE `section_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `slide_table`
--
ALTER TABLE `slide_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `support_table`
--
ALTER TABLE `support_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `version_table`
--
ALTER TABLE `version_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `wallpaper_table`
--
ALTER TABLE `wallpaper_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category_table`
--
ALTER TABLE `category_table`
  ADD CONSTRAINT `FK_1E1AC00FD823E37A` FOREIGN KEY (`section_id`) REFERENCES `section_table` (`id`),
  ADD CONSTRAINT `FK_1E1AC00FEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media_table` (`id`);

--
-- Contraintes pour la table `comment_table`
--
ALTER TABLE `comment_table`
  ADD CONSTRAINT `FK_5FB317B7488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`),
  ADD CONSTRAINT `FK_5FB317B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_table` (`id`);

--
-- Contraintes pour la table `medias_gallerys_table`
--
ALTER TABLE `medias_gallerys_table`
  ADD CONSTRAINT `FK_CC965DCE4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `gallery_table` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CC965DCEEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media_table` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rate_table`
--
ALTER TABLE `rate_table`
  ADD CONSTRAINT `FK_66699665488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`),
  ADD CONSTRAINT `FK_66699665A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_table` (`id`);

--
-- Contraintes pour la table `report_table`
--
ALTER TABLE `report_table`
  ADD CONSTRAINT `FK_DC35883F488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`);

--
-- Contraintes pour la table `section_table`
--
ALTER TABLE `section_table`
  ADD CONSTRAINT `FK_617EE822EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media_table` (`id`);

--
-- Contraintes pour la table `slide_table`
--
ALTER TABLE `slide_table`
  ADD CONSTRAINT `FK_77A0596512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category_table` (`id`),
  ADD CONSTRAINT `FK_77A05965488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`),
  ADD CONSTRAINT `FK_77A05965EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media_table` (`id`);

--
-- Contraintes pour la table `user_followers_table`
--
ALTER TABLE `user_followers_table`
  ADD CONSTRAINT `FK_F9F8215CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_table` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F9F8215CAC24F853` FOREIGN KEY (`follower_id`) REFERENCES `fos_user_table` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `wallpapers_categories_table`
--
ALTER TABLE `wallpapers_categories_table`
  ADD CONSTRAINT `FK_5940759512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category_table` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_59407595488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `wallpapers_colors_table`
--
ALTER TABLE `wallpapers_colors_table`
  ADD CONSTRAINT `FK_94BFE4F5488626AA` FOREIGN KEY (`wallpaper_id`) REFERENCES `wallpaper_table` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_94BFE4F57ADA1FB5` FOREIGN KEY (`color_id`) REFERENCES `color_table` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `wallpaper_table`
--
ALTER TABLE `wallpaper_table`
  ADD CONSTRAINT `FK_FCC6A9EEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_table` (`id`),
  ADD CONSTRAINT `FK_FCC6A9EEEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media_table` (`id`);
