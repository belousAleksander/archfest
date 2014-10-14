<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AboutUsAdmin extends BaseAdmin{
    protected $baseRouteName = 'aboutUs';
    protected $baseRoutePattern = 'aboutUs';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'translatable_field', array(
                'fields'          => array('title', 'mKeywords', 'mDescription', 'content', 'shortInfo', 'footer'),
                'widgets' => array('title' => 'text',
                    'mKeywords' => 'text',
                    'mDescription' => 'textarea',
                    'shortInfo' => 'textarea',
                    'content' => 'textarea',
                    'footer' => 'textarea',
                ),
                'field_options' => array(
                    'shortInfo' => array('attr' => array('class' => 'ckeditor')),
                    'content' => array('attr' => array('class' => 'ckeditor')),
                    'footer' => array('attr' => array('class' => 'ckeditor')),
                ),
                'personal_translation' => 'Archfest\Bundle\Entity\Translations\AboutUsTranslation'
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
        $catalogPageRepository = $em->getRepository('ArchfestBundle:AboutUs');

        $catalogsPages = $catalogPageRepository->findAll();
        if (count($catalogsPages) > 0) {
            $collection->remove('create');
        }
        $collection->remove('delete');
        $collection->remove('show');
    }

}