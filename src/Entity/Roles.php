<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    /**
     * @Groups({"role_routes"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"role_routes", "roles_routes"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $role;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'roles_user')]
    private $id_role;

    /**
     * @Groups({"role_routes", "permissions"})
     */
    #[ORM\ManyToMany(targetEntity: Route::class, mappedBy: 'roles', cascade: ['persist'])]
    private $routes;

    public function __construct()
    {
        $this->id_role = new ArrayCollection();
        $this->routes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getIdRole(): Collection
    {
        return $this->id_role;
    }

    public function addIdRole(User $idRole): self
    {
        if (!$this->id_role->contains($idRole)) {
            $this->id_role[] = $idRole;
            $idRole->setRolesUser($this);
        }

        return $this;
    }

    public function removeIdRole(User $idRole): self
    {
        if ($this->id_role->removeElement($idRole)) {
            // set the owning side to null (unless already changed)
            if ($idRole->getRolesUser() === $this) {
                $idRole->setRolesUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->role;
    }

    /**
     * @return Collection|Route[]
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(Route $route): self
    {
        if (!$this->routes->contains($route)) {
            $this->routes[] = $route;
        }

        return $this;
    }

    public function removeRoute(Route $route): self
    {
        $this->routes->removeElement($route);

        return $this;
    }
}
