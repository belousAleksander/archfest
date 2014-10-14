<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 5/26/14
 * Time: 8:36 PM
 */

namespace Belous\FlashBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class Flash {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $src
     * @ORM\Column(type="string", length=255)
     */
    protected $src;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return Flash
     */
    public function setSrc($src)
    {
        $this->src = $src;
    
        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }
}