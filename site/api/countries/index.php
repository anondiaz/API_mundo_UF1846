<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta api/countries debe aparecer la lista de paises ordenada alfabéticamente por nombre, el continente,
// la superficie, la población, la densidad de población, la esperanza de vida, la capital y cuántos idiomas se hablan en ese país.

if (!$_GET) {
    // Preparar la consulta
    $select = "SELECT 
                        name AS nombre_pais, 
                        continent AS continente, 
                        SurfaceArea AS superficie, 
                        population AS poblacion, 
                        ROUND(population / SurfaceArea, 2) AS densidad_poblacion, 
                        LifeExpectancy AS esperanza_vida, 
                        (SELECT name FROM city WHERE city.ID = country.Capital) AS capital,
                        (SELECT COUNT(language) FROM countrylanguage WHERE countrylanguage.CountryCode = country.Code) AS num_idiomas
                    FROM country
                    ORDER BY name ASC";
    $prep = $pdo->prepare($select);
    // // Vincular el parámetro
    // $prep->bindValue(':country', $country, PDO::PARAM_STR);
    // Ejecutar la consulta
    $prep->execute();
    // Obtener los resultados
    $languages = $prep->fetchAll(PDO::FETCH_ASSOC);
    // Comprobar si se han encontrado países
    echo json_encode($languages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

// En la ruta api/countries?country=nombre_país&cities=true deben aparecer las ciudades de ese país ordenadas 
// alfabéticamente, con su población y su distrito

if (isset($_GET['country']) && isset($_GET['cities']) && $_GET['cities'] == 'true') {
    if (empty($_GET['country'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetro incorrecto. Debe ser "country" con un nombre de país'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    
    $country = $_GET['country'];
    
    // Preparar la consulta
    $select = "SELECT name AS ciudad, Population AS poblacion, District AS distrito 
                FROM city 
                WHERE CountryCode = (SELECT Code FROM country WHERE name = :country) 
                ORDER BY name ASC";
    $prep = $pdo->prepare($select);
    // Vincular el parámetro
    $prep->bindValue(':country', $country, PDO::PARAM_STR);
    // Ejecutar la consulta
    $prep->execute();
    // Obtener los resultados
    $cities = $prep->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cities)) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'País no encontrado o sin ciudades'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    
    echo json_encode($cities, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}


// Si se llega a este punto, significa que el método no es GET
// O estamos en una ruta no válida

http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit();