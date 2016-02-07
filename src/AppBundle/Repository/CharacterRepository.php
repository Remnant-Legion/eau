<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * CharacterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CharacterRepository extends EntityRepository
{

    public function getMainCharacter(User $user){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.user = :user')
            ->andWhere('c.is_main = :main')
            ->setParameters(['user' => $user, 'main' => true])
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function getCharNamesByCorpName($name){
        return $this->createQueryBuilder('c')
            ->select('c.name')
            ->where('c.corporation_name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getResult();
    }
}