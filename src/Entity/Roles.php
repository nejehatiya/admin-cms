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
    #[Groups(['show_api'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $role;


    #[ORM\ManyToMany(targetEntity: Routes::class, mappedBy: 'role')]
    private Collection $routes;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles_user')]
    private Collection $users;
   
    public function __construct()
    {
        $this->id_role = new ArrayCollection();
        $this->routes = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    

    public function __toString()
    {
        return $this->role;
    }

    /**
     * @return Collection<int, Routes>
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(Routes $route): static
    {
        if (!$this->routes->contains($route)) {
            $this->routes->add($route);
            $route->addRole($this);
        }

        return $this;
    }

    public function removeRoute(Routes $route): static
    {
        if ($this->routes->removeElement($route)) {
            $route->removeRole($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRolesUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeRolesUser($this);
        }

        return $this;
    }

}
