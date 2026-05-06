<?php

namespace App\Controller;


use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil')]
final class ProfilController extends AbstractController
{
    #[Route('/update/id', name: 'profil_update', methods:['GET', 'POST'])]
    public function update( Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hash): Response
    {
        $user=$this->getUser();
        if(!$user ) {
            return $this->redirectToRoute('app_login');
        }
        $formUser = $this->createForm(UserFormType::class , $user );
        $formUser->handleRequest($request);
         if($formUser->isSubmitted() && $formUser->isValid()){
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home_index');
         }
        return $this->render('profil/update.html.twig', [
            'formUser' => $formUser->createView(),
        ]);
    }
}
