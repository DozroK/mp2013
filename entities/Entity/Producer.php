<?php
namespace Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Producer
 *
 * @ORM\Table(name="producer")
 * @ORM\Entity
 */
class Producer
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
     * @ORM\Column(name="uuid", type="string", length= 36, nullable=false, unique=true)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

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
     * @var \Event
     *
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="producers")
     *
     */
    private $events;
    
    static private $uuids = array() ;
    
    static function getFromMemory($uuid) {
        if (isset(self::$uuids[$uuid])) {
            return self::$uuids[$uuid];
        }
        return false;
    }

    static function addToMemory(Producer $producer) {
        self::$uuids[$producer->getUuid()] = $producer;
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
