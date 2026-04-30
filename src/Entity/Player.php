<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PlayerPosition;


#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $birth_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $responsable_phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $responsable_email = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?User $coach = null;

   #[ORM\Column(type: 'string', enumType: PlayerPosition::class, nullable: true)]
    private ?PlayerPosition $position = null;

   /**
    * @var Collection<int, Team>
    */
   #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: 'player')]
   private Collection $teams;

   public function __construct()
   {
       $this->teams = new ArrayCollection();
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeImmutable $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getResponsablePhone(): ?string
    {
        return $this->responsable_phone;
    }

    public function setResponsablePhone(?string $responsable_phone): static
    {
        $this->responsable_phone = $responsable_phone;

        return $this;
    }

    public function getResponsableEmail(): ?string
    {
        return $this->responsable_email;
    }

    public function setResponsableEmail(?string $responsable_email): static
    {
        $this->responsable_email = $responsable_email;

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

    public function getPosition(): ?PlayerPosition
    {
        return $this->position;
    }

    public function setPosition(?PlayerPosition $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addPlayer($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removePlayer($this);
        }

        return $this;
    }
}
