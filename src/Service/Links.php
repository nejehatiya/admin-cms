<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Links
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * get link of image
     * {$image}
     */
    public function getLinkImage($image){
        $url = "";
        if($image){
            $date  =  $image->getDateAdd();
            $base_img = "/uploads/". $date->format('Y') . '/' . $date->format('m').'/';
            $image_name = $image->getUrlImage();
            $url = $base_img.$image_name;
        }
        return $url;
    }
    /**
     * get link of post
     * {$post}
     */
    public function getPostLink($post,$base_url=""){
        return "";
    }
}
