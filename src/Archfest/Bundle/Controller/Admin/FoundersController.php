<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 12/3/13
 * Time: 9:38 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Controller\Admin;


use Belous\MediaBundle\Services\ImagesLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FoundersController extends BaseController {
    /**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if(array_key_exists('object', $parameters)) {
            $parameters['objectInfo'] = $this->getObjectInfo($parameters['object']);
        }
        return parent::render($view, $parameters, $response);
    }

    /**
     * Сохраняет изображения при ajax
     * @param Request $request
     * @return Response
     */
    public function saveImgAction (Request $request) {
        /** @var ImagesLoader $imagesLoader */
        $imagesLoader = $this->get('belous_media.images_loader');
        $imagesLoader->setWidth(300);
        $imagesLoader->setSmallImgWidth(103);
        $id = $request->get('id');
        return $this->renderJson($this->saveImg ($id, $imagesLoader));
    }
}