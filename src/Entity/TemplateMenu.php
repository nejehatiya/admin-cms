<?php

namespace App\Entity;

use App\Repository\TemplateMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateMenuRepository::class)]
class TemplateMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_template;

    #[ORM\Column(type: 'text')]
    private $content_template;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    #[ORM\Column(type: 'boolean')]
    private $status_template;

    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'templateMenu')]
    private $menu_id;

    #[ORM\ManyToOne(targetEntity: PathTemplateMenu::class)]
    private $path_template;

    public function __construct()
    {
        $this->menu_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTemplate(): ?string
    {
        return $this->name_template;
    }

    public function setNameTemplate(string $name_template): self
    {
        $this->name_template = $name_template;

        return $this;
    }

    public function getContentTemplate(): ?string
    {
        return $this->content_template;
    }

    public function setContentTemplate(string $content_template): self
    {
        $this->content_template = $content_template;

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

    public function getStatusTemplate(): ?bool
    {
        return $this->status_template;
    }

    public function setStatusTemplate(bool $status_template): self
    {
        $this->status_template = $status_template;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenuId(): Collection
    {
        return $this->menu_id;
    }

    public function addMenuId(Menu $menuId): self
    {
        if (!$this->menu_id->contains($menuId)) {
            $this->menu_id[] = $menuId;
            $menuId->setTemplateMenu($this);
        }

        return $this;
    }

    public function removeMenuId(Menu $menuId): self
    {
        if ($this->menu_id->removeElement($menuId)) {
            // set the owning side to null (unless already changed)
            if ($menuId->getTemplateMenu() === $this) {
                $menuId->setTemplateMenu(null);
            }
        }

        return $this;
    }

    public function getPathTemplate(): ?PathTemplateMenu
    {
        return $this->path_template;
    }

    public function setPathTemplate(?PathTemplateMenu $path_template): self
    {
        $this->path_template = $path_template;

        return $this;
    }

    public function isStatusTemplate(): ?bool
    {
        return $this->status_template;
    }

    
}
