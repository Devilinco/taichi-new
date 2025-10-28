<?php

namespace App\Controller;

use App\Repository\ActualityRepository;
use App\Repository\PriceRepository;
use App\Repository\SlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SlotRepository $slotRepository, PriceRepository $priceRepository): Response
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'current_menu' => 'home',
            'jours' => $jours,
            'slots' => $slotRepository->findBy([], ['dayId' => 'ASC', 'startAt' => 'ASC']),
            'tarifs' => $priceRepository->findAll(),
        ]);
    }

    #[Route('/actuality', name: 'actuality')]
    public function actuality(ActualityRepository $actualityRepository)
    {
        $actus = $actualityRepository->findAll();
        return $this->render('home/actuality.html.twig', [
            'controller_name' => 'HomeController',
            'current_menu' => 'actuality',
            'actualites' => array_reverse($actus),
        ]);
    }

    #[Route('/document', name: 'document')]
    public function document()
    {
        return $this->render('home/document.html.twig', [
            'controller_name' => 'HomeController',
            'current_menu' => 'document',
        ]);
    }

    #[Route('/mention', name: 'mention')]
    public function mention()
    {
        return $this->render('home/mention.html.twig', [
            'controller_name' => 'HomeController',
            'current_menu' => 'mention',
        ]);
    }
}