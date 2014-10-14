<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 1/5/14
 * Time: 6:07 PM
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContactUsPageAdmin extends BaseAdmin {
    protected $baseRouteName = 'contactUsPage';
    protected $baseRoutePattern = 'contactUsPage';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'translatable_field', array(
                'fields'          => array('title', 'mKeywords', 'mDescription'),
                'widgets' => array('title' => 'text',
                    'mKeywords' => 'text',
                    'mDescription' => 'textarea'),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\ContactUsPageTranslation'
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
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
        $catalogPageRepository = $em->getRepository('ArchfestBundle:ContactUsPage');

        $catalogsPages = $catalogPageRepository->findAll();
        if (count($catalogsPages) > 0) {
            $collection->remove('create');
        }
        $collection->remove('delete');
        $collection->remove('show');
    }

} 