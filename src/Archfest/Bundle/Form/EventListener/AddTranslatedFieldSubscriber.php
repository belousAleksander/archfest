<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Form\EventListener;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;

class AddTranslatedFieldSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $options;
    private $container;

    public function __construct(FormFactoryInterface $factory, ContainerInterface $container, Array $options)
    {
        $this->factory = $factory;
        $this->options = $options;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // , form.post_data and form.bind_norm_data event
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_BIND => 'postBind',
            FormEvents::BIND => 'bindNormData'
        );
    }

    private function bindTranslations($data)
    {
        //Small helper function to extract all Personal Translation
        //from the Entity for the field we are interested in
        //and combines it with the fields

        $collection = array();
        $availableTranslations = array();

        foreach($data as $Translation)
        {
            if(strtolower($Translation->getField()) == strtolower($this->options['field']))
            {
                $availableTranslations[ strtolower($Translation->getLocale()) ] = $Translation;
            }
        }

        foreach($this->getFieldNames() as $locale => $fieldName)
        {
            if(isset($availableTranslations[ strtolower($locale) ]))
            {
                $Translation = $availableTranslations[ strtolower($locale) ];
            }
            else
            {
                $Translation = $this->createPersonalTranslation($locale, $this->options['field'], NULL);
            }

            $collection[] = array(
                'locale'      => $locale,
                'fieldName'   => $fieldName,
                'translation' => $Translation,
            );
        }

        return $collection;
    }

    private function getFieldNames()
    {
        //helper function to generate all field names in format:
        // '<locale>' => '<field>|<locale>'
        $collection = array();
        foreach($this->options['locales'] as $locale)
        {
            $collection[ $locale ] = $this->options['field'] .":". $locale;
        }
        return $collection;
    }

    private function createPersonalTranslation($locale, $field, $content)
    {
        //creates a new Personal Translation
        $className = $this->options['personal_translation'];

        return new $className($locale, $field, $content);
    }

    public function bindNormData(FormEvent $event)
    {
        //Validates the submitted form
        $data = $event->getData();
        $form = $event->getForm();

        $validator = $this->container->get('validator');

        foreach($this->getFieldNames() as $locale => $fieldName)
        {
            $content = $form->get($fieldName)->getData();

            if(
                NULL === $content &&
                in_array($locale, $this->options['required_locale']))
            {
                $form->addError(new FormError(sprintf("Field '%s' for locale '%s' cannot be blank", $this->options['field'], $locale)));
            }
            else
            {
                $Translation = $this->createPersonalTranslation($locale, $fieldName, $content);
                $errors = $validator->validate($Translation, array(sprintf("%s:%s", $this->options['field'], $locale)));

                if(count($errors) > 0)
                {
                    foreach($errors as $error)
                    {
                        $form->addError(new FormError($error->getMessage()));
                    }
                }
            }
        }
    }

    public function postBind(FormEvent $event)
    {
        //if the form passed the validattion then set the corresponding Personal Translations
        $form = $event->getForm();
        $data = $form->getData();

        $entity = $form->getParent()->getData();

        foreach($this->bindTranslations($data) as $binded)
        {
            $content = $form->get($binded['fieldName'])->getData();
            $Translation = $binded['translation'];

            // set the submitted content
            $Translation->setContent($content);

            //test if its new
            if($Translation->getId())
            {
                //Delete the Personal Translation if its empty
                if(
                    NULL === $content &&
                    $this->options['remove_empty']
                )
                {
                    $data->removeElement($Translation);

                    if($this->options['entity_manager_removal'])
                    {
                        $this->container->get('doctrine.orm.entity_manager')->remove($Translation);
                    }
                }
            }
            elseif(NULL !== $content)
            {
                //add it to entity
                $entity->addTranslation($Translation);

                if(! $data->contains($Translation))
                {
                    $data->add($Translation);
                }
            }
        }
    }

    public function preSetData(FormEvent $event)
    {
        //Builds the custom 'form' based on the provided locales
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data)
        {
            return;
        }

        foreach($this->bindTranslations($data) as $binded)
        {
            $form->add($this->factory->createNamed(
                $binded['fieldName'],
                $this->options['widget'],
                $binded['translation']->getContent(),
                array(
                    'label' => $binded['locale'],
                    'required' => in_array($binded['locale'], $this->options['required_locale']),
                    'auto_initialize' => false,
                )
            ));
        }
    }
}