<?php
namespace RDFHelper;
use DateTime;
class Event
{
    private static $normalizedEventTypes = array(
    "Expositions / Musées" => "VisualArtsEvent",
    "Festivals et Grands rassemblements" => "Festival",
    "Danse et Opéra" => "DanceEvent",
    "Concerts / Musique" => "MusicEvent",
    "Rencontres / Colloques" => "UserInteraction",        
    "Ouverture / Inauguration" => "SocialEvent",
    "Théatre et Cinéma" => "TheaterEvent",
    "Arts de la rue et du cirque" => "ComedyEvent");

    private $event;

    public function __construct(\Entity\Event $event) {
        $this->event = $event;
    }

    public function __call($method, $attrs) {
        return $this->event->$method();
    }
    
    public function getNormalizedType() {
        if (isset(self::$normalizedEventTypes[$this->event->getType()])) {
            return strtolower(self::$normalizedEventTypes[$this->event->getType()]);
        }
        return "event";
    }

    private function getRDFMarkup($key, $value, $lang = null) {
        $xmllang = "";
        if ($lang) {
            $xmllang = " xml:lang='".$this->event->getLang()."'";
        }
        return "<".$this->getNormalizedType().":".$key.$xmllang.">".$value."</".$this->getNormalizedType().":".$key.">".PHP_EOL;
    }

    public function getRDFName() { 
        return $this->getRDFMarkup("name", htmlspecialchars($this->event->getName(), ENT_QUOTES), true);
    } 
    public function getRDFDescription() {
        return $this->getRDFMarkup("description", htmlspecialchars($this->event->getDescription(), ENT_QUOTES), true);
    }

    public function getRDFStartDate() {
        return $this->getRDFMarkup("startDate", $this->event->getStartDate()->format(DateTime::ISO8601));
        
    }

    public function getRDFEndDate() {
        return $this->getRDFMarkup("endDate", $this->event->getEndDate()->format(DateTime::ISO8601));
    }

    public function getRDFImage() {
        return $this->getRDFMarkup("image", $this->event->getImage());
    }
    public function getRDFTag($switch, $key) {
        $switchTags = array("open" => "<", "close" => "</");
        return $switchTags[$switch].$this->getNormalizedType().":".$key.">".PHP_EOL;
    }

}
