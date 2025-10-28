<?php

namespace App\Controller;

use App\Entity\Actuality;
use App\Form\ActualityType;
use App\Repository\ActualityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/actuality')]
final class ActualityController extends AbstractController
{
    #[Route(name: 'actuality_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request, ActualityRepository $actualityRepository): Response
    {
        $pagination = $paginator->paginate(
            $actualityRepository->findLatest(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('actuality/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'actuality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $actuality = new Actuality();
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($actuality);
            $entityManager->flush();

            return $this->redirectToRoute('actuality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actuality/new.html.twig', [
            'actuality' => $actuality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'actuality_show', methods: ['GET'])]
    public function show(Actuality $actuality): Response
    {
        return $this->render('actuality/show.html.twig', [
            'actuality' => $actuality,
        ]);
    }

    #[Route('/{id}/edit', name: 'actuality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actuality $actuality, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('actuality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actuality/edit.html.twig', [
            'actuality' => $actuality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'actuality_delete', methods: ['POST'])]
    public function delete(Request $request, Actuality $actuality, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $actuality->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($actuality);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actuality_index', [], Response::HTTP_SEE_OTHER);
    }
}