<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamFormType;
use App\Form\TeamPlayerFormType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team')]
final class TeamController extends AbstractController
{
    #[Route('/', name: 'team_index', methods:['GET'])]
    public function index(TeamRepository $teamRepo): Response
    {
        $teams= $teamRepo->findBy( [
           'coach' => $this->getUser()
        ]);
        
        return $this->render('team/index.html.twig', ['teams'=> $teams]);
    }
    #[Route('/new', name:'team_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {   
        $newTeam = new Team();
        $newTeam->setCoach($this->getUser());

        $formTeam = $this-> createForm(TeamFormType::class, $newTeam);
        $formTeam->handleRequest($request);

        if($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em-> persist($newTeam);
            $em-> flush();
            
            return $this->redirectToRoute('team_index');
        }
        return $this->render('team/new.html.twig', ['formTeam'=>$formTeam]);
    }

    #[Route('/show/{id}', name:'team_show', methods:['GET'])]
    public function show(Team $team)
    {
    if ($team->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
     return $this->render('team/show.html.twig', ['team'=>$team]);

    }
    
    #[Route('/update/{id}', name:'team_update', methods:['GET', 'POST'])]
    public function update(Team $team, Request $request, EntityManagerInterface $em ) 
    {
        if ($team->getCoach() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $formTeam = $this-> createForm(TeamFormType::class, $team);
        $formTeam->handleRequest($request);

        if($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em-> persist($team);
            $em-> flush();
            
            return $this->redirectToRoute('team_index');
        }
        return $this->render('team/new.html.twig', ['formTeam'=>$formTeam]);
        
    }
    #[Route('/delete/{id}', name:'team_delete', methods:['POST'])]
    public function delete(Team $team, Request $request, EntityManagerInterface $em) 
    {
    if ($team->getCoach() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
      if($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('team_index');
      }
    }
    #[Route('/{id}/player', name:'team_player', methods:['GET', 'POST'])]
    public function addPlayer(Team $team, Request $request, EntityManagerInterface $em)
    {
        if ($team->getCoach() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
        }

        $form = $this->createForm(TeamPlayerFormType::class , $team, [
        'coach' => $this->getUser(), 
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('team_index');
        }
        return $this->render('team/addPlayer.html.twig' ,[
                'team'=>$team,
                'form'=>$form->createView()
        ]);

    }
}
