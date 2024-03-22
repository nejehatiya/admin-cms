<?php

namespace App\Entity;


use App\Repository\RegionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Regions
 */
#[ORM\Table(name: 'regions')]
#[ORM\Entity(repositoryClass: RegionsRepository::class)]
class Regions
{
    /**
     * @var int
     *
     * @Groups({"regions_list"})
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     *
     * @Groups({"regions_list"})
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    private $name;

    /**
     * @var string
     *
     * @Groups({"regions_list"})
     */
    #[ORM\Column(name: 'slug', type: 'string', length: 255, nullable: false)]
    private $slug;

    /**
     * @var string
     *
     * @Groups({"regions_list"})
     */
    #[ORM\Column(name: 'iso_code', type: 'string', length: 255, nullable: false)]
    private $isoCode;

    #[ORM\OneToMany(targetEntity: Departments::class, mappedBy: 'regions', orphanRemoval: true)]
    private $departements;

    public function __construct()
    {
        $this->departements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsoCode(): ?string
    {
        return $this->isoCode;
    }

    public function setIsoCode(string $isoCode): self
    {
        $this->isoCode = $isoCode;

        return $this;
    }

    /**
     * @return Collection|Departments[]
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departments $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements[] = $departement;
            $departement->setRegions($this);
        }

        return $this;
    }

    public function removeDepartement(Departments $departement): self
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getRegions() === $this) {
                $departement->setRegions(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->slug;
    }
}
