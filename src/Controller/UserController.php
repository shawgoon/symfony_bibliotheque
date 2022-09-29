<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function inscription(ManagerRegistry $doctrine, Request $requete,
    UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($requete);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            // ajout d'une photo de profil
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('avatar')->getData();
            if($imageFile){
                $origineFileName = pathinfo($imageFile->getClientOriginalName(),
                PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($origineFileName);
                $newFileName = $safeFileName."-".uniqid().".".$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('avatar'),
                        $newFileName
                    );
                } catch (FileException $error) {
                    $error->getMessage();
                }
                $user->setAvatar($newFileName);
            }
            $om = $doctrine->getManager();
            $om->persist($user);
            $om->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('user/add.html.twig', [
            "formulaireInscription" => $form->createView()
        ]);
    }
}
