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
        $xml = new lib\CdtXml($content);
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
        
        $eventCount = count($this->em->getRepository('Entity\Event')->findAll());

        
        return "load terminé. $eventCount events in DB";
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

    public function output($format,$events = false, $template = "fullRdf",$loadOpeningHours = true) {
        
        $this->checkFormat($format);
        
        if($loadOpeningHours === true){
            $this->em->getRepository('Entity\Place')->getPlacesWithOpeningHours();
        }        
        
        // if false load all events
        if($events === false){
            $events = $this->em->getRepository('Entity\Event')->getEventsWithOffers();
        }
        
        if(empty($events)){
            header("HTTP/1.1 200 OK");
            $errors = array("errors" => array(array("code" => 9, "message" => "No events matched your request")));
            echo json_encode($errors);
            exit;              
        }
        
        foreach ($events as $value) {
            $view["event"][$value->getIdPatio()][$value->getLang()] = new RDFHelper\Event($value);
        }
                    
        //rendering rdf into a string
        ob_start();
        include(__DIR__."/views/$template.php");
        $rdf = ob_get_clean();
        $factory = new lib\ViewFactory($format,$rdf);
        $view = $factory->build();
        return $view;
    }
    

    public function rdf() {
          $view = $this->output('rdf');
          return $view;  
    }

    /**
     * getEvents function.
     * 
     * @access private
     */
    private function getEvents($params) {
        // Pour l'instant, on ne gère que le GET

        try{
            $from = isset($params["from"])?new \DateTime($params["from"]):new \DateTime(date("Y-m-d"));
        }catch(Exception $e){
            $this->badRequest(1, "from must be a date (format : YYYY-MM-DD)");
        }
        
        try{        
            $to = isset($params["to"])?new \DateTime($params["to"]):null;
        }catch(Exception $e){
            
            $this->badRequest(2, "to must be a date (format : YYYY-MM-DD)");
        }  
        
        $lang = isset($params["lang"])?$params["lang"]:null;
        $limit = (int)isset($params["limit"])?$params["limit"]:(int)$this->parameters['defaultLimit'];
        $offset = (int)isset($params["offset"])?$params["offset"]:0;
                
        if (!in_array($lang, array('fr', 'en', null))) {
            $this->badRequest(3, "if specified, lang parameter can only be fr or en. Default is every languages");            
        }

        $qb = $this->em->createQueryBuilder();
        $qb->add('select', 'e')
           ->add('from', 'Entity\Event e')
           ->leftJoin('e.offers', 'o')
           ->addSelect('o');

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
        
        if(!empty($qbParams)){
            $qb->setParameters($qbParams);
        }
        
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        $events = $qb->getQuery()->getResult();

        return $events;
    }

    /**
     * events function.
     * 
     * @access public
     * @return ViewInterface
     */
    public function events($params) {
        
        $events = $this->getEvents($params);
                
        $format = isset($params['format'])?$params['format']:$this->parameters['defaultOutputFormat'];
        
        if ( isset($params["function"]) and $params["function"] == 'count'){
            echo (count($events));
            exit;   
        } 
        
        return $this->output($format,$events);
    }    
    
    /**
     * near function.
     * 
     * @access public
     * @return ViewInterface
     */
    public function near($params) {
        
        $latitude  = isset($params["latitude"])  ? (float)$params["latitude"]:null;
        $longitude = isset($params["longitude"]) ? (float)$params["longitude"]:null;
        $distance  = isset($params["distance"])  ? (float)$params["distance"]:(float)$this->parameters['defaultDistance'];
        $format = isset($params['format'])?$params['format']:$this->parameters['defaultOutputFormat'];
        
        if ( !is_float($latitude)  OR $latitude < -90.0 OR $latitude > 90.0 ) {           
            $this->badRequest(4, "latitude must be inside the range -90.0 to +90.0 (North is positive) inclusive");
        }

        if ( !is_float($longitude) OR $longitude < -180.0 OR $longitude > 180.0 ) {
            $this->badRequest(5, "longitude must be inside the range -180.0 to +180.0 (East is positive) inclusive");
        }      

        if ( $distance <= 0 ) {            
            $this->badRequest(6, "distance must be a positive number (in Km)");
        }        
        
        if(isset($params["offset"])){
            $offset = (int)$params["offset"];
            unset($params["offset"]);
        }else{
            $offset = 0;
        }
        
        if(isset($params["limit"])){
            $limit = (int)$params["limit"];
            unset($params["limit"]);
        }else{
            $limit = (int)$this->parameters['defaultLimit'];
        }        
        
        $eventsNear = array();
        
        $events = $this->getEvents($params);
                
        foreach($events as $event) {
            
            $eventLatitude =  (float)$event->getPlace()->getLatitude();
            $eventLongitude = (float)$event->getPlace()->getLongitude();
            
            if(empty($eventLatitude) OR empty($eventLongitude)){
                continue;
            }
            
            $distanceBetween = $this->calculateDistance($latitude, $longitude, $eventLatitude, $eventLongitude);

            if($distanceBetween <= $distance){
                $eventsNear[] = $event;
            }
         }
                
        $eventsNear = array_slice($eventsNear,$offset,$limit);

        if ( isset($params["function"]) and $params["function"] == 'count'){
            echo (count($eventsNear));
            exit;           
        } 
        
        return $this->output($format,$eventsNear);
    }
    
    public function page404 () {
        header("HTTP/1.0 404 Not Found");
        echo "404";
        exit;
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
    
    public function id($params) {
        
        $events = $this->em->getRepository('Entity\Event')->findAll();
        
        $format = isset($params['format'])?$params['format']:$this->parameters['defaultOutputFormat'];
        
        return $this->output($format, $events, "onlyIdRdf",false) ;
        
    }
    
    public function event($params) {
        
        if (!isset($params['id'])) {
            $this->badRequest(7, "pleaseprovide an event identifier");
        }
               
        $event = $this->em->getRepository('Entity\Event')->findByIdPatio($params['id']);
        
        $format = isset($params['format'])?$params['format']:$this->parameters['defaultOutputFormat'];
        
        return $this->output($format, $event);
        
    }
    
    private function badRequest($code,$message){
        header("HTTP/1.1 400 Bad Request");
        $errors = array("errors" => array(array("code" => $code, "message" => $message)));
        echo json_encode($errors);
        exit;        
    }

    public function checkFormat($format) {

        // checking if the given output format is supported
        if (!in_array($format, array('json', 'rdf'))) {            
            $this->badRequest(8, "format parameter can only be json or rdf. Default is ".$this->parameters['defaultOutputFormat']);
        }
    }    
    
    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
      $R = 6371;
      $dLat = deg2rad($lat2 - $lat1);
      $dLon = deg2rad($lon2 - $lon1);

      $lat1 = deg2rad($lat1);
      $lat2 = deg2rad($lat2);

      $a = sin($dLat / 2) * sin($dLat/2) +
        sin($dLon / 2) * sin($dLon / 2) * 
        cos($lat1) * cos($lat2);

      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      return $R * $c;
    }        
  
}