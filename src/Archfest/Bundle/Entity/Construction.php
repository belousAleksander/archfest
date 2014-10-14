<?php

namespace Archfest\Bundle\Entity;

use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Interfaces\iObjectWithImg;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ConstructionTranslation")
 */
class Construction extends BaseTranslate implements Translatable, iObjectWithImg
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $enabled
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;

    /**
     * @var string $year
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\OneToMany(targetEntity="ConstructionImg", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $img;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\ConstructionTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->img = new ArrayCollection();
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Construction
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Construction
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }


    /**
     * Add projectImg
     * @param iObjectImg $projectImg
     * @return $this
     */
    public function addImg(iObjectImg $projectImg)
    {
        $this->img[] = $projectImg;

        return $this;
    }

    /**
     * Remove projectImg
     * @param iObjectImg $projectImg
     */
    public function removeImg(iObjectImg $projectImg)
    {
        $this->img->removeElement($projectImg);
    }

    /**
     * Get projectImg
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImg()
    {
        return $this->img;
    }


    public function __toString () {
        if ($this->getId()) {
            return 'Посткройка';
        }

        return 'Добавления новой постройки';
    }

    public function getDescription ($locale = null){
        return $this->_getTranslatedField('description', $locale);
    }
}