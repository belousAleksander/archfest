<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\TypesOfFoundersTranslation")
 */
class TypesOfFounders extends BaseTranslate implements Translatable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\TypesOfFoundersTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @var string $enabled
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;

    /**
     * @ORM\OneToMany(targetEntity="Founders", mappedBy="type", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected  $founders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->founders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param null $locale
     * @return string
     */
    public function getName($locale = null) {
       return $this->_getTranslatedField('name', $locale);
    }

    public function __toString() {
        $name = 'Добавления нового типа учредителей';

        if ($this->getId()) {
            $name = $this->getName();
        }
        return $name;
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
     * Add founders
     *
     * @param Founders $founders
     * @return TypesOfProjects
     */
    public function addFounders(Founders $founders)
    {
        $this->founders[] = $founders;

        return $this;
    }

    /**
     * Remove founders
     *
     * @param Founders $founders
     */
    public function removeFounders(Founders $founders)
    {
        $this->founders->removeElement($founders);
    }

    /**
     * Get founders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFounders()
    {
        return $this->founders;
    }

}