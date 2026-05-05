<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date_immutable', nullable:true)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $opposing_team = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_home = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_away = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?User $coach = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $coach_observation = null;

    /**
     * @var Collection<int, Attendance>
     */
    #[ORM\OneToMany(targetEntity: Attendance::class, mappedBy: 'game')]
    private Collection $attendances;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getOpposingTeam(): ?string
    {
        return $this->opposing_team;
    }

    public function setOpposingTeam(?string $opposing_team): static
    {
        $this->opposing_team = $opposing_team;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getScoreHome(): ?int
    {
        return $this->score_home;
    }

    public function setScoreHome(?int $score_home): static
    {
        $this->score_home = $score_home;

        return $this;
    }

    public function getScoreAway(): ?int
    {
        return $this->score_away;
    }

    public function setScoreAway(?int $score_away): static
    {
        $this->score_away = $score_away;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

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

    public function getCoachObservation(): ?string
    {
        return $this->coach_observation;
    }

    public function setCoachObservation(?string $coach_observation): static
    {
        $this->coach_observation = $coach_observation;

        return $this;
    }
    /**
     * @return Collection<int, Attendance>
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function addAttendance(Attendance $attendance): static
    {
        if (!$this->attendances->contains($attendance)) {
            $this->attendances->add($attendance);
            $attendance->setGame($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getGame() === $this) {
                $attendance->setGame(null);
            }
        }

        return $this;
    }
}
