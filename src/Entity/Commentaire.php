<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    /**
     * @Groups({"comment_show","page_search","data_front"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"comment_show","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $comment_content;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'bigint')]
    private $note_comment;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $email_comment;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'datetime')]
    private $date_add;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    /**
     * @Groups({"comment_show","data_front"})
     */
    #[ORM\Column(type: 'boolean')]
    private $status;

    /**
     * @Groups("comment_show")
     */
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'commentaires')]
    private $id_post;

    /**
     * @Groups("comment_show")
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
    private $id_user;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $parent;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $comment_agent;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $comment_id_migration;

    private $page_service;


    public function __construct()
    {
        $this->date_add = new \DateTime();
        $this->date_upd = new \DateTime();
        $this->first_name = "";
        $this->last_name = "";
        $this->email_comment = "";
        $this->comment_content="";
        $this->note_comment=1;
        $this->page_service="";
        $this->status = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentContent(): ?string
    {
        return $this->comment_content;
    }

    public function setCommentContent(string $comment_content): self
    {
        $this->comment_content = $comment_content;

        return $this;
    }

    public function getNoteComment(): ?string
    {
        return $this->note_comment;
    }

    public function setNoteComment(string $note_comment): self
    {
        $this->note_comment = $note_comment;

        return $this;
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

    public function getEmailComment(): ?string
    {
        return $this->email_comment;
    }

    public function setEmailComment(string $email_comment): self
    {
        $this->email_comment = $email_comment;

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
        $this->date_upd = new \DateTime();
        return $this;
    }

    // public function setDateUpd(\DateTimeInterface $date_upd): self
    // {
    //     $this->date_upd = $date_upd;

    //     return $this;
    // }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdPost(): ?Post
    {
        return $this->id_post;
    }

    public function setIdPost(?Post $id_post): self
    {
        $this->id_post = $id_post;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCommentAgent(): ?string
    {
        return $this->comment_agent;
    }

    public function setCommentAgent(?string $comment_agent): self
    {
        $this->comment_agent = $comment_agent;

        return $this;
    }

    public function getCommentIdMigration(): ?int
    {
        return $this->comment_id_migration;
    }

    public function setCommentIdMigration(?int $comment_id_migration): self
    {
        $this->comment_id_migration = $comment_id_migration;

        return $this;
    }
    public function getPageService(): ?string
    {
        return $this->page_service; 
    }
    public function setPageService(?string $page_service): self
    {
        $this->page_service = $page_service; 
        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

}
