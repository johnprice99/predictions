<?php

namespace EatSleepCode\APIBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Fixture extends EntityRepository {

    public function findAllByMatchDayOrderedByHomeTeam($matchDay) {
        return $this->createQueryBuilder('f')
            ->where('f.matchDay = ' . $matchDay)
            ->leftJoin('f.homeTeam','h')
            ->orderBy('h.name', 'asc')
            ->getQuery()
            ->getResult();
    }

}
