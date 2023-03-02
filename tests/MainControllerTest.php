<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "Serie's detail");
    }

    public function testCreateSerieIsWorkingIfNotLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseRedirects('/login', 302);

    }

    public function testCreateSerieIsWorkingIfLogged(): void
    {
        $client = static::createClient();


        //pour récupérer les services
        $userRepo = static::getContainer()->get(UserRepository::class);
        //on récupère un user
        $user = $userRepo->findOneBy(['email' => 'abc@mail.com']);
        //simule une connexion
        $client->loginUser($user);

        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseIsSuccessful();

    }
}
