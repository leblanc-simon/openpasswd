SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE  TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `passwd` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  `last_connection` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `username_UNIQUE` ON `user` (`username` ASC) ;


-- -----------------------------------------------------
-- Table `group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group` ;

CREATE  TABLE IF NOT EXISTS `group` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `group` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `user_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_has_group` ;

CREATE  TABLE IF NOT EXISTS `user_has_group` (
  `user_id` INT UNSIGNED NOT NULL ,
  `group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`user_id`, `group_id`) ,
  CONSTRAINT `fk_user_has_group_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group`
    FOREIGN KEY (`group_id` )
    REFERENCES `group` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_user_has_group_group` ON `user_has_group` (`group_id` ASC) ;

CREATE INDEX `fk_user_has_group_user` ON `user_has_group` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `account_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_type` ;

CREATE  TABLE IF NOT EXISTS `account_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `account_type` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account` ;

CREATE  TABLE IF NOT EXISTS `account` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `account_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_account_account_type`
    FOREIGN KEY (`account_type_id` )
    REFERENCES `account_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `account_name` ON `account` (`name` ASC) ;

CREATE INDEX `fk_account_account_type` ON `account` (`account_type_id` ASC) ;


-- -----------------------------------------------------
-- Table `account_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_has_group` ;

CREATE  TABLE IF NOT EXISTS `account_has_group` (
  `account_id` INT UNSIGNED NOT NULL ,
  `group_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`account_id`, `group_id`) ,
  CONSTRAINT `fk_account_has_group_account`
    FOREIGN KEY (`account_id` )
    REFERENCES `account` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_group_group`
    FOREIGN KEY (`group_id` )
    REFERENCES `group` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_account_has_group_group` ON `account_has_group` (`group_id` ASC) ;

CREATE INDEX `fk_account_has_group_account` ON `account_has_group` (`account_id` ASC) ;


-- -----------------------------------------------------
-- Table `field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `field` ;

CREATE  TABLE IF NOT EXISTS `field` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  `crypt` TINYINT(1) NOT NULL DEFAULT 0 ,
  `type` ENUM('text','textarea','date','numeric','email','url') NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `field` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `account_type_has_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_type_has_field` ;

CREATE  TABLE IF NOT EXISTS `account_type_has_field` (
  `account_type_id` INT UNSIGNED NOT NULL ,
  `field_id` INT UNSIGNED NOT NULL ,
  `position` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`account_type_id`, `field_id`) ,
  CONSTRAINT `fk_account_type_has_field_account_type`
    FOREIGN KEY (`account_type_id` )
    REFERENCES `account_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_type_has_field_field`
    FOREIGN KEY (`field_id` )
    REFERENCES `field` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_account_type_has_field_field` ON `account_type_has_field` (`field_id` ASC) ;

CREATE INDEX `fk_account_type_has_field_account_type` ON `account_type_has_field` (`account_type_id` ASC) ;


-- -----------------------------------------------------
-- Table `account_has_field`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `account_has_field` ;

CREATE  TABLE IF NOT EXISTS `account_has_field` (
  `account_id` INT UNSIGNED NOT NULL ,
  `field_id` INT UNSIGNED NOT NULL ,
  `value` TEXT NULL ,
  PRIMARY KEY (`account_id`, `field_id`) ,
  CONSTRAINT `fk_account_has_field_account`
    FOREIGN KEY (`account_id` )
    REFERENCES `account` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_field_field`
    FOREIGN KEY (`field_id` )
    REFERENCES `field` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_account_has_field_field` ON `account_has_field` (`field_id` ASC) ;

CREATE INDEX `fk_account_has_field_account` ON `account_has_field` (`account_id` ASC) ;

CREATE INDEX `account_has_field_value` ON `account_has_field` (`value` ASC) ;


-- -----------------------------------------------------
-- View `account_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `account_view` ;
DROP TABLE IF EXISTS `account_view`;
DELIMITER $$
CREATE  OR REPLACE VIEW `account_view` AS
    SELECT f.`name` as `name`, f.`description` as `description`, f.`crypt` as `crypt`, f.`type` as `type`, af.`value` as `value`, ag.`group_id` as `group_id`
    FROM `account`
        INNER JOIN `account_has_field` af
            ON `account`.`id` = `account_has_field`.`account_id`
        INNER JOIN `field` f
            ON `account_has_field`.`field_id` = `field`.`id`
        INNER JOIN `account_type_has_field`
            ON `account_type_has_field`.`account_type_id` = `account`.`account_type_id`
                AND `account_type_has_field`.`field_id` = `field`.`id`
        INNER JOIN `account_has_group` ag
            ON `account`.`id` = `account_has_group`.`account_id`
    ORDER BY `account_type_has_field`.`position` ASC

$$
DELIMITER ;

;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
