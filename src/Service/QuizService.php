<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionImage;
use App\Entity\Quiz;
use App\Entity\QuizImage;
use App\Entity\QuizTaker;
use App\Entity\User;
use App\Service\ImageUploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class QuizService
{
    public function __construct(
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter,
        private ImageUploaderService $imageUploaderService
    ) {
    }

    public function createQuestion(Quiz $quiz, array $params): Question
    {
        $group = $this->iriConverter->getResourceFromIri($params['questionGroup']);
        $question = new Question();
        $question->setQuiz($quiz);
        $question->setQuestionGroup($group);
        $question->setName($params['name']);
        $question->setPosition($params['position']);

        foreach ($params['answers'] as $answerParams) {
            $answer = $this->createAnswer($answerParams);
            $question->addAnswer($answer);
        }

        $this->em->persist($question);
        $this->em->flush();
        return $question;
    }

    public function createAnswer(array $params): Answer
    {
        $answer = new Answer();
        $answer->setPosition($params['position']);
        $answer->setValue($params['value']);
        $answer->setIsRightAnswer($params['isRightAnswer']);

        $this->em->persist($answer);

        return $answer;
    }

    public function takeQuiz(Quiz $quiz, User $user): QuizTaker
    {
        $currentDate = new DateTime();
        $expire = $currentDate->modify('+7 days'); // Add 7 days to the current date

        $quizTaker = new QuizTaker();
        $quizTaker->setIsEnabled(true);
        $quizTaker->setQuizTaker($user);
        $quizTaker->setLicenseExpireDate($expire);
        $quiz->addQuizTaker($quizTaker);

        $this->em->persist($quizTaker);
        $this->em->flush();

        return $quizTaker;
    }
}
