<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 10:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Archfest\Bundle\Form\EventListener\AddTranslatedFieldListener;
class TranslatedFieldType extends AbstractType
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(! class_exists($options['personal_translation']))
        {
            Throw new \InvalidArgumentException(sprintf("Unable to find personal translation class: '%s'", $options['personal_translation']));
        }
        if(!isset($options['fields']))
        {
            Throw new \InvalidArgumentException("You should provide a field to translate");
        }

        $subscriber = new AddTranslatedFieldListener($builder->getFormFactory(), $this->container, $options);
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'remove_empty' => true, //Personal Translations without content are removed
                'csrf_protection' => false,
                'personal_translation' => false, //Personal Translation class
                'locales' => $this->container->getParameter('supported_languages'), //the locales you wish to edit
                'required_locale' => array('en' => false), //the required locales cannot be blank
                'fields' => false, //the field that you wish to translate
                'widget' => "text", //change this to another widget like 'texarea' if needed
                'entity_manager_removal' => true, //auto removes the Personal Translation thru entity manager
                'widgets' => 'text',
                'field_options' => array(),
                'object' => null,
                'required' => false,
                'labels' => array()
            )

        );

    }

    public function getName()
    {
        return 'translatable_field';
    }

}