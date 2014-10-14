<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 1/5/14
 * Time: 5:48 PM
 */

namespace Archfest\Bundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ContactUsTranslation")
 */
class ContactUs extends BaseTranslate implements Translatable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\ContactUsTranslation",
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
        return 'Контакты';
    }

    public function getTown ($locale = null){
        return $this->_getTranslatedField('town', $locale);
    }

    public function getAddress ($locale = null){
        return $this->_getTranslatedField('address', $locale);
    }
}