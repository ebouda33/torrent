/* 
 * To create DB the first time
 */
/**
 * Author:  xgld8274
 * Created: 24 mars 2017
 */

CREATE TABLE `mytorrent`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(45) NULL,
  `name` VARCHAR(45) NULL,
  `password` VARCHAR(45) NULL,
  `email` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC));


CREATE TABLE `mytorrent`.`log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `action` TEXT NOT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY (`id`));
