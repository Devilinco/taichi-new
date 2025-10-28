<?php

namespace App\Controller;

use App\Entity\Price;
use App\Form\PriceType;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/price')]
final class PriceController extends AbstractController
{
    #[Route(name: 'price_index', methods: ['GET'])]
    public function index(PriceRepository $priceRepository): Response
    {
        return $this->render('price/index.html.twig', [
            'prices' => $priceRepository->findBy([], ['price' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'price_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $price = new Price();
        $form = $this->createForm(PriceType::class, $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($price);
            $entityManager->flush();

            return $this->redirectToRoute('price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('price/new.html.twig', [
            'price' => $price,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'price_show', methods: ['GET'])]
    public function show(Price $price): Response
    {
        return $this->render('price/show.html.twig', [
            'price' => $price,
        ]);
    }

    #[Route('/{id}/edit', name: 'price_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Price $price, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PriceType::class, $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('price/edit.html.twig', [
            'price' => $price,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'price_delete', methods: ['POST'])]
    public function delete(Request $request, Price $price, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $price->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($price);
            $entityManager->flush();
        }

        return $this->redirectToRoute('price_index', [], Response::HTTP_SEE_OTHER);
    }
}