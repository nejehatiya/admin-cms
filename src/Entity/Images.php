<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[Groups(['show_api'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Groups(['show_api',"cat_search","terms_devis","data_front"])]
    #[ORM\Column(type: 'text')]
    private $url_image;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name_image;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $description_image;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $alt_image;

    
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'images')]
    private $id_post;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'id_feature_image')]
    private $posts_features;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'datetime')]
    private $date_add;

    #[Groups(['show_api'])]
    #[ORM\Column(type: 'datetime')]
    private $date_update;

    
    #[ORM\Column(type: 'float', nullable: true)]
    private $height;

    
    #[ORM\Column(type: 'float', nullable: true)]
    private $width;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mime_type = null;

    public function __construct()
    {
        $this->id_post = new ArrayCollection();
        $this->posts_features = new ArrayCollection();
        $this->date_add = new \DateTime();
        $this->date_update = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    public function setUrlImage(string $url_image): self
    {
        $this->url_image = $url_image;

        return $this;
    }

    public function getNameImage(): ?string
    {
        return $this->name_image;
    }

    public function setNameImage(?string $name_image): self
    {
        $this->name_image = $this->slugify($name_image);

        return $this;
    }

    public function getDescriptionImage(): ?string
    {
        return $this->description_image;
    }

    public function setDescriptionImage(?string $description_image): self
    {
        $this->description_image = $description_image;

        return $this;
    }

    public function getAltImage(): ?string
    {
        return $this->alt_image;
    }

    public function setAltImage(?string $alt_image): self
    {
        $this->alt_image = $alt_image;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getIdPost(): Collection
    {
        return $this->id_post;
    }

    public function addIdPost(Post $idPost): self
    {
        if (!$this->id_post->contains($idPost)) {
            $this->id_post[] = $idPost;
        }

        return $this;
    }

    public function removeIdPost(Post $idPost): self
    {
        $this->id_post->removeElement($idPost);

        return $this;
    }

    // /**
    //  * @return Collection|Post[]
    //  */
    // public function getPostsFeatures(): Collection
    // {
    //     return $this->posts_features;
    // }

    public function addPostFeatures(Post $post): self
    {
        if (!$this->posts_features->contains($post)) {
            $this->posts_features[] = $post;
            $post->setIdFeatureImage($this);
        }

        return $this;
    }

    public function removePostFeatures(Post $post): self
    {
        if ($this->posts_features->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getIdFeatureImage() === $this) {
                $post->setIdFeatureImage(null);
            }
        }

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

    public function setDateUpdate(\DateTimeInterface $date_update = null): self
    {
        if ($date_update)
            $this->date_update = $date_update;
        else $this->date_update = new \DateTime();
        return $this;
    }
    public function __toString()
    {
        return $this->name_image;
    }

    

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

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsFeatures(): Collection
    {
        return $this->posts_features;
    }

    public function addPostsFeature(Post $postsFeature): static
    {
        if (!$this->posts_features->contains($postsFeature)) {
            $this->posts_features->add($postsFeature);
            $postsFeature->setIdFeatureImage($this);
        }

        return $this;
    }

    public function removePostsFeature(Post $postsFeature): static
    {
        if ($this->posts_features->removeElement($postsFeature)) {
            // set the owning side to null (unless already changed)
            if ($postsFeature->getIdFeatureImage() === $this) {
                $postsFeature->setIdFeatureImage(null);
            }
        }

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function setMimeType(?string $mime_type): static
    {
        $this->mime_type = $mime_type;

        return $this;
    }
}
