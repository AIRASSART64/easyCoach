<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Entity\Status;
use App\Form\AttendanceFormType;
use App\Repository\AttendanceRepository;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\StatusRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/attendance')]
final class AttendanceController extends AbstractController
{
   #[Route('/', name: 'attendance_index', methods:['GET'])]
    public function index(AttendanceRepository $attendanceRepository): Response
    {
        $allAttendances = $attendanceRepository->findAllByCoach($this->getUser());
        return $this->render('attendance/index.html.twig', [
            'attendances' => $allAttendances
        ]);
    }
    
    #[Route('/new', name: 'attendance_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {   
        $newAttendance = new Attendance();
        $formAttendance = $this->createForm(AttendanceFormType::class , $newAttendance, [
    'coach' => $this->getUser(),
]);
        $formAttendance->handleRequest($request);

        if($formAttendance->isSubmitted() && $formAttendance->isValid()) {
            $em->persist($newAttendance);
            $em->flush();

            return $this->redirectToRoute('attendance_index');

        }
        return $this->render('attendance/new.html.twig', [
            'formAttendance' => $formAttendance
        ]);
    }
    #[Route('/show/{id}', name: 'attendance_show', methods:['GET'])]
    public function show( Attendance $attendance)
    {
        return $this->render('attendance/show.html.twig', [
            'attendance' => $attendance
        ]);
    }
      #[Route('/update/{id}', name: 'attendance_update', methods:['GET', 'POST'])]
    public function update(Attendance $attendance, Request $request, EntityManagerInterface $em)
    {
        if ($attendance->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }
        $formAttendance = $this->createForm(AttendanceFormType::class , $attendance, [
        'coach' => $this->getUser(),
    ]);
        $formAttendance->handleRequest($request);

        if($formAttendance->isSubmitted() && $formAttendance->isValid()) {
            $em->persist($attendance);
            $em->flush();

            return $this->redirectToRoute('attendace_index');

        }
        return $this->render('attendance/update.html.twig', [
            'formAttendance' => $formAttendance, 
            'attendance' => $attendance
        ]);
    }
      #[Route('/delete/{id}', name: 'attendance_delete', methods:['POST'])]
    public function delete(Attendance $attendance, Request $request, EntityManagerInterface $em)
    {
        if ($attendance->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
         }
        if($this->isCsrfTokenValid('delete' . $attendance->getId(), $request->request->get('_token'))){
        $em->remove($attendance);
        $em->flush();

      }
         return $this->redirectToRoute('attendance/index.html.twig');
    }

    #[Route('/take/attendance/{type}/{id}', name: 'attendance_take_mass')]
    public function takeMassAttendance(string $type, int $id,
    PlayerRepository $playerRepo,
    AttendanceRepository $attRepo,
    StatusRepository $statusRepo,
    TrainingRepository $trainingRepo,
    GameRepository $gameRepo,
    Request $request,
    EntityManagerInterface $em
    ): Response 
    {
    // 1. Récupération de l'événement (Entraînement ou Match)
    $event = ($type === 'training') ? $trainingRepo->find($id) : $gameRepo->find($id);

    if (!$event) {
        throw $this->createNotFoundException("L'événement n'existe pas.");
    }

    $players = $playerRepo->findBy(['coach' => $this->getUser()]);

  
    $allStatuses = $statusRepo->findAll();
    $orderedStatuses = [];
    $defaultStatusId = null;

    foreach ($allStatuses as $s) {
        if ($s->getName() === 'Présent') {
            array_unshift($orderedStatuses, $s); 
            $defaultStatusId = $s->getId();
        } else {
            $orderedStatuses[] = $s; 
        }
    }

    
    $existingAttendances = $attRepo->findBy([$type => $event]);
    $indexedAttendances = [];

    foreach ($existingAttendances as $att) {
        $indexedAttendances[$att->getPlayer()->getId()] = [
            'statusId' => $att->getStatus() ? $att->getStatus()->getId() : null,
            'observation' => $att->getCoachObservation()
        ];
    }


    if ($request->isMethod('POST')) {
        $attendanceData = $request->request->all('status'); 
        $observations = $request->request->all('observation');

        foreach ($players as $player) {
            $statusId = $attendanceData[$player->getId()] ?? null;

            if ($statusId) {
                $criteria = ['player' => $player];
                $criteria[$type] = $event; 

                $attendance = $attRepo->findOneBy($criteria) ?? new Attendance();

                $attendance->setPlayer($player);
                
                if ($type === 'training') {
                    $attendance->setTraining($event);
                } else {
                    $attendance->setGame($event);
                }

                
                $attendance->setStatus($em->getReference(Status::class, $statusId));
                $attendance->setCoach($this->getUser());
                $attendance->setCoachObservation($observations[$player->getId()] ?? null);

                $em->persist($attendance);
            }
        }
        $em->flush();
        $this->addFlash('success', 'La feuille de présence a été mise à jour.');
        
        
        return $this->redirectToRoute($type . '_index');
    }


    return $this->render('attendance/take.html.twig', [
        'event' => $event,
        'type' => $type,
        'players' => $players,
        'statuses' => $orderedStatuses, 
        'existingAttendances' => $indexedAttendances,
        'defaultStatusId' => $defaultStatusId 
    ]);
}

}