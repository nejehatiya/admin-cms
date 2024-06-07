<?php

namespace App\Entity;

use App\Repository\TaxonomyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaxonomyRepository::class)]
class Taxonomy
{
    #[Groups(['show_api',"terms_devis",'show_data'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['show_api','show_data'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name_taxonomy;
    #[Groups(['show_api','show_data'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug_taxonomy;
    #[Groups(['show_api'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $description_taxonomy;
    #[Groups(['show_api'])]
    #[ORM\Column(type: 'bigint')]
    private $parent_taxonomy;

    #[ORM\Column(type: 'text', nullable: true)]
    private $autre_taxonomy;

    #[ORM\OneToMany(targetEntity: Terms::class, mappedBy: 'id_taxonomy')]
    #[Groups(['show_data'])]
    private $terms;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $OrderTaxonomy;
    #[Groups(['show_api'])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $StatutSideBar;
    #[Groups(['show_api'])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $statutMenu;

    
    #[ORM\ManyToOne(targetEntity: PostType::class, inversedBy: 'taxonomies')]
    private $Posttype;
    #[Groups(['show_api'])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_draft;
    #[Groups(['show_api'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;


    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTaxonomy(): ?string
    {
        return $this->name_taxonomy;
    }

    public function setNameTaxonomy(?string $name_taxonomy): self
    {
        $this->name_taxonomy = $name_taxonomy;

        return $this;
    }

    public function getSlugTaxonomy(): ?string
    {
        return $this->slug_taxonomy;
    }

    public function setSlugTaxonomy(?string $slug_taxonomy): self
    {
        $this->slug_taxonomy = $slug_taxonomy;

        return $this;
    }

    public function getDescriptionTaxonomy(): ?string
    {
        return $this->description_taxonomy;
    }

    public function setDescriptionTaxonomy(?string $description_taxonomy): self
    {
        $this->description_taxonomy = $description_taxonomy;

        return $this;
    }

    public function getParentTaxonomy(): ?string
    {
        return $this->parent_taxonomy;
    }

    public function setParentTaxonomy(string $parent_taxonomy): self
    {
        $this->parent_taxonomy = $parent_taxonomy;

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

    /**
     * @return Collection|Terms[]
     */
    public function getTerms(): Collection
    {
        return $this->terms;
    }
    /**
     * @return Collection|Terms[]
     */
    public function getTermsParents(): Collection
    {
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria = $criteria->orderBy(array('id'=> \Doctrine\Common\Collections\Criteria::DESC));
        $criteria = $criteria->andWhere($criteria->expr()->eq('parentTerms', "0"));
        return $this->terms->matching($criteria);
    }
    public function addTerm(Terms $term): self
    {
        if (!$this->terms->contains($term)) {
            $this->terms[] = $term;
            $term->setIdTaxonomy($this);
        }

        return $this;
    }

    public function removeTerm(Terms $term): self
    {
        if ($this->terms->removeElement($term)) {
            // set the owning side to null (unless already changed)
            if ($term->getIdTaxonomy() === $this) {
                $term->setIdTaxonomy(null);
            }
        }

        return $this;
    }

    

    public function __toString()
    {
        return $this->getNameTaxonomy();
    }

    public function getOrderTaxonomy(): ?int
    {
        return $this->OrderTaxonomy;
    }

    public function setOrderTaxonomy(?int $OrderTaxonomy): self
    {
        $this->OrderTaxonomy = $OrderTaxonomy;

        return $this;
    }

    public function getStatutSideBar(): ?bool
    {
        return $this->StatutSideBar;
    }

    public function setStatutSideBar(?bool $StatutSideBar): self
    {
        $this->StatutSideBar = $StatutSideBar;

        return $this;
    }

    public function getStatutMenu(): ?bool
    {
        return $this->statutMenu;
    }

    public function setStatutMenu(?bool $statutMenu): self
    {
        $this->statutMenu = $statutMenu;

        return $this;
    }

    public function getPosttype(): ?PostType
    {
        return $this->Posttype;
    }

    public function setPosttype(?PostType $Posttype): self
    {
        $this->Posttype = $Posttype;

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

    public function isStatutSideBar(): ?bool
    {
        return $this->StatutSideBar;
    }

    public function isStatutMenu(): ?bool
    {
        return $this->statutMenu;
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
