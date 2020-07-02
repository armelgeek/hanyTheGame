<?php

namespace App\Repository;

use App\Entity\LoginAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class LoginAttemptRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginAttempt::class);
    }

    /**
     * Compte le nombre de tentative de connexion pour un utilisateur
     */
    public function countRecentFor($user, $minutes): int
    {
        try {
            return $this->createQueryBuilder('l')
                ->select('COUNT(l.id) as count')
                ->where('l.user = :user')
                ->andWhere('l.createdAt > :date')
                ->setParameter('date', new \DateTime("-{$minutes} minutes"))
                ->setParameter('user', $user)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

}
