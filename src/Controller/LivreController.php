<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\User;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class LivreController extends AbstractController
{
    /**
     * @Route("/showlivres", name="app_livres")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Livre::class);
        $livres = $repo->findAll();

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }
    /**
     * @Route("/ajoutLivre",name="ajoutlivre")
     */
    public function addBook(ManagerRegistry $doctrine, Request $requete, UserInterface $user){
        $livre = new Livre();
        
        $form = $this->createForm(LivreType::class,$livre);
        $form->handleRequest($requete);
        if($form->isSubmitted() && $form->isValid()){
            $livre->setUser($user);
            $om = $doctrine->getManager();
            $om->persist($livre);
            $om->flush();
            return $this->redirectToRoute("app_livres");
        }
        return $this->render('livre/addBook.html.twig',[
            "formulaire"=>$form->createView()
        ]);
        
    }
}
