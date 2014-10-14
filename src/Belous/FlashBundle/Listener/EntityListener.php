<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 5/31/14
 * Time: 8:58 PM
 */

namespace Belous\FlashBundle\Listener;



use Belous\FlashBundle\Entity\Flash;
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
    public function preRemove(LifecycleEventArgs $event)
    {
        /** @var Flash $entity */
        $entity = $event->getEntity();

        if($entity instanceof Flash) {
            $src = $entity->getSrc();

            $this->removeFile($src);
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
            'preRemove'
        );
    }

} 