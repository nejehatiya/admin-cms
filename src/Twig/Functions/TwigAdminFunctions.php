<?php
namespace App\Twig\Functions;
use App\Entity\Images;
use Twig\TwigFilter;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\ORM\EntityManagerInterface;
class TwigAdminFunctions extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function getFilters()
    {
        return [
        ];
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('getImages', [$this, 'getImages']),
            new TwigFunction('getImagesUrl', [$this, 'getImagesUrl']),
            new TwigFunction('imageMediaSelect', [$this, 'imageMediaSelect']),
        ];
    }

    public function getImages($id){
        $image = $this->em->getRepository(Images::class)->find($id);
        $html = "<picture>";
        if($image){
            $date  =  $image->getDateAdd();
            $base_webp = "/webp/". $date->format('Y') . '/' . $date->format('m').'/';
            $base_img = "/uploads/". $date->format('Y') . '/' . $date->format('m').'/';
            $data = json_decode($image->getData(),true);
            $srcset_webp = [];
            $sizes_webp = [];
            $srcset_img = [];
            $sizes_img = [];
            if($data){
                foreach($data['webp'] as $item){
                    $srcset_webp[] = $base_webp.$item['file'].' '.$item['width'].'w';
                    $sizes_webp[] = "(max-width: ".$item['width']."px) ".$item['width']."px";
                }

                foreach($data['img'] as $item){
                    $srcset_img[] = $base_img.$item['file'].' '.$item['width'].'w';
                    $sizes_img[] = "(max-width: ".$item['width']."px) ".$item['width']."px";
                }
                $html .= '<source type="image/webp" srcset="'.implode(',',$srcset_webp).'" sizes="'.implode(',',$sizes_webp).'" />';
                $html .= '<source type="'.$image->getMimeType().'" srcset="'.implode(',',$srcset_img).'" sizes="'.implode(',',$sizes_img).'" />';
            }
            
            $image_name = $image->getUrlImage();
            $new_name = explode('.',$image_name);
            $new_name[1] = 'webp';
            $new_name = implode('.',$new_name);
            $url = $base_webp.$new_name;
            $html .= '<img decoding="async" width="'.$image->getWidth().'" height="'.$image->getHeight().'"  srcset="'.implode(',',$srcset_webp).'" sizes="'.implode(',',$sizes_webp).'" src="'.$url.'" loading="lazy" alt="'.$image->getAltImage().'" />';
        }
        $html .= "</picture>";
        return $html;
    }

    public function getImagesUrl($image,$width = 0){
        $url = "";
        if($image){
            $date  =  $image->getDateAdd();
            $base_img = "/uploads/". $date->format('Y') . '/' . $date->format('m').'/';
            $image_name = $image->getUrlImage();
            if($width){
                $data = json_decode($image->getData(),true);
                if($data){
                    foreach($data['img'] as $item){
                        if((int)$item['width'] == $width){
                            $image_name = $item['file'];
                        }
                    }
                }
            }
            $url = $base_img.$image_name;
        }
        return $url;
    }

    /***
     * get media button 
     */
    public function imageMediaSelect($selector_class,$multiple,$max_select,$selected=null){
        // traitement image
        $value= '';
        $preview ="";
        if($selected instanceof Images){
            $preview = "<img src='".$this->getImagesUrl($selected,360)."' />";
            $value= $selected->getId();
        }
        ob_start();
        ?>
            <a href="#" class="open-media-action page-title-action  aria-button-if-js" role="button" aria-expanded="true">Ajouter un fichier média</a>
            <div class="previewimage">
                <?php echo $preview; ?>
            </div>
            <input type="hidden" class="media-selected <?php echo $selector_class; ?>" value="<?php echo $value; ?>" data-multiple="<?php echo $multiple; ?>" data-max-select="<?php echo $max_select; ?>">
        <?php
        return ob_get_clean();
    }
}
