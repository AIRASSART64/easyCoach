<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockFormType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stock')]
final class StockController extends AbstractController
{
    #[Route('/', name: 'stock_index' , methods:['GET'])]
    public function index(StockRepository $stockRepository): Response
    {
        $stocks = $stockRepository->findAll();
        return $this->render('stock/index.html.twig', [
            'stocks' => $stocks
        ]);
    }
     #[Route('/new', name:'stock_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newStock = new Stock();

        $formStock = $this-> createForm(StockFormType::class, $newStock);
        $formStock->handleRequest($request);

        if($formStock->isSubmitted() && $formStock->isValid()) {
            $em-> persist($newStock);
            $em-> flush();
            
            return $this->redirectToRoute('stock_index');
        }
        return $this->render('stock/new.html.twig', ['formStock'=>$formStock]);
    }
      #[Route('/show/{id}', name:'stock_show', methods:['GET'])]
    public function show(Stock $stock)
    {
     return $this->render('stock/show.html.twig', ['stock'=>$stock]);

}
  #[Route('/update/{id}', name:'stock_update', methods:['GET', 'POST'])]
    public function update( Stock $stock,  Request $request, EntityManagerInterface $em)
    {

        $formStock = $this-> createForm(StockFormType::class, $stock);
        $formStock->handleRequest($request);

        if($formStock->isSubmitted() && $formStock->isValid()) {
            $em-> persist($stock);
            $em-> flush();
            
            return $this->redirectToRoute('stock_index');
        }
        return $this->render('stock/update.html.twig', ['formStock'=>$formStock]);
    }
    
     #[Route('/delete/{id}', name:'stock_delete', methods:['POST'])]
    public function delete(Stock $stock, Request $request, EntityManagerInterface $em) 
    {
      if($this->isCsrfTokenValid('delete' . $stock->getId(), $request->request->get('_token'))) {
        $em->remove($stock);
        $em->flush();

        return $this->redirectToRoute('stock_index');
      }
    }
}
