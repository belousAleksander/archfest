<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\AboutUsTranslation")
 */
class AboutUs extends BaseTranslate implements Translatable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\AboutUsTranslation",
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

    public function __toString () {
        return 'О нас';
    }


    public function getShortInfo ($locale = null){
        return $this->_getTranslatedField('shortInfo', $locale);
    }

    public function getContent ($locale = null){
        return $this->_getTranslatedField('content', $locale);
    }

    public function getFooter($locale = null){
        return $this->_getTranslatedField('footer', $locale);
    }
}