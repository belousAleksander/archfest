<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/12/13
 * Time: 9:47 PM
 */

namespace Belous\MediaBundle\Services;


use Belous\MediaBundle\Exception\LoadImgException;
use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Interfaces\iObjectWithImg;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoicesToBooleanArrayTransformer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Templating\Helper\CoreAssetsHelper;

class ImagesLoader {
    private $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $height;
    private $width;
    private $smallImgWidth;
    private $smallImgHeight;

    /**
     * @var array
     */
    private $validFileTypes = array('jpg', 'jpeg', 'png', 'gif', 'JPG');

    /**
     * Название директории в котором будут хранится изображения
     * @var string
     */
    private $directory;

    function __construct ($container,EntityManager $em) {
        $this->em = $em;
        $this->container = $container;
        $this->directory = $container->getParameter('loadDirectory');
    }

    public function get($serviceId){

        return $this->container->get($serviceId);
    }

    /**
     * @return CoreAssetsHelper
     */
    private function getAssetHelper () {
        return $this->get('templating.helper.assets');
    }

    /**
     * Возвращает путь к файлу
     * @return string
     */
    public function getPath () {
        return $this->getAssetHelper()->getUrl($this->directory);
    }

    public function setDirectory ($directory) {
        $this->directory = $directory;
    }

    public function getDirectory ()
    {
        return $this->directory;
    }

    /**
     * @param array $types
     */
    public function setValidFileTypes (array $types) {
        $this ->validFileTypes = $types;
    }

    /**
     * Проверяет файл на корректность типа
     * @param UploadedFile $file
     * @throws \Belous\MediaBundle\Exception\LoadImgException
     */
    public function checkTypeImages (UploadedFile $file) {
        $originalName = $file->getClientOriginalName();
        $name_array = preg_split('/[.]/', $originalName);
        $file_type = $name_array[sizeof($name_array) - 1];
        if (!in_array(strtolower($file_type), $this->validFileTypes)) {
            throw new LoadImgException ('Invalid file type');
        }
    }


    /**
     * Создает изображения в необходимом формате
     * @param UploadedFile $file
     * @param null $directory
     * @return resource
     * @throws \Belous\MediaBundle\Exception\LoadImgException
     */
    public function createImage(UploadedFile $file, $directory = null) {
        ini_set('memory_limit', '256M');
        if(!$directory) {
            $directory = $this->getDirectory();
        }

        $name = $file->getClientOriginalName();
        $name_array = preg_split("/[.]/", $name);
        $file_type = $name_array[sizeof($name_array) - 1];
        switch ($file_type)
        {
            case 'jpeg':
            case 'jpg':
            case 'JPG':
                $image = imagecreatefromjpeg($directory);
                break;
            case 'gif':
                $image = imagecreatefromgif($directory);
                break;
            case 'png':
                $image = imagecreatefrompng($directory);
                break;
            default:
                throw new LoadImgException('Invalid file type');
        }

        return $image;
    }

    /**
     * Заносит в кеш ширину изображения
     * @param $width
     * @return $this
     */
    public function setWidth ($width) {
        $this->width = (int) $width;

        return $this;
    }

    /**
     * Заносит в кеш высоту изображения
     * @param $height
     * @return $this
     */
    public function setHeight ($height) {
        $this->height = $height;

        return $this;
    }


    /**
     * Заносит в кеш ширину изображения
     * @param $width
     * @return $this
     */
    public function setSmallImgWidth ($width) {
        $this->smallImgWidth = (int) $width;

        return $this;
    }

    /**
     * Заносит в кеш высоту изображения
     * @param $height
     * @return $this
     */
    public function setSmallImgHeight ($height) {
        $this->smallImgHeight = (int) $height;

        return $this;
    }

    /**
     * Возвращает ширину картинки
     * @param $img
     * @return float|int
     */
    private function getWidth ($img) {
        //вычисляем ширину оригинального изображения
        $originalWidth = imagesx($img);

        if($this->width) {
            $width = $this->width;
        } else {

            if($this->height) {
                $originalHeight = imagesy($img);
                //вычесляем ширину изображения
                $width = ($originalWidth*$this->height)/$originalHeight ;
            } else {
                $width = $originalWidth;
            }
        }

        return $width;
    }


    /**
     * Возвращает ширину картинки
     * @param $img
     * @return float|int
     */
    private function getSmallImgWidth ($img) {
        //вычисляем ширину оригинального изображения
        $originalWidth = imagesx($img);

        if($this->smallImgWidth) {
            $width = $this->smallImgWidth;
        } else {

            if($this->smallImgHeight) {
                $originalHeight = imagesy($img);
                //вычесляем ширину изображения
                $width = ($originalWidth*$this->smallImgHeight)/$originalHeight ;
            } else {
                $width = $originalWidth;
            }
        }

        return $width;
    }


    /**
     * Возвращает высоту картинки
     * @param $img
     * @return float|int
     */
    private function getSmallImgHeight ($img) {
        $originalHeight  = imagesy($img); //вычисляем высоту оригинально изображения
        if($this->smallImgHeight) {

            $height = $this->smallImgHeight;
        } else {
            if($this->smallImgWidth) {
                $originalWidth = imagesx($img);

                //вычисляем высоту изображения
                $height = ($originalHeight*$this->smallImgWidth)/$originalWidth;
            } else {
                $height = $originalHeight;
            }
        }

        return $height;
    }


    /**
     * Возвращает высоту картинки
     * @param $img
     * @return float|int
     */
    private function getHeight ($img) {
        $originalHeight  = imagesy($img); //вычисляем высоту оригинально изображения
        if($this->height) {

            $height = $this->height;
        } else {
            if($this->width) {
                $originalWidth = imagesx($img);

                //вычисляем высоту изображения
                $height = ($originalHeight*$this->width)/$originalWidth;
            } else {
                $height = $originalHeight;
            }
        }

        return $height;
    }


    public function saveImage(iObjectWithImg $object, iObjectImg $imgObject, UploadedFile $img, array $imgInfo) {
        $em = $this->getEntityManager();

        $newFileInfo = $this->loadImg($img);
        $imgObject->setSrc($newFileInfo['path']);
        $imgObject->setSmallImgSrc($newFileInfo['pathSmallImg']);
        $imgObject->setFileName($newFileInfo['name']);
        $imgObject->setObject($object);
        //Если обьект изображения новый  тогда делаем связь с обьектом для которого изображение
        if(!$imgObject->getId()) {
            $object->addImg($imgObject);
        }

        $this->updateImgInfo($object, $imgObject, $imgInfo);


        $em->persist($object);
        $em->persist($imgObject);
        $em->flush();
    }

    public function updateImgInfo(iObjectWithImg $object, iObjectImg $imgObject, array $imgInfo) {
        $em = $this->getEntityManager();
        if(array_key_exists('main', $imgInfo)) {
            /** @var \Doctrine\ORM\PersistentCollection $images */
            $images = $object->getImg();

            /** @var iObjectImg $img */
            foreach ((array) $images->getValues() as $img) {
                if($img->getId() != $imgObject->getId()) {
                    $img->setMain(false);
                } else {
                    $img->setMain(true);
                }
                $em->persist($img);
            }
        } else {
            $imgObject->setMain(false);
            $em->persist($imgObject);
        }
        $this->saveImageTranslations($imgObject, $imgInfo);
        $em->flush();
    }


    /**
     * Сохраняет переводы описания картинки
     * @param iObjectImg $imgObject
     * @param array $imgInfo
     */
    public function saveImageTranslations (iObjectImg $imgObject, array $imgInfo) {
        $em = $this->getEntityManager();
        $classMetaData = $em->getClassMetadata(get_class($imgObject));
        $className = $classMetaData->getAssociationTargetClass('translations');

        $translationRepository = $em->getRepository($className);
        foreach((array) $imgInfo['alt'] as $language => $currentAlt){
            $projectImgTranslation = $translationRepository->findBy(array('object' => $imgObject->getId(), 'field' => 'alt', 'locale' => $language));
            if(!empty($projectImgTranslation)) {
                $projectImgTranslation[0]->setContent($currentAlt);
            } else {

                $imgObject->addTranslation(new $className($language, 'alt', $currentAlt));
            }
        }

        $em->persist($imgObject);
        $em->flush();
    }


    /**
     * @param UploadedFile $img
     * @param null $fileName
     * @return array
     */
    public function loadImg (UploadedFile $img, $fileName = null) {

        $loadDirectory = $this->getDirectory();

        if(!is_dir($loadDirectory)){
            mkdir($loadDirectory);
        }

        $name = $img->getClientOriginalName();

        $path = $loadDirectory.$name;

        $this->moveFile($img);

        //получаем изображения оригинала
        $originalImg = $this->createImage($img, $path, $name);

        $width = $this->getWidth($originalImg);
        $height = $this->getHeight($originalImg);

        $smallImgWidth = $this->getSmallImgWidth($originalImg);
        $smallImgHeight = $this->getSmallImgHeight($originalImg);


        // создаём    пустую картинку
        // важно именно    truecolor!, иначе будем иметь 8-битный результат
        $canvas = imagecreatetruecolor($width, $height);
        $canvasSmall = imagecreatetruecolor($smallImgWidth, $smallImgHeight);

        // маштабируем картинку
        imagecopyresampled($canvas,    $originalImg, 0, 0, 0, 0, $width, $height, imagesx($originalImg), imagesy($originalImg));
        imagecopyresampled($canvasSmall,    $originalImg, 0, 0, 0, 0, $smallImgWidth, $smallImgHeight, imagesx($originalImg), imagesy($originalImg));

        if(!$fileName) {
            $fileName = time().$this->getFileNameWithOutFormat($name).".jpg";
        } else {
            if(file_exists($loadDirectory.$fileName)) {
                unlink    ($loadDirectory.'small'.$fileName);
            }
        }
        $smallFileName = 'small'.$fileName;
        $pathNewImg = $loadDirectory.$fileName;
        $pathNewSmallImg = $loadDirectory.$smallFileName;
        //сохраняем    изображение формата jpg в нужную папку
        imagejpeg($canvas, $pathNewImg, 100);

        //сохраняем    изображение формата jpg в нужную папку
        imagejpeg($canvasSmall, $pathNewSmallImg, 100);

        if($path) {
            unlink    ($path);
        }

        return array('path' => $pathNewImg, 'name' => $fileName, 'pathSmallImg' => $pathNewSmallImg);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager() {
        return $this->em;
    }

    /**
     * Загружает файл на сервер
     * @param UploadedFile $file
     */
    public function moveFile (UploadedFile $file) {
        $this->checkTypeImages($file);
        $file->move($this->getDirectory(), $file->getClientOriginalName());
    }

    /**
     * Возвращает обреззаную с конца до точки
     * @param string $name
     * @return string
     */
    private function getFileNameWithOutFormat ($name) {
        $separator = array('.');//массив нужных нам разделителей
        $i =strlen($name);

        do{
            $i--;
        }while(!in_array($name[$i], $separator) && $i >= 0);//проходим по строке в обратном направлении

        return substr($name, 0, $i);
    }
} 