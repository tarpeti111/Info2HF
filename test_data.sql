-- Inserting 10 missions with creative names
INSERT INTO `SpaceMissions`.`missions` (`title`, `description`, `start_date`, `end_date`, `status`, `launch_location`, `destination`, `image_url`) 
VALUES
('Celestial Voyage', 'A mission to explore the farthest reaches of the galaxy', '2024-01-01', '2024-12-31', 'planned', 'Earth', 'Outer Galaxy', 'celestial_voyage.jpg'),
('Nebula Odyssey', 'Journey into the heart of the Orion Nebula to study its mysteries', '2025-01-01', '2025-12-31', 'planned', 'Earth', 'Orion Nebula', 'nebula_odyssey.jpg'),
('Stellar Awakening', 'A mission to study dormant stars and explore their hidden potentials', '2026-01-01', '2026-12-31', 'planned', 'Mars', 'Stellar System', 'stellar_awakening.jpg'),
('Eclipse Horizon', 'An expedition to observe a rare solar eclipse from space', '2027-01-01', '2027-12-31', 'planned', 'Earth', 'Solar Eclipse', 'eclipse_horizon.jpg'),
('Exoplanet Pioneers', 'First steps towards colonizing distant exoplanets', '2028-01-01', '2028-12-31', 'planned', 'Earth', 'Exoplanet System', 'exoplanet_pioneers.jpg'),
('Black Hole Enigma', 'A daring mission to study the mysteries of a black hole', '2029-01-01', '2029-12-31', 'planned', 'Earth', 'Sagittarius A*', 'black_hole_enigma.jpg'),
('Cosmic Gateway', 'Research on the phenomenon of wormholes and possible travel through them', '2030-01-01', '2030-12-31', 'planned', 'Earth', 'Wormhole Galaxy', 'cosmic_gateway.jpg'),
('Asteroid Dominion', 'Exploring and mining valuable resources from asteroid fields', '2031-01-01', '2031-12-31', 'planned', 'Earth', 'Asteroid Belt', 'asteroid_dominion.jpg'),
('Lunar Genesis', 'A mission to establish the first permanent base on Earth''s Moon', '2032-01-01', '2032-12-31', 'planned', 'Earth', 'Moon Base', 'lunar_genesis.jpg'),
('Red Dawn Expedition', 'An exploration mission to the red dwarf star system', '2033-01-01', '2033-12-31', 'planned', 'Earth', 'Red Dwarf System', 'red_dawn_expedition.jpg');

-- Inserting 20 spaceships with creative names
INSERT INTO `SpaceMissions`.`spaceships` (`name`, `type`, `missions_id`, `description`) 
VALUES
('Titan''s Wrath', 'dreadnaught', 1, 'A powerful dreadnought for long-duration missions to uncharted spaces'),
('Galactic Voyager', 'exploration', 2, 'State-of-the-art exploration vessel for deep-space discovery'),
('Nebula Seeker', 'exploration', 3, 'Sleek, high-speed spaceship designed for navigating through nebulae'),
('Eclipse Star', 'research', 4, 'Futuristic research ship equipped for stellar observations and analysis'),
('Astral Serpent', 'scout', 5, 'Fast and maneuverable scout ship, built for missions requiring stealth and speed'),
('Celestial Ark', 'colony', 6, 'Colony ship designed for long journeys to far-off exoplanets'),
('Solaris Horizon', 'transport', 7, 'Transport ship used for resource gathering and planetary missions'),
('Cosmos Harbinger', 'exploration', 8, 'Long-range exploration vessel designed to travel beyond the Milky Way'),
('Event Horizon', 'research', 9, 'Ship engineered for missions at the edge of black holes'),
('Starfire Pioneer', 'research', 10, 'Research vessel equipped to study distant stars and supernova remnants'),
('Nebula Phoenix', 'exploration', 1, 'Revival ship sent to study the lifecycle of stars within nebulae'),
('Vanguard Nova', 'exploration', 2, 'Spaceship that leads humanity''s exploration into the deepest regions of space'),
('Quantum Drift', 'research', 3, 'High-tech research vessel capable of testing quantum theories in space'),
('Astralis Odyssey', 'exploration', 4, 'Ship designed for deep-space exploration across the most remote galaxies'),
('Aetheric Spirit', 'scout', 5, 'Stealth spacecraft used for covert exploration of planetary systems'),
('Celestia''s Gate', 'research', 6, 'Vessel designed to study and potentially navigate wormholes'),
('Starlight Whisper', 'scout', 7, 'Nimble scout ship for navigating asteroid belts and other hazardous areas'),
('Hyperion Sentinel', 'dreadnaught', 8, 'Mighty space fortress used to guard distant colonies'),
('Vortex Voyager', 'exploration', 9, 'Ship tasked with exploring the boundaries of space-time anomalies'),
('Lunar Horizon', 'transport', 10, 'Specialized ship designed to explore the farthest reaches of the Moon''s surface');

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

-- Insert a user with 'user' access level
INSERT INTO `spacemissions`.`users` (`username`, `email`, `password`, `access_level`) 
VALUES ('user', 'user@example.com', '$2y$10$DCc/wmu1TfKeqrAwVbJa1OlnnzfK56OxqOItYV8DAx6SRD12ngvaG', 'user');

-- Insert a user with 'admin' access level
INSERT INTO `spacemissions`.`users` (`username`, `email`, `password`, `access_level`) 
VALUES ('admin', 'admin@example.com', '$2y$10$NGQF1Sy5vb4PsF06mfAMM.fUwUag24n48FB0hlJovnqQPCKoep5.K', 'admin');

-- Insert a user with 'moderator' access level
INSERT INTO `spacemissions`.`users` (`username`, `email`, `password`, `access_level`) 
VALUES ('moderator', 'moderator@example.com', '$2y$10$JI6ouN9E/ykUcbOKM5VkHe053Hd195DqpUyIFfIzmNL3mN94JoHZ2', 'moderator');
