<?php

declare(strict_types=1);

namespace App\Controller\Api\Action;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Controller\Helper;
use App\Entity\Quiz;
use App\Entity\User;
use App\Service\QuizService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubmitQuizAnswers
{
    public function __construct(
        private EntityManagerInterface $em,
        private QuizService $quizService,
        private NormalizerInterface $normalizer
    ) {
    }

    public function __invoke(Request $request, $id, #[CurrentUser] User $user)
    {
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        if ($request->request->all()) {
            $params = $request->request->all();
        } else {
            $params = json_decode($request->getContent(), true);
        }

        try {
            $quiz = $this->em->getRepository(Quiz::class)->find((int) $id);
            $quizResponses = $this->quizService->submitQuizAnswers($quiz, $user, $params);
            $normalizedResponse = $this->normalizer->normalize($quizResponses, 'application/json', ['groups' => 'app:quiz_response:list']);
        } catch (HttpException $e) {
            throw new HttpException($e->getStatusCode(), $e->getMessage());
        }
        return Helper::JsonResponse(0, array_values($normalizedResponse));
    }
}
