<?php

namespace App\Entity;

use App\Repository\PostAnalyseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostAnalyseRepository::class)]
class PostAnalyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $a_link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $img_src = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $h_heading = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $states = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostType $Post_type = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $count_word = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $ratio_html = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $a_link_text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code_reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getALink(): ?string
    {
        return $this->a_link;
    }

    public function setALink(?string $a_link): static
    {
        $this->a_link = $a_link;

        return $this;
    }

    public function getImgSrc(): ?string
    {
        return $this->img_src;
    }

    public function setImgSrc(?string $img_src): static
    {
        $this->img_src = $img_src;

        return $this;
    }

    public function getHHeading(): ?string
    {
        return $this->h_heading;
    }

    public function setHHeading(string $h_heading): static
    {
        $this->h_heading = $h_heading;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): static
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStates(): ?string
    {
        return $this->states;
    }

    public function setStates(string $states): static
    {
        $this->states = $states;

        return $this;
    }

    public function getPostType(): ?PostType
    {
        return $this->Post_type;
    }

    public function setPostType(?PostType $Post_type): static
    {
        $this->Post_type = $Post_type;

        return $this;
    }

    public function getCountWord(): ?string
    {
        return $this->count_word;
    }

    public function setCountWord(?string $count_word): static
    {
        $this->count_word = $count_word;

        return $this;
    }

    public function getRatioHtml(): ?string
    {
        return $this->ratio_html;
    }

    public function setRatioHtml(string $ratio_html): static
    {
        $this->ratio_html = $ratio_html;

        return $this;
    }

    public function getALinkText(): ?string
    {
        return $this->a_link_text;
    }

    public function setALinkText(?string $a_link_text): static
    {
        $this->a_link_text = $a_link_text;

        return $this;
    }

    public function getCodeReponse(): ?string
    {
        return $this->code_reponse;
    }

    public function setCodeReponse(?string $code_reponse): static
    {
        $this->code_reponse = $code_reponse;

        return $this;
    }
}
