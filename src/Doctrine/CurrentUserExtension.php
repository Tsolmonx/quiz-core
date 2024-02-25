<?php

declare(strict_types=1);

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\QuizResponse;
use App\Entity\User;
use App\Entity\User\ShopUser;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass, $operation->getName());
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass, $operation->getName());
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, ?string $operationName = null): void
    {
        if (str_starts_with($operationName, 'app_get_my')) {
            /** @var ShopUser $user */
            $user = $this->security->getUser();
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if (in_array($resourceClass, [QuizResponse::class])) {
                if ($user instanceof User) {
                    $queryBuilder->andWhere(sprintf('%s.quizTaker = :current_user', $rootAlias));
                    $queryBuilder->setParameter('current_user', $user);
                } else {
                    throw new AccessDeniedException();
                }
            } else {
                return;
            }
        }
    }
}
