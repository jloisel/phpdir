SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `phpdir` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `phpdir`;

-- -----------------------------------------------------
-- Table `phpdir`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `phpdir`.`category` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `allow_submit` tinyint(1) NOT NULL default '1',
  `has_picture` enum('0','1') NOT NULL default '0' COMMENT '0=use default picture; 1=use uploaded picture',
  `website_count` int(8) NOT NULL default '0',
  `is_adult` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `phpdir`.`customer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`customer` (
  `id` INT(8) NOT NULL AUTO_INCREMENT ,
  `ip` VARCHAR(15) NOT NULL ,
  `email` VARCHAR(80) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `firstname` VARCHAR(80) NULL ,
  `lastname` VARCHAR(80) NULL ,
  `level` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=webmaster; 1=moderator; 2=administrator' ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `wallet` DECIMAL(8,2) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`bill`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`bill` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ip` VARCHAR(15) NOT NULL ,
  `price` DECIMAL(5,2) NOT NULL DEFAULT 0 ,
  `paid` DECIMAL(5,2) NOT NULL DEFAULT 0 ,
  `currency` CHAR(3) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`website`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`website` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `category_id` INT(11) NOT NULL ,
  `customer_id` INT(8) NOT NULL ,
  `bill_id` INT(11) NOT NULL ,
  `link` VARCHAR(255) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `bold_title` ENUM('0','1') NOT NULL DEFAULT '0' ,
  `subtitle` VARCHAR(80) NULL ,
  `description` TEXT NOT NULL ,
  `magnify` ENUM('0','1') NULL COMMENT 'Display website with bold border' ,
  `backlink` VARCHAR(255) NULL ,
  `country` VARCHAR(2) NOT NULL ,
  `ins` INT(8) NOT NULL DEFAULT 0 ,
  `outs` INT(8) NOT NULL DEFAULT 0 ,
  `priority` TINYINT(1) NOT NULL DEFAULT 0 ,
  `state` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=pending; 1=accepted; 2=banned' ,
  `is_broken` ENUM('0','1') NOT NULL DEFAULT '0' ,
  `has_extended_infos` ENUM('0','1') NOT NULL DEFAULT '0' ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `updated_on` TIMESTAMP NOT NULL ,
  `validated_on` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_website_category` (`category_id` ASC) ,
  INDEX `fk_website_customer` (`customer_id` ASC) ,
  FULLTEXT INDEX `ft_website` USING HASH (`title` ASC, `description` ASC, `link` ASC) ,
  INDEX `fk_website_payment` (`bill_id` ASC) ,
  CONSTRAINT `fk_website_category`
    FOREIGN KEY (`category_id` )
    REFERENCES `phpdir`.`category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_website_customer`
    FOREIGN KEY (`customer_id` )
    REFERENCES `phpdir`.`customer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_website_payment`
    FOREIGN KEY (`bill_id` )
    REFERENCES `phpdir`.`bill` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `phpdir`.`comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NULL ,
  `ip` VARCHAR(15) NOT NULL ,
  `text` VARCHAR(255) NOT NULL ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `is_approved` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_comment_website` (`website_id` ASC) ,
  CONSTRAINT `fk_comment_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`banned_email`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`banned_email` (
  `id` INT(8) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(80) NOT NULL ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`banned_ip`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`banned_ip` (
  `id` INT(8) NOT NULL AUTO_INCREMENT ,
  `ip` VARCHAR(15) NOT NULL ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`banned_host`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`banned_host` (
  `id` INT(8) NOT NULL AUTO_INCREMENT ,
  `host` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`tag` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(80) NULL ,
  `is_banned` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`website_has_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`website_has_tag` (
  `tag_id` INT(11) NOT NULL ,
  `website_id` INT(11) NOT NULL ,
  PRIMARY KEY (`tag_id`, `website_id`) ,
  INDEX `fk_tag_has_website_tag` (`tag_id` ASC) ,
  INDEX `fk_tag_has_website_website` (`website_id` ASC) ,
  CONSTRAINT `fk_tag_has_website_tag`
    FOREIGN KEY (`tag_id` )
    REFERENCES `phpdir`.`tag` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tag_has_website_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `phpdir`.`feed`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`feed` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NOT NULL ,
  `title` VARCHAR(80) NOT NULL ,
  `link` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_feed_website` (`website_id` ASC) ,
  CONSTRAINT `fk_feed_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`extended_infos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`extended_infos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NOT NULL ,
  `address` VARCHAR(255) NULL ,
  `postcode` VARCHAR(5) NULL ,
  `town` VARCHAR(80) NULL ,
  `telephone` VARCHAR(15) NULL ,
  `fax` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_extended_infos_website` (`website_id` ASC) ,
  CONSTRAINT `fk_extended_infos_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`incoming`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`incoming` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NOT NULL ,
  `ip` VARCHAR(15) NOT NULL ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_in_website` (`website_id` ASC) ,
  CONSTRAINT `fk_in_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`outgoing`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`outgoing` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NOT NULL ,
  `ip` VARCHAR(15) NOT NULL ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_out_website` (`website_id` ASC) ,
  CONSTRAINT `fk_out_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`setting`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`setting` (
  `id` INT(4) NOT NULL AUTO_INCREMENT ,
  `key` VARCHAR(50) NOT NULL ,
  `value` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`partner`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`partner` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `website_id` INT(11) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_partner_website` (`website_id` ASC) ,
  CONSTRAINT `fk_partner_website`
    FOREIGN KEY (`website_id` )
    REFERENCES `phpdir`.`website` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`payment_method`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`payment_method` (
  `id` INT(3) NOT NULL AUTO_INCREMENT ,
  `type` TINYINT(2) NOT NULL COMMENT '0=allopass\n1=paypal' ,
  `options` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`payment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`payment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `customer_id` INT(8) NULL ,
  `payment_method_id` INT(3) NULL ,
  `method` TINYINT(2) NOT NULL DEFAULT 0 COMMENT '0 = allopass' ,
  `amount` DECIMAL(5,2) NOT NULL DEFAULT 0 ,
  `currency` CHAR(3) NOT NULL ,
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_payment_customer` (`customer_id` ASC) ,
  INDEX `fk_payment_payment_method` (`payment_method_id` ASC) ,
  CONSTRAINT `fk_payment_customer`
    FOREIGN KEY (`customer_id` )
    REFERENCES `phpdir`.`customer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payment_payment_method`
    FOREIGN KEY (`payment_method_id` )
    REFERENCES `phpdir`.`payment_method` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `phpdir`.`bill_has_payment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `phpdir`.`bill_has_payment` (
  `bill_id` INT(11) NOT NULL ,
  `payment_id` INT(11) NOT NULL ,
  PRIMARY KEY (`bill_id`, `payment_id`) ,
  INDEX `fk_bill_has_payment_bill` (`bill_id` ASC) ,
  INDEX `fk_bill_has_payment_payment` (`payment_id` ASC) ,
  CONSTRAINT `fk_bill_has_payment_bill`
    FOREIGN KEY (`bill_id` )
    REFERENCES `phpdir`.`bill` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bill_has_payment_payment`
    FOREIGN KEY (`payment_id` )
    REFERENCES `phpdir`.`payment` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
