-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 07:35 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talenttest`
--

-- --------------------------------------------------------

--
-- Table structure for table `k12testdata`
--

CREATE TABLE `k12testdata` (
  `guid` bigint(20) NOT NULL,
  `type` text CHARACTER SET utf8 NOT NULL,
  `username` text CHARACTER SET utf8 NOT NULL,
  `email` text CHARACTER SET utf8 NOT NULL,
  `password` text CHARACTER SET utf8 NOT NULL,
  `salt` varchar(8) CHARACTER SET utf8 NOT NULL,
  `first_name` text CHARACTER SET utf8 NOT NULL,
  `last_name` text CHARACTER SET utf8 NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `activation` text CHARACTER SET utf8 DEFAULT NULL,
  `time_created` int(11) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `k12testdata`
--

INSERT INTO `k12testdata` (`guid`, `type`, `username`, `email`, `password`, `salt`, `first_name`, `last_name`, `last_login`, `last_activity`, `activation`, `time_created`, `mobile`, `description`, `location`) VALUES
(1, '', '9111165131', '', '651dc442c3c665d94ae2da607a2d494e', 'f12d7dbf', 'prachi', 'lalwani', 0, 0, '', 1612525330, 9111165131, '', ''),
(2, '', '9826630688', '', '164a5b8ccac9f2c3d1d14ed3e68bc651', '7175d647', 'Pawan', 'Kumar', 0, 0, '', 1612764951, 9826630688, '', ''),
(3, '', '9826630688', '', '398d61dd229aac6dc5245871e1c412d9', '73e1459b', 'Pawan', 'Kumar', 0, 0, '', 1612764990, 9826630688, 'user', 'Indore');

-- --------------------------------------------------------

--
-- Table structure for table `tb_addons`
--

CREATE TABLE `tb_addons` (
  `id` bigint(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `order` bigint(20) NOT NULL,
  `active` int(1) NOT NULL,
  `settings` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_annotations`
--

CREATE TABLE `tb_annotations` (
  `id` bigint(20) NOT NULL,
  `owner_guid` bigint(20) NOT NULL,
  `subject_guid` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `time_created` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_annotations`
--

INSERT INTO `tb_annotations` (`id`, `owner_guid`, `subject_guid`, `type`, `time_created`) VALUES
(1, 1, 3, 'comments:post', 1501237827),
(2, 3, 3, 'comments:post', 1501680862),
(7, 47, 253, 'comments:entity', 1503986422),
(4, 3, 208, 'comments:entity', 1502603477),
(5, 3, 208, 'comments:entity', 1502603527),
(6, 3, 35, 'comments:post', 1502603734),
(8, 47, 253, 'comments:entity', 1503986434),
(9, 3, 46, 'comments:post', 1504508309),
(10, 3, 55, 'comments:post', 1504709907),
(12, 1, 60, 'comments:post', 1505117066),
(13, 1, 60, 'comments:post', 1505117077),
(14, 47, 60, 'comments:post', 1505144181),
(15, 3, 63, 'comments:post', 1505283557),
(16, 44, 60, 'comments:post', 1505379949),
(18, 47, 54, 'comments:post', 1505812120),
(19, 1, 60, 'comments:post', 1506063463),
(20, 1, 51, 'comments:post', 1506064182),
(21, 49, 72, 'comments:post', 1506073532),
(25, 47, 253, 'comments:post', 1612586590),
(26, 1, 3, 'comments:post', 1612586672);

-- --------------------------------------------------------

--
-- Table structure for table `tb_components`
--

CREATE TABLE `tb_components` (
  `id` bigint(20) NOT NULL,
  `com_id` varchar(20) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_components`
--

INSERT INTO `tb_components` (`id`, `com_id`, `active`) VALUES
(1, 'OssnProfile', 1),
(2, 'OssnWall', 1),
(3, 'OssnComments', 1),
(4, 'OssnLikes', 1),
(5, 'OssnPhotos', 1),
(6, 'OssnNotifications', 1),
(7, 'OssnSearch', 1),
(8, 'OssnMessages', 1),
(9, 'OssnAds', 1),
(10, 'OssnGroups', 1),
(11, 'OssnSitePages', 1),
(12, 'OssnBlock', 1),
(13, 'OssnChat', 1),
(14, 'OssnPoke', 1),
(15, 'OssnInvite', 1),
(16, 'OssnEmbed', 1),
(17, 'OssnSmilies', 1),
(18, 'OssnAdvanceSearch', 1),
(19, 'OssnShare', 0),
(20, 'fancybox', 0),
(21, 'Tetris', 0),
(22, 'Love', 0),
(23, 'login_redirect', 0),
(24, 'google_analytics', 0),
(25, 'footer-links', 0),
(26, 'Stats', 0),
(30, 'SearchSidebar', 0),
(27, 'aboutuser', 0),
(28, 'bio', 0),
(29, 'Metatags', 1),
(31, 'HomeTools', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_entities`
--

CREATE TABLE `tb_entities` (
  `guid` bigint(20) NOT NULL,
  `owner_guid` bigint(20) NOT NULL,
  `type` text NOT NULL,
  `subtype` text NOT NULL,
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `permission` int(1) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_entities`
--

INSERT INTO `tb_entities` (`guid`, `owner_guid`, `type`, `subtype`, `time_created`, `time_updated`, `permission`, `active`) VALUES
(1, 1, 'user', 'birthdate', 1498822362, 1502553940, 2, 1),
(2, 1, 'user', 'gender', 1498822362, 1502553940, 2, 1),
(3, 1, 'user', 'cover_time', 1498822560, 0, 2, 1),
(4, 1, 'object', 'poster_guid', 1498905871, 0, 2, 1),
(5, 1, 'object', 'access', 1498905871, 0, 2, 1),
(6, 1, 'object', 'file:wallphoto', 1498905871, 0, 2, 1),
(185, 45, 'user', 'cover_position', 1502554193, 1502873191, 2, 1),
(10, 3, 'user', 'birthdate', 1499064790, 1505720011, 2, 1),
(11, 3, 'user', 'gender', 1499064790, 1505720011, 2, 1),
(166, 3, 'user', 'cover_position', 1502550282, 1505548505, 2, 1),
(160, 28, 'object', 'access', 1502550269, 0, 2, 1),
(161, 3, 'user', 'file:profile:cover', 1502550276, 0, 2, 1),
(162, 29, 'object', 'item_type', 1502550277, 0, 2, 1),
(163, 29, 'object', 'item_guid', 1502550277, 0, 2, 1),
(164, 29, 'object', 'poster_guid', 1502550277, 0, 2, 1),
(165, 29, 'object', 'access', 1502550277, 0, 2, 1),
(254, 47, 'object', 'item_type', 1502873178, 0, 2, 1),
(29, 1, 'user', 'language', 1499082474, 1502553940, 2, 1),
(34, 3, 'user', 'file:profile:cover', 1499350758, 0, 2, 1),
(33, 3, 'user', 'cover_time', 1499239548, 1502550277, 2, 1),
(184, 31, 'object', 'access', 1502554178, 0, 2, 1),
(167, 44, 'user', 'birthdate', 1502551008, 1503150499, 2, 1),
(168, 44, 'user', 'gender', 1502551008, 1503150499, 2, 1),
(169, 44, 'user', 'cover_time', 1502551103, 1502603786, 2, 1),
(170, 45, 'user', 'birthdate', 1502554012, 1502867183, 2, 1),
(171, 45, 'user', 'gender', 1502554012, 1502867183, 2, 1),
(172, 45, 'user', 'cover_time', 1502554086, 1502873178, 2, 1),
(173, 45, 'user', 'language', 1502554124, 1502867183, 2, 1),
(264, 47, 'user', 'gender', 1503984458, 0, 2, 1),
(175, 45, 'user', 'icon_time', 1502554166, 1502873746, 2, 1),
(50, 3, 'user', 'language', 1499426173, 1505720011, 2, 1),
(158, 28, 'object', 'item_guid', 1502550269, 0, 2, 1),
(159, 28, 'object', 'poster_guid', 1502550269, 0, 2, 1),
(100, 23, 'component', 'url', 1501076503, 0, 2, 1),
(101, 3, 'object', 'poster_guid', 1501237755, 0, 2, 1),
(102, 3, 'object', 'access', 1501237755, 0, 2, 1),
(103, 1, 'annotation', 'comments:post', 1501237827, 0, 2, 1),
(104, 4, 'object', 'membership', 1501246821, 0, 2, 1),
(105, 5, 'object', 'membership', 1501250424, 0, 2, 1),
(106, 6, 'object', 'membership', 1501250511, 0, 2, 1),
(107, 7, 'object', 'membership', 1501250566, 0, 2, 1),
(108, 8, 'object', 'membership', 1501496622, 0, 2, 1),
(156, 3, 'user', 'file:profile:photo', 1502550269, 0, 2, 1),
(157, 28, 'object', 'item_type', 1502550269, 0, 2, 1),
(113, 3, 'user', 'file:profile:photo', 1501678940, 0, 2, 1),
(114, 3, 'user', 'icon_time', 1501678940, 1502603043, 2, 1),
(183, 31, 'object', 'poster_guid', 1502554178, 0, 2, 1),
(182, 31, 'object', 'item_guid', 1502554178, 0, 2, 1),
(253, 45, 'user', 'file:profile:cover', 1502873177, 0, 2, 1),
(180, 45, 'user', 'file:profile:cover', 1502554178, 0, 2, 1),
(181, 31, 'object', 'item_type', 1502554178, 0, 2, 1),
(125, 15, 'object', 'membership', 1501680612, 0, 2, 1),
(126, 4, 'object', 'file:cover', 1501680766, 0, 2, 1),
(127, 2, 'annotation', 'comments:post', 1501680862, 0, 2, 1),
(128, 2, 'annotation', 'file:comment:photo', 1501680862, 0, 2, 1),
(129, 16, 'object', 'poster_guid', 1501680961, 0, 2, 1),
(130, 16, 'object', 'access', 1501680961, 0, 2, 1),
(141, 17, 'object', 'membership', 1502280117, 0, 2, 1),
(142, 18, 'object', 'membership', 1502280312, 1502453612, 2, 1),
(143, 19, 'object', 'membership', 1502347679, 0, 2, 1),
(144, 20, 'object', 'membership', 1502347872, 0, 2, 1),
(145, 21, 'object', 'membership', 1502347964, 0, 2, 1),
(146, 22, 'object', 'membership', 1502348206, 0, 2, 1),
(147, 23, 'object', 'membership', 1502348435, 0, 2, 1),
(149, 25, 'object', 'membership', 1502348613, 0, 2, 1),
(150, 26, 'object', 'membership', 1502348709, 0, 2, 1),
(151, 27, 'object', 'membership', 1502548184, 1502549531, 2, 1),
(154, 27, 'object', 'file:cover', 1502549700, 0, 2, 1),
(155, 27, 'object', 'cover', 1502549706, 0, 2, 1),
(186, 46, 'user', 'birthdate', 1502557802, 0, 2, 1),
(187, 46, 'user', 'gender', 1502557802, 0, 2, 1),
(188, 46, 'user', 'cover_time', 1502557883, 0, 2, 1),
(189, 44, 'user', 'language', 1502561681, 1503150499, 2, 1),
(260, 49, 'object', 'poster_guid', 1503050450, 0, 2, 1),
(263, 47, 'user', 'birthdate', 1503984458, 0, 2, 1),
(192, 3, 'user', 'file:profile:photo', 1502562630, 0, 2, 1),
(193, 32, 'object', 'item_type', 1502562632, 0, 2, 1),
(194, 32, 'object', 'item_guid', 1502562632, 0, 2, 1),
(195, 32, 'object', 'poster_guid', 1502562632, 0, 2, 1),
(196, 32, 'object', 'access', 1502562632, 0, 2, 1),
(197, 3, 'user', 'file:profile:photo', 1502603043, 0, 2, 1),
(198, 33, 'object', 'item_type', 1502603043, 0, 2, 1),
(199, 33, 'object', 'item_guid', 1502603043, 0, 2, 1),
(200, 33, 'object', 'poster_guid', 1502603043, 0, 2, 1),
(201, 33, 'object', 'access', 1502603043, 0, 2, 1),
(202, 34, 'object', 'poster_guid', 1502603168, 0, 2, 1),
(203, 34, 'object', 'access', 1502603168, 0, 2, 1),
(204, 34, 'object', 'file:wallphoto', 1502603168, 0, 2, 1),
(205, 35, 'object', 'poster_guid', 1502603343, 0, 2, 1),
(206, 35, 'object', 'access', 1502603343, 0, 2, 1),
(207, 35, 'object', 'file:wallphoto', 1502603343, 0, 2, 1),
(208, 44, 'user', 'file:profile:photo', 1502603416, 0, 2, 1),
(209, 44, 'user', 'icon_time', 1502603417, 1502603711, 2, 1),
(234, 40, 'object', 'file:cover', 1502866726, 0, 2, 1),
(233, 40, 'object', 'membership', 1502866618, 1502866712, 2, 1),
(214, 4, 'annotation', 'comments:entity', 1502603477, 0, 2, 1),
(215, 5, 'annotation', 'comments:entity', 1502603527, 0, 2, 1),
(216, 44, 'user', 'file:profile:photo', 1502603710, 0, 2, 1),
(217, 37, 'object', 'item_type', 1502603711, 0, 2, 1),
(218, 37, 'object', 'item_guid', 1502603711, 0, 2, 1),
(219, 37, 'object', 'poster_guid', 1502603711, 0, 2, 1),
(220, 37, 'object', 'access', 1502603711, 0, 2, 1),
(221, 44, 'user', 'file:profile:cover', 1502603728, 0, 2, 1),
(236, 45, 'user', 'file:profile:photo', 1502867145, 0, 2, 1),
(235, 40, 'object', 'cover', 1502866777, 1502866798, 2, 1),
(226, 6, 'annotation', 'comments:post', 1502603734, 0, 2, 1),
(227, 44, 'user', 'file:profile:cover', 1502603786, 0, 2, 1),
(228, 39, 'object', 'item_type', 1502603786, 0, 2, 1),
(229, 39, 'object', 'item_guid', 1502603786, 0, 2, 1),
(230, 39, 'object', 'poster_guid', 1502603786, 0, 2, 1),
(231, 39, 'object', 'access', 1502603786, 0, 2, 1),
(232, 44, 'user', 'cover_position', 1502603795, 0, 2, 1),
(237, 41, 'object', 'item_type', 1502867145, 0, 2, 1),
(238, 41, 'object', 'item_guid', 1502867145, 0, 2, 1),
(239, 41, 'object', 'poster_guid', 1502867145, 0, 2, 1),
(240, 41, 'object', 'access', 1502867145, 0, 2, 1),
(241, 42, 'object', 'membership', 1502867328, 1502867393, 2, 1),
(242, 43, 'object', 'membership', 1502867405, 0, 2, 1),
(243, 42, 'object', 'file:cover', 1502867443, 0, 2, 1),
(244, 42, 'object', 'cover', 1502867460, 0, 2, 1),
(245, 44, 'object', 'membership', 1502867580, 1502867828, 2, 1),
(247, 44, 'object', 'cover', 1502867672, 1502867737, 2, 1),
(248, 44, 'object', 'file:cover', 1502867720, 0, 2, 1),
(249, 45, 'object', 'membership', 1502868016, 0, 2, 1),
(250, 46, 'object', 'poster_guid', 1502868747, 0, 2, 1),
(251, 46, 'object', 'access', 1502868747, 0, 2, 1),
(252, 46, 'object', 'file:wallphoto', 1502868747, 0, 2, 1),
(255, 47, 'object', 'item_guid', 1502873178, 0, 2, 1),
(256, 47, 'object', 'poster_guid', 1502873178, 0, 2, 1),
(257, 47, 'object', 'access', 1502873178, 0, 2, 1),
(261, 49, 'object', 'access', 1503050450, 0, 2, 1),
(262, 49, 'object', 'file:wallphoto', 1503050450, 0, 2, 1),
(265, 47, 'user', 'cover_time', 1503984899, 0, 2, 1),
(266, 50, 'object', 'poster_guid', 1503985930, 0, 2, 1),
(267, 50, 'object', 'access', 1503985930, 0, 2, 1),
(268, 7, 'annotation', 'comments:entity', 1503986422, 0, 2, 1),
(269, 8, 'annotation', 'comments:entity', 1503986434, 0, 2, 1),
(270, 51, 'object', 'poster_guid', 1504360638, 0, 2, 1),
(271, 51, 'object', 'access', 1504360638, 0, 2, 1),
(272, 51, 'object', 'file:wallphoto', 1504360638, 0, 2, 1),
(273, 9, 'annotation', 'comments:post', 1504508309, 0, 2, 1),
(274, 48, 'user', 'birthdate', 1504523378, 0, 2, 1),
(275, 48, 'user', 'gender', 1504523378, 0, 2, 1),
(278, 53, 'object', 'poster_guid', 1504523623, 0, 2, 1),
(279, 53, 'object', 'access', 1504523623, 0, 2, 1),
(280, 53, 'object', 'file:wallphoto', 1504523623, 0, 2, 1),
(281, 54, 'object', 'poster_guid', 1504527934, 0, 2, 1),
(282, 54, 'object', 'access', 1504527934, 0, 2, 1),
(283, 54, 'object', 'file:wallphoto', 1504527934, 0, 2, 1),
(284, 55, 'object', 'poster_guid', 1504594471, 0, 2, 1),
(285, 55, 'object', 'access', 1504594471, 0, 2, 1),
(286, 56, 'object', 'poster_guid', 1504681451, 0, 2, 1),
(287, 56, 'object', 'access', 1504681451, 0, 2, 1),
(288, 56, 'object', 'file:wallphoto', 1504681451, 0, 2, 1),
(535, 24, 'annotation', 'comments:post', 1612586281, NULL, 2, 1),
(291, 49, 'user', 'birthdate', 1504689939, 1506076804, 2, 1),
(292, 49, 'user', 'gender', 1504689939, 1506076804, 2, 1),
(293, 50, 'user', 'birthdate', 1504690442, 0, 2, 1),
(294, 50, 'user', 'gender', 1504690442, 0, 2, 1),
(295, 58, 'object', 'poster_guid', 1504694833, 0, 2, 1),
(296, 58, 'object', 'access', 1504694833, 0, 2, 1),
(297, 59, 'object', 'poster_guid', 1504698879, 0, 2, 1),
(298, 59, 'object', 'access', 1504698879, 0, 2, 1),
(299, 60, 'object', 'poster_guid', 1504698921, 0, 2, 1),
(300, 60, 'object', 'access', 1504698921, 0, 2, 1),
(301, 60, 'object', 'file:wallphoto', 1504698921, 0, 2, 1),
(302, 61, 'object', 'poster_guid', 1504699010, 0, 2, 1),
(303, 61, 'object', 'access', 1504699010, 0, 2, 1),
(304, 62, 'object', 'membership', 1504699085, 0, 2, 1),
(305, 10, 'annotation', 'comments:post', 1504709907, 0, 2, 1),
(307, 12, 'annotation', 'comments:post', 1505117066, 0, 2, 1),
(308, 13, 'annotation', 'comments:post', 1505117077, 0, 2, 1),
(309, 14, 'annotation', 'comments:post', 1505144181, 0, 2, 1),
(310, 63, 'object', 'poster_guid', 1505209542, 0, 2, 1),
(311, 63, 'object', 'access', 1505209542, 0, 2, 1),
(312, 63, 'object', 'file:wallphoto', 1505209542, 0, 2, 1),
(313, 64, 'object', 'membership', 1505217715, 0, 2, 1),
(316, 15, 'annotation', 'comments:post', 1505283557, 0, 2, 1),
(315, 64, 'object', 'file:cover', 1505217845, 0, 2, 1),
(317, 16, 'annotation', 'comments:post', 1505379949, 0, 2, 1),
(318, 16, 'annotation', 'file:comment:photo', 1505379949, 0, 2, 1),
(327, 68, 'object', 'poster_guid', 1505396154, 0, 2, 1),
(334, 18, 'annotation', 'comments:post', 1505812120, 0, 2, 1),
(328, 68, 'object', 'access', 1505396154, 0, 2, 1),
(336, 71, 'object', 'access', 1505893181, 0, 2, 1),
(335, 71, 'object', 'poster_guid', 1505893181, 0, 2, 1),
(337, 19, 'annotation', 'comments:post', 1506063463, 0, 2, 1),
(338, 20, 'annotation', 'comments:post', 1506064182, 0, 2, 1),
(339, 51, 'user', 'birthdate', 1506073362, 0, 2, 1),
(340, 51, 'user', 'gender', 1506073362, 0, 2, 1),
(341, 72, 'object', 'poster_guid', 1506073496, 0, 2, 1),
(342, 72, 'object', 'access', 1506073496, 0, 2, 1),
(343, 21, 'annotation', 'comments:post', 1506073532, 0, 2, 1),
(344, 49, 'user', 'cover_time', 1506073551, 1506073681, 2, 1),
(345, 49, 'user', 'file:profile:photo', 1506073659, 0, 2, 1),
(346, 49, 'user', 'icon_time', 1506073659, 1506076692, 2, 1),
(366, 78, 'object', 'poster_guid', 1506075951, 0, 2, 1),
(365, 77, 'object', 'access', 1506075751, 0, 2, 1),
(364, 77, 'object', 'poster_guid', 1506075751, 0, 2, 1),
(351, 49, 'user', 'file:profile:cover', 1506073681, 0, 2, 1),
(352, 74, 'object', 'item_type', 1506073681, 0, 2, 1),
(353, 74, 'object', 'item_guid', 1506073681, 0, 2, 1),
(354, 74, 'object', 'poster_guid', 1506073681, 0, 2, 1),
(355, 74, 'object', 'access', 1506073681, 0, 2, 1),
(356, 49, 'user', 'cover_position', 1506074018, 0, 2, 1),
(357, 75, 'object', 'poster_guid', 1506074186, 0, 2, 1),
(358, 75, 'object', 'access', 1506074186, 0, 2, 1),
(359, 76, 'object', 'poster_guid', 1506074217, 0, 2, 1),
(360, 76, 'object', 'access', 1506074217, 0, 2, 1),
(361, 76, 'object', 'file:wallphoto', 1506074217, 0, 2, 1),
(362, 52, 'user', 'birthdate', 1506074642, 0, 2, 1),
(363, 52, 'user', 'gender', 1506074642, 0, 2, 1),
(367, 78, 'object', 'access', 1506075951, 0, 2, 1),
(368, 52, 'user', 'cover_time', 1506076448, 0, 2, 1),
(369, 52, 'user', 'file:profile:photo', 1506076486, 0, 2, 1),
(370, 52, 'user', 'icon_time', 1506076487, 0, 2, 1),
(371, 79, 'object', 'item_type', 1506076487, 0, 2, 1),
(372, 79, 'object', 'item_guid', 1506076487, 0, 2, 1),
(373, 79, 'object', 'poster_guid', 1506076487, 0, 2, 1),
(374, 79, 'object', 'access', 1506076487, 0, 2, 1),
(375, 49, 'user', 'file:profile:photo', 1506076692, 0, 2, 1),
(376, 80, 'object', 'item_type', 1506076692, 0, 2, 1),
(377, 80, 'object', 'item_guid', 1506076692, 0, 2, 1),
(378, 80, 'object', 'poster_guid', 1506076692, 0, 2, 1),
(379, 80, 'object', 'access', 1506076692, 0, 2, 1),
(380, 49, 'user', 'language', 1506076784, 1506076804, 2, 1),
(381, 81, 'object', 'poster_guid', 1506148699, 0, 2, 1),
(382, 81, 'object', 'access', 1506148699, 0, 2, 1),
(383, 82, 'object', 'poster_guid', 1506148715, 0, 2, 1),
(384, 82, 'object', 'access', 1506148715, 0, 2, 1),
(385, 82, 'object', 'file:wallphoto', 1506148715, 0, 2, 1),
(386, 83, 'object', 'poster_guid', 1506149819, 0, 2, 1),
(387, 83, 'object', 'access', 1506149819, 0, 2, 1),
(388, 83, 'object', 'file:wallphoto', 1506149819, 0, 2, 1),
(437, 101, 'object', 'poster_guid', 1506405084, 0, 2, 1),
(438, 101, 'object', 'access', 1506405084, 0, 2, 1),
(439, 101, 'object', 'file_wallvideo', 1506405084, 0, 2, 1),
(525, 135, 'object', 'poster_guid', 1507289886, 0, 2, 1),
(526, 135, 'object', 'access', 1507289886, 0, 2, 1),
(527, 136, 'object', 'poster_guid', 1507289913, 0, 2, 1),
(528, 136, 'object', 'access', 1507289913, 0, 2, 1),
(529, 137, 'object', 'poster_guid', 1507289946, 0, 2, 1),
(530, 137, 'object', 'access', 1507289946, 0, 2, 1),
(531, 137, 'object', 'file:wallphoto', 1507289946, 0, 2, 1),
(532, 138, 'object', 'poster_guid', 1507289990, 0, 2, 1),
(533, 138, 'object', 'access', 1507289990, 0, 2, 1),
(534, 138, 'object', 'file:wallvideo', 1507289990, 0, 2, 1),
(466, 111, 'object', 'poster_guid', 1506407503, 0, 2, 1),
(467, 111, 'object', 'access', 1506407503, 0, 2, 1),
(472, 114, 'object', 'poster_guid', 1506407619, 0, 2, 1),
(473, 114, 'object', 'access', 1506407619, 0, 2, 1),
(478, 116, 'object', 'poster_guid', 1506408650, 0, 2, 1),
(479, 116, 'object', 'access', 1506408650, 0, 2, 1),
(485, 119, 'object', 'poster_guid', 1506409166, 0, 2, 1),
(486, 119, 'object', 'access', 1506409166, 0, 2, 1),
(487, 119, 'object', 'file:wallvideo', 1506409166, 0, 2, 1),
(501, 126, 'object', 'poster_guid', 1506412119, 0, 2, 1),
(502, 126, 'object', 'access', 1506412119, 0, 2, 1),
(520, 133, 'object', 'poster_guid', 1506419568, 0, 2, 1),
(521, 133, 'object', 'access', 1506419568, 0, 2, 1),
(522, 133, 'object', 'file:wallvideo', 1506419568, 0, 2, 1),
(536, 25, 'annotation', 'comments:post', 1612586590, NULL, 2, 1),
(537, 26, 'annotation', 'comments:post', 1612586672, NULL, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_entities_metadata`
--

CREATE TABLE `tb_entities_metadata` (
  `id` bigint(20) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_entities_metadata`
--

INSERT INTO `tb_entities_metadata` (`id`, `guid`, `value`) VALUES
(1, 1, '15/04/1991'),
(2, 2, 'male'),
(3, 3, '1498822560'),
(4, 4, '1'),
(5, 5, '2'),
(6, 6, 'ossnwall/images/ce9a7dc2ae573bdb87fd4cf14ec1140f.jpg'),
(161, 161, 'profile/cover/10af11293261fda6a24b9886fc4e44f9.jpg'),
(160, 160, '2'),
(10, 10, '14/02/1991'),
(253, 253, 'profile/cover/66785713963c67522d3e8bac313322e9.jpg'),
(158, 158, '156'),
(157, 157, 'profile:photo'),
(163, 163, '161'),
(165, 165, '2'),
(164, 164, '3'),
(34, 34, 'profile/cover/0c0bb52cce4da2c444f2d4c3d5a778a1.jpg'),
(29, 29, 'en'),
(33, 33, '1502550277'),
(159, 159, '3'),
(169, 169, '1502603786'),
(168, 168, 'male'),
(167, 167, '01/09/1992'),
(166, 166, '[\"-172px\",\"0px\"]'),
(180, 180, 'profile/cover/6b2dcb4f90aa57d4afc1eccde5a42d25.jpg'),
(182, 182, '180'),
(181, 181, 'cover:photo'),
(184, 184, '2'),
(183, 183, '45'),
(50, 50, 'en'),
(185, 185, '[\"-180px\",\"0px\"]'),
(254, 254, 'cover:photo'),
(264, 264, ''),
(263, 263, ''),
(173, 173, 'en'),
(175, 175, '1502873746'),
(100, 100, 'http://localhost/SocialNetwork/u/[USERNAME]/edit'),
(101, 101, '1'),
(102, 102, '2'),
(103, 103, 'nice'),
(104, 104, '2'),
(105, 105, '2'),
(106, 106, '2'),
(107, 107, '2'),
(108, 108, '2'),
(156, 156, 'profile/photo/ae5ee4d7f0840ffcfbd1fe056bb34fb5.jpg'),
(113, 113, 'profile/photo/807bb42d2cd66cbe97ef4d9388e941a0.jpg'),
(114, 114, '1502603043'),
(172, 172, '1502873178'),
(171, 171, 'male'),
(170, 170, '14/11/1991'),
(125, 125, '2'),
(126, 126, 'cover/27da8f2f6450d5f783ea7bfcf35fb095.jpg'),
(127, 127, ''),
(128, 128, 'comment/photo/d99ca1cad23ca792c7d66677a200b129.jpg'),
(129, 129, '3'),
(130, 130, '2'),
(162, 162, 'cover:photo'),
(141, 141, '2'),
(142, 142, '2'),
(143, 143, '2'),
(144, 144, '2'),
(145, 145, '2'),
(146, 146, '2'),
(147, 147, '2'),
(148, 148, '2'),
(149, 149, '2'),
(150, 150, '2'),
(151, 151, '2'),
(154, 154, 'cover/c686a3c64257dd67a7e8859bd4db8b01.jpg'),
(155, 155, '[\"-96\",false]'),
(186, 186, ''),
(187, 187, ''),
(188, 188, '1502557883'),
(189, 189, 'en'),
(262, 262, 'ossnwall/images/41c56ce0342f1a373491a5da76e930c8.jpg'),
(260, 260, '44'),
(261, 261, '2'),
(192, 192, 'profile/photo/8e8917781760ad997c7cb6d06e2ec4ec.jpg'),
(193, 193, 'profile:photo'),
(194, 194, '192'),
(195, 195, '3'),
(196, 196, '2'),
(197, 197, 'profile/photo/1a8a7cbe3a74ea10184182523086d1b2.jpg'),
(198, 198, 'profile:photo'),
(199, 199, '197'),
(200, 200, '3'),
(201, 201, '2'),
(202, 202, '3'),
(203, 203, '3'),
(204, 204, 'ossnwall/images/4d61f59ad6ffb55a12d59d8c204e9f8a.jpg'),
(205, 205, '44'),
(206, 206, '2'),
(207, 207, 'ossnwall/images/83a3e7b900704de35787b4049201083a.jpeg'),
(208, 208, 'profile/photo/c885a9343047109a881362dae90c3c6b.jpg'),
(209, 209, '1502603711'),
(234, 234, 'cover/80e797b7e70e453b6f8cc529717072b6.jpg'),
(233, 233, '2'),
(214, 214, '&#x1f60a; &#x1f60a; &#x1f60a; &#x1f60a;'),
(215, 215, '&#x1f370; &#x1f36e; &#x1f371; &#x1f36e; &#x1f372; &#x1f36e; &#x1f37a; &#x1f36e; &#x1f37a; &#x1f36e; &#x1f37a; &#x1f377; &#x1f371;'),
(216, 216, 'profile/photo/417741d95139bedd77cb1c2360598ed3.jpg'),
(217, 217, 'profile:photo'),
(218, 218, '216'),
(219, 219, '44'),
(220, 220, '2'),
(221, 221, 'profile/cover/3bf56f4d348d6662b1d9001a3a5c997a.jpg'),
(236, 236, 'profile/photo/4cf90343ebafc0c6d08ef79b2494d56a.jpg'),
(235, 235, '[\"-227\",false]'),
(226, 226, '&#x1f44c; &#x1f44c; &#x1f44c; &#x1f44c;'),
(227, 227, 'profile/cover/2de87b5b9daeb3f3cb35a56b5bd36cd3.jpg'),
(228, 228, 'cover:photo'),
(229, 229, '227'),
(230, 230, '44'),
(231, 231, '2'),
(232, 232, '[\"-94px\",\"0px\"]'),
(237, 237, 'profile:photo'),
(238, 238, '236'),
(239, 239, '45'),
(240, 240, '2'),
(241, 241, '2'),
(242, 242, '2'),
(243, 243, 'cover/750fb00fb1ff36e9fb2d3f9644b9262b.jpg'),
(244, 244, '[\"-188\",false]'),
(245, 245, '2'),
(249, 249, '2'),
(247, 247, '[\"-3\",false]'),
(248, 248, 'cover/5fcd254f1d6f128950430f0ecaffec73.jpg'),
(250, 250, '44'),
(251, 251, '2'),
(252, 252, 'ossnwall/images/adc94041bbb91efa1f8d371eeeac9822.jpg'),
(255, 255, '253'),
(256, 256, '45'),
(257, 257, '2'),
(265, 265, '1503984899'),
(266, 266, '47'),
(267, 267, '2'),
(268, 268, 'Hi... &#x1f603;'),
(269, 269, 'Hmm'),
(270, 270, '3'),
(271, 271, '2'),
(272, 272, 'ossnwall/images/1e8c79c5435de90f648a69a0b4a473b3.jpg'),
(273, 273, 'Nice'),
(274, 274, ''),
(275, 275, ''),
(279, 279, '2'),
(278, 278, '44'),
(280, 280, 'ossnwall/images/8c2b60b5043e00ae315f1aeca37e7d81.jpg'),
(281, 281, '44'),
(282, 282, '2'),
(283, 283, 'ossnwall/images/4536b0f976935d7b9e9cdbf96deffcb4.jpg'),
(284, 284, '44'),
(285, 285, '2'),
(286, 286, '44'),
(287, 287, '2'),
(288, 288, 'ossnwall/images/a43766a7a161510195bcda8d180d520d.jpg'),
(307, 307, ''),
(306, 306, ''),
(291, 291, '10/09/1991'),
(292, 292, 'male'),
(293, 293, ''),
(294, 294, ''),
(295, 295, '47'),
(296, 296, '2'),
(297, 297, '3'),
(298, 298, '2'),
(299, 299, '3'),
(300, 300, '2'),
(301, 301, 'ossnwall/images/aa7996ac5783af536ed4ff2b5d2ecd04.jpg'),
(302, 302, '3'),
(303, 303, '1'),
(304, 304, '2'),
(305, 305, 'hi'),
(308, 308, 'Hey'),
(309, 309, 'Hi'),
(310, 310, '1'),
(311, 311, '3'),
(312, 312, 'ossnwall/images/1780d339fd46fc588a5c32dfce4773fd.jpg'),
(313, 313, '2'),
(316, 316, 'csadsasa'),
(315, 315, 'cover/d85274be21d7352b2a0cbddfc28f6a03.jpg'),
(317, 317, ''),
(318, 318, 'comment/photo/5adbd80214ba43f823b6df96b10457b6.jpg'),
(328, 328, '2'),
(334, 334, 'Ok..'),
(335, 335, '47'),
(327, 327, '44'),
(336, 336, '2'),
(337, 337, 'hehe'),
(338, 338, 'nice'),
(339, 339, ''),
(340, 340, ''),
(341, 341, '49'),
(342, 342, '2'),
(343, 343, 'hi'),
(344, 344, '1506073681'),
(345, 345, 'profile/photo/aed55b8dfd97c60c374408386ff9121d.jpg'),
(346, 346, '1506076692'),
(367, 367, '2'),
(366, 366, '52'),
(365, 365, '2'),
(364, 364, '3'),
(351, 351, 'profile/cover/5536e31fa93f1f5810076704885db43e.jpg'),
(352, 352, 'cover:photo'),
(353, 353, '351'),
(354, 354, '49'),
(355, 355, '2'),
(356, 356, '[\"-118px\",\"0px\"]'),
(357, 357, '49'),
(358, 358, '2'),
(359, 359, '49'),
(360, 360, '3'),
(361, 361, 'ossnwall/images/44fdf22749abc59e4f9c8db73f1b4b1f.jpg'),
(362, 362, ''),
(363, 363, ''),
(368, 368, '1506076448'),
(369, 369, 'profile/photo/7c4a0618270c24866beaae94654b9da4.jpg'),
(370, 370, '1506076487'),
(371, 371, 'profile:photo'),
(372, 372, '369'),
(373, 373, '52'),
(374, 374, '2'),
(375, 375, 'profile/photo/b8620184cdf1ae188cb7bdf46b34f7fc.jpg'),
(376, 376, 'profile:photo'),
(377, 377, '375'),
(378, 378, '49'),
(379, 379, '2'),
(380, 380, 'en'),
(381, 381, '1'),
(382, 382, '2'),
(383, 383, '1'),
(384, 384, '2'),
(385, 385, 'ossnwall/images/c821231a5c6d84b50987b98c61249fe6.jpg'),
(386, 386, '1'),
(387, 387, '2'),
(388, 388, 'ossnwall/images/96d0d3ac3fc6bbb274d8232792835d5e.png'),
(437, 437, '1'),
(438, 438, '2'),
(439, 439, 'ossnwall/videos/afbd64f6844ae57a6a607dc91fa2be9c.mp4'),
(525, 525, '1'),
(526, 526, '3'),
(527, 527, '1'),
(528, 528, '3'),
(529, 529, '1'),
(530, 530, '3'),
(531, 531, 'ossnwall/images/dfa78848be29caffab4cbbd0319ab1fc.jpeg'),
(532, 532, '1'),
(533, 533, '3'),
(534, 534, 'ossnwall/videos/6be786e3e746ded505618ec12386f5a2.mp4'),
(466, 466, '3'),
(467, 467, '2'),
(473, 473, '2'),
(472, 472, '3'),
(479, 479, '2'),
(478, 478, '3'),
(485, 485, '3'),
(486, 486, '2'),
(487, 487, 'ossnwall/videos/8d1bb2307cb04506a870af4700255bdf.mp4'),
(501, 501, '3'),
(502, 502, '2'),
(520, 520, '1'),
(521, 521, '1'),
(522, 522, 'ossnwall/videos/367d2db43caad29aacba30772449dff9.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `tb_likes`
--

CREATE TABLE `tb_likes` (
  `id` bigint(20) NOT NULL,
  `subject_id` bigint(20) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_likes`
--

INSERT INTO `tb_likes` (`id`, `subject_id`, `guid`, `type`) VALUES
(2, 3, 3, 'post'),
(3, 180, 3, 'entity'),
(9, 253, 47, 'entity'),
(5, 192, 44, 'entity'),
(6, 34, 3, 'post'),
(7, 35, 3, 'post'),
(8, 208, 3, 'entity'),
(13, 46, 3, 'post'),
(16, 60, 3, 'post'),
(17, 58, 3, 'post'),
(18, 54, 3, 'post'),
(19, 53, 3, 'post'),
(20, 51, 3, 'post'),
(21, 58, 44, 'post'),
(22, 60, 47, 'post'),
(23, 13, 47, 'annotation'),
(24, 14, 44, 'annotation'),
(25, 16, 3, 'annotation'),
(27, 10, 3, 'annotation'),
(28, 55, 3, 'post'),
(29, 68, 3, 'post'),
(30, 15, 3, 'annotation'),
(32, 63, 3, 'post'),
(33, 55, 47, 'post'),
(34, 68, 1, 'post'),
(35, 15, 1, 'annotation'),
(36, 60, 1, 'post'),
(37, 59, 1, 'post'),
(38, 11, 1, 'annotation'),
(40, 10, 1, 'annotation'),
(41, 51, 1, 'post'),
(42, 72, 49, 'post'),
(43, 71, 49, 'post'),
(44, 76, 3, 'post'),
(45, 78, 3, 'post');

-- --------------------------------------------------------

--
-- Table structure for table `tb_maincateg_master`
--

CREATE TABLE `tb_maincateg_master` (
  `categid` int(11) NOT NULL,
  `categname` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_maincateg_master`
--

INSERT INTO `tb_maincateg_master` (`categid`, `categname`) VALUES
(1, 'Sports'),
(2, 'Music Instruments'),
(3, 'Martial Arts'),
(4, 'Dancing & Singing Club'),
(5, 'Entertainment'),
(6, 'Student'),
(7, 'Arts'),
(8, 'Science & Technology'),
(9, 'Yoga Meditation Gym'),
(10, 'Toast Masters'),
(11, 'Public Speaking'),
(12, 'Social');

-- --------------------------------------------------------

--
-- Table structure for table `tb_messages`
--

CREATE TABLE `tb_messages` (
  `id` bigint(20) NOT NULL,
  `message_from` bigint(20) NOT NULL,
  `message_to` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `viewed` varchar(1) DEFAULT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_messages`
--

INSERT INTO `tb_messages` (`id`, `message_from`, `message_to`, `message`, `viewed`, `time`) VALUES
(1, 1, 3, 'hi kumaran', '1', 1499240157),
(2, 3, 1, 'hi sharp', '1', 1499240172),
(5, 44, 3, 'y this kolaveri', '1', 1502603246),
(6, 3, 44, 'Summa than da', '1', 1502603292),
(7, 3, 44, 'hi\r\n', '1', 1502873485),
(8, 3, 44, 'hiii\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '1', 1502873499),
(9, 3, 44, 'Hi \r\n', '1', 1504708630),
(10, 3, 1, 'Hi \r\n', '1', 1504708683),
(11, 3, 44, 'Hi', '1', 1504708763),
(12, 3, 44, 'Hi', '1', 1504711975),
(13, 3, 44, 'Hi', '1', 1504792995),
(14, 3, 44, 'Hi', '1', 1504793042),
(15, 3, 1, 'Hi', '1', 1504793077),
(16, 44, 1, 'Hi', '1', 1505481528),
(17, 1, 3, 'hi', '1', 1506057695),
(18, 3, 1, 'hi', '1', 1506057705),
(19, 1, 3, 'hello :)', '1', 1506058976),
(20, 3, 1, 'super', '1', 1506059820),
(21, 1, 3, 'hello', '1', 1506061788),
(22, 1, 44, 'hi', '0', 1506061835),
(23, 1, 3, 'hi sharp how are you', '1', 1506062797),
(24, 1, 3, '8|', '1', 1506062806),
(25, 3, 1, 'hi kumaran g:', '1', 1506062848),
(26, 3, 1, 'hi', '1', 1506063102),
(27, 1, 3, 'hi', '1', 1506063246),
(28, 3, 1, 'hello', '1', 1506063268),
(29, 3, 1, 'had a lunch?????', '1', 1506064047),
(30, 1, 3, 'yes u', '1', 1506064089),
(31, 3, 1, 'hello', '1', 1506064203),
(32, 3, 1, 'hi', '1', 1506064206),
(33, 1, 3, 'hi', '1', 1506064280),
(34, 1, 3, 'hmm', '1', 1506064435),
(35, 1, 3, 'man', '1', 1506064492),
(36, 3, 1, 'what', '1', 1506064517),
(37, 1, 3, 'nothing', '1', 1506065196),
(38, 1, 3, 'what bro', '1', 1506065235),
(39, 3, 1, 'just for lol', '1', 1506065264),
(40, 1, 3, 'hahaha', '1', 1506066069),
(41, 1, 3, 'hi', '1', 1506066294),
(42, 3, 1, 'lol', '1', 1506066540),
(43, 49, 3, 'hi', '1', 1506073886),
(44, 3, 49, 'hi', '1', 1506073908),
(45, 3, 49, 'kojik', '1', 1506073928);

-- --------------------------------------------------------

--
-- Table structure for table `tb_notifications`
--

CREATE TABLE `tb_notifications` (
  `guid` bigint(20) NOT NULL,
  `type` text CHARACTER SET latin1 NOT NULL,
  `poster_guid` bigint(20) NOT NULL,
  `owner_guid` bigint(20) NOT NULL,
  `subject_guid` bigint(20) NOT NULL,
  `viewed` varchar(1) DEFAULT NULL,
  `time_created` int(11) NOT NULL,
  `item_guid` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_notifications`
--

INSERT INTO `tb_notifications` (`guid`, `type`, `poster_guid`, `owner_guid`, `subject_guid`, `viewed`, `time_created`, `item_guid`) VALUES
(1, 'comments:post', 3, 1, 3, NULL, 1501680862, 2),
(3, 'like:entity:file:profile:cover', 3, 45, 180, '', 1502561591, 180),
(14, 'like:entity:file:profile:cover', 47, 45, 253, NULL, 1503986351, 253),
(15, 'comments:entity:file:profile:cover', 47, 45, 253, NULL, 1503986422, 7),
(6, 'like:entity:file:profile:photo', 44, 3, 192, '', 1502563749, 192),
(7, 'like:post', 3, 44, 34, '', 1502603411, 34),
(8, 'like:post', 3, 44, 35, '', 1502603413, 35),
(9, 'like:entity:file:profile:photo', 3, 44, 208, '', 1502603445, 208),
(10, 'comments:entity:file:profile:photo', 3, 44, 208, '', 1502603477, 4),
(11, 'comments:entity:file:profile:photo', 3, 44, 208, '', 1502603527, 5),
(12, 'comments:post', 3, 44, 35, '', 1502603734, 6),
(13, 'group:joinrequest', 3, 1, 27, '', 1502859009, 0),
(16, 'comments:entity:file:profile:cover', 47, 45, 253, NULL, 1503986434, 8),
(18, 'like:post', 3, 44, 46, '', 1504508297, 46),
(19, 'comments:post', 3, 44, 46, '', 1504508309, 9),
(22, 'like:post', 3, 47, 58, NULL, 1504698950, 58),
(23, 'comments:post', 3, 44, 55, '', 1504709907, 10),
(24, 'like:post', 3, 44, 54, '', 1504794964, 54),
(25, 'like:post', 3, 44, 53, '', 1504794968, 53),
(26, 'like:post', 44, 47, 58, NULL, 1505116705, 58),
(27, 'comments:post', 44, 3, 60, '', 1505116818, 11),
(28, 'comments:post', 1, 3, 60, '', 1505117066, 12),
(29, 'comments:post', 1, 44, 60, '', 1505117066, 12),
(30, 'comments:post', 1, 3, 60, '', 1505117077, 13),
(31, 'comments:post', 1, 44, 60, '', 1505117077, 13),
(32, 'like:post', 47, 3, 60, '', 1505144165, 60),
(33, 'comments:post', 47, 3, 60, '', 1505144181, 14),
(34, 'comments:post', 47, 44, 60, '', 1505144181, 14),
(35, 'comments:post', 47, 1, 60, NULL, 1505144181, 14),
(36, 'like:annotation', 47, 1, 60, NULL, 1505144186, 13),
(37, 'comments:post', 3, 1, 63, NULL, 1505283557, 15),
(38, 'like:annotation', 44, 47, 60, NULL, 1505379856, 14),
(39, 'comments:post', 44, 3, 60, '', 1505379949, 16),
(40, 'comments:post', 44, 1, 60, NULL, 1505379949, 16),
(41, 'comments:post', 44, 47, 60, NULL, 1505379949, 16),
(42, 'like:annotation', 3, 44, 60, '', 1505380037, 16),
(43, 'like:post', 3, 44, 55, NULL, 1505569591, 55),
(44, 'like:post', 3, 44, 68, NULL, 1505569597, 68),
(46, 'like:post', 3, 1, 63, '', 1505569616, 63),
(47, 'comments:post', 47, 44, 54, NULL, 1505812120, 18),
(48, 'like:post', 47, 44, 55, NULL, 1505973871, 55),
(49, 'like:post', 1, 44, 68, NULL, 1506060717, 68),
(50, 'like:annotation', 1, 3, 63, '', 1506060722, 15),
(51, 'like:post', 1, 3, 60, '', 1506060726, 60),
(52, 'like:post', 1, 3, 59, '', 1506060734, 59),
(53, 'like:annotation', 1, 44, 60, NULL, 1506061017, 11),
(54, 'like:annotation', 1, 3, 55, '', 1506061032, 10),
(55, 'comments:post', 1, 3, 60, '', 1506063463, 19),
(56, 'comments:post', 1, 44, 60, NULL, 1506063463, 19),
(57, 'comments:post', 1, 47, 60, NULL, 1506063463, 19),
(58, 'like:post', 1, 3, 51, '', 1506064169, 51),
(59, 'comments:post', 1, 3, 51, '', 1506064182, 20),
(60, 'like:post', 49, 47, 71, NULL, 1506073517, 71),
(61, 'like:post', 3, 49, 76, NULL, 1506075775, 76),
(62, 'like:post', 3, 52, 78, '', 1506076221, 78),
(63, 'group:joinrequest', 52, 1, 27, '', 1506076282, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_object`
--

CREATE TABLE `tb_object` (
  `guid` bigint(20) NOT NULL,
  `owner_guid` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `time_created` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` longtext NOT NULL,
  `subtype` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `subcategory` varchar(1000) NOT NULL,
  `city` varchar(300) NOT NULL,
  `area` varchar(300) NOT NULL,
  `state` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_object`
--

INSERT INTO `tb_object` (`guid`, `owner_guid`, `type`, `time_created`, `title`, `description`, `subtype`, `category`, `subcategory`, `city`, `area`, `state`) VALUES
(1, 1, 'user', 1498905871, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(47, 45, 'user', 1502873178, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(3, 1, 'user', 1501237755, '', '{\"post\":\"hi\"}', 'wall', '', '', '', '', ''),
(4, 3, 'user', 1501246821, 'test group', '', 'ossngroup', '', '', '', '', ''),
(5, 1, 'user', 1501250424, 'test', '', 'ossngroup', '', '', '', '', ''),
(6, 1, 'user', 1501250511, 'student', '', 'ossngroup', '', '', '', '', ''),
(7, 1, 'user', 1501250566, 'pro', '', 'ossngroup', '', '', '', '', ''),
(8, 1, 'user', 1501496622, 'stu', 'Stu', 'ossngroup', 'Student', '', '', '', ''),
(28, 3, 'user', 1502550269, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(31, 45, 'user', 1502554178, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(29, 3, 'user', 1502550277, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(15, 3, 'user', 1501680612, 'Testgrup1', 'Testgrup1', 'ossngroup', 'Student', '', '', '', ''),
(16, 3, 'user', 1501680961, '', '{\"post\":\"Hai\",\"location\":\"Pazhavanthangal, Chennai, Tamil Nadu, India\"}', 'wall', '', '', '', '', ''),
(17, 3, 'user', 1502280117, 'test', 'test multiselect', 'ossngroup', '', '', '', '', ''),
(18, 3, 'user', 1502280312, 'test1', 'test multiselect1', 'ossngroup', 'Array', '', '', '', ''),
(19, 1, 'user', 1502347679, 'Entertainment', 'Entertainment', 'ossngroup', 'Entertainment', '', '', '', ''),
(20, 1, 'user', 1502347872, 'Entertainment1', 'Entertainment1', 'ossngroup', 'Entertainment', '', '', '', ''),
(21, 1, 'user', 1502347964, 'Entertainment2', 'Entertainment2', 'ossngroup', 'Entertainment', '', '', '', ''),
(22, 1, 'user', 1502348206, 'Entertainment3', 'Entertainment3', 'ossngroup', 'Entertainment', '', '', '', ''),
(23, 1, 'user', 1502348435, 'Entertainment4', 'Entertainment4', 'ossngroup', 'Entertainment', '', '', '', ''),
(24, 1, 'user', 1502348486, 'Entertainment5', 'Entertainment5', 'ossngroup', 'Entertainment', '', '', '', ''),
(25, 1, 'user', 1502348613, 'Entertainment6', 'Entertainment6', 'ossngroup', 'Entertainment', '', '', '', ''),
(26, 1, 'user', 1502348709, 'Entertainment7', 'Entertainment7', 'ossngroup', 'Entertainment', '', '', '', ''),
(27, 1, 'user', 1502548183, 'Ignovate Club', 'Leading club in the world', 'ossngroup', 'Sports', 'Cricket,Foot Ball,Hockey,Tennis', 'Chennai', 'Nanganallur', 'Tamilnadu'),
(32, 3, 'user', 1502562632, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(33, 3, 'user', 1502603043, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(34, 44, 'user', 1502603168, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(35, 44, 'user', 1502603343, '', '{\"post\":\"Happy Krishna Jayanthi !!!\",\"friend\":\"3\",\"location\":\"Pazhavanthangal, Chennai, Tamil Nadu, India\"}', 'wall', '', '', '', '', ''),
(40, 45, 'user', 1502866618, 'smiley', 'smiley', 'ossngroup', 'Sports', 'Archery', 'chennai', 'velachery', 'tamil nadu'),
(37, 44, 'user', 1502603711, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(39, 44, 'user', 1502603786, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(41, 45, 'user', 1502867145, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(42, 45, 'user', 1502867328, 'Black and White', 'Black and White', 'ossngroup', 'Sports', 'Hockey', 'Guwahati', 'Guwahati', 'Assam'),
(43, 44, 'user', 1502867405, 'ABC', 'Dance', 'ossngroup', '', 'Hockey,Kabbadi', '', '', ''),
(44, 45, 'user', 1502867580, 'Green Crew', 'Green Crew', 'ossngroup', 'Sports', 'Athletics', 'Ahmedabad', 'Ahmedabad', 'Gujarat'),
(45, 45, 'user', 1502868016, 'Blackshirts', 'Blackshirts', 'ossngroup', 'Sports', 'Kabbadi', 'kochi', 'kochi', 'kerala'),
(46, 44, 'user', 1502868747, '', '{\"post\":\"Hi.\"}', 'wall', '', '', '', '', ''),
(50, 47, 'user', 1503985930, '', '{\"post\":\"Hi... &amp;#x1f602; &amp;#x1f603; &amp;#x1f601;\"}', 'wall', '', '', '', '', ''),
(49, 44, 'user', 1503050450, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(51, 3, 'user', 1504360638, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(53, 44, 'user', 1504523623, '', '{\"post\":\"Hi\"}', 'wall', '', '', '', '', ''),
(54, 44, 'user', 1504527934, '', '{\"post\":\"Keykeykey\"}', 'wall', '', '', '', '', ''),
(55, 44, 'user', 1504594471, '', '{\"post\":\"Hello all\"}', 'wall', '', '', '', '', ''),
(56, 44, 'user', 1504681451, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(63, 1, 'user', 1505209542, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(58, 47, 'user', 1504694833, '', '{\"post\":\"Hi..\"}', 'wall', '', '', '', '', ''),
(59, 3, 'user', 1504698879, '', '{\"post\":\"Hi this is a test post.\"}', 'wall', '', '', '', '', ''),
(60, 3, 'user', 1504698921, '', '{\"post\":\"Image test\"}', 'wall', '', '', '', '', ''),
(61, 27, 'group', 1504699010, '', '{\"post\":\"Hi this is the test post in club\"}', 'wall', '', '', '', '', ''),
(62, 3, 'user', 1504699085, 'Test club', 'Test club desc', 'ossngroup', 'Sports,Music Instruments', 'Archery,Badminton,Basket Ball,Cricket', 'Chennai', 'Nanganallur', 'Tamilnadu'),
(64, 44, 'user', 1505217715, 'Trinity Sports Club', 'All sports activities done here', 'ossngroup', 'Sports', 'Archery,Athletics,Badminton,Basket Ball,Carroms,Chess,Cricket,Cycling,Foot Ball,Gymnastics,Hockey,Kabbadi,Motorbike,Other Sports,Running,Snooker/Billiards,Swimming,Table Tennis,Tennis,Volley ball', 'Chennai', 'Nanganallur', 'Tamilnadu'),
(71, 47, 'user', 1505893181, '', '{\"post\":\"Gm..\"}', 'wall', '', '', '', '', ''),
(68, 44, 'user', 1505396154, '', '{\"post\":\"Hi\",\"friend\":\"3\"}', 'wall', '', '', '', '', ''),
(72, 49, 'user', 1506073496, '', '{\"post\":\"hi\"}', 'wall', '', '', '', '', ''),
(77, 3, 'user', 1506075751, '', '{\"post\":\"hi suresh\"}', 'wall', '', '', '', '', ''),
(74, 49, 'user', 1506073681, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(75, 49, 'user', 1506074186, '', '{\"post\":\"hi\\r\\n\"}', 'wall', '', '', '', '', ''),
(76, 49, 'user', 1506074217, '', '{\"post\":\"hi\"}', 'wall', '', '', '', '', ''),
(78, 52, 'user', 1506075951, '', '{\"post\":\"hello all\"}', 'wall', '', '', '', '', ''),
(79, 52, 'user', 1506076487, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(80, 49, 'user', 1506076692, '', '{\"post\":\"null:data\"}', 'wall', '', '', '', '', ''),
(81, 1, 'user', 1506148699, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(82, 1, 'user', 1506148715, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(83, 1, 'user', 1506149819, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(101, 1, 'user', 1506405084, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(135, 3, 'user', 1507289886, '', '{\"post\":\"hi\"}', 'wall', '', '', '', '', ''),
(136, 3, 'user', 1507289913, '', '{\"post\":\"&amp;#x1f602;\"}', 'wall', '', '', '', '', ''),
(137, 3, 'user', 1507289946, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(138, 3, 'user', 1507289990, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(111, 3, 'user', 1506407503, '', '{\"post\":\"http://gph.to/2wi8F5J\"}', 'wall', '', '', '', '', ''),
(114, 3, 'user', 1506407619, '', '{\"post\":\"https://media.giphy.com/media/xThuW2Vrx2ruC42Dcc/giphy.gif\"}', 'wall', '', '', '', '', ''),
(116, 3, 'user', 1506408650, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(119, 3, 'user', 1506409166, '', '{\"post\":\"\"}', 'wall', '', '', '', '', ''),
(126, 3, 'user', 1506412119, '', '{\"post\":\"fggdf\",\"friend\":\"1\"}', 'wall', '', '', '', '', ''),
(133, 27, 'group', 1506419568, '', '{\"post\":\"\"}', 'wall', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_relationships`
--

CREATE TABLE `tb_relationships` (
  `relation_id` bigint(20) NOT NULL,
  `relation_from` bigint(20) NOT NULL,
  `relation_to` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_relationships`
--

INSERT INTO `tb_relationships` (`relation_id`, `relation_from`, `relation_to`, `type`, `time`) VALUES
(7, 4, 3, 'group:join:approve', 1501246821),
(6, 3, 4, 'group:join', 1501246821),
(8, 1, 5, 'group:join', 1501250424),
(9, 5, 1, 'group:join:approve', 1501250424),
(10, 1, 6, 'group:join', 1501250511),
(11, 6, 1, 'group:join:approve', 1501250511),
(12, 1, 7, 'group:join', 1501250566),
(13, 7, 1, 'group:join:approve', 1501250566),
(14, 1, 3, 'friend:request', 1501250633),
(15, 3, 1, 'friend:request', 1501250680),
(16, 1, 8, 'group:join', 1501496622),
(17, 8, 1, 'group:join:approve', 1501496622),
(28, 3, 15, 'group:join', 1501680612),
(29, 15, 3, 'group:join:approve', 1501680612),
(34, 3, 17, 'group:join', 1502280117),
(35, 17, 3, 'group:join:approve', 1502280117),
(36, 3, 18, 'group:join', 1502280312),
(37, 18, 3, 'group:join:approve', 1502280312),
(38, 1, 19, 'group:join', 1502347679),
(39, 19, 1, 'group:join:approve', 1502347679),
(40, 1, 20, 'group:join', 1502347872),
(41, 20, 1, 'group:join:approve', 1502347872),
(42, 1, 21, 'group:join', 1502347964),
(43, 21, 1, 'group:join:approve', 1502347964),
(44, 1, 22, 'group:join', 1502348206),
(45, 22, 1, 'group:join:approve', 1502348206),
(46, 1, 23, 'group:join', 1502348435),
(47, 23, 1, 'group:join:approve', 1502348435),
(48, 1, 24, 'group:join', 1502348486),
(49, 24, 1, 'group:join:approve', 1502348486),
(50, 1, 25, 'group:join', 1502348613),
(51, 25, 1, 'group:join:approve', 1502348613),
(52, 1, 26, 'group:join', 1502348709),
(53, 26, 1, 'group:join:approve', 1502348709),
(54, 1, 27, 'group:join', 1502548184),
(55, 27, 1, 'group:join:approve', 1502548184),
(56, 44, 3, 'friend:request', 1502563778),
(57, 3, 44, 'friend:request', 1502602917),
(58, 3, 27, 'group:join', 1502859009),
(59, 27, 3, 'group:join:approve', 1502859045),
(60, 45, 40, 'group:join', 1502866618),
(61, 40, 45, 'group:join:approve', 1502866618),
(62, 45, 42, 'group:join', 1502867328),
(63, 42, 45, 'group:join:approve', 1502867328),
(64, 44, 43, 'group:join', 1502867405),
(65, 43, 44, 'group:join:approve', 1502867405),
(66, 45, 44, 'group:join', 1502867580),
(67, 44, 45, 'group:join:approve', 1502867580),
(68, 45, 45, 'group:join', 1502868016),
(69, 45, 45, 'group:join:approve', 1502868016),
(70, 3, 62, 'group:join', 1504699085),
(71, 62, 3, 'group:join:approve', 1504699085),
(72, 44, 64, 'group:join', 1505217715),
(73, 64, 44, 'group:join:approve', 1505217715),
(74, 44, 1, 'friend:request', 1505481355),
(75, 1, 44, 'friend:request', 1505481398),
(76, 49, 1, 'friend:request', 1506073754),
(77, 49, 3, 'friend:request', 1506073785),
(78, 3, 49, 'friend:request', 1506073794),
(79, 52, 3, 'friend:request', 1506075907),
(80, 3, 52, 'friend:request', 1506075924),
(81, 52, 27, 'group:join', 1506076282),
(82, 1, 49, 'friend:request', 1506076391),
(83, 27, 52, 'group:join:approve', 1506076408);

-- --------------------------------------------------------

--
-- Table structure for table `tb_site_settings`
--

CREATE TABLE `tb_site_settings` (
  `setting_id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_site_settings`
--

INSERT INTO `tb_site_settings` (`setting_id`, `name`, `value`) VALUES
(1, 'theme', 'goblue'),
(2, 'site_name', 'Talentbook'),
(3, 'language', 'en'),
(4, 'cache', '0'),
(5, 'owner_email', 'ignovatesharp@gmail.com'),
(6, 'notification_email', 'ignovatesharp@gmail.com'),
(7, 'upgrades', '[\"1410545706.php\",\"1411396351.php\", \"1412353569.php\",\"1415553653.php\",\"1415819862.php\", \"1423419053.php\", \"1423419054.php\", \"1439295894.php\", \"1440716428.php\", \"1440867331.php\", \"1440603377.php\", \"1443202118.php\", \"1443211017.php\", \"1443545762.php\", \"1443617470.php\", \"1446311454.php\", \"1448807613.php\", \"1453676400.php\", \"1459411815.php\", \"1468010638.php\", \"1470127853.php\", \"1480759958.php\", \"1495366993.php\"]'),
(9, 'display_errors', 'on'),
(10, 'site_key', 'fa76a623'),
(11, 'last_cache', '0'),
(12, 'site_version', '4.4');

-- --------------------------------------------------------

--
-- Table structure for table `tb_subcateg_master`
--

CREATE TABLE `tb_subcateg_master` (
  `subcategid` int(11) NOT NULL,
  `categid` int(11) NOT NULL,
  `subcategname` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_subcateg_master`
--

INSERT INTO `tb_subcateg_master` (`subcategid`, `categid`, `subcategname`) VALUES
(1, 1, 'Archery'),
(2, 1, 'Athletics'),
(3, 1, 'Badminton'),
(4, 1, 'Basket Ball'),
(5, 1, 'Carroms'),
(6, 1, 'Chess'),
(7, 1, 'Cricket'),
(8, 1, 'Cycling'),
(9, 1, 'Foot Ball'),
(10, 1, 'Gymnastics'),
(11, 1, 'Hockey'),
(12, 1, 'Kabbadi'),
(13, 1, 'Motorbike'),
(14, 1, 'Other Sports'),
(15, 1, 'Running'),
(16, 1, 'Snooker/Billiards'),
(17, 1, 'Swimming'),
(18, 1, 'Table Tennis'),
(19, 1, 'Tennis'),
(20, 1, 'Volley ball'),
(21, 2, 'Bells'),
(22, 2, 'Tuba'),
(23, 2, 'Cello'),
(24, 2, 'Viola'),
(25, 2, 'Trumpet'),
(26, 2, 'Sopranino'),
(27, 2, 'Trombone'),
(28, 2, 'Bugle'),
(29, 2, 'Drums'),
(30, 2, 'Xylophone'),
(31, 2, 'Violin'),
(32, 2, 'Dulcimer'),
(33, 2, 'Flute'),
(34, 2, 'TamTam'),
(35, 2, 'Bass'),
(36, 2, 'Alto'),
(37, 2, 'Harp'),
(38, 2, 'Triangle'),
(39, 2, 'French Horn'),
(40, 2, 'Others Instruments'),
(41, 3, 'Boxing'),
(42, 3, 'Gatka'),
(43, 3, 'Kathi Samu'),
(44, 3, 'Kick boxing'),
(45, 3, 'Kung Fu'),
(46, 3, 'Kalaripayattu'),
(47, 3, 'Lathi Khela'),
(48, 3, 'Malla Yuddha'),
(49, 3, 'Mardani Khel  '),
(50, 3, 'Mixed Martial Arts'),
(51, 3, 'Musti Yuddha'),
(52, 3, 'Pari Khanda'),
(53, 3, 'Shaolin Kung Fu'),
(54, 3, 'Silambam'),
(55, 3, 'Taekwondo'),
(56, 3, 'Thang Ta'),
(57, 3, 'Wrestling'),
(58, 3, 'Xtreme Martial Arts'),
(59, 3, 'Zulu Stick fighting'),
(60, 3, 'Womens Self Defense'),
(61, 3, 'Other Marial Arts'),
(62, 4, 'Disco Dance'),
(63, 4, 'Moonwalk'),
(64, 4, 'Tango '),
(65, 4, 'Rumba'),
(66, 4, 'Samba'),
(67, 4, 'Salsa '),
(68, 4, 'Western Dance'),
(69, 4, 'Belly Dance'),
(70, 4, 'Tap Dance '),
(71, 4, 'Break Dance'),
(72, 4, 'Bollywood Dance'),
(73, 4, 'Bharatanatyam'),
(74, 4, 'Kathakali'),
(75, 4, 'Kathak'),
(76, 4, 'Kuchipudi'),
(77, 4, 'Odissi'),
(78, 4, 'Manipuri'),
(79, 4, 'Folk Tribal Dance'),
(80, 4, 'Singing'),
(81, 4, 'Other Dance Singing Clubs'),
(82, 5, 'Laughing Clubs'),
(83, 5, 'Short Films'),
(84, 5, 'Crazy Videos'),
(85, 5, 'Stunts'),
(86, 5, 'Adventure'),
(87, 5, 'Other Entertainments'),
(88, 6, 'Academics'),
(89, 6, 'College Club_PG'),
(90, 6, 'College Club_UG'),
(91, 6, 'School Club'),
(92, 6, 'Special Purpose'),
(93, 6, 'Special Skill'),
(94, 6, 'Others'),
(95, 7, 'Painting'),
(96, 7, 'Photography'),
(97, 7, 'Cooking'),
(98, 8, 'Automobile'),
(99, 8, 'Innovation'),
(100, 8, 'Research'),
(101, 8, 'Projects'),
(102, 8, 'Agriculture'),
(103, 8, 'Medical'),
(104, 8, 'Others'),
(105, 9, 'Gyms'),
(106, 9, 'Yoga'),
(107, 9, 'Meditation'),
(108, 10, 'Toast Masters'),
(109, 10, 'Others'),
(110, 11, 'Public Speaking'),
(111, 11, 'Others'),
(112, 12, 'Ashrams'),
(113, 12, 'Animal Welfare Clubs'),
(114, 12, 'Old Age Homes'),
(115, 12, 'Orphanages'),
(116, 12, 'Hygiene Awareness Clubs'),
(117, 12, 'Culture Protection Clubs'),
(118, 12, 'Health Clubs'),
(119, 12, 'Others Social Clubs');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `guid` bigint(20) NOT NULL,
  `type` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `salt` varchar(8) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `activation` text DEFAULT NULL,
  `time_created` int(11) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `college` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `work` varchar(1000) NOT NULL,
  `professionalskill` varchar(1000) NOT NULL,
  `school` varchar(500) NOT NULL,
  `othermobile` bigint(20) NOT NULL,
  `aboutyou` varchar(1000) NOT NULL,
  `nickname` varchar(300) NOT NULL,
  `favquotes` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`guid`, `type`, `username`, `email`, `password`, `salt`, `first_name`, `last_name`, `last_login`, `last_activity`, `activation`, `time_created`, `mobile`, `college`, `location`, `description`, `work`, `professionalskill`, `school`, `othermobile`, `aboutyou`, `nickname`, `favquotes`) VALUES
(1, 'admin', 'admin', 'ignovatesharp@gmail.com', 'e742d3073eae3aca306af258a9180c19', '6dade4c0', 'sharp', 'sundar', 1511242281, 1511242353, '', 1498822362, 9874563210, '', 'Chennai', 'Quick learner , eager to learn and teach', '', '', '', 0, '', '', ''),
(3, 'admin', 'kumaran', 'kumaranignovate@gmail.com', 'a8f627aef29549dafa19a2bc46538e9b', '9d676278', 'kumaran', 'elumalai', 1506407919, 1506434086, '', 2147483647, 1499064791, 'Guru Nanak College', 'Chennai, Tamil Nadu, India', 'Quick Learner', 'iGnovate Solutions', 'Php', 'T.Nagar Higher Secondary School', 9876543210, 'Single', 'kumara', 'Do Before Die'),
(44, 'normal', 'suryanand', 'suryanand000@gmail.com', '198c26bee3b9e65235683bae41fec47b', '1a362e03', 'Suryanand', 'Premkumar', 1505547169, 1505550076, '', 1502551008, 9876543210, 'GNC', 'Nanganallur, Chennai, Tamil Nadu, India', 'Java, Php, MySql, Python, odOo, UI &amp; UX\r\nAjax, Jquery, Javascript', 'iGnovate Solution', 'Php, Java', 'Nehru', 9874563210, 'Developer', '', 'Poda Andavane en pakkam irukan'),
(45, 'normal', 'veeraiyan', 'veeraiyan1990@gmail.com', 'd69c2a38c17ed9f390aefec388094268', '79cebcc6', 'Veeraiyan', 'Paramasivam', 1502873421, 1502875307, '', 1502554012, 7894561230, '', 'Chennai, Tamil Nadu, India', '', '', '', '', 0, '', '', ''),
(46, 'normal', 'kumarane', 'elumalai.kumaran14@gmail.com', '66d67f7ba208dec3ee3b4d8847d1c015', '66a9ecfd', 'kumarane', '', 1502557885, 1502558606, '', 1502557802, 0, '', '', '', '', '', '', 0, '', '', ''),
(47, 'normal', 'Raghavendra', 'N.rajesh256@gmail.com', '5fcc9e40096116791f6c9160c2e6da78', 'b4a07565', 'Raghavendra', '', 1505976264, 1505996166, '', 1503984458, 0, '', '', '', '', '', '', 0, '', '', ''),
(48, 'normal', 'Manikatti', 'manikatti90@gmail.com', '84d43edabdd19a35577c6da684c0bb4e', '472aaf0c', 'Manikatti', '', 0, 0, 'f12a717f7240844009e4c81cffe208be', 1504523378, 0, '', '', '', '', '', '', 0, '', '', ''),
(49, 'normal', 'suresh', 'sureshrajendren12297@gmail.com', '5d3fd9d82ccd084f15efca346ede000a', '884e3d67', 'suresh', 'rajenderan', 1506076612, 1506078270, '', 1504689939, 0, '', 'Chennai, Tamil Nadu, India', '', '', '', '', 0, '', '', ''),
(51, 'normal', 'suresh001', 'suresh@gmail.com', 'db39941c9f8bde7784dc3d59338a67dd', 'b122090b', 'suresh001', '', 0, 0, '', 1506073362, 0, '', '', '', '', '', '', 0, '', '', ''),
(52, 'normal', 'sureshrj', 'djshfaj@gmail.com', 'b97f192d87ad8a6e7a2aa9204396e5a0', '012dc1db', 'sureshrj', '', 1506076237, 1506076515, '', 1506074642, 0, '', '', '', '', '', '', 0, '', '', ''),
(53, '', '8928085887', '', 'd689df09def0c97fe0d3e2f9330fbbfb', '0703f365', 'jj', 'kk', 0, 0, '', 1612517489, 0, '', '', '', '', '', '', 0, '', '', ''),
(54, '', '9111165131', '', 'bbbbe2ed925637e3ac9f4d8e45251a29', 'a536cf59', 'prachi', 'lalwani', 0, 0, '', 1612524115, 9111165131, '', '', '', '', '', '', 0, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `k12testdata`
--
ALTER TABLE `k12testdata`
  ADD PRIMARY KEY (`guid`),
  ADD KEY `last_login` (`last_login`),
  ADD KEY `last_activity` (`last_activity`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `type` (`type`(1024)),
  ADD KEY `email` (`email`(1024)),
  ADD KEY `first_name` (`first_name`(1024)),
  ADD KEY `last_name` (`last_name`(1024));

--
-- Indexes for table `tb_addons`
--
ALTER TABLE `tb_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_annotations`
--
ALTER TABLE `tb_annotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_guid` (`owner_guid`),
  ADD KEY `subject_guid` (`subject_guid`),
  ADD KEY `time_created` (`time_created`);
ALTER TABLE `tb_annotations` ADD FULLTEXT KEY `type` (`type`);

--
-- Indexes for table `tb_components`
--
ALTER TABLE `tb_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_entities`
--
ALTER TABLE `tb_entities`
  ADD PRIMARY KEY (`guid`),
  ADD KEY `owner_guid` (`owner_guid`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `time_updated` (`time_updated`),
  ADD KEY `active` (`active`),
  ADD KEY `permission` (`permission`);
ALTER TABLE `tb_entities` ADD FULLTEXT KEY `type` (`type`);
ALTER TABLE `tb_entities` ADD FULLTEXT KEY `subtype` (`subtype`);

--
-- Indexes for table `tb_entities_metadata`
--
ALTER TABLE `tb_entities_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guid` (`guid`);
ALTER TABLE `tb_entities_metadata` ADD FULLTEXT KEY `value` (`value`);

--
-- Indexes for table `tb_likes`
--
ALTER TABLE `tb_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_maincateg_master`
--
ALTER TABLE `tb_maincateg_master`
  ADD PRIMARY KEY (`categid`);

--
-- Indexes for table `tb_messages`
--
ALTER TABLE `tb_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_notifications`
--
ALTER TABLE `tb_notifications`
  ADD PRIMARY KEY (`guid`),
  ADD KEY `poster_guid` (`poster_guid`),
  ADD KEY `owner_guid` (`owner_guid`),
  ADD KEY `subject_guid` (`subject_guid`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `item_guid` (`item_guid`);
ALTER TABLE `tb_notifications` ADD FULLTEXT KEY `type` (`type`);

--
-- Indexes for table `tb_object`
--
ALTER TABLE `tb_object`
  ADD PRIMARY KEY (`guid`),
  ADD KEY `owner_guid` (`owner_guid`),
  ADD KEY `time_created` (`time_created`);
ALTER TABLE `tb_object` ADD FULLTEXT KEY `type` (`type`);
ALTER TABLE `tb_object` ADD FULLTEXT KEY `subtype` (`subtype`);

--
-- Indexes for table `tb_relationships`
--
ALTER TABLE `tb_relationships`
  ADD PRIMARY KEY (`relation_id`),
  ADD KEY `relation_to` (`relation_to`),
  ADD KEY `relation_from` (`relation_from`),
  ADD KEY `time` (`time`);
ALTER TABLE `tb_relationships` ADD FULLTEXT KEY `type` (`type`);

--
-- Indexes for table `tb_site_settings`
--
ALTER TABLE `tb_site_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `tb_subcateg_master`
--
ALTER TABLE `tb_subcateg_master`
  ADD PRIMARY KEY (`subcategid`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`guid`),
  ADD KEY `last_login` (`last_login`),
  ADD KEY `last_activity` (`last_activity`),
  ADD KEY `time_created` (`time_created`);
ALTER TABLE `tb_users` ADD FULLTEXT KEY `type` (`type`);
ALTER TABLE `tb_users` ADD FULLTEXT KEY `email` (`email`);
ALTER TABLE `tb_users` ADD FULLTEXT KEY `first_name` (`first_name`);
ALTER TABLE `tb_users` ADD FULLTEXT KEY `last_name` (`last_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `k12testdata`
--
ALTER TABLE `k12testdata`
  MODIFY `guid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_addons`
--
ALTER TABLE `tb_addons`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_annotations`
--
ALTER TABLE `tb_annotations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_components`
--
ALTER TABLE `tb_components`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tb_entities`
--
ALTER TABLE `tb_entities`
  MODIFY `guid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=538;

--
-- AUTO_INCREMENT for table `tb_entities_metadata`
--
ALTER TABLE `tb_entities_metadata`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=535;

--
-- AUTO_INCREMENT for table `tb_likes`
--
ALTER TABLE `tb_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tb_maincateg_master`
--
ALTER TABLE `tb_maincateg_master`
  MODIFY `categid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_messages`
--
ALTER TABLE `tb_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tb_notifications`
--
ALTER TABLE `tb_notifications`
  MODIFY `guid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_object`
--
ALTER TABLE `tb_object`
  MODIFY `guid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `tb_relationships`
--
ALTER TABLE `tb_relationships`
  MODIFY `relation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `tb_site_settings`
--
ALTER TABLE `tb_site_settings`
  MODIFY `setting_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_subcateg_master`
--
ALTER TABLE `tb_subcateg_master`
  MODIFY `subcategid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `guid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
