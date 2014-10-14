<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 12/3/13
 * Time: 9:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Admin;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FoundersAdmin extends BaseAdmin {
    protected $baseRouteName = 'founders';
    protected $baseRoutePattern = 'founders';

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
            ->add('status', null, array('required' => false))
            ->add('translations', 'translatable_field', array(
                'fields'          => array(
                    'name',
                    'content'
                ),
                'widgets' => array(
                    'name' => 'text',
                    'content' => 'textarea'),
                'field_options' => array(
                    'content' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\FoundersTranslation'
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
            ->add('img', 'string', array('template' => 'ArchfestBundle:Frontend:list_image.html.twig', 'label' => 'Photo'))
            ->add('name')
            ->add('status')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'changePosition' => array(
                        'template' => 'ArchfestBundle:Admin:list_action_position.html.twig'
                    ),
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'ArchfestBundle:Admin\CRUD\Founders:edit.html.twig';
                break;

            case 'list':
                return 'ArchfestBundle:Admin\CRUD:base_sortable_list.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    //Добавляем новый  actions
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
        $collection->add('saveImg');
        $collection->add('removeImg');
        $collection->add('changePosition');
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('status')
            ->add('type', null, array(), null, array('expanded' => true, 'multiple' => true))
        ;
    }
}