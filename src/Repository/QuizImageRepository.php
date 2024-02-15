<?php

namespace App\Repository;

use App\Entity\QuizImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizImage>
 *
 * @method QuizImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizImage[]    findAll()
 * @method QuizImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizImage::class);
    }
}
