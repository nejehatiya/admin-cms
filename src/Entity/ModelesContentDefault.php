<?php

namespace App\Entity;

use App\Repository\ModelesContentDefaultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesContentDefaultRepository::class)]
class ModelesContentDefault
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content_default = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModelesPost $modele_id = null;

    #[ORM\Column(length: 255)]
    private ?string $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentDefault(): ?string
    {
        return $this->content_default;
    }

    public function setContentDefault(string $content_default): static
    {
        $this->content_default = $content_default;

        return $this;
    }

    public function getModeleId(): ?ModelesPost
    {
        return $this->modele_id;
    }

    public function setModeleId(?ModelesPost $modele_id): static
    {
        $this->modele_id = $modele_id;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }
}
