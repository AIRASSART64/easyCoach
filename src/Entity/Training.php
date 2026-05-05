<?php

namespace App\Entity;

use App\Enum\TrainingTheme;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $start_time = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $end_time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?TrainingTheme $theme = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $coach_observation = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    private ?User $coach = null;

    /**
     * @var Collection<int, Attendance>
     */
    #[ORM\OneToMany(targetEntity: Attendance::class, mappedBy: 'training')]
    private Collection $attendances;

    public function __construct()
    {
        $this->attendances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeImmutable $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->end_time;
    }

    public function setEndTime(?\DateTimeImmutable $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getTheme(): ?TrainingTheme
    {
        return $this->theme;
    }

    public function setTheme(?TrainingTheme $theme): static
    {
        $this->theme = $theme;

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

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

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
            $attendance->setTraining($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getTraining() === $this) {
                $attendance->setTraining(null);
            }
        }

        return $this;
    }
}
