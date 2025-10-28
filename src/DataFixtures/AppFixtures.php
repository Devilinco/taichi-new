<?php

namespace App\DataFixtures;

use App\Entity\Actuality;
use App\Entity\Location;
use App\Entity\Price;
use App\Entity\Slot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Actualities ---
        for ($i = 1; $i <= 5; $i++) {
            $actuality = new Actuality();
            $actuality->setTitle("Actuality $i");
            $actuality->setDescription("Description détaillée de l'actualité numéro $i");
            $manager->persist($actuality);
        }

        // --- Prices ---
        $prices = [
            ['title' => 'tarif annuel Plaisir et Fontenay', 'price' => 380],
            ['title' => "l'adhésion à l'association", 'price' => 25],
            ['title' => 'paiement au cours', 'price' => 10],
            ['title' => 'mensualité', 'price' => 40],
            ['title' => 'tarif annuel Clayes-sous-Bois', 'price' => 315],
        ];

        foreach ($prices as $p) {
            $price = new Price();
            $price->setTitle($p['title'])
                ->setPrice($p['price']);
            $manager->persist($price);
        }

        // --- Locations ---
        for ($i = 1; $i <= 10; $i++) {
            $location = new Location();
            $location->setName("Location $i")
                ->setAddress("Adresse $i")
                ->setPostalCode(75000 + $i)
                ->setCity("Paris")
                ->setGoogleMapsLink("https://maps.google.com/location$i");

            $manager->persist($location);

            $locations[$i] = $location;
        }

        // Important : on flush ici pour enregistrer les Locations avant les Slots
        $manager->flush();

        // --- Slots ---
        $slotData = [
            ['location_id' => 5,  'day_id' => 1, 'start_at' => '19:30:00', 'end_at' => '21:30:00', 'level' => 0],
            ['location_id' => 1,  'day_id' => 2, 'start_at' => '11:00:00', 'end_at' => '13:00:00', 'level' => 0],
            ['location_id' => 6,  'day_id' => 3, 'start_at' => '10:00:00', 'end_at' => '11:30:00', 'level' => 0],
            ['location_id' => 4,  'day_id' => 3, 'start_at' => '17:30:00', 'end_at' => '19:00:00', 'level' => 0],
            ['location_id' => 3,  'day_id' => 4, 'start_at' => '10:00:00', 'end_at' => '11:30:00', 'level' => 0],
            ['location_id' => 10, 'day_id' => 4, 'start_at' => '19:00:00', 'end_at' => '20:30:00', 'level' => 0],
            ['location_id' => 10, 'day_id' => 5, 'start_at' => '10:00:00', 'end_at' => '11:30:00', 'level' => 0],
            ['location_id' => 7,  'day_id' => 1, 'start_at' => '10:00:00', 'end_at' => '11:30:00', 'level' => 0],
            ['location_id' => 10, 'day_id' => 3, 'start_at' => '20:00:00', 'end_at' => '22:00:00', 'level' => 0],
            ['location_id' => 7,  'day_id' => 6, 'start_at' => '11:15:00', 'end_at' => '13:15:00', 'level' => 0],
            ['location_id' => 9,  'day_id' => 2, 'start_at' => '20:00:00', 'end_at' => '22:30:00', 'level' => 0],
        ];

        foreach ($slotData as $data) {
            if (!isset($locations[$data['location_id']])) {
                continue;
            }

            $slot = new Slot();
            $slot->setLocation($location)
                ->setDayId($data['day_id'])
                ->setStartAt(new \DateTime($data['start_at']))
                ->setEndAt(new \DateTime($data['end_at']))
                ->setLevel($data['level']);

            $manager->persist($slot);
        }

        $manager->flush();
    }
}