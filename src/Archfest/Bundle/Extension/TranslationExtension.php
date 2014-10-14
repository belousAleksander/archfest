<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leon
 * Date: 27.09.13
 * Time: 14:34
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Extension;

use Archfest\Bundle\Helper\TranslationsHelper;
use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\Form\FormView;

class TranslationExtension extends Twig_Extension {

    protected $helper;

    public function __construct(TranslationsHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('get_languages', array($this, 'getLanguages')),
            new Twig_SimpleFunction('get_entity_translatable_fields', array($this, 'getTranslatableFields')),
        );
    }

    /**
     * Gets available languages
     * @return array
     */
    public function getLanguages()
    {
        return $this->helper->getLanguages();
    }

    /**
     * Gets entity fields available for translation
     * @param $form
     * @return array
     */
    public function getTranslatableFields(FormView $form)
    {
        return $this->helper->getTranslatableFields($form);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'amk_translation_extension';
    }
}