<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../../pdo_bind_connection.php';

// En la ruta api/countries debe aparecer la lista de paises ordenada alfabéticamente por nombre, el continente,
// la superficie, la población, la densidad de población, la esperanza de vida, la capital y cuántos idiomas se hablan en ese país.



// En la ruta api/countries?country=nombre_país&cities=true deben aparecer las ciudades de ese país ordenadas 
// alfabéticamente, con su población y su distrito




// Si se llega a este punto, significa que el método no es GET
// O estamos en una ruta no válida

http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit();