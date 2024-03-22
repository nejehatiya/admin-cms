<?php

namespace App\Entity;

use App\Repository\PostMetaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostMetaRepository::class)]
class PostMeta
{
    /**
     * @Groups({"parentsPermalink", "template_page"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"template_page","data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $meta_key;

    /**
     * @Groups({"parentsPermalink", "template_page","data_front"})
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $meta_value;

    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'postMetas')]
    private $id_post;

    #[ORM\ManyToOne(targetEntity: Acf::class, inversedBy: 'id_acf')]
    private $acf;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetaKey(): ?string
    {
        return $this->meta_key;
    }

    public function setMetaKey(string $meta_key): self
    {
        $this->meta_key = $meta_key;

        return $this;
    }

    public function getMetaValue(): ?string
    {
        return $this->meta_value;
    }

    public function setMetaValue(?string $meta_value): self
    {
        $this->meta_value = $meta_value;

        return $this;
    }

    public function getIdPost(): ?Post
    {
        return $this->id_post;
    }

    public function setIdPost(?Post $id_post): self
    {
        $this->id_post = $id_post;

        return $this;
    }

    public function getAcf(): ?Acf
    {
        return $this->acf;
    }

    public function setAcf(?Acf $acf): self
    {
        $this->acf = $acf;

        return $this;
    }

    public function __toString()
    {
        return $this->meta_key;
    }
}
