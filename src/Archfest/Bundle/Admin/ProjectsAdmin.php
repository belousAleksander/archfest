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
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProjectsAdmin extends BaseAdmin{

    protected $baseRouteName = 'catalog';
    protected $baseRoutePattern = 'catalog';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('type')
        ->add('year')
        ->add('area', null, array('required' => false))
        ->add('top', null, array('required' => false))
        ->add('enabled', null, array('required' => false))
        ->add('imgEnabled', null, array('required' => false))
        ->add('flashEnabled', null, array('required' => false))
        ->add('translations', 'translatable_field', array(
            'fields'          => array('title', 'mDescription',  'mKeywords', 'name', 'briefInformation'),
            'widgets' => array(
                'title' => 'text',
                'mDescription' => 'textarea',
                'mKeywords' => 'text',
                'name' => 'textarea',
                'briefInformation' => 'textarea',
            ),
            'field_options' => array(
                'name' => array('attr' => array('class' => 'ckeditor')),
                'briefInformation' => array('attr' => array('class' => 'ckeditor')),


            ),
            'personal_translation' => 'Archfest\Bundle\Entity\Translations\ProjectsTranslation'
        ))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('img', 'string', array('template' => 'ArchfestBundle:Frontend:list_image.html.twig'))
            ->add('year')
            ->add('type')
            ->add('enabled',null, array('editable' => true))
            ->add('top',null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'changePosition' => array(
                        'template' => 'ArchfestBundle:Admin:list_action_position.html.twig'
                    )
                )
            ));
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'ArchfestBundle:Admin\CRUD\Projects:edit.html.twig';
                break;

            case 'list':
                return 'ArchfestBundle:Admin\CRUD:base_sortable_list.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('enabled')
            ->add('top')
            ->add('type', null, array(), null, array('expanded' => true, 'multiple' => true))
        ;
    }

    //Добавляем новый  actions
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('saveImg');
        $collection->add('removeImg');
        $collection->add('loadFlash');
        $collection->add('removeFlash');
        $collection->add('install');
        $collection->add('changePosition');
        $collection->remove('show');
    }

}