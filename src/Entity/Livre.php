<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", length=255, nullable=true)
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="livre")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="livres")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSortie(): ?string
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?string $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
