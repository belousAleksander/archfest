<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/3/13
 * Time: 9:38 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Belous\MediaBundle\Entity\Images;
use Belous\MediaBundle\Interfaces\iObjectImg;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ProjectImgTranslation")
 */
class FoundersImg extends Images implements Translatable, iObjectImg {

    /**
     * @ORM\ManyToOne(targetEntity="Founders", inversedBy="img")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\FoundersImgTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

}