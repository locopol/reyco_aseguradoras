USE stock_v4;
ALTER TABLE `user` ADD COLUMN `allow_update` BOOLEAN NOT NULL DEFAULT false AFTER `email`;
