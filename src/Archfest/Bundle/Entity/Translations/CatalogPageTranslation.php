<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/10/13
 * Time: 8:00 PM
 */

namespace Archfest\Bundle\Entity\Translations;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalogPage_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class CatalogPageTranslation extends BaseTranslation{
    /**
     * @ORM\ManyToOne(targetEntity="Archfest\Bundle\Entity\CatalogPage", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}