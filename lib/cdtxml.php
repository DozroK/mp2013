<?php
class CdtXml extends SimpleXMLElement
{
    private $addressLocality;

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
        $path = "/cdt:InformationsGestion[1]/cdt:InformationsSpecifiques[1]/cdt:Discipline[1]/cdt:TypeDiscipline[1]/".$lang."[1]/node()[1]";
        return $this->getValue($index, $path);
    }

    public function getEventDescription($index, $lang) {
        $path = "/cdt:ProgrammeDescriptif[1]/cdt:DescriptifValorisant[1]/".$lang."[1]/node()[1]";
        return $this->getValue($index, $path);
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