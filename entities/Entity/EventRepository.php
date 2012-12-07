<?php

namespace Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{

    public function getEventsWithOffers()
    {
        $qb = $this->createQueryBuilder('e')
                   ->leftJoin('e.offers', 'o')
                   ->addSelect('o');

        return $qb->getQuery()
                   ->getResult();
    }
}