<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/3/13
 * Time: 9:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Entity;


use Archfest\Bundle\Entity\Translations\BaseTranslation;

abstract class BaseTranslate {

    protected $_defaultLocale = 'ru';

    public function setDefaultLocale($locale)
    {
        if(is_null($locale) || $locale === '') {
            return;
        }
        $this->_defaultLocale = $locale;
    }
    /**
     * Add translations
     *
     * @param $translations
     * @return MainPage
     */
    public function addTranslation(BaseTranslation $translations)
    {
        if (!$this->getTranslations()->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param $translations
     */
    public function removeTranslation($translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $fieldName
     * @param null $locale
     * @return string
     */
    protected function _getTranslatedField($fieldName, $locale = null)
    {
        $translation = null;

        if (is_null($locale)){
            $locale = $this->_defaultLocale;
        }
        if ($locale) {
            $translation = $this->getTranslations()->filter(
                function ($entity) use ($fieldName, $locale) {

                    /** @var $entity BaseTranslation */
                    return $entity->getField() == $fieldName and $entity->getLocale() == $locale;
                }
            )->first();
        }

        if (empty($translation)) {
            $translation = $this->getTranslations()->filter(
                function ($entity) use ($fieldName) {
                    /** @var $entity BaseTranslation */
                    return $entity->getField() == $fieldName;
                }
            )->first();
        }

        if ($translation) {
            return $translation->getContent();
        }

        return '';
    }

    public function getTitle ($locale = null){
        return $this->_getTranslatedField('title', $locale);
    }

    public function getMKeywords ($locale = null){
        return $this->_getTranslatedField('mKeywords', $locale);
    }

    public function getMDescription ($locale = null){
        return $this->_getTranslatedField('mDescription', $locale);
    }
}