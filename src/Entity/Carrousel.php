<?php

namespace App\Entity;

use App\Repository\CarrouselRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarrouselRepository::class)]
class Carrousel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $title_carrousel;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description_carrousel;

    #[ORM\Column(type: 'text', nullable: true)]
    private $button_carrousel;

    #[ORM\Column(type: 'text', nullable: true)]
    private $image_carrousel;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $shortcode_carrousel;

    #[ORM\OneToOne(targetEntity: CategoryCarrousel::class, inversedBy: 'carrousel', cascade: ['persist', 'remove'])]
    private $category;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleCarrousel(): ?string
    {
        return $this->title_carrousel;
    }

    public function setTitleCarrousel(?string $title_carrousel): self
    {
        $this->title_carrousel = $title_carrousel;

        return $this;
    }

    public function getDescriptionCarrousel(): ?string
    {
        return $this->description_carrousel;
    }

    public function setDescriptionCarrousel(?string $description_carrousel): self
    {
        $this->description_carrousel = $description_carrousel;

        return $this;
    }

    public function getButtonCarrousel(): ?string
    {
        return $this->button_carrousel;
    }

    public function setButtonCarrousel(?string $button_carrousel): self
    {
        $this->button_carrousel = $button_carrousel;

        return $this;
    }

    public function getImageCarrousel(): ?string
    {
        return $this->image_carrousel;
    }

    public function setImageCarrousel(?string $image_carrousel): self
    {
        $this->image_carrousel = $image_carrousel;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

   public function getShortcodeCarrousel(): ?string
    {
        return $this->shortcode_carrousel;
    }

    public function setShortcodeCarrousel(?string $shortcode_carrousel): self
    {
        $this->shortcode_carrousel = $shortcode_carrousel;

        return $this;
    }

    public function getCategory(): ?CategoryCarrousel
    {
        return $this->category;
    }

    public function setCategory(?CategoryCarrousel $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

   
}
