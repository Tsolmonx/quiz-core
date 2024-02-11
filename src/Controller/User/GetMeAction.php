<?php

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetMeAction extends AbstractController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private NormalizerInterface $normalzier
    ) {
    }

    public function __invoke(Request $request): ?UserInterface
    {
        $user = $this->getUser();
        return $user;
    }
}
