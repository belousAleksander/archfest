<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 7:51 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="construction_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ConstructionTranslation extends BaseTranslation {
    /**
     * @ORM\ManyToOne(targetEntity="Archfest\Bundle\Entity\Construction", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

}