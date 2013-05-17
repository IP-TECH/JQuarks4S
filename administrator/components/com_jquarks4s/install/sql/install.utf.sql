-- JQUARKS4S install SQL script


-- --------------------------------------------------------
-- table `#__jquarks4s_analysis_types`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_analysis_types` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DELETE FROM `#__jquarks4s_analysis_types`;

INSERT INTO `#__jquarks4s_analysis_types` (`id`, `title`) VALUES
(1, 'FREQUENCY'),
(2, 'CROSS_TABULATION'),
(3, 'CUSTOM');


-- --------------------------------------------------------
--  table `#__jquarks4s_answers`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `altanswer` text NOT NULL,
  `session_id` int(11) NOT NULL,
  `proposition_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_mat_answers`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_mat_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `row_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_mat_columns`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_mat_columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_mat_rows`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_mat_rows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_propositions`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_propositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposition` varchar(255) NOT NULL,
  `is_text_field` tinyint(1) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_questions`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL DEFAULT 'no_alias',
  `statement` text NOT NULL,
  `nature` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: qualitatif 1:quantitatif',
  `is_compulsory` tinyint(1) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_sections`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_sections_questions`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_sections_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `question_rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_sessions`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `affected_id` int(11) NOT NULL,
  `submit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_snapshot_analysis`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_snapshot_analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  `save_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `analysis_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_snapshot_cross_tab_value`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_snapshot_cross_tab_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `snapshot_proposition1_id` int(11) NOT NULL,
  `snapshot_proposition2_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_snapshot_proposition`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_snapshot_proposition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposition_id` int(11) NOT NULL,
  `proposition` text NOT NULL,
  `sample_size` int(11) NOT NULL,
  `frequency` float NOT NULL,
  `snapshot_question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_snapshot_question`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_snapshot_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `statement` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `nature` tinyint(4) NOT NULL,
  `row_id` int(11) NOT NULL,
  `row_title` varchar(255) NOT NULL,
  `snapshot_analysis_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_snapshot_survey`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_snapshot_survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `nbrQuestions` int(11) NOT NULL,
  `nbrSessions` int(11) NOT NULL,
  `snapshot_analysis_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_surveys`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `footer` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `published_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notify_message` text NOT NULL,
  `unique_session` tinyint(1) NOT NULL DEFAULT '0',
  `access_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

#ALTER TABLE `#__jquarks4s_surveys` ADD `redirect_url` varchar(255) NOT NULL DEFAULT 'index.php?option=com_jquarks4s';


-- --------------------------------------------------------
-- table `#__jquarks4s_surveys_sections`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_surveys_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `section_rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_text_answers`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_text_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answer` text NOT NULL,
  `session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- table `#__jquarks4s_types`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `#__jquarks4s_types`;

INSERT INTO `#__jquarks4s_types` (`id`, `title`) VALUES
(1, 'TEXT_FIELD'),
(2, 'RADIO'),
(3, 'CHECKBOX'),
(4, 'MATRIX');


-- --------------------------------------------------------
-- table `#__jquarks4s_users_surveys`
--
CREATE TABLE IF NOT EXISTS `#__jquarks4s_users_surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

#ALTER TABLE `#__jquarks4s_users_surveys` ADD `is_active` tinyint(1) NOT NULL;