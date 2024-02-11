<?php

namespace App\Repository;

use App\Entity\QuestionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionGroup>
 *
 * @method QuestionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionGroup[]    findAll()
 * @method QuestionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionGroup::class);
    }
}
