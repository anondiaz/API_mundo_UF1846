USE world;

-- En la ruta api/countries debe aparecer la lista de paises ordenada alfabéticamente por nombre, el continente,
-- la superficie, la población, la densidad de población, la esperanza de vida, la capital y cuántos idiomas se hablan en ese país.

SELECT 
    name AS nombre_pais, 
    continent, 
    SurfaceArea AS superficie, 
    population, 
    ROUND(population / SurfaceArea, 2) AS densidad_poblacion, 
    LifeExpectancy AS esperanza_vida, 
    capital AS capital
    -- (SELECT COUNT(*) FROM countrylanguage WHERE countrylanguage.CountryCode = country.CountryCode) AS num_idiomas
FROM country
ORDER BY name ASC
    ;


-- En la ruta api/countries?country=nombre_país&cities=true deben aparecer las ciudades de ese país ordenadas 
-- alfabéticamente, con su población y su distrito



