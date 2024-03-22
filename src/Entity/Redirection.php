<?php

namespace App\Entity;

use App\Repository\RedirectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RedirectionRepository::class)]
class Redirection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 500, unique: true)]
    private $old_root;

    #[ORM\Column(type: 'text')]
    private $new_root;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldRoot(): ?string
    {
        return $this->old_root;
    }

    public function setOldRoot(string $old_root): self
    { 
        $this->old_root = $this->cleanRoute($old_root);

        return $this;
    }

    public function getNewRoot(): ?string
    {
        return $this->new_root;
    }

    public function setNewRoot(string $new_root): self
    {
        $this->new_root = $this->cleanRoute($new_root);

        return $this;
    }

    private function cleanRoute ($route) 
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        if (str_starts_with($route, $actual_link)) $route = str_replace($actual_link, "", $route); 
        
        $array = explode('/', $route);
        $cleanRoot = "";

        foreach ($array as $val)  if ($val!="") $cleanRoot = $cleanRoot . '/' .  $val;//$this->slugify($val);
        //$cleanRoot = '/'. $cleanRoot; 
        $allowed_files = array('.jpg', '.jpeg', '.gif', '.png', '.pdf', '.mp4', '.svg');

        foreach ($allowed_files as $extention)
            if(str_ends_with($cleanRoot, $extention))
                return $cleanRoot;

        $cleanRoot = $cleanRoot ; 
        return $cleanRoot;
    }
    
}
