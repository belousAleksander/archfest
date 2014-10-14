<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/12/13
 * Time: 9:16 PM
 */

namespace Belous\MediaBundle\Entity;

use Archfest\Bundle\Entity\BaseTranslate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\MappedSuperclass
 */
class Images extends BaseTranslate {


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $objectName
     * @ORM\Column(type="string", length=255)
     */
    protected $object;

    /**
     * @var string $src
     * @ORM\Column(type="string", length=255)
     */
    protected $src;

    /**
     * @var string $src
     * @ORM\Column(type="string", length=100)
     */
    protected $fileName;


    /**
     * @var string $src
     * @ORM\Column(type="boolean")
     */
    protected $smallImg = false;

    /**
     * @var string $smallImgSrc
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $smallImgSrc;

    /**
     * @var string $main
     * @ORM\Column(type="boolean")
     */
    protected $main = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

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
     * Set object
     *
     * @param string $object
     * @return Images
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return Images
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

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Images
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set smallImg
     *
     * @param boolean $smallImg
     * @return Images
     */
    public function setSmallImg($smallImg)
    {
        $this->smallImg = $smallImg;
    
        return $this;
    }

    /**
     * Get smallImg
     *
     * @return boolean 
     */
    public function getSmallImg()
    {
        return $this->smallImg;
    }

    /**
     * Set smallImgSrc
     *
     * @param string $smallImgSrc
     * @return Images
     */
    public function setSmallImgSrc($smallImgSrc)
    {
        $this->smallImgSrc = $smallImgSrc;
    
        return $this;
    }

    /**
     * Get smallImgSrc
     *
     * @return string 
     */
    public function getSmallImgSrc()
    {
        return $this->smallImgSrc;
    }

    /**
     * @param bool $main
     * @return $this
     */
    public function setMain($main = false)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * @return bool
     */
    public function getMain()
    {
        return $this->main;
    }

    public function getAlt($locale = null) {
        return $this->_getTranslatedField('alt', $locale);
    }
}