<?php

namespace App\Security\Voter;

use App\Entity\Answer;
use App\Entity\AnswerImage;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AnswerImageVoter extends Voter
{
    public const CREATE = 'ANSWER_IMAGE_CREATE';
    public const DELETE = 'ANSWER_IMAGE_DELETE';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (is_string($subject)) {
            /** @var Answer $answer */
            $answer = $this->em->getRepository(Answer::class)->find((int) $subject);
            if (!$answer instanceof Answer) {
                return false;
            }

            $quiz = $answer->getQuestion()->getQuiz();

            return in_array($attribute, [self::CREATE, self::DELETE]) && $quiz instanceof Quiz;
        } elseif ($subject instanceof AnswerImage) {
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
                /** @var Answer $answer */
                $answer = $this->em->getRepository(Answer::class)->find((int) $subject);
                $quiz = $answer->getQuestion()->getQuiz();
                if ($quiz->getCreatedBy() === $user) {
                    return true;
                }

                break;
            case self::DELETE:
                if ($subject->getOwner()->getQuestion()->getQuiz()->getCreatedBy() === $user) {
                    return true;
                }

                break;
        }


        return false;
    }
}
