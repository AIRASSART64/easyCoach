<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
final class GameController extends AbstractController
{
    #[Route('/', name: 'game_index', methods:['GET'])]
    public function index(GameRepository $gameRepo): Response
    {
        $games = $gameRepo->findBy([
            'coach'=>$this->getUser()
        ]);
        return $this->render('game/index.html.twig', [
            'games' => $games
        ]);
    }
    #[Route('/new', name:'game_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newGame = new Game();
        $newGame->setCoach($this->getUser());

        $formGame = $this-> createForm(GameFormType::class, $newGame);
        $formGame->handleRequest($request);

        if($formGame->isSubmitted() && $formGame->isValid()) {
            $em-> persist($newGame);
            $em-> flush();
            
            return $this->redirectToRoute('game_index');
        }
        return $this->render('game/new.html.twig', ['formGame'=>$formGame]);
    }

    #[Route('/show/{id}', name:'game_show', methods:['GET'])]
    public function show(Game $game)
    {
     if ($game->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");}
     return $this->render('game/show.html.twig', ['game'=>$game]);
    }
   

    #[Route('/update/{id}', name:'game_update', methods:['GET', 'POST'])]
    public function update(Game $game, Request $request, EntityManagerInterface $em)
    {
         if ($game->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");}
        $formGame = $this-> createForm(GameFormType::class, $game);
        $formGame->handleRequest($request);

        if($formGame->isSubmitted() && $formGame->isValid()) {
            $em-> persist($game);
            $em-> flush();
            
            return $this->redirectToRoute('game_index');
        }
        return $this->render('game/update.html.twig', ['formGame'=>$formGame]);
    }
      #[Route('/delete/{id}', name:'game_delete', methods:['POST'])]
    public function delete(Game $game, Request $request, EntityManagerInterface $em)
    {
         if ($game->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");}
        if($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $em-> remove($game);
            $em-> flush();
            
            return $this->redirectToRoute('game_index');
        }
       
    }


}
