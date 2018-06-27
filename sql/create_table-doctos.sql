DROP TABLE IF EXISTS `stock_v4`.`doctos`;
CREATE TABLE  `stock_v4`.`doctos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `idSiniestro` int(10) unsigned NOT NULL,
  `documento` varchar(60) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
);
