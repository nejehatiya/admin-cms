<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_menu;

    #[ORM\Column(type: 'text')]
    private $menu_content;

    #[ORM\Column(type: 'boolean')]
    private $status_menu;

    #[ORM\OneToMany(targetEntity: Emplacement::class, mappedBy: 'menu')]
    private $id_emplacement;

    #[ORM\ManyToOne(targetEntity: TemplateMenu::class, inversedBy: 'menu_id')]
    private $templateMenu;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_update;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'menus')]
    private $user;

    public function __construct()
    {
        $this->id_emplacement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameMenu(): ?string
    {
        return $this->name_menu;
    }

    public function setNameMenu(string $name_menu): self
    {
        $this->name_menu = $name_menu;

        return $this;
    }

    public function getMenuContent(): ?string
    {
        return $this->menu_content;
    }

    public function setMenuContent(string $menu_content): self
    {
        $this->menu_content = $menu_content;

        return $this;
    }

    public function getStatusMenu(): ?bool
    {
        return $this->status_menu;
    }

    public function setStatusMenu(bool $status_menu): self
    {
        $this->status_menu = $status_menu;

        return $this;
    }

    /**
     * @return Collection|Emplacement[]
     */
    public function getIdEmplacement(): Collection
    {
        return $this->id_emplacement;
    }

    public function addIdEmplacement(Emplacement $idEmplacement): self
    {
        if (!$this->id_emplacement->contains($idEmplacement)) {
            $this->id_emplacement[] = $idEmplacement;
            $idEmplacement->setMenu($this);
        }

        return $this;
    }

    public function removeIdEmplacement(Emplacement $idEmplacement): self
    {
        if ($this->id_emplacement->removeElement($idEmplacement)) {
            // set the owning side to null (unless already changed)
            if ($idEmplacement->getMenu() === $this) {
                $idEmplacement->setMenu(null);
            }
        }

        return $this;
    }

    public function getTemplateMenu(): ?TemplateMenu
    {
        return $this->templateMenu;
    }

    public function setTemplateMenu(?TemplateMenu $templateMenu): self
    {
        $this->templateMenu = $templateMenu;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

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

    public function isStatusMenu(): ?bool
    {
        return $this->status_menu;
    }

}
