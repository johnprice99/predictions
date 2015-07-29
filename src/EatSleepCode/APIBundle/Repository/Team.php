<?php

namespace EatSleepCode\APIBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Team extends EntityRepository {

    public function findAllOrderedByName() {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM EatSleepCodeAPIBundle:Team t ORDER BY t.name ASC')
            ->getResult();
    }

    public function findURLsForCache() {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM EatSleepCodeAPIBundle:Team t INDEX BY t.url')
            ->getResult();
    }

}
