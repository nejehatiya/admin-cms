<?php

namespace App\Entity;

use App\Repository\OptionsRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @UniqueEntity("option_name")
 */
#[ORM\Entity(repositoryClass: OptionsRepository::class)]
class Options
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true, unique: true)]
    private $option_name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $option_value;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $option_label;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $option_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionName(): ?string
    {
        return $this->option_name;
    }

    public function setOptionName(?string $option_name): self
    {
        $this->option_name = $option_name;

        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->option_value;
    }

    public function setOptionValue(?string $option_value): self
    {
        $this->option_value = $option_value;

        return $this;
    }

    public function getOptionLabel(): ?string
    {
        return $this->option_label;
    }

    public function setOptionLabel(?string $option_label): self
    {
        $this->option_label = $option_label;

        return $this;
    }

    public function __toString()
    {
        return $this->option_value;
    }

    public function getOptionType(): ?string
    {
        return $this->option_type;
    }

    public function setOptionType(?string $option_type): self
    {
        $this->option_type = $option_type;

        return $this;
    }

   
}
