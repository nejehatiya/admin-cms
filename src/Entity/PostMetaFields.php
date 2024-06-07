<?php

namespace App\Entity;

use App\Repository\PostMetaFieldsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: PostMetaFieldsRepository::class)]
class PostMetaFields
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_api'])]
    private ?string $name = null;
    #[Groups(['show_api'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $fields = null;
    #[Groups(['show_api'])]
    #[ORM\ManyToMany(targetEntity: PostType::class, inversedBy: 'postMetaFields')]
    private Collection $post_type;

    #[ORM\Column]
    #[Groups(['show_api'])]
    private ?bool $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['show_api'])]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->post_type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }

    public function setFields(string $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return Collection<int, PostType>
     */
    public function getPostType(): Collection
    {
        return $this->post_type;
    }

    public function addPostType(PostType $postType): static
    {
        if (!$this->post_type->contains($postType)) {
            $this->post_type->add($postType);
        }

        return $this;
    }

    public function removePostType(PostType $postType): static
    {
        $this->post_type->removeElement($postType);

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
