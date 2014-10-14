<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 10/31/13
 * Time: 9:58 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MainPageController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'     => 'list',
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }

}