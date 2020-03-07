<?php
// src/Controller/ProfilController.php

namespace App\Controller;

use App\Entity\Hobbis;
use App\Entity\Picture;
use App\Entity\User;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilController extends AbstractController
{
  public function addFriend(User $user, User $friend)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $user->addFriend($friend);
    $entityManager->flush();
  }

  public function removeFriend(User $user, User $friend)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $user->removeFriend($friend);
    $entityManager->flush();
  }

  /**
   * @Route("/profil", name="profil")
   */
  public function index(UserInterface $user_connected, Request $request)
  {
    $id = $request->query->get('id');
    if (is_null($id)) {
      throw new Exception("Aucun id entrée !", 1);
    }

    $user = $this->getDoctrine()
      ->getRepository(User::class)
      ->findOneById($id);

    if (is_null($user)) {
      throw new Exception("L'utilisateur n'existe pas !", 1);
    }

    if (!isset($user_connected)) {
      throw new Exception("Vous devez être connecté !", 1);
    }

    $user_connected = $this->getDoctrine()
      ->getRepository(User::class)
      ->findOneByMail($user_connected->getUsername());

    $isFriend = (in_array($user, $user_connected->getFriends()->toArray()));

    $action = $request->query->get('action');
    if (isset($action)) { //une modification de l'ami a eu lieu
      if ($action == "add" && !$isFriend) {
        $this->addFriend($user_connected, $user);
      } else if ($action == "remove" && $isFriend) {
        $this->removeFriend($user_connected, $user);
      }
      return $this->redirectToRoute('profil', array('id' => $user->getId()));
    }

    $pictures = $this->getDoctrine()
      ->getRepository(Picture::class)
      ->findByUser($user);

    $hobbis_raw = $this->getDoctrine()
      ->getRepository(Hobbis::class)
      ->findByUser($user);

    $categories = array();
    foreach ($hobbis_raw as $hobbi) {
      $name = $hobbi->getCategories()->getName();
      if (!key_exists($name, $categories))
        $categories[$name] = array();
      array_push($categories[$name], $hobbi);
    }

    $content = $this->render('Profil/profil.html.twig', [
      'isFriend' => $isFriend,
      'user' => $user,
      'pictures' => $pictures,
      'categories' => $categories,
    ]);

    return new Response($content);
  }
}
