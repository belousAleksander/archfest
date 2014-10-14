<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/15/13
 * Time: 9:43 PM
 */

namespace Belous\MediaBundle\Interfaces;

use Archfest\Bundle\Entity\Translations\BaseTranslation;

interface iObjectImg
{
    public function getId();

    public function addTranslation(BaseTranslation $translation);
}