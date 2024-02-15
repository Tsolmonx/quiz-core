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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class QuizProvider implements ProviderInterface
{
    public function __construct(
        private QuizRepository $quizRepository,
        private TokenStorageInterface $tokenStorage,
        private $collectionExtensions,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $queryBuilder = $this->quizRepository->createQueryBuilder('o');
        $alias = $queryBuilder->getRootAliases()[0];

        if (array_key_exists('filters', $context) && array_key_exists('myTaken', $context['filters']) && (bool) $context['filters']['myTaken'] === true) {
            $token = $this->tokenStorage->getToken();
            if (!$token instanceof TokenInterface) {
                throw new TokenNotFoundException();
            }
            /** @var User $user */
            $user = $token->getUser();
            if (!$user instanceof User) {
                throw new AccessDeniedException();
            }

            $queryBuilder
                ->leftJoin(QuizTaker::class, 'qt', 'WITH', "$alias.id = qt.quiz")
                ->andWhere('qt.quizTaker = :quizTaker')
                ->setParameter('quizTaker', $user);
        } elseif (array_key_exists('filters', $context) && array_key_exists('myCreated', $context['filters']) && (bool) $context['filters']['myCreated'] === true) {
            $token = $this->tokenStorage->getToken();
            if (!$token instanceof TokenInterface) {
                throw new TokenNotFoundException();
            }
            /** @var User $user */
            $user = $token->getUser();
            if (!$user instanceof User) {
                throw new AccessDeniedException();
            }

            $queryBuilder
                ->andWhere("$alias.createdBy = :owner")
                ->setParameter('owner', $user);
        }


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
