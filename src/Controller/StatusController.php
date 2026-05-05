<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusFormType;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/status')]
final class StatusController extends AbstractController
{
    #[Route('/', name: 'status_index', methods:['GET'])]
    public function index(StatusRepository $statusRepository): Response
    {
        $allStatus = $statusRepository->findAll();
        return $this->render('status/index.html.twig', [
            'allStatus' => $allStatus
        ]);
    }
    #[Route('/new', name: 'status_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newStatus = new Status();
        $formStatus = $this->createForm(StatusFormType::class , $newStatus);
        $formStatus->handleRequest($request);

        if($formStatus->isSubmitted() && $formStatus->isValid()) {
            $em->persist($newStatus);
            $em->flush();

            return $this->redirectToRoute('status_index');

        }
        return $this->render('status/new.html.twig', [
            'formStatus' => $formStatus
        ]);
    }
    #[Route('/show/{id}', name: 'status_show', methods:['GET'])]
    public function show( Status $status)
    {
        return $this->render('status/show.html.twig', [
            'status' => $status
        ]);
    }
      #[Route('/update/{id}', name: 'status_update', methods:['GET', 'POST'])]
    public function update(Status $status, Request $request, EntityManagerInterface $em)
    {
      
        $formStatus = $this->createForm(StatusFormType::class , $status);
        $formStatus->handleRequest($request);

        if($formStatus->isSubmitted() && $formStatus->isValid()) {
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('status_index');

        }
        return $this->render('status/update.html.twig', [
            'formStatus' => $formStatus
        ]);
    }
      #[Route('/delete/{id}', name: 'status_delete', methods:['POST'])]
    public function delete(Status $status, Request $request, EntityManagerInterface $em)
    {
    
        if($this->isCsrfTokenValid('delete' . $status->getId(), $request->request->get('_token'))){
        $em->remove($status);
        $em->flush();

      }
         return $this->render('status/index.html.twig', ['status'=>$status]);
    }
}
