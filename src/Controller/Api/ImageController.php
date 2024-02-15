<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Helper;
use App\Entity\Answer;
use App\Entity\AnswerImage;
use App\Entity\Question;
use App\Entity\QuestionImage;
use App\Entity\Quiz;
use App\Entity\QuizImage;
use App\Entity\User;
use App\Entity\UserImage;
use App\Exception\NotFound\AnswerNotFoundException;
use App\Exception\NotFound\QuestionNotFoundException;
use App\Exception\NotFound\QuizNotFoundException;
use App\Exception\NotFound\UserNotFoundException;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ImageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ImageService $imageService,
        private NormalizerInterface $normalier
    ) {
    }

    // START OF ADD IMAGES

    #[IsGranted("QUIZ_IMAGE_CREATE", 'quizId')]
    public function addQuizImage(Request $request, $quizId)
    {
        $type = $request->request->get('type');
        $file = $request->files->get('file');

        try {
            $quiz = $this->em->getRepository(Quiz::class)->find((int) $quizId);
            if (!$quiz instanceof Quiz) {
                throw new QuizNotFoundException((string) $quizId);
            }

            $image = $this->imageService->addQuizImage($quiz, $file, $type);
            $normalizedImage = $this->normalier->normalize($image, null, ['groups' => 'app:image:read']);

            return new JsonResponse($normalizedImage, 201);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("QUESTION_IMAGE_CREATE", 'questionId')]
    public function addQuestionImage(Request $request, $questionId)
    {
        $type = $request->request->get('type');
        $file = $request->files->get('file');

        try {
            $question = $this->em->getRepository(Question::class)->find((int) $questionId);
            if (!$question instanceof Question) {
                throw new QuestionNotFoundException((string) $questionId);
            }

            $image = $this->imageService->addQuestionImage($question, $file, $type);
            $normalizedImage = $this->normalier->normalize($image, null, ['groups' => 'app:image:read']);

            return new JsonResponse($normalizedImage, 201);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("ANSWER_IMAGE_CREATE", 'answerId')]
    public function addAnswerImage(Request $request, $answerId)
    {
        $type = $request->request->get('type');
        $file = $request->files->get('file');

        try {
            $answer = $this->em->getRepository(Answer::class)->find((int) $answerId);
            if (!$answer instanceof Answer) {
                throw new AnswerNotFoundException((string) $answerId);
            }

            $image = $this->imageService->addAnswerImage($answer, $file, $type);
            $normalizedImage = $this->normalier->normalize($image, null, ['groups' => 'app:image:read']);

            return new JsonResponse($normalizedImage, 201);
        } catch (HttpException $e) {
            dd($e);
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("USER_IMAGE_CREATE", 'userId')]
    public function addUserImage(Request $request, $userId)
    {
        $type = $request->request->get('type');
        $file = $request->files->get('file');

        try {
            $user = $this->em->getRepository(User::class)->find((int) $userId);
            if (!$user instanceof User) {
                throw new UserNotFoundException((string) $userId);
            }

            $image = $this->imageService->addUserImage($user, $file, $type);
            $normalizedImage = $this->normalier->normalize($image, null, ['groups' => 'app:image:read']);

            return new JsonResponse($normalizedImage, 201);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    // END OF ADD IMAGES
    // START OF DELETE IMAGES

    #[IsGranted("QUIZ_IMAGE_DELETE", 'image')]
    public function deleteQuizImage(QuizImage $image)
    {
        try {
            $this->imageService->deleteQuizImage($image);

            return Helper::JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("QUESTION_IMAGE_DELETE", 'image')]
    public function deleteQuestionImage(QuestionImage $image)
    {
        try {
            $this->imageService->deleteQuestionImage($image);

            return Helper::JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("ANSWER_IMAGE_DELETE", 'image')]
    public function deleteAnswerImage(AnswerImage $image)
    {
        try {
            $this->imageService->deleteAnswerImage($image);

            return Helper::JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }

    #[IsGranted("USER_IMAGE_DELETE", 'image')]
    public function deleteUserImage(UserImage $image)
    {
        try {
            $this->imageService->deleteUserImage($image);

            return Helper::JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }
    // END OF DELETE IMAGES
}
