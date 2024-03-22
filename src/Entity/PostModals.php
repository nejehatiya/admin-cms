<?php

namespace App\Entity;

use App\Repository\PostModalsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostModalsRepository::class)]
class PostModals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'postModals')]
    private $post;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: ModelesPost::class, inversedBy: 'postModals')]
    private $modele;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content_modele_post;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fields = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getModele(): ?ModelesPost
    {
        return $this->modele;
    }

    public function setModele(?ModelesPost $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getContentModelePost(): ?string
    {
        return $this->content_modele_post;
    }

    public function setContentModelePost(?string $content_modele_post): self
    {
        $this->content_modele_post = $content_modele_post;

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }

    public function setFields(?string $fields): static
    {
        $this->fields = $fields;

        return $this;
    }
}
