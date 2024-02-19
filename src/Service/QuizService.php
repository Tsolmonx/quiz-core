<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionImage;
use App\Entity\Quiz;
use App\Entity\QuizImage;
use App\Entity\QuizResponse;
use App\Entity\QuizTaker;
use App\Entity\User;
use App\Service\ImageUploaderService;
use App\Validator\QuizValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class QuizService
{
    public function __construct(
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter,
        private ImageUploaderService $imageUploaderService,
        private QuizValidator $quizValidator
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

    public function submitQuizAnswers(Quiz $quiz, User $user, array $array)
    {
        $this->quizValidator->validateSubmitQuizAnswers($array);
        foreach ($array as $params) {
            $attempt = 1;
            $prevAttempts = $this->em->getRepository(QuizResponse::class)->findBy(['quizTaker' => $user, 'quiz' => $quiz], ['attempt' => 'desc']);
            if (count($prevAttempts) > 0) {
                $lastAttempt = $prevAttempts[0];
                if ($lastAttempt instanceof QuizResponse) {
                    $attempt = $lastAttempt->getAttempt() + 1;
                }
            }

            $quizResponse = new QuizResponse();
            $quizResponse->setQuizTaker($user);
            $quizResponse->setAttempt($attempt);
            $question = $this->iriConverter->getResourceFromIri($params['questionId']);
            $quizResponse->setQuestion($question);
            $quizResponse->setQuiz($quiz);
            foreach ($params['answers'] as $answerId) {
                $answer = $this->iriConverter->getResourceFromIri($answerId);
                $quizResponse->addSelectedAnswer($answer);
            }
            $this->em->persist($quizResponse);
        }
        $this->em->flush();

        return $quiz->getQuizResponsesByUserAndAttempt($user, $attempt);
    }
}
