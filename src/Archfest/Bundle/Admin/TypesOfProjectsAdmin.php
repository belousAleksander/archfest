<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:58 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Admin;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class TypesOfProjectsAdmin extends BaseAdmin {

    protected $baseRouteName = 'type_of_projects';
    protected $baseRoutePattern = 'type_of_projects';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('status', null, array('required' => false))
            ->add('translations', 'translatable_field', array(
                'fields'          => array('name'),
                'widgets' => array('name' => 'text'),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\TypesOfProjectsTranslation'
            ))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('status')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array()
                )
            ));
    }

    //Добавляем новый  actions
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
        $collection->remove('show');
    }

}