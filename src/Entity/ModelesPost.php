<?php

namespace App\Entity;

use App\Repository\ModelesPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModelesPostRepository::class)]
class ModelesPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['show_api'])]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['show_api'])]
    private $name_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['show_api'])]
    private $content_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['show_api'])]
    private $shortcode_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['show_api'])]
    private $path_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['show_api'])]
    private $variable_modele;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['show_api'])]
    private $status_modele;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['show_api'])]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['show_api'])]
    private $date_upd;

    #[ORM\OneToMany(targetEntity: PostModals::class, mappedBy: 'modele', orphanRemoval: true)]
    private $postModals;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['show_api'])]
    private ?string $fields = null;

    #[ORM\ManyToMany(targetEntity: PostType::class, inversedBy: 'modelesPosts')]
    #[Groups(['show_api'])]
    private Collection $used_in;

    #[ORM\ManyToOne]
    #[Groups(['show_api'])]
    private ?Images $image_preview = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_api'])]
    private ?string $class_sortable = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_api'])]
    private ?bool $is_new = null;


    public function __construct()
    {
        $this->postModals = new ArrayCollection();
        $this->used_in = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameModele(): ?string
    {
        return $this->name_modele;
    }

    public function setNameModele(?string $name_modele): self
    {
        $this->name_modele = $name_modele;

        return $this;
    }

    public function getContentModele(): ?string
    {
        return $this->content_modele;
    }

    public function setContentModele(?string $content_modele): self
    {
        $this->content_modele = $content_modele;

        return $this;
    }

    public function getShortcodeModele(): ?string
    {
        return $this->shortcode_modele;
    }

    public function setShortcodeModele(?string $shortcode_modele): self
    {
        $this->shortcode_modele = $shortcode_modele;

        return $this;
    }

    public function getPathModele(): ?string
    {
        return $this->path_modele;
    }

    public function setPathModele(?string $path_modele): self
    {
        $this->path_modele = $path_modele;

        return $this;
    }

    public function getVariableModele(): ?string
    {
        return $this->variable_modele;
    }

    public function setVariableModele(?string $variable_modele): self
    {
        $this->variable_modele = $variable_modele;

        return $this;
    }

    public function getStatusModele(): ?bool
    {
        return $this->status_modele;
    }

    public function setStatusModele(bool $status_modele): self
    {
        $this->status_modele = $status_modele;

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

    /**
     * @return Collection|PostModals[]
     */
    public function getPostModals(): Collection
    {
        return $this->postModals;
    }

    public function addPostModal(PostModals $postModal): self
    {
        if (!$this->postModals->contains($postModal)) {
            $this->postModals[] = $postModal;
            $postModal->setModele($this);
        }

        return $this;
    }

    public function removePostModal(PostModals $postModal): self
    {
        if ($this->postModals->removeElement($postModal)) {
            // set the owning side to null (unless already changed)
            if ($postModal->getModele() === $this) {
                $postModal->setModele(null);
            }
        }

        return $this;
    }
    

    public function isStatusModele(): ?bool
    {
        return $this->status_modele;
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

    /**
     * @return Collection<int, PostType>
     */
    public function getUsedIn(): Collection
    {
        return $this->used_in;
    }

    public function addUsedIn(PostType $usedIn): static
    {
        if (!$this->used_in->contains($usedIn)) {
            $this->used_in->add($usedIn);
        }

        return $this;
    }

    public function removeUsedIn(PostType $usedIn): static
    {
        $this->used_in->removeElement($usedIn);

        return $this;
    }

    public function getImagePreview(): ?Images
    {
        return $this->image_preview;
    }

    public function setImagePreview(?Images $image_preview): static
    {
        $this->image_preview = $image_preview;

        return $this;
    }

    public function getClassSortable(): ?string
    {
        return $this->class_sortable;
    }

    public function setClassSortable(?string $class_sortable): static
    {
        $this->class_sortable = $class_sortable;

        return $this;
    }

    public function isIsNew(): ?bool
    {
        return $this->is_new;
    }

    public function setIsNew(?bool $is_new): static
    {
        $this->is_new = $is_new;

        return $this;
    }

    
}
