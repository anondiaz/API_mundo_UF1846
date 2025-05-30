USE world;

-- Deben aparecer la lista de idiomas del mundo en orden alfabético y sin repeticiones
--  Es esta información:

SELECT count(language), language FROM countrylanguage 
WHERE language = "Chinese"
GROUP BY language
ORDER BY language ASC
;

SELECT language AS idioma FROM countrylanguage 
GROUP BY idioma 
ORDER BY idioma ASC
;


-- Debe aparecer la lista de idiomas con el total de hablantes estimado,
-- en orden descendente según ese total. Es esta información:
-- { 'idioma' : 'Chinese', 'hablantes' : '1191843539'},
-- { 'idioma' : 'Hindi', 'hablantes' : '405633070'},
-- { 'idioma' : 'Spanish', 'hablantes' : '355029462'},
-- { 'idioma' : 'English', 'hablantes' : '347077867'},
-- { 'idioma' : 'Arabic', 'hablantes' : '233839239'},
-- {.....}

SELECT col.language AS idioma, (SUM(co.Population * col.Percentage / 100)) AS hablantes
FROM countrylanguage col
JOIN country co ON col.CountryCode = co.Code 
GROUP BY idioma
ORDER BY hablantes DESC
-- WHERE col.language = "Chinese"
;



-- Deben aparecer los países en los que se habla el idioma indicado,
-- y el total de hablantes en ese país. Por ejemplo:
--  /api/languages?lang=catalan
-- Debemos obtener:
-- { 'pais' : 'Spain', 'hablantes' : '6665647'},
-- { 'pais' : 'Andorra', 'hablantes' : '25194'},
-- {.......}

SELECT co.Name AS pais, (co.Population * col.Percentage / 100) AS hablantes 
FROM countrylanguage col
JOIN country co ON col.CountryCode = co.Code 
WHERE col.language = "Catalan"
ORDER BY hablantes DESC
;
