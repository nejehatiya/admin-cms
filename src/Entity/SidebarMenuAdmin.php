<?php

namespace App\Entity;

use App\Repository\SidebarMenuAdminRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SidebarMenuAdminRepository::class)]
class SidebarMenuAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_menu;

    #[ORM\Column(type: 'text')]
    private $name_path;

    #[ORM\Column(type: 'integer')]
    private $parent;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'id_user')]
    private $user;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $icon;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $menu_order_sidebar;

    #[ORM\Column(type: 'text', nullable: true)]
    private $params;

    public function __construct()
    {
        $this->date_add =  new \DateTime("now");
        $this->date_upd =  new \DateTime("now");
    }
    /**
     * fill all class properties from array
     *
     * @param array $row => array of properties
     * @return self
     */
    public static function withRow(array $row, $instance = null)
    {
        if (empty($instance))
            $instance = new self();
        $instance->fill($row);
        return $instance;
    }
    protected function fill(array $row)
    {
        // fill all properties from array
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
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

    public function getNamePath(): ?string
    {
        return $this->name_path;
    }

    public function setNamePath(string $name_path): self
    {
        $this->name_path = $name_path;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(int $parent): self
    {
        $this->parent = $parent;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getMenuOrderSidebar(): ?int
    {
        return $this->menu_order_sidebar;
    }

    public function setMenuOrderSidebar(?int $menu_order_sidebar): self
    {
        $this->menu_order_sidebar = $menu_order_sidebar;

        return $this;
    }

    public function getParams(): ?string
    {
        return $this->params;
    }

    public function setParams(?string $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

   
}
