<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageControllerTest extends WebTestCase
{
    public function setUp(): void

    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('SuperTesteur@school.com');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);
    }

    public function testList()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('list'));
        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $crawler->filter('h3:contains("message1")'));
        $this->assertCount(2, $crawler->filter('h3:contains("' . "message2" . '")'));
        $this->assertCount(0, $crawler->filter('h3:contains("' . "message3" . '")'));
    }
    public function testDraftList()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('draftList'));
        $this->assertResponseIsSuccessful();
        $this->assertCount(0, $crawler->filter('h3:contains("' . "message1" . '")'));
        $this->assertCount(0, $crawler->filter('h3:contains("' . "message2" . '")'));
        $this->assertCount(2, $crawler->filter('h3:contains("' . "message3" . '")'));
    }
    public function testFormMessage()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('new'));
        $this->assertResponseIsSuccessful();
        
        $form = $crawler->selectButton('Abandonner')->form();
        $form['message[subjet]'] = 'test_subjet';
        $form['message[text]'] = 'test_text';
        $this->client->submit($form);
        
        $this->assertResponseRedirects('/message');
    }
}