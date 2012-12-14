<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>MP2013 API opendata - Accueil</title>
</head>
<body>
<p>Bienvenue sur le MP2013 API opendata.</p>
<hr>
<h3>Liens internes</h3>
<ul>
<li><a href = "rdf?token=<?php echo rand() ?>">Accès au RDF</a>. Ce fichier est généré automatiquement tous les matins</li>
<li><a href = "events?from=2013-01-01&to=2013-01-15&lang=fr&format=json&offset=10&limit=5&token=<?php echo rand() ?>">Exemple d'accès au api event</a> : 5 évènements à partir du 10e, en français, entre le 01/01/2013 et le 15/01/2013, au format json</li>
</ul>
<hr>
<h3>Liens externes</h3>
<ul>
<li><a href = "https://github.com/DozroK/mp2013">Projet sur GitHub</a></li>
<li><a href = "https://github.com/DozroK/mp2013#readme">Documentation du code</a></li>
<li><a href = "https://github.com/DozroK/mp2013/wiki/RDF---DOM-Proposal">Documentation du RDF</a></li>
<li><a href = "https://github.com/DozroK/mp2013/wiki/API-Events-Proposal">Documentation de l'api</a></li>

<li><a href = "http://www.mp2013.fr/">L'évènement : Marseille-Provence 2013, capital européenne de la culture</a></li>
</ul>
<hr>

Attention : bug sur cette installation : pour forcer le rafraichissement d'une page, passer un paramètre token=<chaine de caractère>.<br><a href = "http://api.mp2013.fr/refresh">Mise en évidence du bug</a> 

<br>
<a href = "?token=<?php echo rand() ?>">Rafraichir cette page</a>
</body>
</html>
