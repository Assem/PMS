-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Client: localhost:3306
-- Généré le: Ven 28 Octobre 2016 à 12:41
-- Version du serveur: 5.5.52-cll-lve
-- Version de PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `yessir15_pms`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `value` int(2) DEFAULT NULL,
  `order` int(3) NOT NULL,
  `id_question` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_answers_question_idx` (`id_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Structure de la table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `ai` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`ai`),
  UNIQUE KEY `ci_sessions_id_ip` (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1635 ;

--
-- Contenu de la table `ci_sessions`
--

INSERT INTO `ci_sessions` (`ai`, `id`, `ip_address`, `timestamp`, `data`) VALUES
(1607, 'e9e29e9ce4d982fd52c18d9cfb287164aa260b34', '197.14.11.154', 1477557550, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373535373435313b617574685f6964656e746966696572737c733a3334343a223831333239343934393665616630323336343331363762333233336561666132363331386530626265643064383964316563363462323236653863383439353462366437613764613338356239613566656430363866363135363733393537663565376562386333646264366239373339326133346631326236626538343333503148303768376c6d4d48735069796a714c756b3732357746523449644663664a674d546d73573731726f5755442b2b493268547670796271454171475776714e5561702f644e59365851534b6b73424a2f47545755615645506c494f6672547a58534f444650383867385a3850777655534942525867744d684d7955627434694f6b5974555634474f416f397949777a6b4849557133654d694838752b68786d5a414346596d53346a614c48726f65427071696b386e485049314e53304a6764716e6e4a72442b39412f374c7063314a2f517731413d3d223b),
(1626, '8ab9e3ede2b66dd3af84bb8cb175755bfe9d31e6', '105.96.8.109', 1477563837, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536333830303b),
(1628, '4323220cbefed09dc9daa1a91046a4cdb30d179f', '105.96.8.109', 1477575083, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373537353032313b617574685f6964656e746966696572737c733a3334343a2266643832623861363835306162663666323532633134343031306335353330666562383734626662333938383361386161396236303066393834326637353835336430383865326332373339316566333966333361363231663461383336636665303439613263303336356439393864343134656536373130373632643435326e6f38746f647365436b396571453354346a4a2f6279766932674f6f4a5079375a6f38785a78706a6c70306d4d3676664c395a3068526d38335046366b4e6566516f4c3071367a4d36376c35576d325144654b784b374b79434763534a736f496b53416d577875597334456d6e6c7161664e726d5235694e6b38464a736c53784b4e454b442b7a7a43614a474e79584c584f6b536c744f5578444e4b6f5548347557665a7a70566d2f614c4d4d795a715a5a6e713570744468334849394f6d6d7072554a6769485949456c6e616e48793563563054413d3d223b737563636573737c733a33383a2246696368652072c3a9706f6e64616e74206372c3a9c3a96520617665632073756363c3a87321223b5f5f63695f766172737c613a313a7b733a373a2273756363657373223b733a333a226f6c64223b7d),
(1630, 'ba0f5818064d3642e18a4eb60b7f1ba3c4b9e27e', '105.96.8.109', 1477575670, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373537353532363b),
(1632, '61ff91d43af5d83b8f9a96891379e8efd3a7ea8d', '41.228.250.145', 1477647300, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373634373236393b617574685f6964656e746966696572737c733a3334343a22343438303565356565623865646163343736383161646336613262306439666562343430653962393534643433666262383035376437613963663865653336626634663633346435613763633066383333306664626536383066336263633032363064313433653730323035636532386165656662353939333365373863663255753645614a6c32495062516f707844645756653976715a4d6d617054533853396577316b78455039497536774a4957714751396d4b714e697754444b71707075756a6e73556f6b314a714c57397033493349702f5348784d5575416f323243664765524769517661664e663235572b506e7a4e6c6d2f5a75696734336f34305350692f5530615349476c30796361737179486b4733323279507376436442387037454f77507a6a50364967544a414a512b7330496e4c38766e6a715934657954502b6e536d6830613955535273514b5056313854413d3d223b),
(1633, 'd9a88343ed42ba3c63199c2f884a8b07b439a3ec', '41.228.241.98', 1477650933, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373635303633343b617574685f6964656e746966696572737c733a3334343a22343438303565356565623865646163343736383161646336613262306439666562343430653962393534643433666262383035376437613963663865653336626634663633346435613763633066383333306664626536383066336263633032363064313433653730323035636532386165656662353939333365373863663255753645614a6c32495062516f707844645756653976715a4d6d617054533853396577316b78455039497536774a4957714751396d4b714e697754444b71707075756a6e73556f6b314a714c57397033493349702f5348784d5575416f323243664765524769517661664e663235572b506e7a4e6c6d2f5a75696734336f34305350692f5530615349476c30796361737179486b4733323279507376436442387037454f77507a6a50364967544a414a512b7330496e4c38766e6a715934657954502b6e536d6830613955535273514b5056313854413d3d223b),
(1634, 'bf7ca9a154be4f2c084aadf75e9075a088d87e5a', '41.228.241.98', 1477650957, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373635303934363b617574685f6964656e746966696572737c733a3334343a22343438303565356565623865646163343736383161646336613262306439666562343430653962393534643433666262383035376437613963663865653336626634663633346435613763633066383333306664626536383066336263633032363064313433653730323035636532386165656662353939333365373863663255753645614a6c32495062516f707844645756653976715a4d6d617054533853396577316b78455039497536774a4957714751396d4b714e697754444b71707075756a6e73556f6b314a714c57397033493349702f5348784d5575416f323243664765524769517661664e663235572b506e7a4e6c6d2f5a75696734336f34305350692f5530615349476c30796361737179486b4733323279507376436442387037454f77507a6a50364967544a414a512b7330496e4c38766e6a715934657954502b6e536d6830613955535273514b5056313854413d3d223b),
(1615, '63730f837c5e8e2d371e811db6790849033319e0', '105.96.8.109', 1477560760, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536303437313b617574685f6964656e746966696572737c733a3334343a22386631333131653262353534303366313830623934633536393130363033353935653463613433656338383365393039353961333562303736366365333336313730303730306666656232613936623130303437643266303138363533336165656532313463313537336438613030346237396462636138323431663739313333674a4c766a5243704e6a472f4f684e516947777741496336375455616b59324c63464f4675356273756a51595a4b63576672625174466a4a48776843725a48554c3677675a75366d312b63416d54505539527a7048572b4846754a57696d2b59455768527a573062356e47514a7a2f416b5a476a4c6a3753366b724679554a675666624f4f544c75734751526c3769593242707a4163742f4465716748664c327372704d3965696e5075684d4e757056692b4d7a534a566b62382f565452466e64354334747479434e584f3550366a6e366f704f413d3d223b),
(1602, '117a872e780d75bfb22edcae2be64fbb1f9f8031', '105.96.8.109', 1477557097, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373535363830313b617574685f6964656e746966696572737c733a3334343a22626161303130373435623234356439663933343661613865383036306537653638633063393062653834623963666433636233333830323231353262353564633630346433626466646265306365353664663837313037326437363434613463386162396530316364663464383663376331643963343566336530653366303941537165685a51686a47795a767942594b4170546f6148543575413555776e50755139436e355357505a3830586b5234756f483371564c626c386d59555832324f335835357a49667838497665627370592b3344726b465a587163625855537844634c7a6a686d68374236662b397235344a55586a426b674c456b5a764450706e306873447370336e464a384b775a723039497945554d4978466755735879684164304d50314b5a556c527939444451466e697a667a7a47744e705a434143637863644a4151384c534e63532b514e462b36597949413d3d223b),
(1601, 'f04557349c2b3daa4e9ff99d2112db21d9fc2bb8', '105.96.8.109', 1477556787, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373535363738373b),
(1604, '6ebabaf9c8bbb983593e82f1a9c78a4368e1ad4f', '197.14.11.154', 1477557383, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373535373130373b617574685f6964656e746966696572737c733a3334343a223831333239343934393665616630323336343331363762333233336561666132363331386530626265643064383964316563363462323236653863383439353462366437613764613338356239613566656430363866363135363733393537663565376562386333646264366239373339326133346631326236626538343333503148303768376c6d4d48735069796a714c756b3732357746523449644663664a674d546d73573731726f5755442b2b493268547670796271454171475776714e5561702f644e59365851534b6b73424a2f47545755615645506c494f6672547a58534f444650383867385a3850777655534942525867744d684d7955627434694f6b5974555634474f416f397949777a6b4849557133654d694838752b68786d5a414346596d53346a614c48726f65427071696b386e485049314e53304a6764716e6e4a72442b39412f374c7063314a2f517731413d3d223b),
(1613, '1838c05dce9e6b20c299a9e6d2476e1d90ed5832', '105.96.8.109', 1477558497, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373535383431373b),
(1616, '14d75e580a4ae2d5decca30270b192e891282edc', '197.14.11.154', 1477560801, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536303737373b617574685f6964656e746966696572737c733a3334343a223831333239343934393665616630323336343331363762333233336561666132363331386530626265643064383964316563363462323236653863383439353462366437613764613338356239613566656430363866363135363733393537663565376562386333646264366239373339326133346631326236626538343333503148303768376c6d4d48735069796a714c756b3732357746523449644663664a674d546d73573731726f5755442b2b493268547670796271454171475776714e5561702f644e59365851534b6b73424a2f47545755615645506c494f6672547a58534f444650383867385a3850777655534942525867744d684d7955627434694f6b5974555634474f416f397949777a6b4849557133654d694838752b68786d5a414346596d53346a614c48726f65427071696b386e485049314e53304a6764716e6e4a72442b39412f374c7063314a2f517731413d3d223b),
(1617, 'c3048aa14a6495b74324dbc5cc1420b0c4797002', '197.14.11.154', 1477561167, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536313136373b617574685f6964656e746966696572737c733a3334343a223831333239343934393665616630323336343331363762333233336561666132363331386530626265643064383964316563363462323236653863383439353462366437613764613338356239613566656430363866363135363733393537663565376562386333646264366239373339326133346631326236626538343333503148303768376c6d4d48735069796a714c756b3732357746523449644663664a674d546d73573731726f5755442b2b493268547670796271454171475776714e5561702f644e59365851534b6b73424a2f47545755615645506c494f6672547a58534f444650383867385a3850777655534942525867744d684d7955627434694f6b5974555634474f416f397949777a6b4849557133654d694838752b68786d5a414346596d53346a614c48726f65427071696b386e485049314e53304a6764716e6e4a72442b39412f374c7063314a2f517731413d3d223b6572726f727c733a32373a224c61206d69736520c3a0206a6f7572206120c3a963686f75c3a921223b5f5f63695f766172737c613a313a7b733a353a226572726f72223b733a333a226e6577223b7d),
(1621, '304d9547792ce71f5a575df52622d73804a9bfbf', '105.96.8.109', 1477561705, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536313531333b617574685f6964656e746966696572737c733a3334343a2263636664666262383739636636313037613439303131626566396430373432336164343964313466323564353661376639353331336132363630643937353632633635376339326637326666313433616230386234306439346636373338346665346634303265633130393332363964336635336262343934633532316435654f546974627435346b3134506e714c4a677374595468756331694d4539523433485059736e786f454a6f664a6b3753526247757747776b785273733270654254524d50575241623573704e734e4e4b755361593474453479575945415a733167593838724e5173666b412f3567565135315a724a70344e46516f7346736e7878385253764b51665359304a2b624a6858596c4e6c7754535932484e6b4f3173457249616c30755854303331735952647a674b394c676275583937696a6e4c5151593769344a476c4c512f61326751367644742f7468413d3d223b),
(1625, '4299dc054c76ff4e3de5899e6b79b6e1c0d0f5e7', '105.96.8.109', 1477562128, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536323130363b),
(1614, '633e01a5a26589a7cf2fa8672d0a062b95756e4b', '197.14.11.154', 1477560488, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437373536303435333b617574685f6964656e746966696572737c733a3334343a223831333239343934393665616630323336343331363762333233336561666132363331386530626265643064383964316563363462323236653863383439353462366437613764613338356239613566656430363866363135363733393537663565376562386333646264366239373339326133346631326236626538343333503148303768376c6d4d48735069796a714c756b3732357746523449644663664a674d546d73573731726f5755442b2b493268547670796271454171475776714e5561702f644e59365851534b6b73424a2f47545755615645506c494f6672547a58534f444650383867385a3850777655534942525867744d684d7955627434694f6b5974555634474f416f397949777a6b4849557133654d694838752b68786d5a414346596d53346a614c48726f65427071696b386e485049314e53304a6764716e6e4a72442b39412f374c7063314a2f517731413d3d223b737563636573737c733a33383a2246696368652072c3a9706f6e64616e74206372c3a9c3a96520617665632073756363c3a87321223b5f5f63695f766172737c613a313a7b733a373a2273756363657373223b733a333a226f6c64223b7d);

-- --------------------------------------------------------

--
-- Structure de la table `denied_access`
--

CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `geolocations`
--

CREATE TABLE IF NOT EXISTS `geolocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_sheet` int(11) DEFAULT NULL,
  `error` varchar(200) DEFAULT NULL,
  `latitude` varchar(30) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_geolocations_agent_idx` (`id_user`),
  KEY `fk_geolocations_sheet_idx` (`id_sheet`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `geolocations`
--

INSERT INTO `geolocations` (`id`, `id_user`, `id_sheet`, `error`, `latitude`, `longitude`, `creation_date`) VALUES
(9, 3848026790, 31, 'User denied the request for Geolocation.', NULL, NULL, '2016-10-27 08:38:33');

-- --------------------------------------------------------

--
-- Structure de la table `ips_on_hold`
--

CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `login_errors`
--

CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `login_errors`
--

INSERT INTO `login_errors` (`ai`, `username_or_email`, `IP_address`, `time`) VALUES
(8, 'USER-010', '105.101.80.84', '2016-10-26 17:39:37');

-- --------------------------------------------------------

--
-- Structure de la table `lov`
--

CREATE TABLE IF NOT EXISTS `lov` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  `id_parent` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lov_parent_idx` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Contenu de la table `lov`
--

INSERT INTO `lov` (`id`, `group`, `value`, `id_parent`) VALUES
(4, 'marital_status', 'Marié', NULL),
(5, 'marital_status', 'Célébataire', NULL),
(6, 'marital_status', 'Divorcé', NULL),
(7, 'educational_level', 'Aucun', NULL),
(8, 'educational_level', 'Primaire', NULL),
(9, 'educational_level', 'Secondaire', NULL),
(10, 'educational_level', 'Universitaire', NULL),
(11, 'educational_level', 'Troisième cycle', NULL),
(12, 'professional_status', 'Chômage', NULL),
(13, 'professional_status', 'CDI', NULL),
(14, 'professional_status', 'CDD', NULL),
(15, 'company_type', 'Etatique', NULL),
(16, 'company_type', 'Privée', NULL),
(17, 'company_type', 'Semi-étatique', NULL),
(18, 'company_type', 'Propriétaire', NULL),
(20, 'country', 'Tunisie', NULL),
(21, 'country', 'Algérie', NULL),
(48, 'town', 'Alger', 21),
(49, 'town', 'Oran', 21),
(50, 'town', 'Sétif', 21);

-- --------------------------------------------------------

--
-- Structure de la table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(80) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `code` varchar(30) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `actif` tinyint(4) NOT NULL DEFAULT '1',
  `max_surveys_number` int(11) DEFAULT NULL,
  `customer` varchar(120) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_polls_1_idx` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `polls`
--

INSERT INTO `polls` (`id`, `label`, `description`, `code`, `start_date`, `end_date`, `actif`, `max_surveys_number`, `customer`, `creation_date`, `update_date`, `created_by`) VALUES
(12, 'Téléphonie mobile', 'Étude pour préparer le dossier clear-coat ', 'SON-000007', '2016-10-25', '2016-11-30', 1, 100, 'NY ', '2016-10-26 17:19:35', '2016-10-27 04:54:49', 3491954500);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `required` tinyint(4) NOT NULL DEFAULT '1',
  `id_poll` int(10) unsigned NOT NULL,
  `free_answer_type` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_questions_poll_idx` (`id_poll`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Contenu de la table `questions`
--

INSERT INTO `questions` (`id`, `description`, `order`, `type`, `required`, `id_poll`, `free_answer_type`) VALUES
(35, 'Le nombre de téléphones portables opérationnels en Algérie?', 1, 'free_text', 1, 12, 'numeric'),
(36, 'Le classement des marques par part de marché?\r\n1-Samsung / 2-Apple / 3-Huawei / 4-Sony / 5-Condor / 6-Wiko / 7-LG / 8-Oppo / 9-Autre', 3, 'free_text', 1, 12, 'alphanumeric'),
(37, 'Le pourcentage par paliers des prix?\r\n1-Inférieur à 9 999DA / 2-Entre 10 000 et 29 999DA / 3-Entre 30 000 et 49 999DA / 4-Entre 50 000 et 69 999DA / 5-70 000 et plus', 4, 'free_text', 1, 12, 'alphanumeric'),
(38, 'Les accessoires de téléphone portable les plus achetés?', 5, 'free_text', 1, 12, 'alphanumeric'),
(39, 'Le nombre de téléphones portables vendus par an?', 2, 'free_text', 1, 12, 'numeric');

-- --------------------------------------------------------

--
-- Structure de la table `respondents`
--

CREATE TABLE IF NOT EXISTS `respondents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `age` int(3) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sexe` char(1) DEFAULT NULL,
  `educational_level` int(11) unsigned DEFAULT NULL,
  `marital_status` int(11) unsigned DEFAULT NULL,
  `professional_status` int(11) unsigned DEFAULT NULL,
  `childs_nbr` int(3) unsigned DEFAULT NULL,
  `brothers_nbr` int(3) unsigned DEFAULT NULL,
  `sisters_nbr` int(3) unsigned DEFAULT NULL,
  `gsm` int(30) DEFAULT NULL,
  `company_type` int(3) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `id_poll` int(10) unsigned DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_respondent_user_idx` (`created_by`),
  KEY `fk_respondent_poll_idx` (`id_poll`),
  KEY `fk_respondents_marital` (`marital_status`),
  KEY `fk_respondents_prof_stat` (`professional_status`),
  KEY `fk_respondents_comp_type` (`company_type`),
  KEY `fk_respondents_educ_idx` (`educational_level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Contenu de la table `respondents`
--

INSERT INTO `respondents` (`id`, `age`, `country`, `city`, `email`, `sexe`, `educational_level`, `marital_status`, `professional_status`, `childs_nbr`, `brothers_nbr`, `sisters_nbr`, `gsm`, `company_type`, `created_by`, `creation_date`, `id_poll`, `notes`) VALUES
(45, NULL, 0, 0, '', 'H', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 71259671, '2016-10-27 04:28:08', 12, NULL),
(48, NULL, 0, 0, '', 'H', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3848026790, '2016-10-27 04:52:55', 12, NULL),
(51, NULL, 21, 48, 'contact@yessir15.com', 'H', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 3848026790, '2016-10-27 08:31:23', 12, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sequences`
--

CREATE TABLE IF NOT EXISTS `sequences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL,
  `next_index` int(10) unsigned NOT NULL DEFAULT '1',
  `prefix` varchar(45) DEFAULT NULL,
  `fillers` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_UNIQUE` (`key`),
  UNIQUE KEY `key_next_Unique` (`key`,`next_index`,`prefix`,`fillers`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `sequences`
--

INSERT INTO `sequences` (`id`, `key`, `next_index`, `prefix`, `fillers`) VALUES
(1, 'polls_code_seq', 9, 'SON-', 6),
(2, 'users_code_seq', 13, 'USER-', 3);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(100) NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('dashboard_last_errors_number', '10'),
('dashboard_last_sheets_number', '10'),
('map_idle_interval', '60'),
('map_show_all_sheets', '0'),
('map_update_interval', '10');

-- --------------------------------------------------------

--
-- Structure de la table `sheets`
--

CREATE TABLE IF NOT EXISTS `sheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_poll` int(10) unsigned NOT NULL,
  `id_respondent` int(10) unsigned NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sheets_respondent_idx` (`id_respondent`),
  KEY `fk_sheets_agent_idx` (`created_by`),
  KEY `fk_sheets_poll_idx` (`id_poll`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `sheets`
--

INSERT INTO `sheets` (`id`, `id_poll`, `id_respondent`, `notes`, `created_by`, `creation_date`) VALUES
(31, 12, 51, '', 3848026790, '2016-10-27 08:38:33');

-- --------------------------------------------------------

--
-- Structure de la table `sheet_answers`
--

CREATE TABLE IF NOT EXISTS `sheet_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_question` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `id_sheet` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sheet_question` (`id_question`,`id_sheet`),
  KEY `fk_sheet_answers_question_idx` (`id_question`),
  KEY `fk_sheet_answers_sheet_idx` (`id_sheet`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `sheet_answers`
--

INSERT INTO `sheet_answers` (`id`, `id_question`, `value`, `id_sheet`) VALUES
(21, 35, '40000000', 31),
(22, 39, '10000000', 31),
(23, 36, '1\r\n5\r\n3\r\n4\r\n6\r\n2\r\n8\r\n7', 31),
(24, 37, '1: 35%\r\n2: 25%\r\n3: 15%\r\n4: 10%\r\n5: 5%', 31),
(25, 38, '- Housses et étuis\r\n- Chargeurs\r\n- Écouteurs\r\n- Batteries', 31);

-- --------------------------------------------------------

--
-- Structure de la table `username_or_email_on_hold`
--

CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(12) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(60) NOT NULL,
  `user_salt` varchar(32) NOT NULL,
  `user_last_login` datetime DEFAULT NULL,
  `user_login_time` datetime DEFAULT NULL,
  `user_session_id` varchar(40) DEFAULT NULL,
  `user_date` datetime NOT NULL,
  `user_modified` datetime NOT NULL,
  `user_agent_string` varchar(32) DEFAULT NULL,
  `user_level` tinyint(2) unsigned NOT NULL,
  `user_banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `pms_user_gsm` int(30) NOT NULL,
  `pms_user_first_name` varchar(80) NOT NULL,
  `pms_user_code` varchar(30) NOT NULL,
  `pms_user_last_name` varchar(80) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_pass`, `user_salt`, `user_last_login`, `user_login_time`, `user_session_id`, `user_date`, `user_modified`, `user_agent_string`, `user_level`, `user_banned`, `passwd_recovery_code`, `passwd_recovery_date`, `pms_user_gsm`, `pms_user_first_name`, `pms_user_code`, `pms_user_last_name`) VALUES
(71259671, 'yessir', 'bayahiassem@yahoo.fr', '$2a$09$63db20a86f16190fe567aO/KmXJ3HUbIzpvpm8NYew1KoUumK/Oz.', '63db20a86f16190fe567aa5c059a8ad3', '2016-10-28 04:34:29', '2016-10-28 04:34:29', 'bf7ca9a154be4f2c084aadf75e9075a088d87e5a', '2015-11-23 22:06:53', '2016-10-26 15:12:02', 'b58c00e4a28dd0f99b79d80dc2e43b10', 9, '0', NULL, NULL, 23290269, 'Assem', 'USE-002', 'Bayahi'),
(3491954500, 'yasserbayahi', 'yasserbayahi@yessir15.com', '$2a$09$6b14a87e51a4addf507a2uE1Oqmtjr4.9JWY8tJH5z2psYlCZ/zf2', '6b14a87e51a4addf507a265695d01f2a', '2016-10-27 08:38:46', NULL, NULL, '2016-10-26 10:49:10', '2016-10-26 10:49:10', 'd1f91e7d7b92cc19cfba2458defafe8e', 9, '0', NULL, NULL, 661616666, 'Yasser', 'USER-007', 'Bayahi'),
(3848026790, 'yasbay', 'yasserbayahi@gmail.com', '$2a$09$972faf230441129bd73b1OiUddezloZeY2Q5qvhdFzjKAYO.1vA0C', '972faf230441129bd73b1bc211bb53a7', '2016-10-27 08:30:21', NULL, NULL, '2016-10-26 17:38:53', '2016-10-26 17:38:53', 'd1f91e7d7b92cc19cfba2458defafe8e', 3, '0', NULL, NULL, 550777777, 'Yasser', 'USER-010', 'Bayahi ');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `fk_answers_question` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `geolocations`
--
ALTER TABLE `geolocations`
  ADD CONSTRAINT `fk_geolocations_agent` FOREIGN KEY (`id_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_geolocations_sheet` FOREIGN KEY (`id_sheet`) REFERENCES `sheets` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `lov`
--
ALTER TABLE `lov`
  ADD CONSTRAINT `fk_lov_parent` FOREIGN KEY (`id_parent`) REFERENCES `lov` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `fk_polls_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_poll` FOREIGN KEY (`id_poll`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `respondents`
--
ALTER TABLE `respondents`
  ADD CONSTRAINT `fk_respondents_comp_type` FOREIGN KEY (`company_type`) REFERENCES `lov` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respondents_educ` FOREIGN KEY (`educational_level`) REFERENCES `lov` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respondents_marital` FOREIGN KEY (`marital_status`) REFERENCES `lov` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respondents_prof_stat` FOREIGN KEY (`professional_status`) REFERENCES `lov` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respondent_poll` FOREIGN KEY (`id_poll`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respondent_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `sheets`
--
ALTER TABLE `sheets`
  ADD CONSTRAINT `fk_sheets_agent` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sheets_poll` FOREIGN KEY (`id_poll`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sheets_respondent` FOREIGN KEY (`id_respondent`) REFERENCES `respondents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sheet_answers`
--
ALTER TABLE `sheet_answers`
  ADD CONSTRAINT `fk_sheet_answers_question` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sheet_answers_sheet` FOREIGN KEY (`id_sheet`) REFERENCES `sheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
