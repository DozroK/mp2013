<?php
namespace RDFHelper;
use DateTime;
use Normalizer;
class Event extends \Entity\Event
{
    private static $eventTypeResources = array(
    "Expositions / Musées" => "http://data.mp2013.fr/VisualArtsEvent",
    "Festivals et Grands rassemblements" => "http://data.mp2013.fr/Festival",
    "Danse et Opéra" => "http://data.mp2013.fr/DanceEvent",
    "Concerts / Musique" => "http://data.mp2013.fr/MusicEvent",
    "Rencontres / Colloques" => "http://data.mp2013.fr/BusinessEvent",        
    "Ouverture / Inauguration" => "http://data.mp2013.fr/SocialEvent",
    "Théatre et Cinéma" => "http://data.mp2013.fr/TheaterEvent",
    "Arts de la rue et du cirque" => "http://data.mp2013.fr/ComedyEvent");

    private $event;
    private $place;
    
    public function __construct(\Entity\Event $event) {
        $this->event = $event;
        $this->place = new Place($this->event->getPlace());
    }

    public function getPlace() {
        return $this->place;
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
        return "http://data.mp2013.fr/Event";
    }

    private function getMarkup($key, $value, $lang = null, $parseType = null) {
 
        $xmllang = "";
        if ($lang) {
            $xmllang = " xml:lang='".$this->event->getLang()."'";
        }
        if ($parseType) {
            $parseType = " rdf:parseType='".$parseType."'";
        }
        return "<event:".$key.$parseType.$xmllang.">".$value."</event:".$key.">".PHP_EOL;
        
        
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
            $description = $this->event->getDescription();

/*            $description = htmlentities($this->event->getDescription(), ENT_COMPAT, 'UTF-8');
            $description = html_entity_decode($description,  ENT_COMPAT, 'UTF-8');
*/

            return $this->getMarkup("description", htmlspecialchars($description, ENT_QUOTES), true, 'Literal');

//            return $this->getMarkup("description", htmlspecialchars(Normalizer::normalize($this->event->getDescription()), ENT_QUOTES), true, 'Literal');
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

    public function getDisability() {
        return $this->getMarkup("disability", ($this->event->getDisability() ? 'True' : 'False'));
    }

    public function getCanceled() {
        return $this->getMarkup("canceled", ($this->event->getCanceled() ? 'True' : 'False'));
    }

    public function getFree() {
        if ($this->event->getFree()) {
            return $this->getMarkup("free", ($this->event->getFree() ? 'True' : 'False'));
        }
        return "";
    }


    public function getTag($switch, $key) {
        $switchTags = array("open" => "<", "close" => "</");
        return $switchTags[$switch]."event:".$key.">".PHP_EOL;
    }

}
