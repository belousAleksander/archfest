<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/26/13
 * Time: 8:03 PM
 */

namespace Archfest\Bundle\Entity\Translations;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="foundersImg_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class FoundersImgTranslation extends BaseTranslation {
    /**
     * @ORM\ManyToOne(targetEntity="Archfest\Bundle\Entity\FoundersImg", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
} 