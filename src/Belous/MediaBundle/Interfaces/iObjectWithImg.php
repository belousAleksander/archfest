<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/15/13
 * Time: 9:43 PM
 */

namespace Belous\MediaBundle\Interfaces;


// Объявим интерфейс 'iTemplate'
interface iObjectWithImg
{
    public function addImg(iObjectImg $projectImg);

    public function removeImg(iObjectImg $projectImg);

    public function getImg();
}