
DROP TABLE IF EXISTS `user_company`;
CREATE TABLE  `user_company` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `idUser` int(10) unsigned NOT NULL,
  `company` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

insert into user_company values (0,1,"ALL");
insert into user_company values (0,2,"ALL");
insert into user_company values (0, 6, "CHILENA CONSOLIDADA SEGUROS GRLES S.A.");
insert into user_company values (0, 7, "CONSORCIO NACIONAL DE SEGUROS S.A.");
insert into user_company values (0, 8, "LIBERTY SEGUROS S.A.");
insert into user_company values (0, 9, "PENTA SECURITY S.A.");
insert into user_company values (0, 10, "RSA SEGUROS GRALES S.A.");
insert into user_company values (0, 11, "ASISTENCIA");
insert into user_company values (0, 12, "ZENIT SEGUROS GENERALES S.A.");
insert into user_company values (0, 15, "AUTORENTAS TATTERSALL LTDA.");
insert into user_company values (0, 16, "ZENIT SEGUROS GENERALES S.A.");
insert into user_company values (0, 16, "BCI SEGUROS GENERALES S.A.");