<?php

declare(strict_types=1);

namespace App\Controller\Api\Action;

use App\Entity\Quiz;
use App\Service\QuizService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateQuestion
{
    public function __construct(private EntityManagerInterface $em, private QuizService $quizService)
    {
    }

    public function __invoke(Request $request, $quizId)
    {
        if ($request->request->all()) {
            $params = $request->request->all();
        } else {
            $params = json_decode($request->getContent(), true);
        }

        try {
            $quiz = $this->em->getRepository(Quiz::class)->find((int) $quizId);
            $question = $this->quizService->createQuestion($quiz, $params);
        } catch (HttpException $e) {
            throw new HttpException($e->getStatusCode(), $e->getMessage());
        }
        return $question;
    }
}
