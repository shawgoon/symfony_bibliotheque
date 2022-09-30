<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\User;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/modifLivre/{id}",name="app_modiflivre")
     * @Route("/ajoutLivre",name="app_ajoutlivre")
     */
    public function addBook(ManagerRegistry $doctrine, Request $requete, UserInterface $user, Livre $livre=null){
        if(!$livre){
            $livre = new Livre();
        }
        
        $form = $this->createForm(LivreType::class,$livre);
        $form->handleRequest($requete);
        if($form->isSubmitted() && $form->isValid()){
            $livre->setUser($user);
            $om = $doctrine->getManager();
            $om->persist($livre);
            $om->flush();
            return $this->redirectToRoute("app_livres");
        }
        $mode = false;
        if($livre->getId() != null){
            $mode = true;
        }
        return $this->render('livre/addBook.html.twig',[
            "livre" => $livre,
            "formulaireInscription"=>$form->createView(),
            "mode" => $mode
        ]);
    }
    /**
     * @Route("/supprimeLivre/{id},name="app_supprimlivre")
     */
    public function remove(ManagerRegistry $doctrine, Livre $livre){
        $om = $doctrine->getManager();
        $om->remove($livre);
        $om->flush();
        return $this->redirectToRoute("livres");
    }
}
