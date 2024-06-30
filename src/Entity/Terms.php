<?php

namespace App\Entity;

use App\Repository\TermsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: TermsRepository::class)]
class Terms
{
    #[Groups(["id_terms","cat_search","terms_devis","data_front",'show_api','show_data'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[Groups(["id_terms","cat_search","terms_devis","data_front",'show_api','show_data'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $name_terms;

    #[Groups(["terms_devis","data_front",'show_api','show_data'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $description_terms;

    
    #[Groups(["terms_devis","data_front",'show_api','show_data'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $slug_terms;

    

    #[ORM\Column(type: 'text', nullable: true)]
    private $autre_taxonomy;

    #[Groups(["terms_devis","data_front",'show_api'])]
    #[ORM\ManyToOne(targetEntity: Taxonomy::class, inversedBy: 'terms')]
    private $id_taxonomy;

    
    #[Groups(['cat_search'])]
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'terms')]
    private $id_post;
    #[Groups(['cat_search','show_api'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $parentTerms;

    #[Groups(['terms_devis','show_api'])]
    #[ORM\ManyToOne(targetEntity: Images::class)]
    private $image;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $id_migration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $parent_migration;
    
    #[Groups(['id_terms','show_api'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $level;

    #[Groups(['id_terms','show_api'])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_draft;

    #[Groups(['show_api'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;


    public function __construct()
    {
        $this->id_post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTerms(): ?string
    {
        return $this->name_terms;
    }

    public function setNameTerms(?string $name_terms): self
    {
        $this->name_terms = $name_terms;

        return $this;
    }

    public function getDescriptionTerms(): ?string
    {
        return $this->description_terms;
    }

    public function setDescriptionTerms(?string $description_terms): self
    {
        $this->description_terms = $description_terms;

        return $this;
    }

    public function getSlugTerms(): ?string
    {
        return $this->slug_terms;
    }

    public function setSlugTerms(?string $slug_terms): self
    {
        $this->slug_terms = $slug_terms;

        return $this;
    }

    

    public function getAutreTaxonomy(): ?string
    {
        return $this->autre_taxonomy;
    }

    public function setAutreTaxonomy(?string $autre_taxonomy): self
    {
        $this->autre_taxonomy = $autre_taxonomy;

        return $this;
    }

    public function getIdTaxonomy(): ?Taxonomy
    {
        return $this->id_taxonomy;
    }

    public function setIdTaxonomy(?Taxonomy $id_taxonomy): self
    {
        $this->id_taxonomy = $id_taxonomy;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getIdPost(): Collection
    {
        return $this->id_post;
    }

    public function addIdPost(Post $idPost): self
    {
        if (!$this->id_post->contains($idPost)) {
            $this->id_post[] = $idPost;
        }

        return $this;
    }

    public function removeIdPost(Post $idPost): self
    {
        $this->id_post->removeElement($idPost);

        return $this;
    }

    public function getParentTerms(): ?int
    {
        return $this->parentTerms;
    }

    public function setParentTerms(?int $parentTerms): self
    {
        $this->parentTerms = $parentTerms;

        return $this;
    }

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(?Images $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIdMigration(): ?string
    {
        return $this->id_migration;
    }

    public function setIdMigration(string $id_migration): self
    {
        $this->id_migration = $id_migration;

        return $this;
    }

    public function getParentMigration(): ?string
    {
        return $this->parent_migration;
    }

    public function setParentMigration(string $parent_migration): self
    {
        $this->parent_migration = $parent_migration;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getIsDraft(): ?bool
    {
        return $this->is_draft;
    }

    public function setIsDraft(bool $is_draft): self
    {
        $this->is_draft = $is_draft;

        return $this;
    }

    public function __toString()
    {
        return $this->name_terms;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
