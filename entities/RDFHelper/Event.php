<?php
namespace RDFHelper;
use DateTime;
class Event extends \Entity\Event
{
    private static $eventTypeResources = array(
    "Expositions / Musées" => "http://schema.org/VisualArtsEvent",
    "Festivals et Grands rassemblements" => "http://schema.org/Festival",
    "Danse et Opéra" => "http://schema.org/DanceEvent",
    "Concerts / Musique" => "http://schema.org/MusicEvent",
    "Rencontres / Colloques" => "http://schema.org/UserInteraction",        
    "Ouverture / Inauguration" => "http://schema.org/SocialEvent",
    "Théatre et Cinéma" => "http://schema.org/TheaterEvent",
    "Arts de la rue et du cirque" => "http://schema.org/ComedyEvent");

    private $event;

    public function __construct(\Entity\Event $event) {
        $this->event = $event;
    }

    public function getParent() {
        return $this->event;
    }
    
    public function __call($method, $attrs) {
        return $this->event->$method();
    }
    
    public function getEventTypeResources() {
        if (isset(self::$eventTypeResources[$this->event->getType()])) {
            return (self::$eventTypeResources[$this->event->getType()]);
        }
        return "http://schema.org/Event";
    }

    private function getMarkup($key, $value, $lang = null) {
        $xmllang = "";
        if ($lang) {
            $xmllang = " xml:lang='".$this->event->getLang()."'";
        }
        return "<event:".$key.$xmllang.">".$value."</event:".$key.">".PHP_EOL;
    }

    public function getType() { 
        return '<rdf:type rdf:resource="'.$this->getEventTypeResources().'"/>';
    } 



    public function getName() { 
        if ($this->event->getName()) {
            return $this->getMarkup("name", htmlspecialchars($this->event->getName(), ENT_QUOTES), true);
        }
    } 
    public function getDescription() {
        if ($this->event->getDescription()) {
            return $this->getMarkup("description", htmlspecialchars($this->event->getDescription(), ENT_QUOTES), true);
        }
    }

    public function getStartDate() {
        return $this->getMarkup("startDate", $this->event->getStartDate()->format(DateTime::ISO8601));
        
    }

    public function getEndDate() {
        return $this->getMarkup("endDate", $this->event->getEndDate()->format(DateTime::ISO8601));
    }
    public function getSuperEvent() {
        if (strlen($this->event->getSuperEvent())>0) {
            return '<event:superEvent rdf:resource="'.$this->event->getSuperEvent().'" />';
        }
        return "";
    }


    public function getImage() {
        return $this->getMarkup("image", $this->event->getImage());
    }
    public function getTag($switch, $key) {
        $switchTags = array("open" => "<", "close" => "</");
        return $switchTags[$switch]."event:".$key.">".PHP_EOL;
    }

}
