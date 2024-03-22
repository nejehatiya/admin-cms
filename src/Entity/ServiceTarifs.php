<?php

namespace App\Entity;

use App\Repository\ServiceTarifsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceTarifsRepository::class)]
class ServiceTarifs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Titre;

    #[ORM\Column(type: 'text', nullable: true)]
    private $Description;

    #[ORM\Column(type: 'boolean')]
    private $Status;

    #[ORM\Column(type: 'datetime')]
    private $Date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_up;

    #[ORM\Column(type: 'text', nullable: true)]
    private $ContentTarifs;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->Date_add;
    }

    public function setDateAdd(\DateTimeInterface $Date_add): self
    {
        $this->Date_add = $Date_add;

        return $this;
    }

    public function getDateUp(): ?\DateTimeInterface
    {
        return $this->date_up;
    }

    public function setDateUp(\DateTimeInterface $date_up): self
    {
        $this->date_up = $date_up;

        return $this;
    }

    public function getContentTarifs(): ?string
    {
        return $this->ContentTarifs;
    }

    public function setContentTarifs(?string $ContentTarifs): self
    {
        $this->ContentTarifs = $ContentTarifs;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    
}
