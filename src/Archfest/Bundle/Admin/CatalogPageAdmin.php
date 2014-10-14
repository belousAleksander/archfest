<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/10/13
 * Time: 8:03 PM
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CatalogPageAdmin extends BaseAdmin {
    protected $baseRouteName = 'catalogPage';
    protected $baseRoutePattern = 'catalogPage';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'translatable_field', array(
                'fields'          => array('title', 'mKeywords', 'mDescription', 'content'),
                'widgets' => array(
                    'title' => 'text',
                    'mKeywords' => 'text',
                    'mDescription' => 'textarea',
                    'content' => 'textarea'
                ),
                'field_options' => array(
                    'content' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\CatalogPageTranslation'
            ))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('mKeywords')
            ->add('mDescription')
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
        $catalogPageRepository = $em->getRepository('ArchfestBundle:CatalogPage');

        $catalogsPages = $catalogPageRepository->findAll();
        if (count($catalogsPages) > 0) {
            $collection->remove('create');
        }
        $collection->remove('delete');
        $collection->remove('show');

    }
} 