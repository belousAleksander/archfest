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
 * @ORM\Entity (repositoryClass="Archfest\Bundle\Repository\ProjectImgRepository")
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\ProjectImgTranslation")
 */
class ProjectImg extends Images implements Translatable, iObjectImg {

    /**
     * @ORM\ManyToOne(targetEntity="Projects", inversedBy="projectImg")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\ProjectImgTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

}