<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SlotRepository::class)]
class Slot
{

    const days = [
        '1' => 'Lundi',
        '2' => 'Mardi',
        '3' => 'Mercredi',
        '4' => 'Jeudi',
        '5' => 'Vendredi',
        '6' => 'Samedi',
    ];
    const levels = [
        '0' => 'Tous niveaux',
        '1' => 'Débutant',
        '2' => 'Intermédiaire',
        '3' => 'Avancé'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ["default" => 1])]
    #[Assert\NotBlank]
    private int $dayId;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    private \DateTime $startAt;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    private \DateTime $endAt;

    #[ORM\Column(options: ["default" => 0])]
    #[Assert\NotBlank]
    private int $level = 0;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    private Location $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayId(): string
    {
        return self::days[$this->dayId];
    }

    public function setDayId(int $dayId): static
    {
        $this->dayId = $dayId;

        return $this;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): \DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getLevel(): string
    {
        return self::levels[$this->level];
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}