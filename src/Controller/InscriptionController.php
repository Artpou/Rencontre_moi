<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(Environment $twig, Request $request): Response
    {
        $user = new User(); //nouvelle utilisateur

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            //cryptage du mot de passe
            
            $passwordEncoded = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($passwordEncoded);            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task); //insertion de l'utilisateur
            $entityManager->flush(); 

            return $this->redirectToRoute('home');
        }

        $content = $twig->render('inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);

        //$content = $twig->render('home.html.twig');
        return new Response($content);
    }
}
