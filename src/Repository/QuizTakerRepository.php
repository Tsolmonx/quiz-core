<?php

namespace App\Repository;

use App\Entity\QuizTaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizTaker>
 *
 * @method QuizTaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizTaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizTaker[]    findAll()
 * @method QuizTaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizTakerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizTaker::class);
    }
}
