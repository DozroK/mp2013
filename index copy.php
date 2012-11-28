<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>MP2013 API opendata</title>
</head>
<body>

<?php

include_once("bootstrap.php");
//var_dump($em);
$filename = "./cdt_Evenement.xml";
$content = file_get_contents($filename);
$xml = new SimpleXMLElement($content);

/* Premier test
$x = $xml->object[0]->children('cdt', true)->InformationGenerales->NomAnnonce->children()->fr[0];
echo($x);
*/

// Il faut commencer par Place

$place = new Entity\Place();

$addressLocality = $xml->object[0]->children('cdt', true)->InformationGenerales->LieuEvenement->LieuLibre->Ville->NomVille;
echo "--".$addressLocality."--";

exit;
$place->setAddressLocality($addressLocality);
$em->persist($place);
$em->flush();


?>

</body>
</html>
