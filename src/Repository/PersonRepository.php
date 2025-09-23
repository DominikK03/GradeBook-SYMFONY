<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }
    public function findByUserID(User $userID): ?Person
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.user = :user")
            ->setParameter('user', $userID->getId())
            ->getQuery()
            ->getResult()[0];
    }
}
