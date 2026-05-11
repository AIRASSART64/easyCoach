<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentFormType;
use App\Repository\EquipmentRepository;
use App\Service\StockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/equipment')]
final class EquipmentController extends AbstractController
{
    #[Route('/', name: 'equipment_index', methods:['GET'])]
    public function index(EquipmentRepository $equipmentRepository , StockService $stockService): Response
    {
        $equipments = $equipmentRepository->findBy( [
            'coach'=>$this->getUser()
        ]);
        $equipmentData = [];

        foreach ($equipments as $equipment) {
        $equipmentData[] = [
            'equipment' => $equipment,
            'currentStock' => $stockService->calculateCurrentStock($equipment),
            'isLow' => $stockService->isStockLow($equipment)
        ];
    }
        
        return $this->render('equipment/index.html.twig', [
            'equipments' => $equipmentData
        ]);
    }
      #[Route('/new', name:'equipment_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newEquipment = new Equipment();
        $newEquipment->setCoach($this->getUser());

        $formEquipment = $this-> createForm(EquipmentFormType::class, $newEquipment, [
            'coach'=>$this->getUser()
        ]);
        $formEquipment->handleRequest($request);

        if($formEquipment->isSubmitted() && $formEquipment->isValid()) {
            $em-> persist($newEquipment);
            $em-> flush();
            
            return $this->redirectToRoute('equipment_index');
        }
        return $this->render('equipment/new.html.twig', ['formEquipment'=>$formEquipment]);
    }
      #[Route('/show/{id}', name:'equipment_show', methods:['GET'])]
    public function show(Equipment $equipment)
    {
        if ($equipment->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");}
     return $this->render('equipment/show.html.twig', ['equipment'=>$equipment]);

    }
        #[Route('/update/{id}', name:'equipment_update', methods:['GET', 'POST'])]
    public function update(Equipment $equipment, Request $request, EntityManagerInterface $em ) 
    {
         if ($equipment->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
        $formEquipment = $this-> createForm(EquipmentFormType::class, $equipment, [
            'coach'=>$this->getUser()
        ]);
        $formEquipment->handleRequest($request);

        if($formEquipment->isSubmitted() && $formEquipment->isValid()) {
            $em-> persist($equipment);
            $em-> flush();
            
            return $this->redirectToRoute('equipment_index');
        }
        return $this->render('equipment/new.html.twig', ['formEquipment'=>$formEquipment]);
        
    }
     #[Route('/delete/{id}', name:'equipment_delete', methods:['POST'])]
    public function delete(Equipment $equipment, Request $request, EntityManagerInterface $em) 
    {
        if ($equipment->getCoach() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas accés à ces informations");
    }
        
      if($this->isCsrfTokenValid('delete' . $equipment->getId(), $request->request->get('_token'))) {
        $em->remove($equipment);
        $em->flush();

        return $this->redirectToRoute('equipment_index');
      }
    }
}
