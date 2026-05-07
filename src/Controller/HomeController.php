<?php

namespace App\Controller;

use App\Repository\AttendanceRepository;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home')]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index', methods:['GET'])]
    public function index(TrainingRepository $trainingRepo, GameRepository $gameRepo, PlayerRepository $playerRepo, AttendanceRepository $attendanceRepo): Response
{
    $now = new \DateTime();
    $user = $this->getUser();

    if (!$user) {
        return $this->render('home/index.html.twig');
    }

    return $this->render('home/index.html.twig', [
        'nextTraining' => $trainingRepo->findNextTraining($now, $user),
        'nextGame'     => $gameRepo->findNextGame($now, $user),
        'countPlayers'   => $playerRepo->countAllPlayers($user),
        'attendanceRate' => $attendanceRepo->getGlobalAttendanceRate($user),
        'injuredCount'   => $attendanceRepo->countInjuredPlayers($user),
        'countGames'     => $gameRepo->countAllGames($user),
    ]);
}
}
