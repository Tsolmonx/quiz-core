<?php

namespace App\Repository;

use App\Entity\AnswerImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnswerImage>
 *
 * @method AnswerImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerImage[]    findAll()
 * @method AnswerImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerImage::class);
    }
}
