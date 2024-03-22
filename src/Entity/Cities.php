<?php

namespace App\Entity;

use App\Repository\CitiesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Cities
 */
#[ORM\Table(name: 'cities')]
#[ORM\Index(name: 'Name', columns: ['name'])]
#[ORM\Entity(repositoryClass: CitiesRepository::class)]
class Cities
{
    /**
     * @var int
     *
     * @Groups({"cities_json", "cities_devis"})
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     *
     * @Groups({"cities_json", "cities_devis"})
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    private $name;

    /**
     * @var string
     *
     * @Groups({"cities_json", "cities_devis"})
     */
    #[ORM\Column(name: 'slug', type: 'string', length: 255, nullable: false)]
    private $slug;

    /**
     * @var string
     *
     * @Groups({"cities_json"})
     */
    #[ORM\Column(name: 'pattern', type: 'string', length: 255, nullable: false)]
    private $pattern;

    /**
     * @var string
     *
     * @Groups({"cities_json", "cities_devis"})
     */
    #[ORM\Column(name: 'postal_code', type: 'string', length: 125, nullable: false)]
    private $postalCode;

    /**
     * @var float
     *
     * @Groups({"cities_json"})
     */
    #[ORM\Column(name: 'gps_lat', type: 'float', precision: 9, scale: 4, nullable: false)]
    private $gpsLat;

    /**
     * @var float
     *
     * @Groups({"cities_json"})
     */
    #[ORM\Column(name: 'gps_lon', type: 'float', precision: 9, scale: 4, nullable: false)]
    private $gpsLon;

    /**
     * @var int|null
     *
     * @Groups({"cities_json"})
     */
    #[ORM\Column(name: 'population', type: 'integer', nullable: true)]
    private $population;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Departments::class, inversedBy: 'cities')]
    private $departments;

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

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getGpsLat(): ?float
    {
        return $this->gpsLat;
    }

    public function setGpsLat(float $gpsLat): self
    {
        $this->gpsLat = $gpsLat;

        return $this;
    }

    public function getGpsLon(): ?float
    {
        return $this->gpsLon;
    }

    public function setGpsLon(float $gpsLon): self
    {
        $this->gpsLon = $gpsLon;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(?int $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getDepartments(): ?Departments
    {
        return $this->departments;
    }

    public function setDepartments(?Departments $departments): self
    {
        $this->departments = $departments;

        return $this;
    }

    public function __toString()
    {
        return $this->slug;
    }
}
