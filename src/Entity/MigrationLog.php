<?php

namespace App\Entity;

use App\Repository\MigrationLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MigrationLogRepository::class)]
class MigrationLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(targetEntity: PostType::class, cascade: ['persist', 'remove'])]
    private $post_type;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $post_traiter;

    #[ORM\Column(type: 'datetime')]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostType(): ?PostType
    {
        return $this->post_type;
    }

    public function setPostType(PostType $post_type): self
    {
        $this->post_type = $post_type;

        return $this;
    }

    public function getPostTraiter(): ?int
    {
        return $this->post_traiter;
    }

    public function setPostTraiter(?int $post_traiter): self
    {
        $this->post_traiter = $post_traiter;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
