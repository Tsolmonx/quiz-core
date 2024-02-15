<?php

declare(strict_types=1);

namespace App\Controller\Api\Action;

use App\Controller\Helper;
use App\Entity\Quiz;
use App\Entity\User;
use App\Service\QuizService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TakeQuiz
{
    public function __construct(
        private EntityManagerInterface $em,
        private QuizService $quizService,
        private NormalizerInterface $normalizer
    ) {
    }

    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request, $id, #[CurrentUser] User $user)
    {
        // TODO PAYMENT
        try {
            $quiz = $this->em->getRepository(Quiz::class)->find((int) $id);
            $quizTaker = $this->quizService->takeQuiz($quiz, $user);
            $normalized = $this->normalizer->normalize($quizTaker, null, ['groups' => 'app:quiz_taker:read']);

            return new JsonResponse($normalized, Response::HTTP_CREATED);
        } catch (HttpException $e) {
            return Helper::JsonResponse($e->getCode(), $e->getMessage(), $e->getStatusCode());
        }
    }
}
