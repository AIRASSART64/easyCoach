<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'coach')]
    private Collection $players;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'coach', orphanRemoval: true)]
    private Collection $teams;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'coach')]
    private Collection $games;

    /**
     * @var Collection<int, Training>
     */
    #[ORM\OneToMany(targetEntity: Training::class, mappedBy: 'coach')]
    private Collection $trainings;

    /**
     * @var Collection<int, Attendance>
     */
    #[ORM\OneToMany(targetEntity: Attendance::class, mappedBy: 'coach')]
    private Collection $attendances;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'coach')]
    private Collection $stocks;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\OneToMany(targetEntity: Equipment::class, mappedBy: 'coach')]
    private Collection $equipment;

    /**
     * @var Collection<int, TeamCategory>
     */
    #[ORM\OneToMany(targetEntity: TeamCategory::class, mappedBy: 'coach')]
    private Collection $teamCategories;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->attendances = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->equipment = new ArrayCollection();
        $this->teamCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setCoach($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getCoach() === $this) {
                $player->setCoach(null);
            }
        }

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
            $team->setCoach($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getCoach() === $this) {
                $team->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setCoach($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getCoach() === $this) {
                $game->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setCoach($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getCoach() === $this) {
                $training->setCoach(null);
            }
        }

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
            $attendance->setCoach($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getCoach() === $this) {
                $attendance->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setCoach($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getCoach() === $this) {
                $stock->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
            $equipment->setCoach($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getCoach() === $this) {
                $equipment->setCoach(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamCategory>
     */
    public function getTeamCategories(): Collection
    {
        return $this->teamCategories;
    }

    public function addTeamCategory(TeamCategory $teamCategory): static
    {
        if (!$this->teamCategories->contains($teamCategory)) {
            $this->teamCategories->add($teamCategory);
            $teamCategory->setCoach($this);
        }

        return $this;
    }

    public function removeTeamCategory(TeamCategory $teamCategory): static
    {
        if ($this->teamCategories->removeElement($teamCategory)) {
            // set the owning side to null (unless already changed)
            if ($teamCategory->getCoach() === $this) {
                $teamCategory->setCoach(null);
            }
        }

        return $this;
    }
}
