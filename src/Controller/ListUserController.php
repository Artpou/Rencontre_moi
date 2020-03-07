<?php

namespace App\Controller;

use App\Entity\Hobbis;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ListUserController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(UserInterface $user, Request $request)
    {
        if (!isset($user)) {
            throw new Exception("Error Processing Request", 1);
        }

        $filter = $request->query->get('filter');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByMail($user->getUsername());

        if(isset($filter) && $filter = "preference") {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAllByPreference($user);
        } else {
            $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAllExceptUser($user);
        }


        $content = $this->render('list_user/list.html.twig', [
            'users' => $users,
        ]);
        return new Response($content);
    }
}
