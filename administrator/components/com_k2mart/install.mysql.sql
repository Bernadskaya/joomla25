CREATE TABLE IF NOT EXISTS `#__k2mart` (
  `baseID` int(11) NOT NULL,
  `referenceID` int(11) NOT NULL,
  PRIMARY KEY (`baseID`),
  KEY `referenceID` (`referenceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;