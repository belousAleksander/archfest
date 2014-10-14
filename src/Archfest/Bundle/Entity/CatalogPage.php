<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/10/13
 * Time: 7:58 PM
 */

namespace Archfest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\CatalogPageTranslation")
 */
class CatalogPage extends BaseTranslate implements Translatable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\CatalogPageTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
    /**
     * Constructor
     */
    public function __construct()
    {
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

    public function getTitle ($locale = null){
        return $this->_getTranslatedField('title', $locale);
    }

    public function getMKeywords ($locale = null){
        return $this->_getTranslatedField('mKeywords', $locale);
    }

    public function getMDescription ($locale = null){
        return $this->_getTranslatedField('mDescription', $locale);
    }

    public function getContent ($locale = null){
        return $this->_getTranslatedField('content', $locale);
    }

    public function __toString () {
        return 'Описание страницы';
    }
}