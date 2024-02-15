<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserImageVoter extends Voter
{
    public const CREATE = 'USER_IMAGE_CREATE';
    public const DELETE = 'USER_IMAGE_DELETE';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (is_string($subject)) {
            $user = $this->em->getRepository(User::class)->find((int) $subject);

            return in_array($attribute, [self::CREATE, self::DELETE]) && $user instanceof User;
        } elseif ($subject instanceof UserImage) {
            return in_array($attribute, [self::CREATE, self::DELETE]);
        }
        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                /** @var User $user */
                if ((int) $subject === $user->getId()) {
                    return true;
                }
                break;

            case self::DELETE:
                if ($subject->getOwner() === $user) {
                    return true;
                }

                break;
        }

        return false;
    }
}
