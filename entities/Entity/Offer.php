<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity
 */
class Offer
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
     * @ORM\Column(name="item_offered_fr", type="string", nullable=true)
     */
    private $itemOfferedFr;
    
    /**
     * @var string
     *
     * @ORM\Column(name="item_offered_en", type="string", nullable=true)
     */
    private $itemOfferedEn;

    /**
     * @var string
     *
     * @ORM\Column(name="description_fr", type="string", nullable=true)
     */
    private $descriptionFr;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="string", nullable=true)
     */
    private $descriptionEn;    

    /**
     * @var string
     *
     * @ORM\Column(name="eligible_customer_type", type="string",  nullable=true)
     */
    private $eligibleCustomerType;

    /**
     * @var float
     *
     * @ORM\Column(name="min_price", type="float", nullable=true)
     */
    private $minPrice;
    
    /**
     * @var float
     *
     * @ORM\Column(name="max_price", type="float", nullable=true)
     */
    private $maxPrice;

    /**
     * @var \Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="offers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;    
    
    public function setEvent($value){
        $this->event = $value;
        $value->addOffer($this);
    }
    
    public function get($property) 
    { 
        return $this->$property; 
    } 
    public function set($property, $value) 
    { 
        $this->$property = $value; 
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
