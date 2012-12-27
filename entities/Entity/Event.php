<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EventRepository")
 */
class Event
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
     * @ORM\Column(name="id_patio", type="string", length=255, nullable=false)
     */
    private $idPatio;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=2, nullable=true)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var datetime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="super_event", type="string", length=36, nullable=true)
     */
    private $superEvent;


    /**
     * @var \Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * })
     */
    private $place;

    /**
     * @ORM\OneToMany(targetEntity="Offer", mappedBy="event", cascade="remove")
     */
    private $offers; 
    

    /**
     * @var \Producer
     *
     * @ORM\ManyToMany(targetEntity="Producer", inversedBy="events")
     * @ORM\JoinTable(name="events_producers",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="producer_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $producers;


    public function __construct()
    {
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function addOffer(Offer $offer)
    {
        $this->offers[] = $offer;
        return $this;
    }

    public function removeOffer(Offer $offer)
    {
        $this->offers->removeElement($offer);
    }    
    
    public function get($property) 
    { 
        return $this->$property; 
    } 
    public function set($property, $value) 
    { 
        $this->$property = $value; 
    } 

    public function addProducer($producer) 
    { 
        $this->producers[$producer->getUuid()] = $producer; 
    } 

    public function removeProducers() 
    { 
        $this->producers = new \Doctrine\Common\Collections\ArrayCollection();
    } 

    public function hasProducer($uuid) {
        if (isset($this->producers[$uuid])) {
            return true;
        }
        return false;

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
      if ($prefix == 'add' && $cattrs == 1) { // Un setter (avec des attributs)
        return $this->add($suffix, $attrs[0]);
      }
    }
    trigger_error("La méthode $method n’existe pas.");
  }


}
