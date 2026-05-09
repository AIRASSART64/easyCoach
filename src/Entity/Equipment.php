<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $total_quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $alert_level = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'equipment')]
    private Collection $stocks;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    private ?User $coach = null;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTotalQuantity(): ?int
    {
        return $this->total_quantity;
    }

    public function setTotalQuantity(?int $total_quantity): static
    {
        $this->total_quantity = $total_quantity;

        return $this;
    }

    public function getAlertLevel(): ?int
    {
        return $this->alert_level;
    }

    public function setAlertLevel(?int $alert_level): static
    {
        $this->alert_level = $alert_level;

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
            $stock->setEquipment($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getEquipment() === $this) {
                $stock->setEquipment(null);
            }
        }

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

    public function getCurrentStock(): int
{
    $total = $this->total_quantity ?? 0;
    foreach ($this->stocks as $stock) {
        $total += ($stock->getQuantity() * $stock->getType()->getSign());
    }
    return $total;
}
}
