<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta /api/cities deben aparecer la lista de ciudades, su población, el país y el continente
// en orden alfabético por nombre de ciudad

// En la ruta /api/cities?city=nombre_ciudad deben aparecer los datos de esa ciudad:
// nombre, poblacion, país, continente

if (!$_GET) {
    // Preparar la consulta
    $select = "SELECT ci.Name AS nombre, ci.Population AS poblacion, co.Name AS pais, co.Continent AS continente
                FROM city ci
                JOIN country co
                ON ci.CountryCode = co.Code
                ORDER BY ci.Name ASC";
    $prep = $pdo->prepare($select);
    // // Vincular el parámetro
    // $prep->bindValue(':country', $country, PDO::PARAM_STR);
    // Ejecutar la consulta
    $prep->execute();
    // Obtener los resultados
    $cities = $prep->fetchAll(PDO::FETCH_ASSOC);
    // Comprobar si se han encontrado países
    echo json_encode($cities, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}