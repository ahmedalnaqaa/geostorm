<?php

namespace Todo\Bundle\ListBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Todo\Bundle\UserBundle\Entity\User;

class ListRepository extends EntityRepository
{
    /**
     * Return User Lists
     * @ParamConverter(name="user", Class="TodoUserBundle:User")
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function getUserLists($user)
    {
        $QueryBuilder = $this->createQueryBuilder('listItem');
        $QueryBuilder
            ->andWhere($QueryBuilder->expr()->eq('listItem.user',':user'))
            ->setParameter('user', $user);

        $QueryBuilder->orderBy('listItem.created_at','DESC');

        return $QueryBuilder->getQuery()->execute();
    }
}
