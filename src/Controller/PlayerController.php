<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerFormType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player')]
final class PlayerController extends AbstractController
{
    #[Route('/', name: 'player_index', methods:['GET'])]
    public function index(PlayerRepository $playerRepository): Response
    {
        $allPlayers = $playerRepository->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $allPlayers,
        ]);
    }
    #[Route('/new', name:'player_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newPlayer = new Player();

        $formPlayer = $this-> createForm(PlayerFormType::class, $newPlayer);
        $formPlayer->handleRequest($request);

        if($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            $em-> persist($newPlayer);
            $em-> flush();
            
            return $this->redirectToRoute('player_index');
        }
        return $this->render('player/new.html.twig', ['formPlayer'=>$formPlayer]);
    }

    #[Route('/show/{id}', name:'player_show', methods:['GET'])]
    public function show(Player $player)
    {
     return $this->render('player/show.html.twig', ['player'=>$player]);

    }
    
    #[Route('/update/{id}', name:'player_update', methods:['GET', 'POST'])]
    public function update(Player $player, Request $request, EntityManagerInterface $em ) 
    {
        $formPlayer = $this-> createForm(PlayerFormType::class, $player);
        $formPlayer->handleRequest($request);

        if($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            $em-> persist($player);
            $em-> flush();
            
            return $this->redirectToRoute('player_index');
        }
        return $this->render('player/new.html.twig', ['formPlayer'=>$formPlayer]);
        
    }
    #[Route('/delete/{id}', name:'player_delete', methods:['POST'])]
    public function delete(Player $player, Request $request, EntityManagerInterface $em) 
    {
      if($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
        $em->remove($player);
        $em->flush();

        return $this->redirectToRoute('player_index');
      }
    }


}






