<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 12/10/13
 * Time: 8:09 PM
 */

namespace Archfest\Bundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class BaseAdmin extends Admin {
    /**
     * @return \Doctrine\ORM\EntityManager object
     */
    protected function getEntityManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array();
    }
} 