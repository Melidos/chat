<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/add", name="addMessage")
     */
    public function addMessage()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $message = new Message();

        $message->setUsername($_GET["pseudo"]);
        $message->setMessage($_GET["message"]);
        $message->setDate(new DateTime());

        $entityManager->persist($message);

        $entityManager->flush();

        return $this->redirect("/message", 302);
    }

    /**
     * @Route("/message", name="getMessage")
     */
    public function getMessages()
    {
        $message = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findAll();
        
        $user = array();
        foreach ($message as $mess){
            array_push($user, $mess->getUsername());
        }

        return $this->render('User/user.html.twig', [
            'messages' => $message,
            'users' => array_unique($user),
            'controller_name' => 'MessageController',
        ]);
    }
}