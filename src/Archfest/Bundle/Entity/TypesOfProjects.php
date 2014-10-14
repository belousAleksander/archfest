<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/3/13
 * Time: 9:35 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Archfest\Bundle\Entity\Translations\TypesOfProjectsTranslation")
 */
class TypesOfProjects extends BaseTranslate implements Translatable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $status
     * @ORM\Column(type="boolean")
     */
    protected $status = false;

    /**
     * @ORM\OneToMany(targetEntity="Projects", mappedBy="type", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $projects;

    /**
     * @ORM\OneToMany(targetEntity="Archfest\Bundle\Entity\Translations\TypesOfProjectsTranslation",
     * mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add projects
     *
     * @param \Archfest\Bundle\Entity\Projects $projects
     * @return TypesOfProjects
     */
    public function addProject(\Archfest\Bundle\Entity\Projects $projects)
    {
        $this->projects[] = $projects;
    
        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Archfest\Bundle\Entity\Projects $projects
     */
    public function removeProject(\Archfest\Bundle\Entity\Projects $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param null $locale
     * @return string
     */
    public function getName($locale = null) {
        return $this->_getTranslatedField('name', $locale);
    }

    /**
     * Set status
     * @param $status
     * @return $this
     *
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function __toString() {
        $name = 'Добавления нового типа проэктов';

        if ($this->getId()) {
            $name = $this->getName();
        }
        return $name;
    }
}