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
  `start_date` DATE NOT NULL,
  `end_date` DATE NULL,
  `status` ENUM('planned', 'in_progress', 'completed', 'cancelled') NULL DEFAULT 'planned',
  `launch_location` VARCHAR(200) NULL,
  `destination` VARCHAR(200) NULL,
  `image_url` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SpaceMissions`.`spaceships`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`spaceships` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`spaceships` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `type` ENUM('scout', 'exploration', 'transport', 'dreadnaught', 'research', 'colony', 'resupply', 'mining') NOT NULL,
  `missions_id` INT UNSIGNED NULL,
  `description` VARCHAR(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name`),
  INDEX `fk_spaceships_missions1_idx` (`missions_id`),
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
  INDEX `fk_astronauts_spaceships_idx` (`spaceships_id`),
  CONSTRAINT `fk_astronauts_spaceships`
    FOREIGN KEY (`spaceships_id`)
    REFERENCES `SpaceMissions`.`spaceships` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SpaceMissions`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `SpaceMissions`.`users` ;

CREATE TABLE IF NOT EXISTS `SpaceMissions`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `access_level` ENUM('user', 'admin') NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username`),
  UNIQUE INDEX `email_UNIQUE` (`email`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
