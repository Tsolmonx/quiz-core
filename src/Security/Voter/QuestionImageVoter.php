<?php

namespace App\Security\Voter;

use App\Entity\Question;
use App\Entity\QuestionImage;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionImageVoter extends Voter
{
    public const CREATE = 'QUESTION_IMAGE_CREATE';
    public const DELETE = 'QUESTION_IMAGE_DELETE';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (is_string($subject)) {
            /** @var Question $question */
            $question = $this->em->getRepository(Question::class)->find((int) $subject);
            if (!$question instanceof Question) {
                return false;
            }

            $quiz = $question->getQuiz();

            return in_array($attribute, [self::CREATE, self::DELETE]) && $quiz instanceof Quiz;
        } elseif ($subject instanceof QuestionImage) {
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
                $question = $this->em->getRepository(Question::class)->find((int) $subject);
                $quiz = $question->getQuiz();
                if ($quiz->getCreatedBy() === $user) {
                    return true;
                }

                break;
            case self::DELETE:
                if ($subject->getOwner()->getQuiz()->getCreatedBy() === $user) {
                    return true;
                }

                break;
        }

        return false;
    }
}
