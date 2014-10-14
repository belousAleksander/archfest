<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 5/27/14
 * Time: 9:50 PM
 */

namespace Belous\FlashBundle\Lib;

use Belous\FlashBundle\Entity\Flash;
use Belous\FlashBundle\Exception\FlashLoadException;
use Belous\FlashBundle\Interfaces\iObjectWithFlash;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FlashLoader {
    private $validFileTypes = array('swf');
    private $loadDirectory = 'flash/';
    /**
     * @var \Belous\FlashBundle\Interfaces\iObjectWithFlash
     */
    private $object;

    public function __construct(iObjectWithFlash $object) {
        $this->object = $object;
    }

    public function checkFormat(UploadedFile $file) {
        $originalName = $file->getClientOriginalName();
        $name_array = preg_split('/[.]/', $originalName);
        $file_type = $name_array[sizeof($name_array) - 1];
        if (!in_array(strtolower($file_type), $this->validFileTypes)) {
            throw new FlashLoadException ('Invalid file type');
        }
    }

    public function getLoadDirectory (){

        return $this->loadDirectory;
    }

    /**
     * Проверяет существует ли папка для загрузки если нет то создаст её
     */
    public function checkDirectory() {
        $loadDirectory = $this->getLoadDirectory();

        if(!is_dir($loadDirectory)){
            mkdir($loadDirectory);
        }
    }

    public function saveFlash (UploadedFile $file, $flash = null) {
        ini_set('memory_limit', '256M');
        $this->checkFormat($file);
        $this->checkDirectory();

        $object = $this->object;
        if(!$flash) {
            $fileName = time().$file->getClientOriginalName();
            $flash = new Flash();
            $object->addFlash($flash);
            $flash->setSrc($this->getLoadDirectory().$fileName);
        } elseif ($flash instanceof Flash) {
            $src = $flash->getSrc();
            $this->removeFile($src);
            $data = explode("/", $src);
            $fileName = $data[count($data) - 1];
        } else {
            throw new FlashLoadException('Object witch save as Flash must be instanceof Flash');
        }
        $file->move($this->getLoadDirectory(), $fileName);

        return $flash;
    }

    public function removeFile($path) {
        if(file_exists($path)) {
            unlink($path);
        }
    }

} 