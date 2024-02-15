<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\QuizTaker;
use App\Entity\User;
use App\Repository\QuizRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class QuizTakersQuizzesProvider implements ProviderInterface
{
    public function __construct(
        private QuizRepository $quizRepository,
        private TokenStorageInterface $tokenStorage,
        private $collectionExtensions,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $token = $this->tokenStorage->getToken();
        /** @var User $user */
        $user = $token->getUser();
        $queryBuilder = $this->quizRepository->createQueryBuilder('o');
        $alias = $queryBuilder->getRootAliases()[0];
        // dd($user->getId());
        $queryBuilder
            ->leftJoin(QuizTaker::class, 'qt', 'WITH', "$alias.id = qt.quiz")
            ->andWhere('qt.quizTaker = :quizTaker')
            ->setParameter('quizTaker', $user);

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            // Extensions are (in this order)
            // - "App\Doctrine\BookExtension"
            // - "ApiPlatform\Doctrine\Orm\Extension\FilterExtension"
            // - "ApiPlatform\Doctrine\Orm\Extension\FilterEagerLoadingExtension"
            // - "ApiPlatform\Doctrine\Orm\Extension\EagerLoadingExtension"
            // - "ApiPlatform\Doctrine\Orm\Extension\OrderExtension"
            // - "ApiPlatform\Doctrine\Orm\Extension\PaginationExtension"

            $extension->applyToCollection(
                $queryBuilder,
                $queryNameGenerator,
                $context['resource_class'],
                // $context['operation_name'],
                $operation,
                $context
            );

            // This next condition check if we have the pagination activated (by default is yes) and the result is returned
            if (
                $extension instanceof QueryResultCollectionExtensionInterface
                &&
                $extension->supportsResult($context['resource_class'], $operation, $context)
            ) {
                return $extension->getResult($queryBuilder, $context['resource_class'], $operation, $context);
            }
        }

        // we are here only if we have deactivate the pagination
        return $queryBuilder->getQuery()->getResult();
    }
}
