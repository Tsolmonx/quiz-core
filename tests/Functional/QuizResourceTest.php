<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Component\HttpClient\HttpOptions;

class QuizResourceTest extends ApiTestCase
{
    use ResetDatabase;

    public function testPostToCreateQuiz(): void
    {
        $client = self::createClient();
        $client->request(
            'POST',
            '/api/quizzes/new',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'title' => 'Test quiz',
                    'description' => 'Some description',
                    'type' => 'binary',
                    'isGrouped' => false,
                ],
            ]
        );
    }
}
