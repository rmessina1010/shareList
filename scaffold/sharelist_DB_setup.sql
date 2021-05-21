/*GLCats*/

CREATE TABLE `GLCats` (
  `GLCID` int(11) NOT NULL AUTO_INCREMENT,
  `GLCName` tinytext NOT NULL,
  `GLCOrd` int(11) NOT NULL,
  `GLCParent` int(11) NOT NULL,
  PRIMARY KEY (`GLCID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*GLItems*/

CREATE TABLE `GLItems` (
  `GLIID` int(11) NOT NULL AUTO_INCREMENT,
  `inGList` tinyint(4) NOT NULL,
  `GLICat` text NOT NULL,
  `ItemName` tinytext NOT NULL,
  `Needed` tinyint(1) NOT NULL,
  `QTY` int(11) NOT NULL,
  `image` mediumtext NOT NULL,
  `notes` mediumtext NOT NULL,
  `GLIOrd` int(11) NOT NULL,
  PRIMARY KEY (`GLIID`)
) ENGINE=MyISAM AUTO_INCREMENT=243 DEFAULT CHARSET=latin1;

/*GLists*/

CREATE TABLE `GLists` (
  `GLID` int(11) NOT NULL AUTO_INCREMENT,
  `GLName` tinytext NOT NULL,
  `GLOwner` tinyint(128) unsigned NOT NULL,
  `GLEditors` text NOT NULL,
  `GLSubs` text NOT NULL,
  PRIMARY KEY (`GLID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*ListOwners*/

CREATE TABLE `ListOwners` (
  `LOID` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
  `LOName` varchar(50) DEFAULT NULL,
  `LOLastName` tinytext NOT NULL,
  `LOEmail` tinytext NOT NULL,
  `LOPassword` varchar(50) DEFAULT NULL,
  `LOPrefs` text NOT NULL,
  PRIMARY KEY (`LOID`),
  UNIQUE KEY `TOName` (`LOName`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*ListOwnersNew*/

CREATE TABLE `ListOwnersNew` (
  `LOID` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
  `LOName` varchar(50) DEFAULT NULL,
  `LOLastName` tinytext NOT NULL,
  `LOEmail` tinytext NOT NULL,
  `LOPassword` varchar(255) DEFAULT NULL,
  `LOPrefs` text NOT NULL,
  PRIMARY KEY (`LOID`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;