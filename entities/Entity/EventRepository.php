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

    public function getEventsWithOffersByIds($ids)
    {
        $qb = $this->createQueryBuilder('e')
                   ->where('e.id IN (:ids)')
                   ->leftJoin('e.offers', 'o')
                   ->addSelect('o')
                   ->setParameter('ids', $ids);  

        return $qb->getQuery()
                   ->getResult();
    }  
    
}