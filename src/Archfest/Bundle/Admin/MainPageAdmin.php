<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 9:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MainPageAdmin extends BaseAdmin {
    protected $baseRouteName = 'main_page';
    protected $baseRoutePattern = 'main_page';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'translatable_field', array(
                'fields'          => array('title', 'mKeywords','mDescription', 'content'),
                'widgets' => array('title' => 'text',
                    'mKeywords' => 'text',
                    'mDescription' => 'textarea',
                    'content' => 'textarea'),
                'field_options' => array(
                    'content' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\MainPageTranslation'
            ))

        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper;
    }
    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array()
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $em = $this->getEntityManager();
        $catalogPageRepository = $em->getRepository('ArchfestBundle:MainPage');

        $catalogsPages = $catalogPageRepository->findAll();

        if (count($catalogsPages) > 0) {
            $collection->remove('create');
        }
        $collection->remove('delete');
        $collection->remove('show');
    }
}