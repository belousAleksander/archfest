<?php
/**
 * Created by PhpStorm.
 * User: oleksandr
 * Date: 2/17/14
 * Time: 7:29 PM
 */

namespace Archfest\Bundle\Repository;

use Doctrine\ORM\EntityRepository;
class ProjectImgRepository extends EntityRepository {
    public function getMainImage ($projectId) {
        $query = $this->createQueryBuilder('img')
            ->where('img.main = :value')
            ->andWhere('img.object = :objectId')
            ->setParameter('value', true)
            ->setParameter('objectId', $projectId)
            ->getQuery();
        $result = $query->getResult();

        return $result[0];
    }


    public function getImageWithOutMain ($projectId) {
        $query = $this->createQueryBuilder('img')
            ->where('img.main = :value')
            ->andWhere('img.object = :objectId')
            ->setParameter('value', false)
            ->setParameter('objectId', $projectId)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }
} 