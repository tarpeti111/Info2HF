<?php
require_once("db.php");

// Get the missions and the most common spaceship type for each mission
$missionsQuery = $db->prepare("
    SELECT 
        m.title AS mission_title,
        COALESCE(s.type, 'None') AS most_common_spaceship_type
    FROM missions m
    LEFT JOIN (
        SELECT 
            spaceships.missions_id, 
            spaceships.type,
            COUNT(spaceships.type) AS type_count
        FROM spaceships
        GROUP BY spaceships.missions_id, spaceships.type
    ) s ON m.id = s.missions_id
    LEFT JOIN (
        SELECT 
            missions_id, 
            type AS most_common_type, 
            MAX(type_count) AS max_count
        FROM (
            SELECT 
                missions_id,
                type,
                COUNT(type) AS type_count
            FROM spaceships
            GROUP BY missions_id, type
        ) sub
        GROUP BY missions_id
    ) top_type ON m.id = top_type.missions_id AND s.type = top_type.most_common_type
");

$missionsQuery->execute();
$missions = $missionsQuery->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($missions);
