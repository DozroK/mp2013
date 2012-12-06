<?php

namespace Entity;

use Doctrine\ORM\EntityRepository;

class PlaceRepository extends EntityRepository
{

    public function getPlacesWithOpeningHours()
    {
        $qb = $this->createQueryBuilder('p')
                   ->join('p.openingHours', 'o')
                   ->addSelect('o');

        return $qb->getQuery()
                   ->getResult();
    }
}