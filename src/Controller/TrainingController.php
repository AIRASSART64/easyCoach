<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingFormType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('/', name: 'training_index', methods:['GET'])]
    public function index(TrainingRepository $trainingRepo): Response
    {
        $trainings = $trainingRepo->findBy([
            'coach'=>$this->getUser()
        ]);

        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }
        #[Route('/new', name:'training_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newTraining = new Training();
        $newTraining->setCoach($this->getUser());

        $formTraining = $this-> createForm(TrainingFormType::class, $newTraining);
        $formTraining->handleRequest($request);

        if($formTraining->isSubmitted() && $formTraining->isValid()) {
            $em-> persist($newTraining);
            $em-> flush();
            
            return $this->redirectToRoute('training_index');
        }
        return $this->render('training/new.html.twig', ['formTraining'=>$formTraining]);
    }
     #[Route('/show/{id}', name:'training_show', methods:['GET'])]
    public function show(Training $training)
    {
         if ($training->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
         return $this->render('training/show.html.twig', ['training'=>$training]);
    }
        #[Route('/update/{id}', name:'training_update', methods:['GET', 'POST'])]
    public function update(Training $training, Request $request, EntityManagerInterface $em)
    {    
        if ($training->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
        $formTraining = $this-> createForm(TrainingFormType::class, $training);
        $formTraining->handleRequest($request);

        if($formTraining->isSubmitted() && $formTraining->isValid()) {
            $em-> persist($training);
            $em-> flush();
            
            return $this->redirectToRoute('training_index');
        }
        return $this->render('training/new.html.twig', ['formTraining'=>$formTraining]);
    }
      #[Route('/delete/{id}', name:'training_delete', methods:['POST'])]
    public function delete(Training $training , Request $request , EntityManagerInterface $em)
    { 
         if ($training->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
      if($this->isCsrfTokenValid('delete' . $training->getId(), $request->request->get('_token'))){
        $em->remove($training);
        $em->flush();

      }
         return $this->render('training/index.html.twig', ['training'=>$training]);
    }
       
    
}
