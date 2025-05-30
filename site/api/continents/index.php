<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta api/continent debe aparecer la lista de los continentes ordenada alfabéticamente, 
// con el total de superficie, el total de poblacion y la densidad de población

if (!$_GET) {
    // Preparar la consulta
    $select = "SELECT 
                        Continent AS continente,
                        SUM(SurfaceArea) AS superficie,
                        SUM(population) AS poblacion,
                        ROUND(SUM(population) / NULLIF(SUM(SurfaceArea), 0), 2) AS densidad
                    FROM country
                    GROUP BY Continent
                    ORDER BY continente";
    $prep = $pdo->prepare($select);
    // // Vincular el parámetro
    // $prep->bindValue(':country', $country, PDO::PARAM_STR);
    // Ejecutar la consulta
    $prep->execute();
    // Obtener los resultados
    $continent = $prep->fetchAll(PDO::FETCH_ASSOC);
    // Comprobar si se han encontrado países
    echo json_encode($continent, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}


// En la ruta api/continent?continent=nombre_continente&countries=true debe aparecer la lista de paises del
// continente indicado ordenada alfabéticamente (de la A a la Z), la población de ese país y la capital

if (isset($_GET['continent']) && isset($_GET['countries'])) {

    // Continuar con el manejo de la solicitud GET
    if (empty($_GET['continent']) || empty($_GET['countries'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetros incorrectos. Deben ser "continent" con un nombre de continente y "countries" con un estado'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    if ($_GET['countries'] == 'true') {
        $continent = $_GET['continent'];
        // Preparar la consulta
        $select = "SELECT co.Name AS pais, co.Population AS poblacion, ci.Name AS capital
                    FROM country co
                    JOIN city ci ON co.Capital = ci.ID
                    WHERE co.Continent = :continent
                    ORDER BY co.Name ASC";
        $prep = $pdo->prepare($select);
        // Vincular el parámetro
        $prep->bindValue(':continent', $continent, PDO::PARAM_STR);
        // Ejecutar la consulta
        $prep->execute();
        // Obtener los resultados
        $countries = $prep->fetchAll(PDO::FETCH_ASSOC);
        // Comprobar si se han encontrado países
        if (empty($countries)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Parámetro "continent" no existe o no tiene países asociados.'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit();
        }
        echo json_encode($countries, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
}

// Si se llega a este punto, significa que el método no es GET
// O estamos en una ruta no válida

http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit();