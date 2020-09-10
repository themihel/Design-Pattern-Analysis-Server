# Basic table for saving the tracked information
CREATE TABLE `actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `version` tinyint(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;