<?php
class CdtXml extends SimpleXMLElement
{

    public function getAddressLocality($index) {
        $path = "/cdt:InformationGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuAnnonce[1]/cdt:InformationsGenerales[1]/cdt:Adresse[1]/cdt:Ville[1]/cdt:Ville[1]/cdt:NomVille[1]/node()[1]";
        if ($this->getValue($index, $path)) {
            return $this->getValue($index, $path);
        }

        $path = "/cdt:InformationGenerales[1]/cdt:EvenementPere[1]/cdt:InformationsGenerales[1]/cdt:LieuEvenement[1]/cdt:LieuLibre[1]/cdt:Ville[1]/cdt:Ville[1]/cdt:NomVille[1]/node()[1]";
        return $this->getValue($index, $path);
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
        $path = "/cdt:ProgrammeDescriptif[1]/cdt:DescriptifValorisant[1]/".$lang."[1]/node()[1]";
        return $this->getValue($index, $path);
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
                      
                    $openingHours = new Entity\OpeningHours();
                    $openingHours->setCloses(new DateTime($closes));
                    $openingHours->setDayOfWeek(Entity\OpeningHours::frDayOfWeekToRDFSpec( $day));
                    $openingHours->setOpens(new DateTime($opens));
                    $return[]=$openingHours;  
                }
            }
        }
        return $return;
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
                $offer = new Entity\Offer();  
                $offer->set("maxPrice", $maxPrice === null ? null : (float)$maxPrice);
                $offer->set("minPrice", $minPrice === null ? null : (float)$minPrice);
                $offer->set("itemOfferedFr", $itemOfferedFr === null ? null : (string)$itemOfferedFr);
                $offer->set("itemOfferedEn", $itemOfferedEn === null ? null : (string)$itemOfferedEn);
                $offer->set("descriptionFr", $descriptionFr === null ? null : (string)$descriptionFr);
                $offer->set("descriptionEn", $descriptionEn === null ? null : (string)$descriptionEn);
                
                $return[] = $offer;
                
            }else{
                
                foreach($eligibleCustomerTypes as $type){
                    $offer = new Entity\Offer();  
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

    public function getIdPatio($index) {
        $array = $this->xpath("/cdt:export[1]/object[".$index."]");
        return $array[0]->attributes()->name;
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
    
}