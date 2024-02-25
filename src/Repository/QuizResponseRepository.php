<?php

namespace App\Repository;

use App\Entity\QuizResponse;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizResponse>
 *
 * @method QuizResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizResponse[]    findAll()
 * @method QuizResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizResponse::class);
    }

    public function getMyQuizResponses(User $user, ?array $args = null)
    {
        return $this->createQueryBuilder('qr')
            ->leftJoin(User::class, 'u', 'WITH', 'u.id = qr.quizTaker')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
