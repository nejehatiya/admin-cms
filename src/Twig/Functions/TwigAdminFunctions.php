<?php
namespace App\Twig\Functions;
use App\Entity\Post;
use App\Entity\User;
use Twig\TwigFilter;
use Twig\Environment;
use Twig\TwigFunction;
use App\Entity\PostType;
use App\Entity\Taxonomy;
use App\Entity\{Images,ModelesPost};
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TwigAdminFunctions extends AbstractExtension
{
    private $em;
    private $assets_front_directory;
    private $filesystem;
    private $security;
    private $urlGenerator;
    private $authorization;
    private $router;
    public function __construct(EntityManagerInterface $em,RouterInterface $router,AuthorizationCheckerInterface $authorization,string $assets_front_directory,Security $security,UrlGeneratorInterface $urlGenerator) {
        $this->em = $em;
        $this->assets_front_directory = $assets_front_directory;
        $this->filesystem = new Filesystem();
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->authorization = $authorization;
        $this->router = $router;
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

            new TwigFunction('getParentName', [$this, 'getParentName']),
            new TwigFunction('fetchMenuHearchy', [$this, 'fetchMenuHearchy']),
            new TwigFunction('getUniqueRef', [$this, 'getUniqueRef']),
            new TwigFunction('loadCssModalsWithParentClass', [$this, 'loadCssModalsWithParentClass']),
            new TwigFunction('getModeleNameById', [$this, 'getModeleNameById']),
            new TwigFunction('timeAgo', [$this, 'timeAgo']),
            
            new TwigFunction('getPostTypeList', [$this, 'getPostTypeList']),
            new TwigFunction('getPostTypeTaxonomyList', [$this, 'getPostTypeTaxonomyList']),
            new TwigFunction('getAllowedRoutes', [$this, 'getAllowedRoutes']),
            
            new TwigFunction('checkMenuOpen', [$this, 'checkMenuOpen']),
            new TwigFunction('userHasAccess', [$this, 'userHasAccess']),
            new TwigFunction('getPageParentName', [$this, 'getPageParentName']),
            new TwigFunction('getUserCreator', [$this, 'getUserCreator']),
            new TwigFunction('getPostTerms', [$this, 'getPostTerms']),
        ];
    }
    /**
     * get time ago 
     */
    public function timeAgo(\DateTime $dateTime)
    {
        $now = new \DateTime();
        $diff = $now->diff($dateTime);

        if ($diff->y > 0) {
            return $diff->y . ' ans' . ($diff->y > 1 ? 's' : '');
        }
        if ($diff->m > 0) {
            return $diff->m . ' moi' . ($diff->m > 1 ? 's' : '');
        }
        if ($diff->d > 0) {
            return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        }
        if ($diff->h > 0) {
            return $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        }
        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        }
        return 'just now';
    }
    // get images
    public function getImages($id,$is_background=false){
        if(!(int)$id){
            return "";
        }
        $image = $this->em->getRepository(Images::class)->find($id);
        $url="";
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
        return $is_background?$url:$html;
    }
    // get images url
    public static function getImagesUrl($image,$width = 0){
        $url = "";
        if($image instanceof Images){
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
        }else if(is_array($image)){
            $date  =  new \DateTime($image['date_add']);
            $base_img = "/uploads/". $date->format('Y') . '/' . $date->format('m').'/';
            $image_name = $image['url_image'];
            if($width){
                $data = json_decode($image['data'],true);
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
    public function imageMediaSelect($selector_class,$multiple,$max_select,$selected=null,$is_post=false,$params=array()){
        // traitement image
        $value= '';
        $preview ="";
        if($selected instanceof Images || (int)$selected){
            $selected = $selected instanceof Images ? $selected : $this->em->getRepository(Images::class)->find((int)$selected);
            
            if(!$is_post){
                $preview = "<img src='".$this->getImagesUrl($selected,360)."' />";
            }else{
                $preview = $this->getImagesUrl($selected,360);
            }
            $value= $selected->getId();
        }
        ob_start();
        if($is_post){
        ?>
            <div class="elementor-control elementor-control-image elementor-control-type-media elementor-label-block elementor-control-separator-default elementor-control-dynamic">
                <div class="elementor-control-content">
                    <div class="elementor-control-field elementor-control-media">
                        <label class="elementor-control-title">
                            <?php
                            if(array_key_exists('titre_label',$params)){
                                echo $params['titre_label'];
                            }else{
                                echo "Choisissez feature  image";
                            }
                            ?>
                        </label>
                        <div class="elementor-control-input-wrapper open-media-action">
                            <div class="elementor-control-media__content elementor-control-tag-area elementor-control-preview-area">
                            <div class="elementor-control-media-area">
                                <div class="elementor-control-media__remove elementor-control-media__content__remove" title="Retirer">
                                    <i class="eicon-trash-o" aria-hidden="true"></i>
                                    <span class="elementor-screen-only">Retirer</span>
                                </div>
                                <div class="elementor-control-media__preview" style="background-image: url(<?php echo strlen($preview)?$preview:'/assets/images/placeholder.png' ?>);"></div>
                            </div>
                            <div class="elementor-control-media-upload-button elementor-control-media__content__upload-button">
                                <i class="eicon-plus-circle" aria-hidden="true"></i>
                                <span class="elementor-screen-only">Ajouter</span>
                            </div>
                            <div class="elementor-control-media__tools elementor-control-dynamic-switcher-wrapper">
                                <div class="elementor-control-media__tool elementor-control-media__replace" data-media-type="image">Choisissez une image</div>
                                <button class="elementor-control-dynamic-switcher elementor-control-unit-1" data-tooltip="Balises dynamiques" original-title="">
                                    <i class="eicon-database" aria-hidden="true"></i>
                                    <span class="elementor-screen-only">Balises dynamiques</span>
                                </button>
                            </div>
                            </div>
                        </div>
                        <input data-var-name="<?php if(array_key_exists('var_name',$params)){ echo $params['var_name']; }?>"  type="hidden" data-setting="image" class="media-selected <?php echo $selector_class; ?>" value="<?php echo $value; ?>" data-multiple="<?php echo $multiple; ?>" data-max-select="<?php echo $max_select; ?>">    
                    </div>
                </div>
            </div>
        <?php
        }else{
        ?>
            <a href="#" class="open-media-action page-title-action  aria-button-if-js" role="button" aria-expanded="true">Ajouter un fichier média</a>
            <div class="previewimage">
                <?php echo $preview; ?>
            </div>
            <input type="hidden" class="media-selected <?php echo $selector_class; ?>" value="<?php echo $value; ?>" data-multiple="<?php echo $multiple; ?>" data-max-select="<?php echo $max_select; ?>">
        <?php
        }
        return ob_get_clean();
    }

    // getParentName
    public function getParentName($id,$entity_name,$field_to_display){
        $class = 'APP\\Entity\\'.$entity_name;
        $query= $this->em->createQueryBuilder()
        ->select('t.'.$field_to_display)
        ->from($class,'t')
        ->andWhere('t.id = :val')
        ->setParameter('val', $id);
        $query=$query->getQuery()->getResult();
        return $query;
    }

    // fetche menu hearchy
    public function fetchMenuHearchy($json, $parent_id = 0, $level = 0) {
        $flatArray = [];
    
        foreach ($json as $item) {
            $item['level'] = $level;
            $item['parent'] = $parent_id;
            $flatArray[] = $item;
    
            if (!empty($item['child'])) {
                $flatArray = array_merge($flatArray, $this->fetchMenuHearchy($item['child'], $item['id'], $level + 1));
            }
            
            // Remove the 'child' element to keep the final structure flat
            unset($flatArray[count($flatArray) - 1]['child']);
        }
    
        return $flatArray;
    }

    // get unique ref
    public function getUniqueRef(){
        return strtotime("now");
    }
    // 
    public function loadCssModalsWithParentClass($class_sortable=""){
        // Nom du fichier CSS
        if($class_sortable == null || !strlen($class_sortable)){
            return "";
        }
        // check if file already exists
        $cssFile =  $this->assets_front_directory.'css/modeles/'.$class_sortable.'.css';
        if(!$this->filesystem->exists($cssFile)){
            return "";
        }
        // Lire le contenu du fichier CSS
        $cssContent = file_get_contents($cssFile);

        // Définir le sélecteur parent
        $parentSelector = '.preview-modal-list';

        $pattern = '/(^|\})\s*([^{]+)/';
        $replacement = function ($matches) use ($parentSelector) {
            $selectors = explode(',', $matches[2]);
            foreach ($selectors as &$selector) {
                $selector = trim($parentSelector . ' ' . trim($selector));
            }
            return $matches[1] . ' ' . implode(', ', $selectors);
        };
        
        $newCssContent = preg_replace_callback($pattern, $replacement, $cssContent);

        // Servir le CSS modifié
        return  '<style type="text/css">'.$newCssContent.'</style>';
    }
    // 
    public function getModeleNameById($id){
        $modele = $this->em->getRepository(ModelesPost::class)->find($id);
        if(!empty($modele)){
            return $modele->getNameModele();
        }
        return "";
    }
    // get post tist to show in sidebar menu
    public function getPostTypeList(){
        $post_type_list = $this->em->getRepository(PostType::class)->findSidebarList();
        return $post_type_list;
    }

    // get taxomy post list
    public function getPostTypeTaxonomyList($slug_post_type){
        $taxonomy_post_type = $this->em->getRepository(Taxonomy::class)->findByPostType($slug_post_type);
        return $taxonomy_post_type;
    }

    // get Allowed Routes for user
    public function getAllowedRoutes(){
        //var_dump($this->security->getUser());exit;
        /**
         * get user 
         */
        $user = $this->security->getUser();
        /**
         * init allowed routes list
         */
        $routes_allowed = array();
        if ($user) {
            /**
             * get roles user 
             */
            $roles = $user->getRolesUser();
            foreach ($roles as $role) {
                //var_dump($role->getRoutes());
                /**
                 * get routes allowed to user
                 */
                $routes = $role->getRoutes();
                foreach ($routes as $route) {
                    /**
                     * collect allowed routes
                     */
                    $routes_allowed[] = $route->getPath();
                }
            }
        }
        //var_dump($routes_allowed);exit;
        return $routes_allowed;
    }

    // public function check menu open and role is exist
    public function checkMenuOpen($current_path,$allowed_root,$key){

        if($key=="Developeur"){
            $list_item = [
                'app_modeles_post_index',
                'app_modeles_post_new',
                'app_modeles_post_edit',
                'app_post_type_index',
                'app_post_type_new',
                'app_post_type_edit',
                'app_template_page_index',
                'app_template_page_new',
                'app_template_page_edit',
                'app_post_meta_fields_index',
                'app_post_meta_fields_edit',
                'app_post_meta_fields_new',
                'app_taxonomy_index',
                'app_emplacement_index',
                'app_emplacement_new',
                'app_emplacement_edit',
            ];
            $is_current = in_array($current_path,$list_item);
            return $is_current;
        }
        exit;
        return false;
    }

    // public function check user can
    public function userHasAccess($current_path,$allowed_root,$key,$patern="",$value=""){
        
        $user = $this->security->getUser();
        $pattern = $this->parsePatternFromUrl($key);
        $check_pattern = array_filter($allowed_root,function($ele) use($pattern,$key){
            return preg_match($pattern, $ele) == 1 || $key == $ele;
        });
        if(is_array($patern) && is_array($value)){
            foreach($patern as $k=>$v){
                $key = str_replace('/'.$value[$k],'/'.$v,$key);
            }
        }elseif(strlen($patern)){
            $key = str_replace($value,$patern,$key);
        }
        //var_dump($check_pattern);
        if(in_array($key,$allowed_root) || !empty($check_pattern) || in_array('ROLE_SUPER_ADMIN',$user->getRoles())) {
            return true;
        }
        return false;
    }

    public function parsePatternFromUrl($url) {
        // Split the URL into components
        $urlComponents = explode('/', trim($url, '/'));
        
        // Base pattern
        $pattern = '/^';
        
        // Iterate through the components
        foreach ($urlComponents as $component) {
            if (is_numeric($component)) {
                $pattern .= '\/[0-9]+'; // Replace numeric components with [0-9]+
            } elseif ($component === 'edit') {
                $pattern .= '\/edit'; // Literal 'edit'
            } else {
                $pattern .= '\/' . preg_quote($component, '/'); // Escape other components
            }
        }
        
        // Close the pattern
        $pattern .= '$/';
        
        return $pattern;
    }

    public function isValidUrl($url, $pattern) {
        // Use preg_match to check if the URL matches the pattern
        return preg_match($pattern, $url) === 1;
    }
    // get page parent name
    public function getPageParentName($id_parent){
        if((int)$id_parent){
            $post_parent =  $this->em->getRepository(Post::class)->find($id_parent);
            if(!empty($post_parent)){
                return $post_parent->getPostTitle();
            }
        }
        return '-';
    }

    // get user email creator
    public function getUserCreator($id_user){
        if(is_array($id_user) && array_key_exists('id',$id_user)){
            $user =  $this->em->getRepository(User::class)->find($id_user["id"]);
            if(!empty($user)){
                return $user->getFirstName().' '.$user->getLastName();
            }
        }
        return '-';
    }
    // get post terms
    public function getPostTerms($id_post){
        $terms_string = [];
        if((int)$id_post){
            $post =  $this->em->getRepository(Post::class)->find($id_post);
            if(!empty($post)){
                $terms = $post->getTerms();
                foreach($terms as $term){
                    $terms_string[]=$term->getNameTerms();
                }
                return !empty($terms_string)?implode(',',$terms_string):'-';
            }
        }
        return '-';
    }
}