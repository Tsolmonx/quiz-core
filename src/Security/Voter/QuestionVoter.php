<?php

namespace App\Security\Voter;

use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    public const EDIT = 'QUESTION_EDIT';
    public const CREATE = 'QUESTION_CREATE';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (is_string($subject)) {
            $entity = $this->em->getRepository(Quiz::class)->find((int) $subject);
            return in_array($attribute, [self::EDIT, self::CREATE]) && $entity instanceof Quiz;
        } elseif ($subject instanceof Question) {
            return in_array($attribute, [self::EDIT, self::CREATE]);
        }
        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Question $subject */
        switch ($attribute) {
            case self::CREATE:
                $quiz = $this->em->getRepository(Quiz::class)->find((int) $subject);
                if ($quiz->getCreatedBy() === $user) {
                    return true;
                }

                break;
            case self::EDIT:
                $quiz = $subject->getQuestionGroup()->getQuiz();
                if ($quiz->getCreatedBy() === $user) {
                    return true;
                }

                break;
        }

        return false;
    }
}
