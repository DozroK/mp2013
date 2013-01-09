<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="EventRepository")
 */
class History
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
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;



    public function __construct()
    {
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
      if ($prefix == 'add' && $cattrs == 1) { // Un setter (avec des attributs)
        return $this->add($suffix, $attrs[0]);
      }
    }
    trigger_error("La méthode $method n’existe pas.");
  }


}
