<?php

namespace App\Entity;

use App\Repository\AttendanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_attendance_training', columns: ['player_id', 'training_id'])]
#[ORM\UniqueConstraint(name: 'unique_attendance_game', columns: ['player_id', 'game_id'])]

class Attendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $start_date = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $end_date = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $justification_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $coach_observation = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?Training $training = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?User $coach = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeImmutable $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeImmutable $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getJustificationDate(): ?\DateTimeImmutable
    {
        return $this->justification_date;
    }

    public function setJustificationDate(?\DateTimeImmutable $justification_date): static
    {
        $this->justification_date = $justification_date;

        return $this;
    }

    public function getCoachObservation(): ?string
    {
        return $this->coach_observation;
    }

    public function setCoachObservation(?string $coach_observation): static
    {
        $this->coach_observation = $coach_observation;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }
}
