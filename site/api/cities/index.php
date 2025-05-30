<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta /api/cities deben aparecer la lista de ciudades, su población, el país y el continente
// en orden alfabético por nombre de ciudad

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

// En la ruta /api/cities?city=nombre_ciudad deben aparecer los datos de esa ciudad:
// nombre, poblacion, país, continente

if (isset($_GET['city'])) {

    // Continuar con el manejo de la solicitud GET
    if (empty($_GET['city'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetro incorrecto. Debe ser "city" con un nombre de población'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    if ($_GET['city']) {
        $city = $_GET['city'];
        // Preparar la consulta
        $select = "SELECT ci.Name AS nombre, ci.Population AS poblacion, co.Name AS pais, co.Continent AS continente
                    FROM city ci
                    JOIN country co
                    ON ci.CountryCode = co.Code
                    WHERE ci.Name = :city ";
        $prep = $pdo->prepare($select);
        // Vincular el parámetro
        $prep->bindValue(':city', $city, PDO::PARAM_STR);
        // Ejecutar la consulta
        $prep->execute();
        // Obtener los resultados
        $city = $prep->fetchAll(PDO::FETCH_ASSOC);
        // Comprobar si se han encontrado países
        if (empty($city)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Parámetro "lang" demasiado corto, vacio o no existe.'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit();
        }
        echo json_encode($city, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
}

// Si se llega a este punto, significa que el método no es GET
// O estamos en una ruta no válida

http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit();