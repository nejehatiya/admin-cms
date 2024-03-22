<?php

namespace App\Entity;

use App\Repository\PostTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: PostTypeRepository::class)]
class PostType
{
    /**
     * @Groups({"id_posttype","data_front"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Groups({"id_posttype","data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $name_post_type;

    /**
     * @Groups({"data_front"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $slug_post_type;

    #[ORM\Column(type: 'string', length: 255)]
    private $type_post_type;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'post_type')]
    private $posts;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $statutMenuSideBar;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $OrderPostType;

    #[ORM\OneToMany(targetEntity: Taxonomy::class, mappedBy: 'Posttype')]
    private $taxonomies;

    
    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0])]
    private $is_draft;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $displayInSitemap;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pathParentSitemap;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $metaTitleSeoSitmap;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $metaDescriptionSeoSitemap;


    public function __construct()
    {
        $this->taxonomies = new ArrayCollection();
        $this->OrderPostType = 0;
        $this->statutMenuSideBar = 0;
        $this->is_draft = 0;
        $this->posts = new ArrayCollection();
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

    public function getNamePostType(): ?string
    {
        return $this->name_post_type;
    }

    public function setNamePostType(string $name_post_type): self
    {
        $this->name_post_type = $name_post_type;

        return $this;
    }

    public function getSlugPostType(): ?string
    {
        return $this->slug_post_type;
    }

    public function setSlugPostType(string $slug_post_type): self
    {
        $this->slug_post_type = $slug_post_type;

        return $this;
    }

    public function getTypePostType(): ?string
    {
        return $this->type_post_type;
    }

    public function setTypePostType(string $type_post_type): self
    {
        $this->type_post_type = $type_post_type;

        return $this;
    }


    public function __toString() {
        return $this->name_post_type;
    }

    public function getStatutMenuSideBar(): ?bool
    {
        return $this->statutMenuSideBar;
    }

    public function setStatutMenuSideBar(?bool $statutMenuSideBar): self
    {
        $this->statutMenuSideBar = $statutMenuSideBar;

        return $this;
    }

    public function getOrderPostType(): ?int
    {
        return $this->OrderPostType;
    }

    public function setOrderPostType(?int $OrderPostType): self
    {
        $this->OrderPostType = $OrderPostType;

        return $this;
    }
    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        $criteria = \Doctrine\Common\Collections\Criteria::create()
        ->orderBy(array('id'=> \Doctrine\Common\Collections\Criteria::DESC))
        ->setMaxResults(20000);
        return $this->posts->matching($criteria);
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setPostType($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPostType() === $this) {
                $post->setPostType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Taxonomy[]
     */
    public function getTaxonomies(): Collection
    {
        return $this->taxonomies;
    }

    public function addTaxonomy(Taxonomy $taxonomy): self
    {
        if (!$this->taxonomies->contains($taxonomy)) {
            $this->taxonomies[] = $taxonomy;
            $taxonomy->setPosttype($this);
        }

        return $this;
    }

    public function removeTaxonomy(Taxonomy $taxonomy): self
    {
        if ($this->taxonomies->removeElement($taxonomy)) {
            // set the owning side to null (unless already changed)
            if ($taxonomy->getPosttype() === $this) {
                $taxonomy->setPosttype(null);
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

    public function getDisplayInSitemap(): ?bool
    {
        return $this->displayInSitemap;
    }

    public function setDisplayInSitemap(?bool $displayInSitemap): self
    {
        $this->displayInSitemap = $displayInSitemap;

        return $this;
    }

    public function getPathParentSitemap(): ?string
    {
        return $this->pathParentSitemap;
    }

    public function setPathParentSitemap(?string $pathParentSitemap): self
    {
        $this->pathParentSitemap = $pathParentSitemap;

        return $this;
    }

    public function getMetaTitleSeoSitmap(): ?string
    {
        return $this->metaTitleSeoSitmap;
    }

    public function setMetaTitleSeoSitmap(?string $metaTitleSeoSitmap): self
    {
        $this->metaTitleSeoSitmap = $metaTitleSeoSitmap;

        return $this;
    }

    public function getMetaDescriptionSeoSitemap(): ?string
    {
        return $this->metaDescriptionSeoSitemap;
    }

    public function setMetaDescriptionSeoSitemap(?string $metaDescriptionSeoSitemap): self
    {
        $this->metaDescriptionSeoSitemap = $metaDescriptionSeoSitemap;

        return $this;
    }

    public function isStatutMenuSideBar(): ?bool
    {
        return $this->statutMenuSideBar;
    }

    public function isDisplayInSitemap(): ?bool
    {
        return $this->displayInSitemap;
    }


}
