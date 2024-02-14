<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class AuthenticationTest extends ApiTestCase
{
    use ResetDatabase;

    public function testLoginAndRegister(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $manager = $container->get('doctrine')->getManager();

        // retrieve a token
        $response = $client->request('POST', '/api/register', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'plainPassword' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            "@context" => "/api/contexts/User",
            "@id" => "/api/users/1",
            "@type" => "User",
            "id" => 1,
            "email" => "test@example.com"
        ]);

        $response = $client->request('POST', '/api/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
    }
}
