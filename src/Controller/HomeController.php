<?php

namespace App\Controller;

use App\Repository\AttendanceRepository;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TrainingRepository;
use App\Service\TrainingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home')]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods:['GET'])]
    public function index(TrainingRepository $trainingRepo, GameRepository $gameRepo, PlayerRepository $playerRepo, AttendanceRepository $attendanceRepo , TrainingService $trainingService): Response
{
    $now = new \DateTime();
    $user = $this->getUser();

    if (!$user) {
        return $this->render('home/index.html.twig');
    }
  
    $nextTraining = $trainingRepo->findNextTraining($now, $user);
    
  
    $trainingDuration = null;
    if ($nextTraining) {
        $trainingDuration = $trainingService->getDurationFormatted($nextTraining);
    }

    return $this->render('home/index.html.twig', [
        'nextTraining' => $nextTraining,
        'trainingDuration' => $trainingDuration,
        'nextGame'     => $gameRepo->findNextGame($now, $user),
        'countPlayers'   => $playerRepo->countAllPlayers($user),
        'attendanceRate' => $attendanceRepo->getGlobalAttendanceRate($user),
        'injuredCount'   => $attendanceRepo->countInjuredPlayers($user),
        'countGames'     => $gameRepo->countAllGames($user),
    ]);
}
}
