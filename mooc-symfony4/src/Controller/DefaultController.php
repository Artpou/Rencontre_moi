<?php
// src/Controller/DefaultController.php

namespace App\Controller;

use App\Form\UserFirstFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;


class DefaultController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function index(Environment $twig): Response
  {
    $form = $this->createForm(UserFirstFormType::class);

    $content = $twig->render('index.html.twig', [
      'form' => $form->createView()
    ]);

    //$content = $twig->render('home.html.twig');
    return new Response($content);
  }
}
