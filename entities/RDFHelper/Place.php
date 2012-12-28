<?php
namespace RDFHelper;
use DateTime;
use Normalizer;
class Place extends \Entity\Place
{

    private $place;

    public function __construct(\Entity\Place $place) {
        $this->place = $place;
    }

    public function getParent() {
        return $this->place;
    }
    
    public function __call($method, $attrs) {
        return $this->place->$method();
    }
/*    
    private function getMarkup($key, $value, $lang = null, $parseType = null) {
 
        $xmllang = "";
        if ($lang) {
            $xmllang = " xml:lang='".$this->place->getLang()."'";
        }
        if ($parseType) {
            $parseType = " rdf:parseType='".$parseType."'";
        }
        return "<place:".$key.$parseType.$xmllang.">".$value."</place:".$key.">".PHP_EOL;
    }



    public function getFree() {
        if ($this->event->getFree()) {
            return $this->getMarkup("free", ($this->event->getFree() ? 'True' : 'False'));
        }
        return "";
    }
*/

    public function getGeo() { 

        if ($this->place->getLatitude() and $this->place->getLongitude()) {
            return "<place___geo>
                    <geo___latitude>".$this->place->getLatitude()."</geo___latitude>
                    <geo___longitude>".$this->place->getLongitude()."</geo___longitude>
                </place___geo>";
        }
        return "";
    }
}
