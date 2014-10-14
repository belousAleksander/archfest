<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leon
 * Date: 27.09.13
 * Time: 14:36
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Helper;

use Archfest\Bundle\Exception\NoTranslationsDefinedException;
use Archfest\Bundle\Lib\BaseTranslation;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\FormView;


class TranslationsHelper extends ContainerAware {

    /**
     * @var array
     */
    protected $languages = array();

    public function __construct()
    {
        $this->languages = BaseTranslation::getSupportedLanguages();

    }

    /**
     * Gets entity available fields
     * @param FormView $form
     * @return array
     * @throws NoTranslationsDefinedException
     */
    public function getTranslatableFields(FormView $form)
    {
        $fields = array();
        if (isset($form->children) and is_array($form->children)) {
            foreach ($form->children as $fieldKey => $formChild) {
                $field = explode(':', $fieldKey);
                $fields[$field[0]]['fields'][$field[1]] = $fieldKey;
                $fields[$field[0]]['label'] = $formChild->vars['label'];
            }
        }

        return $fields;
    }

    /**
     * Gets available languages for translation
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

}