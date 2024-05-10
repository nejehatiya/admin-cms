<?php

namespace App\Service;

use App\Entity\Images;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use WebPConvert\WebPConvert;

class UploadAttachement
{
    private $slugger;
    private $images_reposotory;
    private $em;
    private $upload_directory;
    private $webp_directory;
    private $allowed_files ;
    private $imagine;
    
    private const images_sizes = array(
        'thumb_media'=>[150,150],
        'thumb_small'=>[320,320],
        'thumb_medium'=>[360,360],
        'thumb_medium_1' =>[414,414],
        'large_small' => [500,500],
        'large_medium' => [700,700],
        'xlarge' => [1024,1024],
        'custom' => [1366,1366],
        'custom' => [1440,1440],
        'full' => [1920,580],
    );
    public function __construct(string $upload_directory,string $webp_directory, SluggerInterface $slugger, ImagesRepository $ImagesRepository, EntityManagerInterface $em)
    {
        $this->slugger = $slugger;
        $this->images_reposotory = $ImagesRepository;
        $this->em = $em;
        $this->upload_directory = $upload_directory;
        $this->webp_directory = $webp_directory;
        $this->allowed_files=array('jpg', 'jpeg', 'gif', 'png', 'pdf','mp4','svg');
        $this->imagine = new Imagine();
    }
    /**
     * upload une seul images
     */
    public function uploadImage($file)
    {
        $filesystem = new Filesystem();
        $date = new \DateTime();
        $webPath =$this->webp_directory;
        $build_directory = $this->upload_directory . '/' . $date->format('Y') . '/' . $date->format('m');
        $dossier = $webPath . '/' . $date->format('Y') . '/' . $date->format('m');
        //$url_images = 'http://localhost:7500/uploads/'. $date->format('Y') . '/' . $date->format('m');
        $filesystem->mkdir($build_directory, 0777);
        $filesystem->mkdir($dossier, 0777);
        $brochureFile = $file;
        $image = null;
        $file_size = $brochureFile->getSize()/(1024*1024);
        $extension = $brochureFile->guessExtension();
        $mimeType  = $brochureFile->getMimeType();
        if ($brochureFile && in_array($extension,$this->allowed_files) && $file_size<=40) {
            
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = substr($this->slugger->slug($originalFilename), 0, 254);

            $fileName = $this->imageUniqueName($extension,$safeFilename);

            try {
                $brochureFile->move(
                    $build_directory,
                    $fileName
                );
                $file_path = $build_directory.'/'.$fileName;
                
                //  generate web image
                $destination = str_replace(['uploads','.'.$extension],['webp','.webp'],$file_path);
                WebPConvert::convert($file_path, $destination, []);
                $build_file_path = $build_directory.'/'.$fileName;
                //list($iwidth, $iheight) = getimagesize($file_path);
                $sizes = $this->getImageSizetwig($file_path);
                //$filesystem->copy($file_path,$build_file_path );
                $data = $this->resize($file_path,$extension,$fileName);
                //$this->resize($build_file_path);
                $image = $this->createNewImages($safeFilename, $fileName,null,"",$sizes,$data,$mimeType);
            } catch (FileException $e) {

            }
        }
        return $image;
    }
    public function getImageSizetwig($path)
    {
        if (str_ends_with($path, '.svg')) {
            $xml = @simplexml_load_file($path);
            if($xml){
                $attr = $xml->attributes();
                $width = 50;$height=50;
                foreach($attr as $a => $b) {
                    if($a=="width"){
                        $width = (float)$b;
                    }elseif($a=="height"){
                        $height = (float)$b;
                    }
                }
                return array($width, $height);
            } else {
                return array(50, 50);
            }
        } else {
            list($width, $height, $type, $attr) = @getimagesize($path);
            return array($width, $height);
        }
        //return array('', '');
    }
    /**
     * upload multiples images
     */
    public function uploadMultipleImage($file, $directory)
    {
        $imageUrl = "";
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $directory,
                    $fileName
                );
            } catch (FileException $e) {
            }
            $imageUrl = $fileName;
        }
        return $imageUrl;
    }
    /**
     * get upload images directory
     */
    public function getUploadDirectory()
    {
        return $this->upload_directory;
    }
    /**
     * check if name image already existe
     */
    public function imageUniqueName($extension,$file_name)
    {
        $index = 1 ;
        $fileName = $this->slugger->slug($file_name) . '.' . $extension;
        $is_existe = $this->images_reposotory->findOneBy(array('url_image' => $fileName));
        if(!empty($is_existe)) {
            while ($is_existe) :
                $fileName = $this->slugger->slug($file_name.'-'.$index) . '.' . $extension;
                $is_existe = $this->images_reposotory->findOneBy(array('url_image' => $fileName));
                $index = $index + 1;
            endwhile;
        }
        return $fileName;
    }
    /**
     * public create new Images
     */
    public function createNewImages($safeFilename, $fileName,$date_insert_image=null, $alt="",$size=array(0,0),$data=array(),$mimeType="")
    { 
        $image = new Images();
        $image->setNameImage($safeFilename);
        $image->setUrlImage($fileName);
        $image->setAltImage($alt);
        $image->setMimeType($mimeType);
        $image->setData(json_encode($data));
        if(!is_null($date_insert_image)){
            $d = \DateTime::createFromFormat('Y-m-d', $date_insert_image);
            $date = $d && $d->format('Y-m-d') === $date_insert_image?new \DateTime($date_insert_image):new \DateTime();
            $image->setDateAdd($date);
        }else{
            $image->setDateAdd(new \DateTime());
        }
        $image->setDateUpdate(new \DateTime());
        $image->setHeight($size[1]);
        $image->setWidth($size[0]);
        $this->em->persist($image);
        $this->em->flush();
        return $image;
    }
    
    public function get_absolute_url_image($images)
    {

        if ($images instanceof Images) {
            $date_add = $images->getDateAdd();
            $url_image = $date_add->format('Y') . '/' . $date_add->format('m') . '/' . $images->getUrlImage();
            return $url_image;
        }
        return '';
    }
    /**
     * resize file name
     */
    public function resize(string $filename,string $extension,string $name)
    {
        $data = ['webp'=>[],'img'=>[]];
        foreach(self::images_sizes as $key=>$size){
            //dump($size);exit;
            $width = $size[0];
            $height = $size[1];
            list($iwidth, $iheight) = getimagesize($filename);
            if($iheight && $iwidth):
                if($iwidth >= $width){
                    $ratio = $iwidth / $iheight;
                    if ($width / $height > $ratio) {
                        $width = $height * $ratio;
                    } else {
                        $height = $width / $ratio;
                    }
                    $photo = $this->imagine->open($filename);
                    $new_filename = explode('.',$filename);
                    $new_name = explode('.',$name);
                    $new_name[0] = $new_name[0].'-'.$key.'-'. ceil($width).'-'.ceil($height);
                    $new_name = implode('.',$new_name);
                    $data['img'][]=['width'=>$width,'height'=>$height,'file'=>$new_name];
                    $new_filename[count($new_filename)-2]=$new_filename[count($new_filename)-2].'-'.$key.'-'. ceil($width).'-'.ceil($height);
                    $new_filename = implode('.',$new_filename);
                    $photo->resize(new Box($width, $height))->save($new_filename, [
                        'png_compression_level' => 0,
                        'jpeg_quality' => 100,
                        'webp_quality' => 100,
                    ]);
                    //  generate web image
                    $destination = str_replace(['uploads','.'.$extension],['webp','.webp'],$new_filename);
                    $data['webp'][]=['width'=>$width,'height'=>$height,'file'=>str_replace(['.'.$extension],['.webp'],$new_name)];
                    WebPConvert::convert($new_filename, $destination, []);
                }
            endif;
        }
        return $data;
    }

    /**
     * remove form directory
     */
    public function removeFromDirectory($image)
    {
        $fileSystem = new Filesystem();

        $url_image = $this->get_absolute_url_image($image);

        if ($url_image !== '')
            $fileSystem->remove($this->getUploadDirectory() . '/' . $url_image);
    }
    /**
     * get sizes
     */
    public static function getAllSizes(){
        return self::images_sizes;
    }
}
