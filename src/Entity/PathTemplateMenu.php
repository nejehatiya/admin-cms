<?php

namespace App\Entity;

use App\Repository\PathTemplateMenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PathTemplateMenuRepository::class)]
class PathTemplateMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $path_menu;

    #[ORM\Column(type: 'integer')]
    private $nombre_entree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPathMenu(): ?string
    {
        return $this->path_menu;
    }

    public function setPathMenu(string $path_menu): self
    {
        $this->path_menu = $path_menu;

        return $this;
    }

    public function getNombreEntree(): ?int
    {
        return $this->nombre_entree;
    }

    public function setNombreEntree(int $nombre_entree): self
    {
        $this->nombre_entree = $nombre_entree;

        return $this;
    }

    
}
