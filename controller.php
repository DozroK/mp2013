<?php
class Controller
{

    public function __construct() {
        include_once("bootstrap.php");
        $this->em = $em;
        if (!is_object($this->em)) {
            echo '$this->em est pas un objet';
        }
        $this->parameters = $parameters;
        $this->get = $get;
    }

    
    public function index() {
    
    }
    
    public function getfile() {
    
        if (!$this->checkKey()) {
            return;
        }
        $content = file_get_contents('http://www.mp2013.fr/ext/patio2013/cdt_Evenement.xml');
        if ($content == false) {
            echo "file_get_contents fail";
            exit;
        }
        $file = fopen(__DIR__."/xml/cdt_Evenement.xml", 'w');
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

        if (!$this->checkKey()) {
            return;
        }

        $this->em->createQuery('delete from Entity\Offer')->execute();
        $this->em->createQuery('delete from Entity\Event')->execute();
        $this->em->createQuery('delete from Entity\OpeningHours')->execute();
        $this->em->createQuery('delete from Entity\Place')->execute();
        return "truncate OK";

    }
    
    public function phpinfo() {

        if (!$this->checkKey()) {
            return;
        }
    }

    public function checkKey() {
    
        if (!isset($this->get["key"])) {
            echo "This function needs a key";
            return false;
        }

        if (!in_array($this->get["key"], $this->parameters['key.process'])) {
            echo "This key is not valid";
            return false;
        }
        return true;

    }
    public function load()
    {
        
        if (!$this->checkKey()) {
            return;
        }

        $filename = __DIR__."/xml/cdt_Evenement.xml";
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
                $events[$i][$lang]->setSuperEvent($xml->getSuperEvent($i));

                /*  désactivé temporairement car trop couteux  $events[$i][$lang]->setImage($xml->getImage($i));      */
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

        if (!$this->checkKey()) {
            return;
        }

        $this->getfile();
        $this->truncate();    
        $this->load();
        echo __FUNCTION__ . "OK";
    }

    public function output($ids = null, $format = 'json') {

        $view["place"] = $this->em->getRepository('Entity\Place')->findAll();
        
        if(empty($ids)){
            $view["event_stupid_key"] = $this->em->getRepository('Entity\Event')->getEventsWithOffers();
        }else{
            $view["event_stupid_key"] = $this->em->getRepository('Entity\Event')->getEventsWithOffersByIds($ids);            
        }
        
        foreach ($view["event_stupid_key"] as $key => $value) {
            $view["event"][$value->getIdPatio()][$value->getLang()] = new RDFHelper\Event($value);
        }
           
        $this->em->getRepository('Entity\Place')->getPlacesWithOpeningHours();
        
        //rendering rdf into a string
        ob_start();
        include(__DIR__."/views/rdfContent.php");
        $rdf = ob_get_clean();
        include_once(__DIR__."/lib/viewFactory.php");
        $factory = new viewFactory($format,$rdf);
        $view = $factory->build();
        return $view;
    }
    

    public function rdf() {
          $view = $this->output(null,'rdf');
          return $view;  
    }
    /**
     * events function.
     * 
     * @access public
     * @return void
     */
    public function events($params) {
        // Pour l'instant, on ne gère que le GET

        // step 1 : Sanitize
        $from = isset($params["from"])?new \DateTime($params["from"]):null;
        $to = isset($params["to"])?new \DateTime($params["to"]):null;
        $lang = isset($params["lang"])?$params["lang"]:null;
        $limit = (int)isset($params["limit"])?$params["limit"]:10000;
        $offset = (int)isset($params["offset"])?$params["offset"]:0;
        $format = isset($params["format"])?$params["format"]:"rdf";
        $function = (int)isset($params["function"])?$params["function"]:null;

        // step 2 : Filter
        /*
        • 200 - OK
        • 400 - Bad Request
        • 500 - Internal Server Error
        */

        if (get_class($from) != "DateTime") {
            header("HTTP/1.1 400 Bad Request");
            $errors = array("errors" => array(array("code" => 4, "message" => "from must be a date (format : YYYY-MM-DD)")));
            echo json_encode($errors);
            return;
        }

        if (get_class($to) != "DateTime") {
            header("HTTP/1.1 400 Bad Request");
            $errors = array("errors" => array(array("code" => 5, "message" => "to must be a date (format : YYYY-MM-DD)")));
            echo json_encode($errors);
            return;
        }


        if (!in_array($lang, array('fr', 'en', null))) {
            header("HTTP/1.1 400 Bad Request");
            $errors = array("errors" => array(array("code" => 2, "message" => "if specified, lang parameter can only be fr or en. Default is every languages")));
            echo json_encode($errors);
            return;
        }

        if (!in_array($format, array('json', 'rdf'))) {
            header("HTTP/1.1 400 Bad Request");
            $errors = array("errors" => array(array("code" => 1, "message" => "format parameter can only be json or rdf. Default is json")));
            echo json_encode($errors);
            return;
        }





        $qb = $this->em->createQueryBuilder();
        $qb->add('select', 'e')
           ->add('from', 'Entity\Event e');
        if (get_class($from) == "DateTime") {
           $qb->andWhere('e.startDate >= :from');
            $qbParams["from"] = $from;
        }
        if (get_class($to) == "DateTime") {
           $qb->andWhere('e.endDate <= :to');
            $qbParams["to"] = $to;
        }
        if ($lang) {
           $qb->andWhere('e.lang = :lang');
            $qbParams["lang"] = $lang;
        }
        $qb->setParameters($qbParams);
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        $events = $qb->getQuery()->getResult();
        $id = array();
        foreach ($events as $event) {
            $id[] = $event->getId();
        }
        if ($function == 'count') {
            echo (count($id));
            return;
        }
        return $this->output($id, $format);
    }
    
    public function page404 () {
        header("HTTP/1.0 404 Not Found");
        echo "404";
        return;
    }

    public function refresh () {
        echo "cette fonction fait : <br>";
        echo 'echo "date : ".date("Y-m-d H:i:s")<br>';
        echo 'echo "rand : ".rand();<br>';
        echo 'Resultat : <br>';
        echo "date : ".date("Y-m-d H:i:s");
        echo '<br>';
        echo "rand : ".rand();
    }

    
}