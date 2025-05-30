USE world;

-- En la ruta api/countries debe aparecer la lista de paises ordenada alfabéticamente por nombre, el continente,
-- la superficie, la población, la densidad de población, la esperanza de vida, la capital y cuántos idiomas se hablan en ese país.

SELECT 
name AS nombre_pais, 
continent AS continente, 
SurfaceArea AS superficie, 
population AS poblacion, 
ROUND(population / SurfaceArea, 2) AS densidad_poblacion, 
LifeExpectancy AS esperanza_vida, 
(SELECT name FROM city WHERE city.ID = country.Capital) AS capital,
(SELECT COUNT(language) FROM countrylanguage WHERE countrylanguage.CountryCode = country.Code) AS num_idiomas
FROM country
ORDER BY name ASC
;


-- En la ruta api/countries?country=nombre_país&cities=true deben aparecer las ciudades de ese país ordenadas 
-- alfabéticamente, con su población y su distrito

SELECT name AS ciudad, Population AS poblacion, District AS distrito 
FROM city 
WHERE CountryCode = (SELECT Code FROM country WHERE name = "Spain") 
ORDER BY name ASC
;
