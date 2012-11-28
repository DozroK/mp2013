<?php
 
// c.php
function test()
{

include_once("bootstrap.php");
//var_dump($em);
$filename = "./cdt_Evenement.xml";
$langs = array("en","fr");
$content = file_get_contents($filename);
$xml = new SimpleXMLElement($content);

/* Premier test
$x = $xml->object[0]->children('cdt', true)->InformationGenerales->NomAnnonce->children()->fr[0];
echo($x);
*/

// Il faut commencer par Place

$place = new Entity\Place();
$em->persist($place);

$place->setAddressLocality($xml->object[0]->children('cdt', true)->InformationGenerales->LieuEvenement->LieuLibre->Ville->Ville->NomVille);

$place->setLatitude($xml->object[0]->children('cdt', true)->InformationGenerales->LieuEvenement->LieuLibre->Ville->Ville->Latitude);

$place->setLongitude($xml->object[0]->children('cdt', true)->InformationGenerales->LieuEvenement->LieuLibre->Ville->Ville->Longitude);



$place->getId();

//Maintenant event
foreach ($langs as $lang) {
    $events[$lang] = new Entity\Event();
    $em->persist($events[$lang]);

    $events[$lang]->setPlace($place);

    $events[$lang]->setName($xml->object[0]->children('cdt', true)->InformationGenerales->NomAnnonce->children()->$lang);
    $events[$lang]->setLang($lang);
    $events[$lang]->setDescription($xml->object[0]->children('cdt', true)->ProgrammeDescriptif->DescriptifValorisant->children()->$lang);
}

$em->flush();

return "--".$xml->object[0]->children('cdt', true)->InformationGenerales->NomAnnonce->children()->fr."--";

}
  