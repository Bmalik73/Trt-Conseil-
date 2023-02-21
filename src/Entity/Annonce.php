<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Candidature::class)]
    private Collection $postulation;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $owner = null;

    public function __construct()
    {
        $this->postulation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

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

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getPostulation(): Collection
    {
        return $this->postulation;
    }

    public function addPostulation(Candidature $postulation): self
    {
        if (!$this->postulation->contains($postulation)) {
            $this->postulation->add($postulation);
            $postulation->setAnnonce($this);
        }

        return $this;
    }

    public function removePostulation(Candidature $postulation): self
    {
        if ($this->postulation->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getAnnonce() === $this) {
                $postulation->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
    
}
