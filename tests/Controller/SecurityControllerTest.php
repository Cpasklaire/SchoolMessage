<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{

        public function testRegistration()
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/enregistrer');
            $this->assertResponseIsSuccessful();
    
            $form = $crawler->selectButton('Créer')->form();
            $form['user[pseudo]'] = 'test_user';
            $form['user[password][first]'] = 'testpassword';
            $form['user[password][second]'] = 'testpassword';
            $client->submit($form);
    
            $this->assertResponseRedirects('/connection');
        }

        public function testLogout()
        {
            $client = static::createClient();
            $client->request('GET', '/logout');
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
        }

        public function testLogin()
        {
            $client = static::createClient();
            $client->request('GET', '/connection');
    
            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('h2', 'Connexion');
            $this->assertSelectorExists('form[action="/connection"][method="post"]');
            $this->assertSelectorExists('input[placeholder="Pseudo"][type="text"][name="_username"]');
            $this->assertSelectorExists('input[placeholder="Mot de passe"][type="password"][name="_password"]');
            $this->assertSelectorExists('button[type="submit"]');
        }
    }
