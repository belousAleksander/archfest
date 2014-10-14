<?php
namespace Belous\FlashBundle\Interfaces;
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 5/27/14
 * Time: 9:52 PM
 */

interface iObjectWithFlash {
    /**
     * Add flash
     *
     * @param \Belous\FlashBundle\Entity\Flash $flash
     */
    public function addFlash(\Belous\FlashBundle\Entity\Flash $flash);

    /**
     * Remove flash
     *
     * @param \Belous\FlashBundle\Entity\Flash $flash
     */
    public function removeFlash(\Belous\FlashBundle\Entity\Flash $flash);

    /**
     * Get flash
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlash();

    public function getFlashById($flashId);
}