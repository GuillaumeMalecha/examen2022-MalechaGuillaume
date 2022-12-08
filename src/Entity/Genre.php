<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Chanson::class, mappedBy="genre")
     */
    private $chansons;

    public function __construct()
    {
        $this->chansons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Chanson>
     */
    public function getChansons(): Collection
    {
        return $this->chansons;
    }

    public function addChanson(Chanson $chanson): self
    {
        if (!$this->chansons->contains($chanson)) {
            $this->chansons[] = $chanson;
            $chanson->setGenre($this);
        }

        return $this;
    }

    public function removeChanson(Chanson $chanson): self
    {
        if ($this->chansons->removeElement($chanson)) {
            // set the owning side to null (unless already changed)
            if ($chanson->getGenre() === $this) {
                $chanson->setGenre(null);
            }
        }

        return $this;
    }
}
