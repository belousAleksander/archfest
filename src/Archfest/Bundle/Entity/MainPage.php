<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 8:19 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Gedmo\Translatable\Translatable;
/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\MainPageTranslation")
 */
class MainPage extends BaseTranslate implements Translatable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\MainPageTranslation",
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


    public function __toString() {
        return "Редактирования главной страницы";
    }

    public function getContent ($locale = null){
        return $this->_getTranslatedField('content', $locale);
    }
}