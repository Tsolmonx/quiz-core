<?php

namespace App\Repository;

use App\Entity\QuestionImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionImage>
 *
 * @method QuestionImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionImage[]    findAll()
 * @method QuestionImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionImage::class);
    }
}
