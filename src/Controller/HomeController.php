<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home')]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods:['GET'])]
    public function index(TrainingRepository $trainingRepo, GameRepository $gameRepo): Response
    {
        $now = new \DateTime();

    $nextTraining = $trainingRepo->createQueryBuilder('t')
        ->where('t.date >= :now')
        ->setParameter('now', $now)
        ->orderBy('t.date', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

  
    $nextGame = $gameRepo->createQueryBuilder('g')
        ->where('g.date >= :now')
        ->setParameter('now', $now)
        ->orderBy('g.date', 'ASC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

    return $this->render('home/index.html.twig', [
        'nextTraining' => $nextTraining,
        'nextGame' => $nextGame,
    ]);
    }
}
