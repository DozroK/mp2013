<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpeningHours
 *
 * @ORM\Table(name="opening_hours")
 * @ORM\Entity
 */
class OpeningHours
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="day_of_week", type="string", nullable=false)
     */
    private $dayOfWeek;    
    
    /**
     * @var DateTime
     *
     * @ORM\Column(name="opens", type="time", nullable=false)
     */
    private $opens;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="closes", type="time", nullable=false)
     */
    private $closes;    
    
    /**
     * @var DateTime
     *
     * @ORM\Column(name="valid_from", type="datetime", nullable=true)
     */
    private $validFrom;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="valid_trough", type="datetime", nullable=true)
     */
    private $validTrough;

    /**
     * @var \Place
     *
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="openingHours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * })
     */
    private $place;
        
    public function get($property) 
    { 
        return $this->$property; 
    } 
    public function set($property, $value) 
    { 
        $this->$property = $value; 
    }
    
    public function setPlace($value){
        $this->place = $value;
        $value->addOpeningHours($this);
    }
    
    public static function frDayOfWeekToRDFSpec($value) 
    { 
        $days = array(
            "Lundi" => "Monday",
            "Mardi" => "Tuesday",
            "Mercredi" => "Wednesday",
            "Jeudi" => "Thursday",
            "Vendredi" => "Friday",
            "Samedi" => "Saturday",
            "Dimanche" => "Sunday",
        );
        if(empty($days[$value])){
            return "";    
        }
        return "http://purl.org/goodrelations/v1#".$days[$value];
    } 

 
  /* Notre fameuse fonction _call, appelée lorsque d'une fonction inexistante est demandée
    http://web-de-franck.com/blog/
    */
  public function __call($method, $attrs) {
    $prefix = substr($method, 0, 3); // "get" ou "set"
    $suffix = chr(ord(substr($method, 3, 1)) + 32);
    $suffix .= substr($method, 4); // Récupération du nom de l'attribut
    $cattrs = count($attrs);
    if (property_exists($this, $suffix)) {
      if ($prefix == 'set' && $cattrs == 1) { // Un setter (avec des attributs)
        return $this->set($suffix, $attrs[0]);
      }
      if ($prefix == 'get' && $cattrs == 0) { // Un getter (sans attributs)
        return $this->get($suffix);
      }
    }
    trigger_error("La méthode $method n’existe pas.");
  }


}
