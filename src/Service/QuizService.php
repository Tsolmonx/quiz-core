<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuestionGroup;
use App\Entity\QuestionImage;
use App\Entity\QuestionResponse;
use App\Entity\Quiz;
use App\Entity\QuizImage;
use App\Entity\QuizResponse;
use App\Entity\QuizTaker;
use App\Entity\User;
use App\Exception\BadRequest\AnswerNotExistsInQuestionException;
use App\Exception\NotFound\QuestionGroupNotFoundException;
use App\Repository\QuizRepository;
use App\Repository\QuizResponseRepository;
use App\Service\ImageUploaderService;
use App\Validator\QuizValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmozart\Assert\Assert;

class QuizService
{
    public function __construct(
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter,
        private ImageUploaderService $imageUploaderService,
        private QuizValidator $quizValidator,
        private QuizResponseRepository $quizResponseRepository,
        private NormalizerInterface $normalizer
    ) {
    }

    public function addQuestions(Quiz $quiz, array $array)
    {
        foreach ($array as $params) {
            $this->createQuestion($quiz, $params);
        }
        $this->em->flush();
        return $quiz;
    }

    public function createQuestion(Quiz $quiz, array $params): Question
    {
        $group = $this->iriConverter->getResourceFromIri($params['questionGroup']);

        if (!$group instanceof QuestionGroup) {
            throw new QuestionGroupNotFoundException($params['questionGroup']);
        }

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

    public function submitQuizAnswers(Quiz $quiz, User $user, array $array): QuizResponse
    {
        $this->quizValidator->validateSubmitQuizAnswers($array);

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
        $quizResponse->setQuiz($quiz);

        $totalRightAnswers = 0;
        $totalQuestions = count($quiz->getQuestions());
        foreach ($array as $params) {
            /** @var Question $question */
            $question = $this->iriConverter->getResourceFromIri($params['questionId']);
            Assert::isInstanceOf($question, Question::class, 'Question not found');

            $questionResponse = new QuestionResponse();
            $questionResponse->setQuestion($question);
            $quizResponse->addQuestionResponse($questionResponse);

            $question->getAnswers()->map(function (Answer $answer) use ($questionResponse) {
                if ($answer->isIsRightAnswer() === true) {
                    $questionResponse->addRightAnswer($answer);
                }
            });

            $answersSelected =  count($params['answers']);
            $rightAnswers = 0;
            foreach ($params['answers'] as $answerId) {
                /** @var Answer $answer */
                $answer = $this->iriConverter->getResourceFromIri($answerId);
                $answerExists = false;
                foreach ($question->getAnswers() as $questionAnswer) {
                    if ($answer->getId() === $questionAnswer->getId()) {
                        $answerExists = true;
                    }
                }
                if (!$answerExists) {
                    throw new AnswerNotExistsInQuestionException($answerId);
                }

                if ($answer->isIsRightAnswer() === true) {
                    $rightAnswers++;
                }
                $questionResponse->addSelectedAnswer($answer);
            }
            $isCorrect = false;
            if ($rightAnswers > 0 && $answersSelected / $rightAnswers == 1) {
                $isCorrect = true;
                $totalRightAnswers++;
            }
            $questionResponse->setIsCorrect($isCorrect);

            $this->em->persist($questionResponse);
        }

        $quizResponse->setTotalRightAnswers($totalRightAnswers);
        $quizResponse->setPercent($totalRightAnswers * 100 / $totalQuestions);

        $this->em->persist($quizResponse);
        $this->em->flush();

        return $quizResponse;
    }
}
