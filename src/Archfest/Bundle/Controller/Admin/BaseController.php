<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/5/13
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Controller\Admin;

use Archfest\Bundle\Entity\ProjectImg;
use Belous\FlashBundle\Entity\Flash;
use Belous\FlashBundle\Interfaces\iObjectWithFlash;
use Belous\FlashBundle\Lib\FlashLoader;
use Belous\MediaBundle\Exception\LoadImgException;
use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Interfaces\iObjectWithImg;
use Belous\MediaBundle\Services\ImagesLoader;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseController extends Controller {

    /**
     * Возвращает info о файле
     * @param $imgKey
     * @return array
     */
    protected function getFileInfo ($imgKey) {

        /** @var Request $request */
        $request = $this->get('request');
        $uniqid = $request->get('uniqid');
        $files = $request->files->get($uniqid.'file');
        $fileInfo = $request->get($uniqid.'file');
        $info = array();
            $imgId = null;

        if(array_key_exists('id', $fileInfo[$imgKey])) {
            $info['id'] = $fileInfo[$imgKey]['id'];
        }

        if(array_key_exists('alt', $fileInfo[$imgKey])) {
            $info['alt'] = $fileInfo[$imgKey]['alt'];
        }

        if(array_key_exists('main', $fileInfo[$imgKey])) {
            $info['main'] = $fileInfo[$imgKey]['main'];
        }

        $info['file'] = $files[$imgKey];

        return $info;
    }


    protected function getUniqid () {
        return $this->admin->getUniqid();
    }


    /**
     * @param Projects $object
     * @return array
     */
    protected function getObjectInfo($object) {
        $objectInfo = array();
        $projectImages = $object->getImg();
        $arrayImages = array();
        if($projectImages) {
            /** @var ProjectImg $img */
            foreach ($projectImages as $img) {
                $arrayImages[] = $this->getImagesInfo($img);
            }
        }
        $objectInfo['images'] = json_encode($arrayImages);
        $objectInfo['newImgInfo'] = json_encode($this->getImagesInfo());
        return $objectInfo;
    }

    /**
     * Возвращает информацию об картинке и её описания
     * @param $img
     * @return array
     */
    protected function  getImagesInfo ($img = null) {
        $assetsHelper = $this->get('templating.helper.assets');
        $imgInfo = array();
        $src = 'images/default/default_images.jpg';
        $translates =array();

        if($img) {
            $imgInfo['id'] = $img->getId();
            $src = $img->getSrc();
            $translates = $img->getTranslations()->getValues();
            $imgInfo['main'] = $img->getMain();
        }
        $imgInfo['src'] = $assetsHelper->getUrl($src, $packageName = null);
        $imgInfo['alt'] = array();
        foreach ((array) $this->container->getParameter('supported_languages') as $language) {
            $content = '';
            foreach ((array) $translates as $imgAlt) {
                if($language === $imgAlt->getLocale() && $imgAlt->getField() === 'alt') {
                    $content = $imgAlt->getContent();
                }
            }

            $imgInfo['alt'][] = array('language' => $language,
            'value' => $content);
        }

        return $imgInfo;
    }

    public function removeImgAction (Request $request){
        $imgId = $request->get('imgId');
        $objectId = $request->get('objectId');

        $object = $this->admin->getObject($objectId);
        try{
            if(!$object) {
                throw new LoadImgException(sprintf('unable to find the object with id : %s', $objectId));
            }
            $em = $this->getDoctrine()->getManager();
            $classMetaData = $em->getClassMetadata(get_class($object));
            $className = $classMetaData->getAssociationTargetClass('img');
            $imgRepository = $this->getDoctrine()->getRepository($className);
            $imgObject = $imgRepository->find($imgId);

            if(!$imgObject) {
                throw new LoadImgException(sprintf('unable to find the object with id : %s', $imgId));
            }

            /** @var ImagesLoader $imagesLoader */
            $imagesLoader = $this->get('belous_media.images_loader');

            $filePath = $imagesLoader->getDirectory().$imgObject->getFileName();
            $smallFilePath = $imagesLoader->getDirectory().'small'.$imgObject->getFileName();
            if(file_exists($filePath)) {
                unlink($filePath);
            }

            if(file_exists($smallFilePath)) {
                unlink($smallFilePath);
            }

            $em->remove($imgObject);
            $em->flush();
        } catch (LoadImgException $e) {
            return new JsonResponse(array('success' => false, 'message' => $e->getMessage()));
        }

        return new JsonResponse(array('success' => true));
    }

    /**
     * @param iObjectWithImg $object
     * @param array $imageInfo
     * @return iObjectImg object
     */
    protected function getImgObject(iObjectWithImg $object, array $imageInfo) {
        $em = $this->getDoctrine()->getManager();
        $classMetaData = $em->getClassMetadata(get_class($object));
        $className = $classMetaData->getAssociationTargetClass('img');
        if(array_key_exists('id', $imageInfo)) {
            $projectsImgRepository = $this->getDoctrine()->getRepository($className);
            $object = $projectsImgRepository->find($imageInfo['id']);
            if(!$object) {
                $object = new $className();
            }
        } else {
            $object = new $className();
        }

        return $object;
    }


    /**
     * Сохраняет изображения при ajax
     * @param Request $request
     * @return Response
     */
    public function saveImgAction (Request $request) {
        /** @var ImagesLoader $imagesLoader */
        $imagesLoader = $this->get('belous_media.images_loader');
        $imagesLoader->setWidth(1024);
        $imagesLoader->setSmallImgHeight(160);
        $id = $request->get('id');
        return $this->renderJson($this->saveImg ($id, $imagesLoader));
    }


    /**
     * Сохраняет изображения
     * @param $id
     * @param ImagesLoader $imagesLoader
     * @return array
     */
    public function saveImg ($id, ImagesLoader $imagesLoader) {
        $response = array('success' => false,
            'message' => 'Произошла оишибка при загрузки изображенияю');

        $imgInfo = $this->getFileInfo('imgInfo');
        $object = $this->admin->getObject($id);
        $ProjectImgObject = $this->getImgObject($object, $imgInfo);
        $file = $imgInfo['file'];
        $pathOriginalFile = null;
        if($file){
            try{
                //Сохраняем изображения
                $imagesLoader->saveImage($object, $ProjectImgObject, $file, $imgInfo);

                $this->admin->getModelManager()->update($object);
                return array('success' => true, 'imgId' => $ProjectImgObject->getId());
            } catch (LoadImgException $e) {
                $response['message'] = $e->getMessage();
                return $response;
            }
        } else {
            if($ProjectImgObject->getId()) {
                $imagesLoader->updateImgInfo($object, $ProjectImgObject, $imgInfo);

                return array('success' => true, 'imgId' => $ProjectImgObject->getId());
            }
            $response['message'] = 'Отсутствует файл для загрузки';

            return $response;
        }
    }

    public function loadFlashAction (Request $request) {
        $response = array();
            $id = $request->get('id');
            $flashId = $request->get('flashId');

            $object = $this->admin->getObject($id);
        try {
            if (!$object) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }

            if (false === $this->admin->isGranted('EDIT', $object)) {
                throw new AccessDeniedException();
            }
            $flash = $object->getFlashById($flashId);
            $flashLoader = new FlashLoader($object);
            $uploadFlash = $request->files->get('flash');
            if(empty($uploadFlash)) {
                throw new \Exception('Can not read upload file.');
            }
            $flash = $flashLoader->saveFlash($uploadFlash, $flash);
            $this->admin->getModelManager()->update($object);
            $response['success'] = true;
            $response['flashInfo'] = $this->getFlashInfo($flash);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return $this->renderJson($response);
    }

    protected function getObjectFlashInfo (iObjectWithFlash $object) {
        $info = array();
        foreach ($object->getFlash() as $flash) {
            $info[]= $this->getFlashInfo($flash);
        }

        return $info;
    }

    protected function getFlashInfo(Flash $flash) {
        return array('id' => $flash->getId(), 'src' => $flash->getSrc());
    }

    public function removeFlashAction(Request $request) {
        $response = array();
        $em = $this->getDoctrine()->getManager();

        try{
            $id = $request->get('id');
            $flashId = $request->get('flashId');
            $object = $this->admin->getObject($id);
            if (!$object) {
                throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
            }

            if (false === $this->admin->isGranted('DELETE', $object)) {
                throw new AccessDeniedException();
            }

            $flashObject = $object->getFlashById($flashId);
            $object->removeFlash($flashObject);
            $em->remove($flashObject);
            $em->flush();
            $response['success'] = true;

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return $this->renderJson($response);

    }

    public function changePositionAction(Request $request) {
        $objectId = $request->get('id');
        $value = $request->get('value');

        $object = $this->admin->getObject($objectId);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s',  $objectId));
        }

        $object->setPosition($value);

        $this->admin->update($object);

        return $this->renderJson(array('success'=> true));
    }
}