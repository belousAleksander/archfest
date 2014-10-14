<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/3/13
 * Time: 9:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects_img_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ProjectImgTranslation extends BaseTranslation {


    /**
     * @ORM\ManyToOne(targetEntity="Archfest\Bundle\Entity\ProjectImg", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

}