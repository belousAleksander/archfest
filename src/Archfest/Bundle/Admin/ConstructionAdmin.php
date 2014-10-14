<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConstructionAdmin extends BaseAdmin{
    protected $baseRouteName = 'сonstruction';
    protected $baseRoutePattern = 'сonstruction';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('enabled', null, array('required' => false) )
            ->add('year', null, array('required' => false) )
            ->add('translations', 'translatable_field', array(
                'fields'          => array('description'),
                'widgets' => array(
                    'description' => 'textarea'),
                'field_options' => array(
                    'description' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\ConstructionTranslation'
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('img', 'string', array('template' => 'ArchfestBundle:Frontend:list_image.html.twig'))
            ->add('enabled',null, array('editable' => true))
            ->add('year')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }


    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'ArchfestBundle:Admin\CRUD\Construction:edit.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    //Добавляем новый  actions
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('saveImg');
        $collection->add('removeImg');
        $collection->remove('show');
    }

}