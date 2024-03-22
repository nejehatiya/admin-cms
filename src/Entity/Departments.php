<?php

namespace App\Entity;

use App\Repository\DepartmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Departments
 */
#[ORM\Table(name: 'departments')]
#[ORM\Entity(repositoryClass: DepartmentsRepository::class)]
class Departments
{
    /**
     * @var int
     *
     * @Groups({"departements_list","cities_devis"})
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     *
     * @Groups({"departements_list"})
     */
    #[ORM\Column(name: 'code', type: 'string', length: 10, nullable: false, options: ['fixed' => true])]
    private $code;

    /**
     * @var string
     *
     * @Groups({"departements_list"})
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    private $name;

    /**
     * @var string
     *
     * @Groups({"departements_list"})
     */
    #[ORM\Column(name: 'slug', type: 'string', length: 255, nullable: false)]
    private $slug;

    /**
     * @var string
     *
     * @Groups({"departements_list"})
     */
    #[ORM\Column(name: 'iso_code', type: 'string', length: 255, nullable: false)]
    private $isoCode;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Regions::class, inversedBy: 'departements')]
    private $regions;

    #[ORM\OneToMany(targetEntity: Cities::class, mappedBy: 'departments', orphanRemoval: true)]
    private $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getRegions(): ?Regions
    {
        return $this->regions;
    }

    public function setRegions(?Regions $regions): self
    {
        $this->regions = $regions;

        return $this;
    }

    /**
     * @return Collection|Cities[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(Cities $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setDepartments($this);
        }

        return $this;
    }

    public function removeCity(Cities $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getDepartments() === $this) {
                $city->setDepartments(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->slug;
    }
}
