<?php
class Controller
{

    public function __construct() {
        include_once("bootstrap.php");
        $this->em = $em;
        if (!is_object($this->em)) {
            echo '$this->em est pas un objet';
        }
    }

    
    public function index() {
    
    }
    
    public function getfile() {
        $content = file_get_contents('http://www.mp2013.fr/ext/patio2013/cdt_Evenement.xml');
        if ($content == false) {
            echo "file_get_contents fail";
            exit;
        }
        $file = fopen(__DIR__."/cdt_Evenement.xml", 'w');
        if ($file == false) {
            echo "fopen fail";
            exit;
        }
        if (fwrite($file, $content) == false) {
            echo "fwrite fail";
            exit;
        }
        echo "OK";
        return "récupération en local du xml distant OK. Vous pouvez maintenant <a href = 'load'>Charger le fichier en bdd</a>";
    }

    public function truncate() {
        $this->em->createQuery('delete from Entity\Offer')->execute();
        $this->em->createQuery('delete from Entity\Event')->execute();
        $this->em->createQuery('delete from Entity\OpeningHours')->execute();
        $this->em->createQuery('delete from Entity\Place')->execute();
        return "truncate OK";

    }
    
    public function phpinfo() {
    
    }
    public function load()
    {
        
        $filename = __DIR__."/cdt_Evenement.xml";
        $langs = array("en","fr");
        $content = file_get_contents($filename);
        include_once(__DIR__."/lib/cdtxml.php");
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
                $events[$i][$lang]->setIdPatio($xml->getIdPatio($i));
            
                $events[$i][$lang]->setName($xml->getEventName($i, $lang));
                $events[$i][$lang]->setLang($lang);
            
                $events[$i][$lang]->setType($xml->getEventType($i, $lang));
                $events[$i][$lang]->setDescription($xml->getEventDescription($i, $lang));
                $events[$i][$lang]->setStartDate($xml->getEventStartDate($i));
                $events[$i][$lang]->setEndDate($xml->getEventEndDate($i));
                //  désactivé temporairement car trop couteux $events[$i][$lang]->setImage($xml->getImage($i));      
                //Offers 
                foreach( $xml->getEventOffers($i) as $offer ){
                    $this->em->persist($offer);
                    $offer->setEvent($events[$i][$lang]);
                }
            }
            
            //Opening Hours
            foreach ($xml->getEventOpeningHours($i) as $hours) {               
                $this->em->persist($hours);
                $hours->setPlace($place[$i]);
                $hours->setValidFrom($events[$i][$lang]->get('startDate'));
                $hours->setValidTrough($events[$i][$lang]->get('endDate'));
            }
        }
        $this->em->flush();
        return "load terminé";
    }

    public function process() {
        $this->getfile();
        $this->truncate();    
        $this->load();
        echo __FUNCTION__ . "OK";
    }

    public function rdf() {
        $view["place"] = $this->em->getRepository('Entity\Place')->findAll();
        $view["event_stupid_key"] = $this->em->getRepository('Entity\Event')->getEventsWithOffers();
        foreach ($view["event_stupid_key"] as $key => $value) {
            $view["event"][$value->getIdPatio()][$value->getLang()] = new RDFHelper\Event($value);
        }
           
        $this->em->getRepository('Entity\Place')->getPlacesWithOpeningHours();
        
        return $view;
    }
}