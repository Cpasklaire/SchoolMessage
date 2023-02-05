<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $dateImmutable = new \DateTimeImmutable();

        $user = new User();
        $user->setPseudo('SuperTesteur');
        $user->setRoles(['']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'motdepasse'));
        $user->setEmail('SuperTesteur@school.com');
        $user->setCreatedAt($dateImmutable);
        $manager->persist($user);

        $message1 = new Message();
        $message1->setUserSender($user);
        $message1->setCreatedAt($dateImmutable);
        $message1->setSubjet('message1');
        $message1->setSent(true);
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setUserSender($user);
        $message2->setCreatedAt($dateImmutable);
        $message2->setSubjet('message2');
        $message2->setSent(true);
        $manager->persist($message2);

        $message3 = new Message();
        $message3->setUserSender($user);
        $message3->setCreatedAt($dateImmutable);
        $message3->setSubjet('message3');
        $message3->setSent(false);
        $manager->persist($message3);

        $manager->flush();
    }
}

