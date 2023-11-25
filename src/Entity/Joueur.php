<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    public const TYPELICENCE = [
        'Loisir' => 'Loisir',
        'Compétition' => 'Compétition',
        'Evénementiel' => 'Evénementiel',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroLicence = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'joueurs')]
    private ?Equipe $equipe = null;

    #[ORM\Column(options: ['default' => 500])]
    private ?int $points = null;

    #[ORM\Column(length: 255, options: ['default' => self::TYPELICENCE['Loisir']])]
    private ?string $typeLicence = null;

    #[ORM\ManyToMany(targetEntity: Rencontre::class, mappedBy: 'joueurs')]
    private Collection $rencontres;

    public function __construct()
    {
        $this->rencontres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroLicence(): ?string
    {
        return $this->numeroLicence;
    }

    public function setNumeroLicence(string $numeroLicence): static
    {
        $this->numeroLicence = $numeroLicence;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom().' '.$this->getPrenom().' - '.$this->getPoints();
    }

    public function getTypeLicence(): ?string
    {
        return $this->typeLicence;
    }

    public function setTypeLicence(string $typeLicence): static
    {
        $this->typeLicence = $typeLicence;

        return $this;
    }

    /**
     * @return Collection<int, Rencontre>
     */
    public function getRencontres(): Collection
    {
        return $this->rencontres;
    }

    public function addRencontre(Rencontre $rencontre): static
    {
        if (!$this->rencontres->contains($rencontre)) {
            $this->rencontres->add($rencontre);
            $rencontre->addJoueur($this);
        }

        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): static
    {
        if ($this->rencontres->removeElement($rencontre)) {
            $rencontre->removeJoueur($this);
        }

        return $this;
    }
}
