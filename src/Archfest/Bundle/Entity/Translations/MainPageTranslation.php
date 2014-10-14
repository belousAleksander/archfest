<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 8:51 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MainPageTranslation extends BaseTranslation {

    /**
     * @ORM\ManyToOne(targetEntity="Archfest\Bundle\Entity\MainPage", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

}