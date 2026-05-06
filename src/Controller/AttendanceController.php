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
        $allAttendances = $attendanceRepository->findAll();
        return $this->render('attendance/index.html.twig', [
            'attendances' => $allAttendances
        ]);
    }
    
    #[Route('/new', name: 'attendance_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newAttendance = new Attendance();
        $formAttendance = $this->createForm(AttendanceFormType::class , $newAttendance);
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
      
        $formAttendance = $this->createForm(AttendanceFormType::class , $attendance);
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

    // 2. Récupération des joueurs
    $players = $playerRepo->findAll();

    // 3. Logique pour les Statuts : On force "Présent" en première position
    $allStatuses = $statusRepo->findAll();
    $orderedStatuses = [];
    $defaultStatusId = null;

    foreach ($allStatuses as $s) {
        if ($s->getName() === 'Présent') {
            array_unshift($orderedStatuses, $s); // On le met tout en haut de la liste
            $defaultStatusId = $s->getId();
        } else {
            $orderedStatuses[] = $s; // On ajoute les autres à la suite
        }
    }

    // 4. Indexation des présences existantes pour pré-remplir le formulaire
    $existingAttendances = $attRepo->findBy([$type => $event]);
    $indexedAttendances = [];

    foreach ($existingAttendances as $att) {
        $indexedAttendances[$att->getPlayer()->getId()] = [
            'statusId' => $att->getStatus() ? $att->getStatus()->getId() : null,
            'observation' => $att->getCoachObservation()
        ];
    }

    // 5. Traitement du formulaire lors de la soumission (POST)
    if ($request->isMethod('POST')) {
        $attendanceData = $request->request->all('status'); // On utilise request->all() plutôt que getPayload() pour la compatibilité
        $observations = $request->request->all('observation');

        foreach ($players as $player) {
            $statusId = $attendanceData[$player->getId()] ?? null;

            if ($statusId) {
                $criteria = ['player' => $player];
                $criteria[$type] = $event; 

                // On cherche l'existant ou on crée une nouvelle entité
                $attendance = $attRepo->findOneBy($criteria) ?? new Attendance();

                $attendance->setPlayer($player);
                
                if ($type === 'training') {
                    $attendance->setTraining($event);
                } else {
                    $attendance->setGame($event);
                }

                // On récupère une référence au statut pour ne pas refaire une requête SQL inutile
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

    // 6. Rendu du template
    return $this->render('attendance/take.html.twig', [
        'event' => $event,
        'type' => $type,
        'players' => $players,
        'statuses' => $orderedStatuses, // Liste triée avec "Présent" en premier
        'existingAttendances' => $indexedAttendances,
        'defaultStatusId' => $defaultStatusId // Utile pour la logique Twig
    ]);
}

}