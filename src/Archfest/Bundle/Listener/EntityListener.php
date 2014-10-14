<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/20/13
 * Time: 9:56 PM
 */

namespace Archfest\Bundle\Listener;


use Belous\MediaBundle\Interfaces\iObjectImg;
use Belous\MediaBundle\Services\ImagesLoader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityListener implements EventSubscriber {

    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postRemove(LifecycleEventArgs $event)
    {
        /** @var iObjectImg $entity */
        $entity = $event->getEntity();
        if($entity instanceof iObjectImg) {
            $imagesLoader = new ImagesLoader($this->container, $this->container->get('doctrine.orm.entity_manager'));
            $filePath = $imagesLoader->getDirectory().$entity->getFileName();
            $smallFilePath = $imagesLoader->getDirectory().'small'.$entity->getFileName();
            $this->removeFile($filePath);
            $this->removeFile($smallFilePath);
        }

    }

    private function removeFile($path) {
        if(file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Define which event will be processed
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postRemove'
        );
    }

} 