<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\QuizTaker;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getMyTakenQuizzes(User $user)
    {
        $this->createQueryBuilder('q')
            ->leftJoin(QuizTaker::class, 'qt', 'WITH', 'q.id = qt.quiz')
            ->leftJoin(User::class, 'u', 'WITH', 'u.id = qt.quizTaker')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
