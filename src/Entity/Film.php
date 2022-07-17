<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
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
    private $titreFilm;

    /**
     * @ORM\Column(type="datetime")
     */
    private $anneeSortie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dureeMin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $affiche;

    /**
     * @ORM\ManyToOne(targetEntity=Realisateur::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="films")
     */
    private $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreFilm(): ?string
    {
        return $this->titreFilm;
    }

    public function setTitreFilm(string $titreFilm): self
    {
        $this->titreFilm = $titreFilm;

        return $this;
    }

    public function getAnneeSortie(): ?\DateTimeInterface
    {
        return $this->anneeSortie;
    }

    public function setAnneeSortie(\DateTimeInterface $anneeSortie): self
    {
        $this->anneeSortie = $anneeSortie;

        return $this;
    }

    public function getDureeMin(): ?int
    {
        return $this->dureeMin;
    }

    public function setDureeMin(?int $dureeMin): self
    {
        $this->dureeMin = $dureeMin;

        return $this;
    }

    public function getHours()
    {
        $minutes= $this->dureeMin;
        $hours = intdiv($minutes, 60).' Heures : '. ($minutes % 60)." Minutes ";
        return $hours;
    }
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(?string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    public function getRealisateur(): ?Realisateur
    {
        return $this->realisateur;
    }

    public function setRealisateur(?Realisateur $realisateur): self
    {
        $this->realisateur = $realisateur;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function showGenres()
    {
        foreach ($this->genres as $genre) {
            return $genre;
        }
    }

    public function showStars()
    {
    for ($i=0,0; $i < $this->note ; $i++) { 
        echo "<i class='fa-solid fa-star'></i>";
    }

    }
    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }
    public function __toString()
    {
        return $this->titreFilm;
    }
}
