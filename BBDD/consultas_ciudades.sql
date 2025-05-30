USE world;

-- En la ruta /api/cities deben aparecer la lista de ciudades, su población, el país y el continente
-- en orden alfabético por nombre de ciudad

SELECT ci.Name AS nombre, ci.Population AS poblacion, co.Name AS pais, co.Continent AS continente
FROM city ci
JOIN country co
ON ci.CountryCode = co.Code
ORDER BY ci.Name ASC
;

-- En la ruta /api/cities?city=nombre_ciudad deben aparecer los datos de esa ciudad: 
-- nombre, poblacion, país, continente

SELECT ci.Name AS nombre, ci.Population AS poblacion, co.Name AS pais, co.Continent AS continente
FROM city ci
JOIN country co
ON ci.CountryCode = co.Code
WHERE ci.Name = "Madrid"
;
