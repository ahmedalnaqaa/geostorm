<?php

namespace Todo\Bundle\ListBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Todo\Bundle\UserBundle\Entity\User;

class ListRepository extends EntityRepository
{
    /**
     * Return User Lists
     *
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function getUserLists($user)
    {
        $QueryBuilder = $this->createQueryBuilder('list');
        $QueryBuilder
            ->andWhere($QueryBuilder->expr()->eq('list.user',':user'))
            ->setParameter('user', $user);

        $QueryBuilder->orderBy('list.created_at','DESC');

        return $QueryBuilder->getQuery()->execute();
    }
}
