<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    const NIVEAU = [
        'PRO A' => 'PRO A',
        'PRO B' => 'PRO B',
        'N1' => 'N1',
        'N2' => 'N2',
        'N3' => 'N3',
        'PN' => 'PN',
        'R1' => 'R1',
        'R2' => 'R2',
        'R3' => 'R3',
        'R4' => 'R4',
        'D1' => 'D1',
        'D2' => 'D2',
        'D3' => 'D3',
    ];

    const DEFAULT_PRIORITY = [
        'PRO A' => 100,
        'PRO B' => 90,
        'N1' => 80,
        'N2' => 70,
        'N3' => 60,
        'PN' => 50,
        'R1' => 40,
        'R2' => 30,
        'R3' => 20,
        'R4' => 15,
        'D1' => 10,
        'D2' => 5,
        'D3' => 0,
    ];

    const MAXJOUEUR = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $niveau = null;

    #[ORM\Column]
    private ?int $priorite = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Joueur::class)]
    private Collection $joueurs;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $numero = null;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getPriorite(): int
    {
        return $this->priorite;
    }

    public function setPriorite(int $priorite): static
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
            $joueur->setEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getEquipe() === $this) {
                $joueur->setEquipe(null);
            }
        }

        return $this;
    }

    public function getMaxJoueur(): int
    {
        return Equipe::MAXJOUEUR;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }
}
