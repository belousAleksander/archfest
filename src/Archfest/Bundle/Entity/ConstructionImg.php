<?php
namespace Archfest\Bundle\Entity;

use Belous\MediaBundle\Entity\Images;
use Belous\MediaBundle\Interfaces\iObjectImg;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ConstructionImgTranslation")
 */
class ConstructionImg extends Images implements Translatable, iObjectImg {
    /**
     * @ORM\ManyToOne(targetEntity="Construction", inversedBy="ConstructionImg")
     * @ORM\JoinColumn(name="construction_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\ConstructionImgTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

}