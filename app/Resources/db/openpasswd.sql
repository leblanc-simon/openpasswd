/**
* This file is part of the OpenPasswd package.
*
* (c) Simon Leblanc <contact@leblanc-simon.eu>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE  TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `slug` VARCHAR(45) BINARY NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `passwd` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  `last_connection` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `username_unique_idx` (`username` ASC) ,
  UNIQUE INDEX `slug_unique_idx` (`slug` ASC) )
ENGINE = InnoDB;

INSERT INTO `user` (`id`, `slug`, `username`, `passwd`, `name`, `created_at`, `updated_at`, `last_connection`)
    VALUES (1, 'admin', 'admin', '$2y$10$KjO6I7QBSG9zIry8b54ZE.FBENGdSYmF9tPLburNW/KGlqeWQy4h2', 'Administrator', NOW(), NOW(), NOW());


-- -----------------------------------------------------
-- Table `group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group` ;

CREATE  TABLE IF NOT EXISTS `group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `slug` VARCHAR(45) BINARY NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_unique_idx` (`name` ASC) ,
  UNIQUE INDEX `slug_unique_idx` (`slug` ASC) )
ENGINE = InnoDB;

INSERT INTO `group` (`id`, `slug`, `name`, `description`, `created_at`, `updated_at`)
    VALUES (1, 'admin', 'Administrator', NULL, NOW(), NOW());


-- -----------------------------------------------------
-- Table `user_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_has_group` ;

CREATE  TABLE IF NOT EXISTS `user_has_group` (
  `user_id` INT UNSIGNED NOT NULL ,
  `group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`user_id`, `group_id`) ,
  INDEX `fk_user_has_group_group_idx` (`group_id` ASC) ,
  INDEX `fk_user_has_group_user_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_user_has_group_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group`
    FOREIGN KEY (`group_id` )
    REFERENCES `group` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO `user_has_group` (`user_id`, `group_id`)
    VALUES (1, 1);


-- -----------------------------------------------------
-- Table `account_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_type` ;

CREATE  TABLE IF NOT EXISTS `account_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `slug` VARCHAR(45) BINARY NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_unique_idx` (`name` ASC) ,
  UNIQUE INDEX `slug_unique_idx` (`slug` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account` ;

CREATE  TABLE IF NOT EXISTS `account` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `slug` VARCHAR(255) BINARY NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `account_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `account_name` (`name` ASC) ,
  INDEX `fk_account_account_type_idx` (`account_type_id` ASC) ,
  UNIQUE INDEX `slug_unique_idx` (`slug` ASC) ,
  CONSTRAINT `fk_account_account_type`
    FOREIGN KEY (`account_type_id` )
    REFERENCES `account_type` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `account_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_has_group` ;

CREATE  TABLE IF NOT EXISTS `account_has_group` (
  `account_id` INT UNSIGNED NOT NULL ,
  `group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`account_id`, `group_id`) ,
  INDEX `fk_account_has_group_group_idx` (`group_id` ASC) ,
  INDEX `fk_account_has_group_account_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_account_has_group_account`
    FOREIGN KEY (`account_id` )
    REFERENCES `account` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_group_group`
    FOREIGN KEY (`group_id` )
    REFERENCES `group` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `field` ;

CREATE  TABLE IF NOT EXISTS `field` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `slug` VARCHAR(45) BINARY NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  `crypt` TINYINT(1) NOT NULL DEFAULT 0 ,
  `type` ENUM('text','textarea','date','numeric','email','url') NOT NULL ,
  `required` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_unique_idx` (`name` ASC) ,
  UNIQUE INDEX `slug_unique_idx` (`slug` ASC) )
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `account_type_has_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_type_has_field` ;

CREATE  TABLE IF NOT EXISTS `account_type_has_field` (
  `account_type_id` INT UNSIGNED NOT NULL ,
  `field_id` INT UNSIGNED NOT NULL ,
  `position` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`account_type_id`, `field_id`) ,
  INDEX `fk_account_type_has_field_field_idx` (`field_id` ASC) ,
  INDEX `fk_account_type_has_field_account_type_idx` (`account_type_id` ASC) ,
  CONSTRAINT `fk_account_type_has_field_account_type`
    FOREIGN KEY (`account_type_id` )
    REFERENCES `account_type` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_type_has_field_field`
    FOREIGN KEY (`field_id` )
    REFERENCES `field` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `account_has_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_has_field` ;

CREATE  TABLE IF NOT EXISTS `account_has_field` (
  `account_id` INT UNSIGNED NOT NULL ,
  `field_id` INT UNSIGNED NOT NULL ,
  `value` TEXT NULL ,
  PRIMARY KEY (`account_id`, `field_id`) ,
  INDEX `fk_account_has_field_field_idx` (`field_id` ASC) ,
  INDEX `fk_account_has_field_account_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_account_has_field_account`
    FOREIGN KEY (`account_id` )
    REFERENCES `account` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_field_field`
    FOREIGN KEY (`field_id` )
    REFERENCES `field` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `account_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `account_view` (`name` INT, `description` INT, `crypt` INT, `type` INT, `required` INT, `value` INT, `group_id` INT);

-- -----------------------------------------------------
-- View `account_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `account_view` ;
DROP TABLE IF EXISTS `account_view`;
CREATE  OR REPLACE VIEW `account_view` AS
    SELECT f.`name` as `name`, f.`description` as `description`, f.`crypt` as `crypt`, f.`type` as `type`, f.`required` as `required`, af.`value` as `value`, ag.`group_id` as `group_id`
    FROM `account` a
        INNER JOIN `account_has_field` af
            ON a.`id` = af.`account_id`
        INNER JOIN `field` f
            ON af.`field_id` = f.`id`
        INNER JOIN `account_type_has_field`
            ON `account_type_has_field`.`account_type_id` = a.`account_type_id`
                AND `account_type_has_field`.`field_id` = f.`id`
        INNER JOIN `account_has_group` ag
            ON a.`id` = ag.`account_id`
    ORDER BY `account_type_has_field`.`position` ASC
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
