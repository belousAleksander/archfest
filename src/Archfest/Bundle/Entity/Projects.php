<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/3/13
 * Time: 9:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Belous\FlashBundle\Entity\Flash;
use Belous\FlashBundle\Interfaces\iObjectWithFlash;
use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Interfaces\iObjectWithImg;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ProjectsTranslation")
 */
class Projects extends BaseTranslate implements Translatable, iObjectWithImg, iObjectWithFlash {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="TypesOfProjects", inversedBy="projects")
     */
    protected  $type;

    /**
     * @var string $year
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @var string $area
     * @ORM\Column(type="string")
     */
    protected $area;


    /**
     * @var string $enabled
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;


    /**
     * @var string $top
     * @ORM\Column(type="boolean")
     */
    protected $top = false;

    /**
     * @ORM\OneToMany(targetEntity="ProjectImg", mappedBy="object", cascade={"persist", "remove"})
     */
    protected  $img;


    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\ProjectsTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * Односторонняя связь - множество пользователей могут пометить множество комментариев как прочтенные
     *
     * @ORM\ManyToMany(targetEntity="Belous\FlashBundle\Entity\Flash" , cascade={"persist", "remove"})
     */
    protected  $flash;

    /**
     * @var string $flashEnabled
     * @ORM\Column(type="boolean")
     */
    protected $flashEnabled = false;

    /**
     * @var string $imgEnabled
     * @ORM\Column(type="boolean")
     */
    protected $imgEnabled = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->img = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->flash = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param \Archfest\Bundle\Entity\TypesOfProjects $type
     * @return Projects
     */
    public function setType(\Archfest\Bundle\Entity\TypesOfProjects $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Archfest\Bundle\Entity\TypesOfProjects
     */
    public function getType()
    {
        return $this->type;
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


    public function getTitle ($locale = null){
        return $this->_getTranslatedField('title', $locale);
    }

    public function getName ($locale = null){
        return $this->_getTranslatedField('name', $locale);
    }


    public function getBriefInformation ($locale = null){
        return $this->_getTranslatedField('briefInformation', $locale);
    }

    public function __toString () {
        $title = 'Добавления нового проэкта';

        if ($this->getId()) {
            $title = $this->getTitle();
        }
        return $title;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Projects
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
     * Set top
     *
     * @param boolean $top
     * @return Projects
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return boolean
     */
    public function getTop()
    {
        return $this->top;
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
     * Set flashEnabled
     *
     * @param boolean $flashEnabled
     * @return Projects
     */
    public function setFlashEnabled($flashEnabled)
    {
        $this->flashEnabled = $flashEnabled;
    
        return $this;
    }

    /**
     * Get flashEnabled
     *
     * @return boolean 
     */
    public function getFlashEnabled()
    {
        return $this->flashEnabled;
    }

    /**
     * Add flash
     *
     * @param \Belous\FlashBundle\Entity\Flash $flash
     * @return Projects
     */
    public function addFlash(\Belous\FlashBundle\Entity\Flash $flash)
    {
        $this->flash[] = $flash;
    
        return $this;
    }

    /**
     * Remove flash
     *
     * @param \Belous\FlashBundle\Entity\Flash $flash
     */
    public function removeFlash(\Belous\FlashBundle\Entity\Flash $flash)
    {
        $this->flash->removeElement($flash);
    }

    /**
     * Get flash
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFlash()
    {
        return $this->flash;
    }

    public function getFlashById($flashId) {
        $filterResult = $this->getFlash()->filter(function ($flash) use ($flashId) {
            /** @var Flash  $flash */
            return $flash->getId() === (int) $flashId;
        });

        /** @var ArrayCollection $filterResult */
        if($filterResult->count() > 0) {
            return $filterResult->last();
        }

        return null;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return Projects
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set imgEnabled
     *
     * @param boolean $imgEnabled
     * @return Projects
     */
    public function setImgEnabled($imgEnabled)
    {
        $this->imgEnabled = $imgEnabled;
    
        return $this;
    }

    /**
     * Get imgEnabled
     *
     * @return boolean 
     */
    public function getImgEnabled()
    {
        return $this->imgEnabled;
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