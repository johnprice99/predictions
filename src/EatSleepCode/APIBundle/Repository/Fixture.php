<?php

namespace EatSleepCode\APIBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Fixture extends EntityRepository {

    public function findAllByMatchDayOrderedByHomeTeam($matchDay) {
        return $this->createQueryBuilder('f')
            ->where('f.matchDay = ' . $matchDay)
            ->leftJoin('f.homeTeam','h')
            ->orderBy('f.date', 'asc')
            ->addOrderBy('h.name', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function findUnplayedByMatchDay($matchDay) {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.matchDay = ' . $matchDay)
            ->andWhere('f.played = false')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
