ALTER TABLE stocklist ADD COLUMN `pcircula` VARCHAR(1) NOT NULL AFTER `numfac`;
ALTER TABLE stocklist ADD COLUMN `rtecnica` VARCHAR(1) NOT NULL AFTER `pcircula`;
ALTER TABLE stocklist ADD COLUMN `conllave2` VARCHAR(1) NOT NULL AFTER `rtecnica`;
ALTER TABLE stocklist ADD COLUMN `mandato` VARCHAR(1) NOT NULL AFTER `conllave2`;
ALTER TABLE stocklist ADD COLUMN `ubicafisica` VARCHAR(300) NOT NULL AFTER `mandato`;