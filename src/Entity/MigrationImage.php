<?php

namespace App\Entity;

use App\Repository\MigrationImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MigrationImageRepository::class)]
class MigrationImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $url_images;

    #[ORM\Column(type: 'datetime')]
    private $date_insert;

    #[ORM\Column(type: 'boolean')]
    private $uploaded;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $id_image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlImages(): ?string
    {
        return $this->url_images;
    }

    public function setUrlImages(string $url_images): self
    {
        $this->url_images = $url_images;

        return $this;
    }

    public function getDateInsert(): ?\DateTimeInterface
    {
        return $this->date_insert;
    }

    public function setDateInsert(\DateTimeInterface $date_insert): self
    {
        $this->date_insert = $date_insert;

        return $this;
    }

    public function getUploaded(): ?bool
    {
        return $this->uploaded;
    }

    public function setUploaded(bool $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    public function getIdImage(): ?int
    {
        return $this->id_image;
    }

    public function setIdImage(?int $id_image): self
    {
        $this->id_image = $id_image;

        return $this;
    }

    public function isUploaded(): ?bool
    {
        return $this->uploaded;
    }
}
