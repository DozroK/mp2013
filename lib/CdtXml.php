<?php
namespace lib;

use SimpleXMLElement;
use DateTime;
use Entity\Offer;
use Entity\OpeningHours;

class CdtXml extends SimpleXMLElement
{


    /**
     * getPlaceName function.
     * 
     * @access public
     * @param mixed $index
     * @return string
     * history : 
     * https://github.com/DozroK/mp2013/issues/28#issuecomment-11686718
     */
    public function getPlaceName($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:NomAnnonce[1]/fr[1]";
        return $this->getValue($index, $path);
    }

    public function getPlaceStreetAddress($index) {

        $paths[] = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:LigneAdresse1[1]";
        $paths[] = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:Numero[1]";
        $paths[] = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:Voie[1]/cdt:TypeVoie[1]/fr[1]";
        $paths[] = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:NomVoie[1]";

        $values = $this->getValues($index, $paths);
        $strlen = 0;
        foreach ($values as $value) {
            $strlen += strlen(trim($value));
        }
        if ($strlen == 0) {
            return null;
        }
        
        if ($values) {
            return trim((empty($values[0]) ? "" : $values[0]. ", "). $values[1]. " ".$values[2]." ".$values[3]);
        }
        return null;
    }


    public function getAddressLocality($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:Ville[1]/cdt:Ville[1]/cdt:NomVille[1]/node()[1]";
        if ($this->getValue($index, $path)) {
            return $this->getValue($index, $path);
        }

        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuLibre[1]/cdt:Ville[1]/cdt:Ville[1]/cdt:NomVille[1]" ;
        if ($this->getValue($index, $path)) {
            return $this->getValue($index, $path);
        }
    }

    public function getPostalCode($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:CodePostal[1]";
        return $this->getValue($index, $path);
    }

    public function getLatitude($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:SituationGeographique[1]/cdt:Latitude[1]/node()[1]";
        return $this->getValue($index, $path);
    }

    public function getLongitude($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:SituationGeographique[1]/cdt:Longitude[1]/node()[1]";
        return $this->getValue($index, $path);
    }

    public function getEventName($index, $lang) {
        $path = "/cdt:InformationGenerales[1]/cdt:NomAnnonce[1]/".$lang."[1]/node()[1]";
        return $this->getValue($index, $path);
    }

    public function getEventType($index, $lang) {
        // Whichever $lang, we take the "french" path
        $path = "/cdt:InformationsGestion[1]/cdt:InformationsSpecifiques[1]/cdt:Discipline[1]/cdt:TypeDiscipline[1]/fr[1]/node()[1]";
        return $this->getValue($index, $path);
    }

    public function getEventDescription($index, $lang) {
        $paths[] = "/cdt:ProgrammeDescriptif[1]/cdt:DescriptifValorisant[1]/".$lang."[1]/node()[1]";
        $paths[] = "/cdt:InformationGenerales[1]/cdt:EvenementPere[1]/cdt:InformationsGenerales[1]/cdt:DescriptifValorisant[1]/".$lang."[1]/node()[1]";
        
        
        return implode(PHP_EOL."===".PHP_EOL, $this->getValues($index, $paths));
    }

    public function getEventStartDate($index) {
        $path = "/cdt:PeriodesEtDates[1]/cdt:Definitions[1]/cdt:Debut[1]/node()[1]";
        return new DateTime($this->getValue($index, $path));
    }

    public function getEventEndDate($index) {
        $path = "/cdt:PeriodesEtDates[1]/cdt:Definitions[1]/cdt:Fin[1]/node()[1]";
        return new DateTime($this->getValue($index, $path));
    }
    
    public function getEventOpeningHours($index) {
        
        $return = array();
        
        $daysInFrench=array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
        
        foreach ($daysInFrench as $day) {
            
            for ($i = 1; $this->getValue($index,"/cdt:PeriodesEtDates[1]/cdt:Definitions[1]/cdt:JoursNommes[1]/cdt:Heures$day"."[$i]") instanceof CdtXml ;$i++) {
               
                $openingPath = "/cdt:PeriodesEtDates[1]/cdt:Definitions[1]/cdt:JoursNommes[1]/cdt:Heures$day"."[$i]/cdt:Debut[1]";
                $closingPath = "/cdt:PeriodesEtDates[1]/cdt:Definitions[1]/cdt:JoursNommes[1]/cdt:Heures$day"."[$i]/cdt:Fin[1]";
                
                $opens = $this->getValue($index, $openingPath);
                $closes = $this->getValue($index, $closingPath);
                
                if( isset($closes) and isset($opens) ){
                      
                    $openingHours = new OpeningHours();
                    $openingHours->setCloses(new DateTime($closes));
                    $openingHours->setDayOfWeek(OpeningHours::frDayOfWeekToRDFSpec( $day));
                    $openingHours->setOpens(new DateTime($opens));
                    $return[]=$openingHours;  
                }
            }
        }
        return $return;
    }
    
    public function hasEventProducer($index, $i) {
        if ($this->getValue($index,"/cdt:InformationGenerales[1]/cdt:Organisateur[$i]") instanceof CdtXml) {
            return true;
        } else {
            return false;
        }
    }

    public function getEventOffers($index){
         
        $return = array();
        
        for ($i = 1; $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]") instanceof CdtXml ;$i++) {
            
            $maxPrice             =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:PrixMax[1]");
            $minPrice             =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:PrixMin[1]");
            $itemOfferedFr        =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:Type[1]/cdt:Type[1]/fr[1]");
            $itemOfferedEn        =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:Type[1]/cdt:Type[1]/en[1]");            
            $descriptionFr        =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:DescriptifPrix[1]/fr[1]");
            $descriptionEn        =  $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:DescriptifPrix[1]/en[1]");           
            
            $eligibleCustomerTypes =  array();
            
            for($j = 1; $this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:Categorie[$j]") instanceof CdtXml ;$j++){
            
                $eligibleCustomerTypes[] =  (string)$this->getValue($index,"/cdt:Tarification[1]/cdt:Tarifs[$i]/cdt:Categorie[$j]/cdt:Categorie[1]/fr[1]");
            }
                        
            if( empty ($eligibleCustomerTypes) ){
                $offer = new Offer();  
                $offer->set("maxPrice", $maxPrice === null ? null : (float)$maxPrice);
                $offer->set("minPrice", $minPrice === null ? null : (float)$minPrice);
                $offer->set("itemOfferedFr", $itemOfferedFr === null ? null : (string)$itemOfferedFr);
                $offer->set("itemOfferedEn", $itemOfferedEn === null ? null : (string)$itemOfferedEn);
                $offer->set("descriptionFr", $descriptionFr === null ? null : (string)$descriptionFr);
                $offer->set("descriptionEn", $descriptionEn === null ? null : (string)$descriptionEn);
                
                $return[] = $offer;
                
            }else{
                
                foreach($eligibleCustomerTypes as $type){
                    $offer = new Offer();  
                    $offer->set("maxPrice", $maxPrice === null ? null : (float)$maxPrice);
                    $offer->set("minPrice", $minPrice === null ? null : (float)$minPrice);
                    $offer->set("itemOfferedFr", $itemOfferedFr === null ? null : (string)$itemOfferedFr);
                    $offer->set("itemOfferedEn", $itemOfferedEn === null ? null : (string)$itemOfferedEn);
                    $offer->set("descriptionFr", $descriptionFr === null ? null : (string)$descriptionFr);
                    $offer->set("descriptionEn", $descriptionEn === null ? null : (string)$descriptionEn);
                    
                    if(!empty($type)){
                        $offer->set("eligibleCustomerType", $type);
                    }
                    $return[] = $offer;
                }               
            }
        }
        return $return;
    }
        
    public function getImage($index)
    {
        $base = "http://www.mp2013.fr/ext/basephotos/";
        $extensions = array("JPG", "jpeg", "JPEG", "jpg", "PNG", "png");
        $idPatio = $this->getIdPatio($index);
        foreach ($extensions as $extension) {
            $url = $base.$idPatio.".".$extension;
            // très couteux : 
            $header = (get_headers($url));
            if (strpos($header[0],"200") !== false) {
                return $url;
            }
        }
        return null;
    }

    public function getSuperEvent($index) {
        $superEvents = array(
            "a509c46d-0c64-4cb4-89f2-8aa06ae5a53f" => "http://data.mp2013.fr/episode/#1",
            "b2eb0aec-e755-4d1d-946f-c49d44fc35e6" => "http://data.mp2013.fr/episode/#2",
            "905b4dc6-3059-4daa-b64d-08c80cae12dd" => "http://data.mp2013.fr/episode/#3"
        );
        $path = "/cdt:InformationsGestion[1]/cdt:InformationsSpecifiques[1]/cdt:Episode[1]/cdt:TypeEpisode[1]/@jcr:uuid";
        $id = $this->getValue($index, $path);
        $id = (string)$id;
        if (!empty($id)) {
            if (isset($superEvents[$id])) {
                return $superEvents[$id];
            }
        }
        return null;
    }

    public function getIdPatio($index) {
        $array = $this->xpath("/cdt:export[1]/object[".$index."]");
        return $array[0]->attributes()->name;
    }


    public function getDisability($index) {

        for ($i = 1; ; $i++) {

            $condition = $this->getValue($index,"/cdt:AccueilClientele[1]/cdt:ConditionAccueil[1]/cdt:TypeCondition[1]/cdt:TypeConditionAccueil[$i]/@jcr:uuid") ;

            if (!($condition instanceof CdtXml)) {
                break;
            }
            
            if ((string)$condition == "a958bee0-70df-4ecf-80da-710028565e12") {
                return true;
            }
        }
        return false;
    }
    
    public function getCanceled ($index) {
        $path = "/cdt:InformationsGestion[1]/cdt:Audit[1]/@cdt:IndicateurAnnonceSupprimee" ;
        if ((string)$this->getValue($index, $path) == "true") {
            return true;
        }
        return false;
    }

    public function getFree ($index) {
        $path = "/cdt:Tarification[1]/cdt:Gratuit[1]/node()[1]" ;
        if ((string)$this->getValue($index, $path) == "true") {
            return true;
        }
        return false;
    }


    public function getProducerUuid($index, $i) {
        $path = "/cdt:InformationGenerales[1]/cdt:Organisateur[".$i."]/@jcr:uuid";
        return (string)$this->getValue($index, $path);
    }

    public function getProducerName($index, $i) {
        $path = "/cdt:InformationGenerales[1]/cdt:Organisateur[".$i."]/cdt:InformationsGenerales[1]/cdt:NomAnnonce[1]/fr[1]/node()[1]";
        return (string)$this->getValue($index, $path);
    }

    public function getProducerTelephone($index, $i) {
        $path = "/cdt:InformationGenerales[1]/cdt:Organisateur[".$i."]/cdt:InformationsGenerales[1]/cdt:Telephone[1]/node()[1]";
        return (string)$this->getValue($index, $path);
    }

    public function getProducerUrl($index, $i) {
        $path = "/cdt:InformationGenerales[1]/cdt:Organisateur[".$i."]/cdt:InformationsGenerales[1]/cdt:AdresseWeb[1]/node()[1]";
        return (string)$this->getValue($index, $path);
    }


    public function hasObject($index) {
        $array = $this->xpath("/cdt:export[1]/object[".$index."]");
        if (empty($array)) {
            return false;
        }
        return true;
    }

          
    private function getValue($index, $path) {
        $array = $this->xpath("/cdt:export[1]/object[".$index."]".$path);
        
        if (isset($array[0])) { 
            return ($array[0]);
        }
        return null;
    }
    
    private function getValues($index, $paths) {
        foreach ($paths as $path) {
            $values[] = $this->getValue($index, $path);
        }
        return $values;
    }
}