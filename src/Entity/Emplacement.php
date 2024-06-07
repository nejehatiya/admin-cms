<?php

namespace App\Entity;

use App\Repository\EmplacementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: EmplacementRepository::class)]
class Emplacement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['show_api'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['show_api'])]
    private $key_emplacement;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['show_api'])]
    private $status;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['show_api'])]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['show_api'])]
    private $date_upd;

    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'id_emplacement')]
    
    private $menu;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyEmplacement(): ?string
    {
        return $this->key_emplacement;
    }

    public function setKeyEmplacement(string $key_emplacement): self
    {
        $this->key_emplacement = $key_emplacement;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

 
}
