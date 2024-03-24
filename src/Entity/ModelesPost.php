<?php

namespace App\Entity;

use App\Repository\ModelesPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesPostRepository::class)]
class ModelesPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $name_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    private $shortcode_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    private $path_modele;

    #[ORM\Column(type: 'text', nullable: true)]
    private $variable_modele;

    #[ORM\Column(type: 'boolean')]
    private $status_modele;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    #[ORM\OneToMany(targetEntity: PostModals::class, mappedBy: 'modele', orphanRemoval: true)]
    private $postModals;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image_preview;

    #[ORM\Column(type: 'text', nullable: true)]
    private $used_in;

    public function __construct()
    {
        $this->postModals = new ArrayCollection();
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
    
    public function getImagePreview(): ?string
    {
        return $this->image_preview;
    }

    public function setImagePreview(?string $image_preview): self
    {
        $this->image_preview = $image_preview;

        return $this;
    }

    public function getUsedIn(): ?string
    {
        return $this->used_in;
    }

    public function setUsedIn(?string $used_in): self
    {
        $this->used_in = $used_in;

        return $this;
    }

    public function isStatusModele(): ?bool
    {
        return $this->status_modele;
    }
}