<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:15 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;


use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Interfaces\iObjectWithImg;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\FoundersTranslation")
 */
class Founders extends BaseTranslate implements Translatable, iObjectWithImg {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="TypesOfFounders", inversedBy="projects")
     */
    protected  $type;


    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;


    /**
     * @ORM\OneToMany(targetEntity="FoundersImg", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $img;

    /**
     * @var string $status
     * @ORM\Column(type="boolean")
     */
    protected $status = false;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\FoundersTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->img = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set type
     *
     * @param \Archfest\Bundle\Entity\TypesOfFounders $type
     * @return Founders
     */
    public function setType(\Archfest\Bundle\Entity\TypesOfFounders $type = null)
    {
        $this->type = $type;
    
        return $this;
    }


    /**
     * Set status
     *
     * @param boolean $status
     * @return Projects
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Get type
     *
     * @return \Archfest\Bundle\Entity\TypesOfFounders 
     */
    public function getType()
    {
        return $this->type;
    }

    public function getName ($locale = null){
        return $this->_getTranslatedField('name', $locale);
    }

    public function getContent ($locale = null){
        return $this->_getTranslatedField('content', $locale);
    }

    public function __toString () {
        if ($this->getId()) {
            return 'Учредитель';
        }

        return 'Добавление учредителя';
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }
}