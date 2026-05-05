<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Form\AttendanceFormType;
use App\Repository\AttendanceRepository;
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
}
