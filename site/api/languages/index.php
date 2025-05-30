<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta /api/languages deben aparecer la lista de idiomas del mundo en orden alfabético y sin repeticiones

if (!$_GET) {
    // Preparar la consulta
    $select = "SELECT language AS idioma FROM countrylanguage 
            GROUP BY idioma 
            ORDER BY idioma ASC";
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

// En la ruta /api/languages?total=true debe aparece la lista de idiomas con el total de hablantes estimado,
// en orden descendente según ese total. Es esta información:
//   [
//       { 'idioma' : 'Chinese', 'hablantes' : '1191843539'},
//       { 'idioma' : 'Hindi', 'hablantes' : '405633070'},
//       { 'idioma' : 'Spanish', 'hablantes' : '355029462'},
//       { 'idioma' : 'English', 'hablantes' : '347077867'},
//       { 'idioma' : 'Arabic', 'hablantes' : '233839239'},
//       etc
//   ]

if (isset($_GET['total'])) {

    // Continuar con el manejo de la solicitud GET
    if (empty($_GET['total'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetro incorrecto. Debe ser "total" con un estado'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    if ($_GET['total'] == 'true') {
        $select = "SELECT col.language AS idioma, (SUM(co.Population * col.Percentage / 100)) AS hablantes
                    FROM countrylanguage col
                    JOIN country co ON col.CountryCode = co.Code 
                    GROUP BY idioma
                    ORDER BY hablantes DESC";
        $prep = $pdo->prepare($select);
        // Ejecutar la consulta
        $prep->execute();
        // Obtener los resultados
        $languages = $prep->fetchAll(PDO::FETCH_ASSOC);
        // Comprobar si se han encontrado países
        foreach ($languages as &$language) {
            $language['hablantes'] = round($language['hablantes']); // Redondeamos el número
        }        
        echo json_encode($languages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetro incorrecto. Debe ser "total" con un estado adecuado'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
} 

// En la ruta /api/languages?lang=nombre_idioma deben aparecer los países en los que se habla el idioma indicado,
// y el total de hablantes en ese país. Por ejemplo:
// /api/languages?lang=catalan
//    Debemos obtener:
//    [
//        { 'pais' : 'Spain', 'hablantes' : '6665647'},
//        { 'pais' : 'Andorra', 'hablantes' : '25194'}
//    ] 

if (isset($_GET['lang'])) {
    if (empty($_GET['lang'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Parámetro incorrecto. Debe ser "lang" con el nombre de un pais'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    if ($_GET['lang']) {
        $language = $_GET['lang'];
        // Preparar la consulta
        $select = "SELECT co.Name AS pais, (co.Population * col.Percentage / 100) AS hablantes 
                    FROM countrylanguage col
                    JOIN country co ON col.CountryCode = co.Code 
                    WHERE col.language = :language
                    ORDER BY hablantes DESC";
        $prep = $pdo->prepare($select);
        // Vincular el parámetro
        $prep->bindValue(':language', $language, PDO::PARAM_STR);
        // Ejecutar la consulta
        $prep->execute();
        // Obtener los resultados
        $languages = $prep->fetchAll(PDO::FETCH_ASSOC);
        // Comprobar si se han encontrado países
        if (empty($languages)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Parámetro "lang" demasiado corto, vacio o no existe.'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit();
        }
        foreach ($languages as &$language) {
            $language['hablantes'] = round($language['hablantes']); // Redondeamos el número
        } 
        echo json_encode($languages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

}

// Si se llega a este punto, significa que el método no es GET
// O estamos en una ruta no válida


http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit();

