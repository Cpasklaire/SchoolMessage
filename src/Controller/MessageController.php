<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Services\MailerService;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'list')]
    public function list(MessageRepository $messagerepo, Security $security): Response
    {
        $user = $security->getUser();
        $messages = $messagerepo->findBy(['userSender' => $user,'sent' => 1]);
        return $this->render('message/list.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/message/brouillons', name: 'draftList')]
    public function draftList(MessageRepository $messagerepo, Security $security): Response
    {
        $user = $security->getUser();
        $messages = $messagerepo->findBy(['userSender' => $user,'sent' => 0]);
        return $this->render('message/list.html.twig', [
            'messages' => $messages,
        ]);
    }
    
    //create message
    #[Route('/message/nouveau', name: 'new')]
    #[Route('/message/modifier/{id}', name: 'edit')]
    public function formMessage(Message $message = null, Request $request, EntityManagerInterface $manager): Response {
        
        if (!$message) {
            $message = new Message();
        }
        
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$message->getId()) {
                $message->setCreatedAt(new \DateTimeImmutable());
            }

            $message->setUpDate(new \DateTime())
                ->setUserSender($this->getUser())
                ->setSent(false);
            $manager->persist($message);
            $manager->flush();
            
            if ($request->request->has('send')) {
                return $this->redirectToRoute('sending', ['id' => $message->getId()]);
            }else{
                $this->addFlash("flash", "Message enregistrer");
                return $this->redirectToRoute('list');
            }          
        }
        return $this->render('message/new.html.twig', [
            'form' => $form->createView(),
            'editMode' => $message->getId() !== null,
        ]);
    }

    //create message
    #[Route('/message/envoi/{id}', name: 'sending')]
    public function sendMail($id, Request $request, MailerService $mailer, EntityManagerInterface $manager, MessageRepository $messageRepo): Response 
    {
        $message = $messageRepo->findOneBy(['id' => $id]);
        
        $from = $message->getUserSender()->getEmail();      
        $to = $message->getRecipient();
        $subject = $message->getSubjet();
        $text = $message->getText();

        $email = $request->request->get('email');
        $mailer->sendEmail(
            from: $from, 
            to: $to, 
            subject: $subject, 
            text: $text);   
            
        $message->setSent(true);
        $manager->persist($message);
        $manager->flush();

        $this->addFlash("flash", "Message envoyer");
        return $this->redirectToRoute('list');
    }

    //delect message
    #[Route('/delete/{id}', name: 'deleteMessage')]
    public function deleteMessage(Message $message, EntityManagerInterface $manager): Response
    {
        $manager->remove($message);
        $manager->flush();
        $this->addFlash("flash", "Message supprimer");
        return $this->redirectToRoute('list');
    }
}
