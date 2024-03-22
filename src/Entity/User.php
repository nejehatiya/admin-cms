<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"user_post_list", "post_details", "comment_show"})
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private $password;

    /**
     * @Groups({"post_details", "comment_show"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    /**
     * @Groups({"post_details", "comment_show"})
     */
    #[ORM\Column(type: 'string', length: 255)] // .
    private $last_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $token_key;

    /**
     * @Groups({"post_details", "comment_show"})
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image_profil;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    #[ORM\ManyToOne(targetEntity: Roles::class, inversedBy: 'id_role')]
    private $roles_user;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'id_user')]
    private $commentaires;

    #[ORM\OneToMany(targetEntity: Notifications::class, mappedBy: 'id_user')]
    private $notifications;

    #[ORM\OneToMany(targetEntity: SidebarMenuAdmin::class, mappedBy: 'user')]
    private $id_user;

    /**
     * @Groups({"user_post_list"})
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author')]
    private $posts;

    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'user')]
    private $menus;

    private $text_password;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $authCode;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->date_add =  new \DateTime("now");
        $this->date_upd =  new \DateTime("now");
        $this->status = true;
        $this->roles = ['ROLE_USER'];
        $this->commentaires = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->id_user = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->token_key = "";
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getTokenKey(): ?string
    {
        return $this->token_key;
    }

    public function setTokenKey(string $token_key): self
    {
        $this->token_key = $token_key;

        return $this;
    }

    public function getImageProfil(): ?string
    {
        return $this->image_profil;
    }

    public function setImageProfil(string $image_profil): self
    {
        $this->image_profil = $image_profil;

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

    public function setDateUpd(): self
    {
        $this->date_upd =  new \DateTime("now");

        return $this;
    }

    public function getRolesUser(): ?Roles
    {
        return $this->roles_user;
    }

    public function setRolesUser(?Roles $roles_user): self
    {
        $this->roles_user = $roles_user;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setIdUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getIdUser() === $this) {
                $notification->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SidebarMenuAdmin[]
     */
    public function getIdUser(): Collection
    {
        return $this->id_user;
    }

    public function addIdUser(SidebarMenuAdmin $idUser): self
    {
        if (!$this->id_user->contains($idUser)) {
            $this->id_user[] = $idUser;
            $idUser->setUser($this);
        }

        return $this;
    }

    public function removeIdUser(SidebarMenuAdmin $idUser): self
    {
        if ($this->id_user->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getUser() === $this) {
                $idUser->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->first_name;
    }


    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * Set the value of posts
     *
     * @return  self
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }
    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setUser($this);
        }
        return $this;
    }
    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getUser() === $this) {
                $menu->setUser(null);
            }
        }
        return $this;
    }

    public function getTextPassword(): string
    {
        return $this->text_password;
    }

    public function setTextPassword(string $text_password): self
    {
        $this->text_password = $text_password;
        return $this;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function setAuthCode(?string $authCode): static
    {
        $this->authCode = $authCode;

        return $this;
    }
}
