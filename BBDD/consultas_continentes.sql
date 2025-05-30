USE world;

-- En la ruta api/continent debe aparecer la lista de los continentes ordenada alfabéticamente, 
-- con el total de superficie, el total de poblacion y la densidad de población

SELECT 
    Continent AS continente,
    SUM(SurfaceArea) AS superficie,
    SUM(population) AS poblacion,
    ROUND(SUM(population) / NULLIF(SUM(SurfaceArea), 0), 2) AS densidad
FROM country
GROUP BY Continent
ORDER BY continente
;


-- En la ruta api/continent?continent=nombre_continente&countries=true debe aparecer la lista de paises del
-- continente indicado ordenada alfabéticamente (de la A a la Z), la población de ese país y la capital

SELECT co.Name AS pais, co.Population AS poblacion, ci.Name AS capital
FROM country co
JOIN city ci ON co.Capital = ci.ID
WHERE co.Continent = "Asia"
ORDER BY co.Name ASC
;