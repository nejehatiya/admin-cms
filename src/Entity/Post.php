<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post 
{
    /**
     * @Groups({"user_post_list", "post_details", "parentsPermalink", "comment_show", "post_rapid","template_page","etiquette","blog_search","cat_search","page_search","data_front"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"user_post_list", "post_details" ,"parentsPermalink", "comment_show", "post_rapid","template_page","etiquette","blog_search","cat_search","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_title;

    /**
     * @Groups({"post_rapid","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_content;

    /**
     * @Groups({"post_rapid","blog_search","cat_search","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_excerpt;

    /**
     * @Groups({"parentsPermalink", "comment_show", "post_rapid","template_page","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_name;

    /**
     * @Groups({"post_rapid","page_search","data_front"})
     */
    #[ORM\Column(type: 'bigint', nullable: true)]
    private $post_parent;

    /**
     * @Groups({"post_rapid","page_search","data_front"})
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $guide;

    /**
     * @Groups({"post_rapid","data_front"})
     */
    #[ORM\Column(type: 'bigint', nullable: true)]
    private $menu_ordre;

    /**
     * @Groups({"post_details", "post_rapid","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_status;

    /**
     * @Groups({"post_rapid","data_front"})
     */
    #[ORM\Column(type: 'boolean')]
    private $comment_status;

    /**
     * @Groups({"post_details", "post_rapid","blog_search","page_search","data_front"})
     */
    #[ORM\Column(type: 'datetime')]
    private $date_add;

    /**
     * @Groups({"post_details", "post_rapid","blog_search","cat_search","data_front"})
     */
    #[ORM\Column(type: 'datetime')]
    private $date_upd;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'id_post')]
    private $commentaires;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\ManyToMany(targetEntity: Images::class, mappedBy: 'id_post')]
    private $images;

    /**
     * @Groups({"template_page","data_front"})
     */
    #[ORM\OneToMany(targetEntity: PostMeta::class, mappedBy: 'id_post')]
    private $postMetas;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\ManyToMany(targetEntity: Terms::class, mappedBy: 'id_post')]
    private $terms;

    /**
     * @Groups({"post_rapid","page_search","data_front"})
     */
    #[ORM\Column(type: 'text')]
    private $post_content_2;

    /**
     *@Groups({"blog_search","cat_search","page_search","data_front"})
     */
    #[ORM\ManyToOne(targetEntity: Images::class, inversedBy: 'posts_features')]
    private $id_feature_image;

    /**
     * @Groups({"post_details","data_front"})
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    private $author;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: PostType::class, inversedBy: 'posts')]
    private $post_type;

    #[ORM\OneToMany(targetEntity: PostModals::class, mappedBy: 'post')]
    private $postModals;

    /**
     * @Groups({"template_page","page_search","data_front"})
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $post_order_content;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $post_parent_migration;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $post_id__migration;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $page_template;

    #[ORM\Column(type: 'text', nullable: true)]
    private $post_content_3;

    #[ORM\Column(type: 'text', nullable: true)]
    private $post_content_4;

    #[ORM\Column(type: 'text', nullable: true)]
    private $post_content_5;

    #[ORM\Column(type: 'text', nullable: true)]
    private $post_order_content_preinsertion;

    /**
     * @Groups({"template_page","page_search"})
     */
    #[ORM\OneToMany(targetEntity: Revision::class, mappedBy: 'post', cascade: ['remove'])]
    private $revisions;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $is_draft;

    #[ORM\Column(type: 'boolean')]
    private $page_menu;

    /**
     * @Groups({"post_rapid","page_search"})
     * @Groups({"data_front"})
     */
    public $my_parent_slug; // not for doctrine but it useful for template service in ApiGeneratePageOneByOne
    /**
     * @Groups({"data_front"})
     */
    #[ORM\Column(type: 'text', nullable: true)]
    private $sommaire;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\Column(type: 'boolean', options: ['default' => 1])]
    private $is_index;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\Column(type: 'boolean', options: ['default' => 1])]
    private $is_follow; 

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->postMetas = new ArrayCollection();
        $this->terms = new ArrayCollection();
        $this->date_add =  new \DateTime("now");
        $this->date_upd =  new \DateTime("now");
        $this->post_parent = 0;
        $this->post_status = "Brouillon";
        $this->comment_status = false;
        $this->menu_ordre  = 1;
        $this->postModals = new ArrayCollection();
        $this->revisions = new ArrayCollection(); 
        $this->page_menu = false;
        $this->is_index = false;
        $this->is_follow = false;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostTitle(): ?string
    {
        return $this->post_title;
    }

    public function setPostTitle(string $post_title): self
    {
        $this->post_title = $post_title;

        return $this;
    }

    public function getPostContent(): ?string
    {
        return $this->post_content;
    }

    public function setPostContent(string $post_content): self
    {
        $this->post_content = $post_content;

        return $this;
    }

    public function getPostExcerpt(): ?string
    {
        return $this->post_excerpt;
    }

    public function setPostExcerpt(string $post_excerpt): self
    {
        $this->post_excerpt = $post_excerpt;

        return $this;
    }

    public function getPostName(): ?string
    {
        return $this->post_name;
    }

    public function setPostName(string $post_name): self
    {
        $this->post_name = $post_name;

        return $this;
    }

    public function getPostParent(): ?string
    {
        return $this->post_parent;
    }

    public function setPostParent(?string $post_parent): self
    {
        $this->post_parent = $post_parent;

        return $this;
    }

    public function getGuide(): ?string
    {
        return $this->guide;
    }

    public function setGuide(?string $guide): self
    {
        $this->guide = $guide;

        return $this;
    }

    public function getMenuOrdre(): ?string
    {
        return $this->menu_ordre;
    }

    public function setMenuOrdre(?string $menu_ordre): self
    {
        $this->menu_ordre = $menu_ordre;

        return $this;
    }

    public function getPostStatus(): ?string
    {
        return $this->post_status;
    }

    public function setPostStatus(?string $post_status): self
    {
        $this->post_status = $post_status;

        return $this;
    }

    public function getCommentStatus(): ?bool
    {
        return $this->comment_status;
    }

    public function setCommentStatus(bool $comment_status): self
    {
        $this->comment_status = $comment_status;

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

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        $criteria = \Doctrine\Common\Collections\Criteria::create()
        ->andWhere(\Doctrine\Common\Collections\Criteria::expr()->eq('status', true))
        ->orderBy(array('id'=> \Doctrine\Common\Collections\Criteria::DESC))
        ->setMaxResults(100);
        return $this->commentaires->matching($criteria);;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdPost() === $this) {
                $commentaire->setIdPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->addIdPost($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            $image->removeIdPost($this);
        }

        return $this;
    }

    /**
     * @return Collection|PostMeta[]
     */
    public function getPostMetas(): Collection
    {
        return $this->postMetas;
    }

    public function addPostMeta(PostMeta $postMeta): self
    {
        if (!$this->postMetas->contains($postMeta)) {
            $this->postMetas[] = $postMeta;
            $postMeta->setIdPost($this);
        }

        return $this;
    }

    public function removePostMeta(PostMeta $postMeta): self
    {
        if ($this->postMetas->removeElement($postMeta)) {
            // set the owning side to null (unless already changed)
            if ($postMeta->getIdPost() === $this) {
                $postMeta->setIdPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Terms[]
     */
    public function getTerms(): Collection
    {
        return $this->terms;
    }

    public function addTerm(Terms $term): self
    {
        if (!$this->terms->contains($term)) {
            $this->terms[] = $term;
            $term->addIdPost($this);
        }

        return $this;
    }

    public function removeTerm(Terms $term): self
    {
        if ($this->terms->removeElement($term)) {
            $term->removeIdPost($this);
        }

        return $this;
    }

    public function getPostContent2(): ?string
    {
        return $this->post_content_2;
    }

    public function setPostContent2(string $post_content_2): self
    {
        $this->post_content_2 = $post_content_2;

        return $this;
    }

    public function getIdFeatureImage(): ?Images
    {
        return $this->id_feature_image;
    }

    public function setIdFeatureImage(?Images $id_feature_image): self
    {
        $this->id_feature_image = $id_feature_image;

        return $this;
    }

    public function __toString()
    {
        return $this->post_title;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    public function getPostType(): ?PostType
    {
        return $this->post_type;
    }
    public function setPostType(?PostType $post_type): self
    {
        $this->post_type = $post_type;
        return $this;
    }

    /**
     * @return Collection|PostModals[]
     */
    public function getPostModals(): Collection
    {
        return $this->postModals;
    }

    public function addPostModal(PostModals $postModal): self
    {
        if (!$this->postModals->contains($postModal)) {
            $this->postModals[] = $postModal;
            $postModal->setPost($this);
        }

        return $this;
    }

    public function removePostModal(PostModals $postModal): self
    {
        if ($this->postModals->removeElement($postModal)) {
            // set the owning side to null (unless already changed)
            if ($postModal->getPost() === $this) {
                $postModal->setPost(null);
            }
        }

        return $this;
    }

    public function getPostOrderContent(): ?string
    {
        return $this->post_order_content;
    }

    public function setPostOrderContent(?string $post_order_content): self
    {
        $this->post_order_content = $post_order_content;

        return $this;
    }

    public function getPostParentMigration(): ?int
    {
        return $this->post_parent_migration;
    }

    public function setPostParentMigration(?int $post_parent_migration): self
    {
        $this->post_parent_migration = $post_parent_migration;

        return $this;
    }

    public function getPostIdMigration(): ?int
    {
        return $this->post_id__migration;
    }

    public function setPostIdMigration(?int $post_id__migration): self
    {
        $this->post_id__migration = $post_id__migration;

        return $this;
    }

    public function getPageTemplate(): ?string
    {
        return $this->page_template;
    }

    public function setPageTemplate(?string $page_template): self
    {
        $this->page_template = $page_template;

        return $this;
    }

    public function getPostContent3(): ?string
    {
        return $this->post_content_3;
    }

    public function setPostContent3(?string $post_content_3): self
    {
        $this->post_content_3 = $post_content_3;

        return $this;
    }

    public function getPostContent4(): ?string
    {
        return $this->post_content_4;
    }

    public function setPostContent4(?string $post_content_4): self
    {
        $this->post_content_4 = $post_content_4;

        return $this;
    }

    public function getPostContent5(): ?string
    {
        return $this->post_content_5;
    }

    public function setPostContent5(?string $post_content_5): self
    {
        $this->post_content_5 = $post_content_5;

        return $this;
    }

    public function getPostOrderContentPreinsertion(): ?string
    {
        return $this->post_order_content_preinsertion;
    }

    public function setPostOrderContentPreinsertion(?string $post_order_content_preinsertion): self
    {
        $this->post_order_content_preinsertion = $post_order_content_preinsertion;

        return $this;
    }

    /**
     * @return Collection|Revision[]
     */
    public function getRevisions(): Collection
    {
        $criteria = \Doctrine\Common\Collections\Criteria::create()
        ->orderBy(array('date'=> \Doctrine\Common\Collections\Criteria::DESC))
        ->setMaxResults(20);
        return $this->revisions->matching($criteria);
    }

    public function addRevision(Revision $revision): self
    {
        if (!$this->revisions->contains($revision)) {
            $this->revisions[] = $revision;
            $revision->setPost($this);
        }

        return $this;
    }

    public function removeRevision(Revision $revision): self
    {
        if ($this->revisions->removeElement($revision)) {
            // set the owning side to null (unless already changed)
            if ($revision->getPost() === $this) {
                $revision->setPost(null);
            }
        }

        return $this;
    }

    public function getIsDraft(): ?int
    {
        return $this->is_draft;
    }

    public function setIsDraft(int $is_draft): self
    {
        $this->is_draft = $is_draft;

        return $this;
    }

    public function getPageMenu(): ?bool
    {
        return $this->page_menu;
    }

    public function setPageMenu(bool $page_menu): self
    {
        $this->page_menu = $page_menu;

        return $this;
    }

    public function getSommaire(): ?string
    {
        return $this->sommaire;
    }

    public function setSommaire(?string $sommaire): self
    {
        $this->sommaire = $sommaire;

        return $this;
    }

    public function isTrashed() 
    {
        return $this->post_status == "Corbeille";
    }

    public function isPublished() 
    {
        return $this->post_status == "Publié";
    }

    public function isDrafted() 
    {
        return $this->post_status == "Brouillon";
    }

    public function getIsIndex(): ?bool
    {
        return $this->is_index;
    }

    public function setIsIndex(bool $is_index): self
    {
        $this->is_index = $is_index;

        return $this;
    }

    public function getIsFollow(): ?bool
    {
        return $this->is_follow;
    }

    public function setIsFollow(bool $is_follow): self
    {
        $this->is_follow = $is_follow;

        return $this;
    }

    public function isCommentStatus(): ?bool
    {
        return $this->comment_status;
    }

    public function isPageMenu(): ?bool
    {
        return $this->page_menu;
    }

    public function isIsIndex(): ?bool
    {
        return $this->is_index;
    }

    public function isIsFollow(): ?bool
    {
        return $this->is_follow;
    }
}
