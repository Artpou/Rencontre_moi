<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessagesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(UserInterface $user)
    {
      if(!empty($_GET['id'])){ // on vérifie que l'id est bien présent et pas vide
          $receiver_id = (int) $_GET['id'];
          $receiver=$this->getDoctrine()
              ->getRepository(User::class)
              ->findOneById($receiver_id);

          $messages=$this->getDoctrine()
            ->getRepository(Messages::class)
            ->getMessages($user->getId(),$receiver_id);
          return $this->render('chat/index.html.twig', [
              'messages' => $messages,
              'receiver' => $receiver
          ]);
        }
        return new Response("Error on ID");
    }

    /**
     * @Route("/charger", name="charger")
     */
     public function charger()
     {
       if(!empty($_POST['premierID']) && !empty($_POST['author_id'])&& !empty($_POST['receiver_id']) ){ // on vérifie que l'id est bien présent et pas vide
           $id = (int) $_POST['premierID']; // on s'assure que c'est un nombre entier
           $author_id = (int) $_POST['author_id']; // on s'assure que c'est un nombre entier
           $receiver_id = (int) $_POST['receiver_id']; // on s'assure que c'est un nombre entier
           $req=$this->getDoctrine()
                ->getRepository(Messages::class)
                ->getNewMessages($id,$author_id,$receiver_id);
          $messages="";
          foreach ($req as $donnees){
                if($author_id==$donnees->getAuthor()->getId()) $class="my-message";
                else $class="other-message";
                $messages .= "<scroll-page class=\"".$class."\" id=\"" . $donnees->getId() . "\">" .  $donnees->getAuthor()->getName() . " : " . $donnees->getMessage() . "</scroll-page>";
          }
          return new Response($messages); // enfin, on retourne les messages à notre script JS
       }
     }

     /**
      * @Route("/traitement", name="traitement")
      */
     public function traitement()
     {
       if(isset($_POST['submit'])){ // si on a envoyé des données avec le formulaire
           if(!empty($_POST['message'])){ // si les variables ne sont pas vides
               $author_id = (int) $_POST['author_id']; // on s'assure que c'est un nombre entier
               $receiver_id = (int) $_POST['receiver_id']; // on s'assure que c'est un nombre entier
               $message = $_POST['message']; // on sécurise nos données
               $author=$this->getDoctrine()
                   ->getRepository(User::class)
                   ->findOneById($author_id);
               $receiver=$this->getDoctrine()
                   ->getRepository(User::class)
                   ->findOneById($receiver_id);

               $this->getDoctrine()
                  ->getRepository(Messages::class)
                  ->insertMessage($author,$receiver,$message);
           }
       }else {
         return new Response("Erreur no argument");
       }
    }
}
