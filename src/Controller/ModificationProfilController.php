<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Hobbis;
use App\Entity\Picture;
use App\Entity\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ModificationProfilController extends AbstractController
{
    public function createHobbi($name,Categories $categorie,User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $hobbi = new Hobbis();
        $hobbi->setName($name);
        $hobbi->setUser($user);
        $hobbi->setCategories($categorie);

        $entityManager->persist($hobbi);

        $entityManager->flush();
    }

    public function deleteHobbi(Hobbis $hobbi)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($hobbi);
        $entityManager->flush();
    }

    /**
     * @Route("/modification", name="modification_profil")
     */
    public function index(UserInterface $user, Request $request)
    {
        if (!isset($user)) {
            throw new Exception("Error Processing Request", 1);
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByMail($user->getUsername());

        $hobbis = $this->getDoctrine()
            ->getRepository(Hobbis::class)
            ->findByUser($user);

        $all_categories = $this->getDoctrine()
            ->getRepository(Categories::class)
            ->findAll($user);

        $action = $request->query->get('action');

        if (isset($action)) { //une modification a eu lieu
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($all_categories as $categorie) { //changement dans une des catégories
                if ($action == "remove_".$categorie->getName()) { // suppresion d'une donnée
                    $hobbi = $this->getDoctrine()
                        ->getRepository(Hobbis::class)
                        ->findOneByName($request->request->get('_name'));
                    $this->deleteHobbi($hobbi);
                } else if ($action == "add_".$categorie->getName()) { //ajout d'une donnée
                    $this->createHobbi($request->request->get('_name'),$categorie,$user);
                }
            }

            if ($action == "profil_picture") { //changement de l'image de profil
                //TODO
            } else if ($action == "description") { //changement de la description
                $user->setDescription($request->request->get('_description'));
            }
            $entityManager->flush(); //mise à jour des entités
            return $this->redirectToRoute('modification_profil');
        }

        $pictures = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findByUser($user);


        $categories = array();
        foreach ($hobbis as $hobbi) { //association nom_categorie => hobbi
            $name = $hobbi->getCategories()->getName();
            if (!key_exists($name, $categories)) { //si l'user possède des champs dans cette catégorie
                $categories[$name] = array();
            }
            array_push($categories[$name], $hobbi);
        }

        $others_categories = $this->getDoctrine()
            ->getRepository(Categories::class)
            ->findAllExceptedUser($user);

        $content = $this->render('modification_profil/index.html.twig', [
            'user' => $user,
            'pictures' => $pictures,
            'categories' => $categories,
            'others_categories' => $others_categories,
        ]);

        return new Response($content);
    }
}
