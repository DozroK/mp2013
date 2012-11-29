<?php
class Controller
{

    public function __construct() {
        include_once("bootstrap.php");
        $this->em = $em;
    }
    
    public function index() {
    
    }
    
    public function truncate() {
    $this->em->query('TRUNCATE TABLE event');
    $this->em->query('TRUNCATE TABLE place');
    echo "truncate done";
    }
    
    public function phpinfo() {
    
    }
    public function load()
    {
    
    
    if (!is_object($this->em)) {
    echo '$this->em est pas un objet';
    }
    
    $filename = "./cdt_Evenement.xml";
    $langs = array("en","fr");
    $content = file_get_contents($filename);
    include_once("./lib/cdtxml.php");
    $xml = new CdtXml($content);
    if (!is_object($xml)) {
        echo '$xml est pas un objet';
    }
    // Il faut commencer par Place
    
    for ($i = 1; $xml->hasObject($i);$i++) {
        $place[$i] = new Entity\Place();
        $this->em->persist($place[$i]);
    
        // Ville
        $place[$i]->setAddressLocality($xml->getAddressLocality($i));
        $place[$i]->setPostalCode($xml->getPostalCode($i));
            
        $place[$i]->setLatitude($xml->getLatitude($i));
        $place[$i]->setLongitude($xml->getLongitude($i));
    
        //Maintenant event
        foreach ($langs as $lang) {
            $events[$i][$lang] = new Entity\Event();
            $this->em->persist($events[$i][$lang]);
        
            $events[$i][$lang]->setPlace($place[$i]);
        
            $events[$i][$lang]->setName($xml->getEventName($i, $lang));
            $events[$i][$lang]->setLang($lang);
        
            $events[$i][$lang]->setType($xml->getEventType($i, $lang));
            $events[$i][$lang]->setDescription($xml->getEventDescription($i, $lang));
            $events[$i][$lang]->setImage($xml->getImage($i));        
        }
    }
    $this->em->flush();
    
    }
}