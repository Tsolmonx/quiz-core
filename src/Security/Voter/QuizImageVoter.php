<?php

namespace App\Security\Voter;

use App\Entity\Quiz;
use App\Entity\QuizImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuizImageVoter extends Voter
{
    public const CREATE = 'QUIZ_IMAGE_CREATE';
    public const DELETE = 'QUIZ_IMAGE_DELETE';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (is_string($subject)) {
            $entity = $this->em->getRepository(Quiz::class)->find((int) $subject);
            return in_array($attribute, [self::CREATE, self::DELETE]) && $entity instanceof Quiz;
        } elseif ($subject instanceof QuizImage) {
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
                $quiz = $this->em->getRepository(Quiz::class)->find((int) $subject);
                if ($quiz->getCreatedBy() === $user) {
                    return true;
                }

                break;
            case self::DELETE:
                if ($subject->getOwner()->getCreatedBy() === $user) {
                    return true;
                }

                break;
        }

        return false;
    }
}
