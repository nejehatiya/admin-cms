<?php

namespace App\Entity;

use App\Repository\AcfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcfRepository::class)]
class Acf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type_acf;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $field_label;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $field_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $field_content;

    #[ORM\OneToMany(targetEntity: PostMeta::class, mappedBy: 'acf')]
    private $id_acf;

    public function __construct()
    {
        $this->id_acf = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAcf(): ?string
    {
        return $this->type_acf;
    }

    public function setTypeAcf(?string $type_acf): self
    {
        $this->type_acf = $type_acf;

        return $this;
    }

    public function getFieldLabel(): ?string
    {
        return $this->field_label;
    }

    public function setFieldLabel(?string $field_label): self
    {
        $this->field_label = $field_label;

        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(?string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }

    public function getFieldContent(): ?string
    {
        return $this->field_content;
    }

    public function setFieldContent(?string $field_content): self
    {
        $this->field_content = $field_content;

        return $this;
    }

    /**
     * @return Collection|PostMeta[]
     */
    public function getIdAcf(): Collection
    {
        return $this->id_acf;
    }

    public function addIdAcf(PostMeta $idAcf): self
    {
        if (!$this->id_acf->contains($idAcf)) {
            $this->id_acf[] = $idAcf;
            $idAcf->setAcf($this);
        }

        return $this;
    }

    public function removeIdAcf(PostMeta $idAcf): self
    {
        if ($this->id_acf->removeElement($idAcf)) {
            // set the owning side to null (unless already changed)
            if ($idAcf->getAcf() === $this) {
                $idAcf->setAcf(null);
            }
        }

        return $this;
    }
}
