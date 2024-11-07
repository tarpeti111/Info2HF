-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema SpaceMissions
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `SpaceMissions` ;

-- -----------------------------------------------------
-- Schema SpaceMissions
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `SpaceMissions` DEFAULT CHARACTER SET utf8 ;
USE `SpaceMissions` ;

-- -----------------------------------------------------
-- Table `SpaceMissions`.`missions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`missions` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`missions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `description` VARCHAR(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SpaceMissions`.`spaceships`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`spaceships` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`spaceships` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `type` ENUM('scout', 'exploration', 'transport', 'dreadnaught', 'research', 'colony', 'resupply', 'mining') NOT NULL,
  `missions_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_spaceships_missions1_idx` (`missions_id` ASC),
  CONSTRAINT `fk_spaceships_missions1`
    FOREIGN KEY (`missions_id`)
    REFERENCES `SpaceMissions`.`missions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SpaceMissions`.`astronauts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`astronauts` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`astronauts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `occupation` ENUM('commander', 'engineer', 'scientist', 'pilot', 'medic', 'technician', 'security', 'communicator', 'robotics') NOT NULL,
  `birth_date` DATE NOT NULL,
  `spaceships_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_astronauts_spaceships_idx` (`spaceships_id` ASC),
  CONSTRAINT `fk_astronauts_spaceships`
    FOREIGN KEY (`spaceships_id`)
    REFERENCES `SpaceMissions`.`spaceships` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SpaceMissions`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`user` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`user` (
  `username` VARCHAR(16) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  PRIMARY KEY (`username`));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
