<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"routes_names"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    /**
     * @Groups({"role_routes", "permissions", "roles_routes"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $path;

    /**
     * @Groups({"role_routes"})
     */
    #[ORM\Column(type: 'string', length: 10)]
    private $method;

    /**
     * @Groups({"role_routes"})
     */
    #[ORM\Column(type: 'string', length: 30)]
    private $module;

    #[ORM\Column(type: 'integer')]
    private $priority;

    /**
     * @Groups({"roles_routes"})
     */
    #[ORM\JoinTable(name: 'permission_roles_routes')]
    #[ORM\ManyToMany(targetEntity: Roles::class, inversedBy: 'routes')]
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->priority = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Collection|Roles[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addRoute($this);
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removeRoute($this);
        }

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(string $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

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
}
