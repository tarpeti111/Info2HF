INSERT INTO `SpaceMissions`.`missions` (`title`, `description`) VALUES
('Alpha Exploration', 'A deep-space exploration mission to investigate new star systems.'),
('Lunar Recon', 'Scouting and mapping mission of the lunar surface.'),
('Mars Research', 'In-depth research mission on the surface of Mars.'),
('Colony Setup', 'Establishing the first human colony on a nearby exoplanet.'),
('Nebula Defense', 'Defensive mission in the Orion Nebula region.'),
('Supply Run', 'Transport mission delivering supplies to the Mars base.'),
('Jupiter Station', 'Establishing a research station on one of Jupiter’s moons.'),
('Asteroid Mining', 'Mining resources from an asteroid belt near the outer planets.'),
('Proxima Expedition', 'Exploratory mission to Proxima Centauri.'),
('Galactic Survey', 'Mapping and surveying new galaxies beyond the Milky Way.');

INSERT INTO `SpaceMissions`.`spaceships` (`name`, `type`, `missions_id`) VALUES
('Voyager', 'exploration', 1),
('Starlight', 'scout', 2),
('Odyssey', 'research', 3),
('Orion', 'colony', 4),
('Prometheus', 'dreadnaught', 5),
('Lunar Express', 'transport', 6),
('Titan', 'resupply', 7),
('Apollo', 'mining', 8),
('Nebula', 'scout', 9),
('Galileo', 'exploration', 10),
('Aurora', 'colony', 1),
('Falcon', 'scout', 2),
('Endeavor', 'research', 3),
('Mercury', 'transport', 4),
('Hercules', 'dreadnaught', 5),
('Atlas', 'mining', 6),
('Pioneer', 'resupply', 7),
('Eclipse', 'exploration', 8),
('Daedalus', 'colony', 9),
('Celestial', 'research', 10);

INSERT INTO `SpaceMissions`.`astronauts` (`first_name`, `last_name`, `occupation`, `birth_date`, `spaceships_id`) VALUES
-- Crew for Voyager (exploration, 2 astronauts)
('John', 'Shepard', 'commander', '1985-03-14', 1),
('Diana', 'Voss', 'scientist', '1991-02-12', 1),

-- Crew for Starlight (scout, 1 astronaut)
('Sarah', 'Reed', 'scientist', '1990-07-22', 2),

-- Crew for Odyssey (research, 2 astronauts)
('Tom', 'Santos', 'pilot', '1988-11-02', 3),
('Hana', 'Kim', 'scientist', '1985-04-10', 3),

-- Crew for Orion (colony, 2 astronauts)
('Elena', 'Martinez', 'engineer', '1984-09-18', 4),
('Robert', 'Yale', 'technician', '1987-05-15', 4),

-- Crew for Prometheus (dreadnaught, 3 astronauts)
('James', 'Keller', 'medic', '1992-05-30', 5),
('Michael', 'Brooks', 'commander', '1983-08-22', 5),
('Andrea', 'Fisher', 'security', '1988-12-05', 5),

-- Crew for Lunar Express (transport, 1 astronaut)
('Linda', 'Chang', 'technician', '1987-01-12', 6),

-- Crew for Titan (resupply, 1 astronaut)
('Ravi', 'Patel', 'security', '1983-06-17', 7),

-- Crew for Apollo (mining, 2 astronauts)
('Karen', 'Lewis', 'communicator', '1989-04-23', 8),
('Leo', 'Nguyen', 'technician', '1990-09-12', 8),

-- Crew for Nebula (scout, 1 astronaut)
('Alex', 'King', 'robotics', '1991-08-05', 9),

-- Crew for Galileo (exploration, 2 astronauts)
('Maria', 'Gomez', 'scientist', '1986-10-13', 10),
('Oliver', 'Quinn', 'pilot', '1989-02-18', 10),

-- Crew for Aurora (colony, 2 astronauts)
('Isabella', 'Jones', 'engineer', '1984-11-22', 11),
('Nina', 'Chen', 'medic', '1987-06-28', 11),

-- Crew for Falcon (scout, 1 astronaut)
('Samuel', 'Parker', 'pilot', '1989-07-14', 12),

-- Crew for Endeavor (research, 2 astronauts)
('Victor', 'Ortiz', 'scientist', '1992-03-25', 13),
('Sophia', 'Alvarez', 'engineer', '1986-08-16', 13),

-- Crew for Mercury (transport, 1 astronaut)
('Lucas', 'Miller', 'communicator', '1985-10-19', 14),

-- Crew for Hercules (dreadnaught, 3 astronauts)
('Emma', 'Hart', 'commander', '1983-12-08', 15),
('Joshua', 'Lee', 'security', '1989-05-17', 15),
('Amanda', 'Wu', 'engineer', '1987-07-30', 15),

-- Crew for Atlas (mining, 2 astronauts)
('Ethan', 'Carter', 'technician', '1986-02-01', 16),
('Liam', 'White', 'security', '1991-11-04', 16),

-- Crew for Pioneer (resupply, 1 astronaut)
('Chloe', 'Diaz', 'pilot', '1990-12-22', 17),

-- Crew for Eclipse (exploration, 2 astronauts)
('Benjamin', 'Ross', 'scientist', '1987-04-15', 18),
('Ava', 'Taylor', 'robotics', '1992-01-11', 18),

-- Crew for Daedalus (colony, 2 astronauts)
('Sophie', 'Evans', 'engineer', '1985-08-20', 19),
('Ryan', 'Peterson', 'medic', '1984-10-07', 19),

-- Crew for Celestial (research, 2 astronauts)
('Isabel', 'Garcia', 'scientist', '1988-06-26', 20),
('Oscar', 'Rogers', 'technician', '1990-09-09', 20);