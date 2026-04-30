<?php

namespace App\Controller;

use App\Entity\TeamCategory;
use App\Form\TeamCategoryType;
use App\Repository\TeamCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team_category')]
final class TeamCategoryController extends AbstractController
{
    #[Route('/', name: 'team_category_index', methods: ['GET'])]
    public function index(TeamCategoryRepository $teamCategoryRepository): Response
    {
        return $this->render('team_category/index.html.twig', [
            'team_categories' => $teamCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'team_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teamCategory = new TeamCategory();
        $form = $this->createForm(TeamCategoryType::class, $teamCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teamCategory);
            $entityManager->flush();

            return $this->redirectToRoute('team_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team_category/new.html.twig', [
            'team_category' => $teamCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'team_category_show', methods: ['GET'])]
    public function show(TeamCategory $teamCategory): Response
    {
        return $this->render('team_category/show.html.twig', [
            'team_category' => $teamCategory,
        ]);
    }

    #[Route('/update/{id}', name: 'team_category_update', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeamCategory $teamCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamCategoryType::class, $teamCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('team_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team_category/update.html.twig', [
            'team_category' => $teamCategory,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'team_category_delete', methods: ['POST'])]
    public function delete(Request $request, TeamCategory $teamCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teamCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teamCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('team_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
