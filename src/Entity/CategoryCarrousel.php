<?php

namespace App\Entity;

use App\Repository\CategoryCarrouselRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryCarrouselRepository::class)]
class CategoryCarrousel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_category;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug_category;

    #[ORM\OneToOne(targetEntity: Carrousel::class, mappedBy: 'category', cascade: ['persist', 'remove'])]
    private $carrousel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->name_category;
    }

    public function setNameCategory(string $name_category): self
    {
        $this->name_category = $name_category;

        return $this;
    }

    public function getSlugCategory(): ?string
    {
        return $this->slug_category;
    }

    public function setSlugCategory(string $slug_category): self
    {
        $this->slug_category = $slug_category;

        return $this;
    }

    public function getCarrousel(): ?Carrousel
    {
        return $this->carrousel;
    }

    public function setCarrousel(?Carrousel $carrousel): self
    {
        // unset the owning side of the relation if necessary
        if ($carrousel === null && $this->carrousel !== null) {
            $this->carrousel->setCategory(null);
        }

        // set the owning side of the relation if necessary
        if ($carrousel !== null && $carrousel->getCategory() !== $this) {
            $carrousel->setCategory($this);
        }

        $this->carrousel = $carrousel;

        return $this;
    }

    public function __toString() {
        return $this->name_category;
    }
}
