<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 1/5/14
 * Time: 5:52 PM
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContactUsAdmin extends BaseAdmin{
    protected $baseRouteName = 'contactUs';
    protected $baseRoutePattern = 'contactUs';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'translatable_field', array(
                'fields'          => array('town','address'),
                'widgets' => array(
                    'town' => 'text',
                    'address' => 'textarea'),
                'field_options' => array(
                    'address' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\ContactUsTranslation'
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('town')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $em = $this->getEntityManager();
        $contactUsRepository = $em->getRepository('ArchfestBundle:ContactUs');

        $contactUs = $contactUsRepository->findAll();
        if (count($contactUs) > 2) {
            $collection->remove('create');
        }
        $collection->remove('delete');
        $collection->remove('show');
    }
}
