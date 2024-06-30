<?php

namespace App\Service;
use App\Service\Links;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\PostAnalyse;

use App\Twig\AppExtension; 
use App\Repository\PostRepository;
use App\Repository\PostTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\PostEditibaleRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Controller\Admin\PostMetaKeysDb\PostMetaKeysDb;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class AnalyseService  extends AbstractController {

    public $post_type_repos;
    public $post_repo;
    public $get_post_permalink ;
    public $post_editibale_repo;
    public $em;
    public $twig;
    public $formFactory;
    public $requestStack;
    public $links_service;
    private $serializer;
    public $current_themes;
    public function __construct(PostTypeRepository $postTypeRepository,SerializerInterface $serializer, PostRepository $PostRepository,Links $links_service,
    PostEditibaleRepository $post_editibale_repo,string $current_themes="",EntityManagerInterface $em
    ){
        $this->post_type_repos = $postTypeRepository;
        $this->post_repo = $PostRepository;
        $this->links_service = $links_service;
        $this->post_editibale_repo =$post_editibale_repo;
        $this->current_themes=$current_themes;
        $this->serializer = $serializer;
        $this->em = $em;
    }
    

    public function traitementAnalysePost($id,$post_type,$modeles_content){
        $urls_list=array();
        $urls_list_pages=array();
        $msg='';
        $states=true;
        $url_img= array();
        $base_url = "";
        $base_url = str_ends_with($base_url,'/')?substr($base_url, 0, -1):$base_url;
        $url_post = "";
        $status = 'valider';
        $all_url_list = array();
        if(strlen($modeles_content)){
            $order_content_array = json_decode($modeles_content, true);
            $traitement_html = $this->traitementHtml($post_type,$modeles_content,$url_post,$base_url);
            $analyse_html = $this->renderView('admin/global/analyse/analyse.html.twig',$traitement_html);
            return $analyse_html;
        }
        /**
         * 1 - get post by id_post_type et index page
         */
        $check_post =  $this->post_repo->findOneBy(["id"=>$id]);
        $wordCount = $ratio = 0;
        $code_reponse = false; 
        if(!empty($check_post)){
            $url_post = $this->getPostLink($check_post,$base_url);
            $code_reponse = @get_headers($url_post);
            $code_reponse = is_array($code_reponse) && array_key_exists(0,$code_reponse) ? $code_reponse[0] : json_encode($code_reponse);
            $posttype = $check_post->getPostType()->getSlugPostType();
            $json_order_content = $check_post->getPostOrderContent();
            $idPost=  $check_post->getId();
            $name_post=  $check_post->getPostTitle();
            $posttypeobj = $check_post->getPostType();
        
            if(!empty($json_order_content)) {
                $order_content_array = json_decode($json_order_content, true);
                $html = $this->gethtml($check_post, $order_content_array,$url_post,$base_url,$post_type);
                   
                if (!empty($html )){
                    $dom = new \DOMDocument('1.0', 'utf-8');
                    // Create a DOMXPath object

                    try {
                        libxml_use_internal_errors(true);
                        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                    } catch (Exception $e) {  //Exeception typof ErrorException
                        return $html;
                    }
                    $dom2 =  $dom;
                    $result =$this->testHeadingStructure($html);
                    $msg=$result;
                    if (!$result["status"]){
                        $status = 'non valide';
                    }
                    //$dom->preserveWhiteSpace = false;
                    
                    $images = $dom->getElementsByTagName('img');
                    $hrefs = $dom->getElementsByTagName('a');
                    // test url structure
                    foreach ($hrefs as $href) {

                        $url = $href->getAttribute('href');
                        $all_url_list[]=$url;
                        if(!empty($url)) { 
                            $headers = @get_headers($url);
                            // url contains espace ou url 404 ou avec redirection ou ne termine pas avec /
                            $option=['url'=>$url,'prob'=>'','status'=>is_array($headers) && array_key_exists(0,$headers) ? $headers[0] : $headers ];
                            if(count(explode(' ',$url)) > 1 && strrpos( $url, ' ') !== false){
                                $option['prob'] .= "l'url contient un  espace,";
                            }
                            if(( $headers && strrpos( $headers[0], '301') !== false )){
                                $option['prob'] .= "redirection 301,";
                            }else if(( $headers && strrpos( $headers[0], '302') !== false )){
                                $option['prob'] .= "redirection 302,";
                            }else if(( $headers && strrpos( $headers[0], '40') !== false )){
                                $option['prob'] .= "url n'existe pas,";
                            }
                            if(str_ends_with($url,'//') ) {
                                $option['prob'] .= "double /,";
                            }
                            if(str_starts_with($url,'http://www.metal2000.fr')  ) {
                                $option['prob'] .= "url sans https,";
                            }
                            if(str_starts_with($url,'https://metal2000.fr')  ) {
                                $option['prob'] .= "url sans www,";
                            }
                            if (preg_match('/[A-ZÀ-ÖØ-Þ]/u', $url) && str_contains($url,$base_url) &&  !str_contains($url,'.pdf') && !str_contains($url,'/build/')) {
                                $option['prob'] .= "caractères en majuscules ou des lettres accentuées,";
                            }
                            if(!str_ends_with($url,'/') && str_contains($url,$base_url) &&  !str_contains($url,'.pdf') && !str_contains($url,'/build/')) {
                                $option['prob'] .= "absence de  / a la fin d'url";
                            }
                            if(!str_starts_with($url, $base_url) &&   !str_contains($url,'tel:') && !str_contains($url,'mailto:')  ) {
                                $parsed_url = parse_url($url);
                                if(array_key_exists('host',$parsed_url) &&!strlen($parsed_url['host'])  || !array_key_exists('host',$parsed_url) ){
                                    $option['prob'] .= "absence de nom de domaines";
                                }
                            }
                            if(strlen($option['prob'])){
                                $status = 'non valide';
                            }
                            array_push($urls_list, $option);
                        }
                    }
    
                    foreach ($images as $image) {
                        $src = $image->getAttribute('src');
                        $alt = $image->getAttribute('alt');  
                        $src = str_starts_with($src,'/build')?$base_url.$src:$src;   
                        $all_url_list[]=$src;
                        if (!empty($src)) {  
                            $headers = @get_headers($src);  
                            if($src && strpos( $src, '200') == false) { 
                                $option=['url'=>$src,'prob'=> "URL n'exist pas"];
                                array_push($url_img, $option);
                                $status = 'non valide';
                            }
                            if (empty($alt)) {    
                                $option=['url'=>$src,'prob'=> "absence de alt"];
                                array_push($url_img, $option);
                                $status = 'non valide';
                            }  
                        }
                        
                    }
                    $dom2 = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $dom2->loadHTML($html);
                    libxml_clear_errors();
                    $xpath = new \DOMXPath($dom2 );

                    // Extract text content from the HTML document
                    $text_length = $xpath->query('//text()');
                    for($i=0;$i<$text_length->length;$i++){

                        // Count the total number of words
                        $wordCount += str_word_count($xpath->query('//text()')->item($i)->nodeValue);
                    }
                    // Count the total number of HTML tags
                    $tagCount = $dom2->getElementsByTagName('*')->length;
                    // Calculate the word-to-HTML-tag ratio
                    if ($tagCount > 0) {
                        $ratio = $wordCount / $tagCount;
                    } else {
                        $ratio = 0; // Avoid division by zero
                    }
                }
            }
        }
      
        //$entityManager = $this->getDoctrine()->getManager();
        $postAnalyse = $this->em->getRepository(PostAnalyse::class)
            ->findOneBy(['post'=>$idPost]);
        //add
        if(empty($postAnalyse)){
            $postAnalyse = new PostAnalyse(); 
        }
        $postAnalyse->setALink(json_encode($urls_list));
        $postAnalyse->setImgSrc(json_encode($url_img));
        $postAnalyse->setALinkText(implode(',',$all_url_list));
        $post_editibale=$this->post_editibale_repo->findOneBy(array('post_id'=>$idPost));
        $postAnalyse->setUser( $this->getUser()); 
        $postAnalyse->setDateUpd(new \DateTime());
        $postAnalyse->setPost($check_post);
        $postAnalyse->setStates($status);
        $postAnalyse->setHHeading(json_encode($msg));
        $postAnalyse->setRatioHtml($ratio);
        $postAnalyse->setCountWord($wordCount);
        $postAnalyse->setPostType($posttypeobj);
        $postAnalyse->setCodeReponse($code_reponse);
        $this->em->persist($postAnalyse);
        $this->em->flush();

        $option_pages=['urls_list'=> $urls_list, 'id'=>$idPost,'name'=>$name_post, "post_type"=>$posttype];
        //array_push($urls_list_pages, $option_pages);
        return($option_pages);

    }
    

    /**
     * traitemnt html function
     */
    public function traitementHtml($post_type,$json_order_content,$url_post,$base_url){
        $urls_list=array();
        $urls_list_pages=array();
        $msg='';
        $states=true;
        $url_img= array();
        $base_url = "";
        $base_url = str_ends_with($base_url,'/')?substr($base_url, 0, -1):$base_url;
        $url_post = "";
        $status = 'valider';
        $all_url_list = array();
        $wordCount = $ratio = 0;
        $code_reponse = false; 
        if(!empty($json_order_content)) {
            $order_content_array = json_decode($json_order_content, true);
            $html = $this->gethtml(null, $order_content_array,$url_post,$base_url,$post_type);
               
            if (!empty($html )){
                $dom = new \DOMDocument('1.0', 'utf-8');
                // Create a DOMXPath object

                try {
                    libxml_use_internal_errors(true);
                    $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                } catch (Exception $e) {  //Exeception typof ErrorException
                    return $html;
                }
                $dom2 =  $dom;
                $result =$this->testHeadingStructure($html);
                $msg=$result;
                if (!$result["status"]){
                    $status = 'non valide';
                }
                //$dom->preserveWhiteSpace = false;
                
                $images = $dom->getElementsByTagName('img');
                $hrefs = $dom->getElementsByTagName('a');
                // test url structure
                foreach ($hrefs as $href) {

                    $url = $href->getAttribute('href');
                    $all_url_list[]=$url;
                    if(!empty($url)) { 
                        $headers = @get_headers($url);
                        // url contains espace ou url 404 ou avec redirection ou ne termine pas avec /
                        $option=['url'=>$url,'prob'=>'','status'=>is_array($headers) && array_key_exists(0,$headers) ? $headers[0] : $headers ];
                        if(count(explode(' ',$url)) > 1 && strrpos( $url, ' ') !== false){
                            $option['prob'] .= "l'url contient un  espace,";
                        }
                        if(( $headers && strrpos( $headers[0], '301') !== false )){
                            $option['prob'] .= "redirection 301,";
                        }else if(( $headers && strrpos( $headers[0], '302') !== false )){
                            $option['prob'] .= "redirection 302,";
                        }else if(( $headers && strrpos( $headers[0], '40') !== false )){
                            $option['prob'] .= "url n'existe pas,";
                        }
                        if(str_ends_with($url,'//') ) {
                            $option['prob'] .= "double /,";
                        }
                        if(str_starts_with($url,'http://www.metal2000.fr')  ) {
                            $option['prob'] .= "url sans https,";
                        }
                        if(str_starts_with($url,'https://metal2000.fr')  ) {
                            $option['prob'] .= "url sans www,";
                        }
                        if (preg_match('/[A-ZÀ-ÖØ-Þ]/u', $url) && str_contains($url,$base_url) &&  !str_contains($url,'.pdf') && !str_contains($url,'/build/')) {
                            $option['prob'] .= "caractères en majuscules ou des lettres accentuées,";
                        }
                        if(!str_ends_with($url,'/') && str_contains($url,$base_url) &&  !str_contains($url,'.pdf') && !str_contains($url,'/build/')) {
                            $option['prob'] .= "absence de  / a la fin d'url";
                        }
                        if(!str_starts_with($url, $base_url) &&   !str_contains($url,'tel:') && !str_contains($url,'mailto:')  ) {
                            $parsed_url = parse_url($url);
                            if(array_key_exists('host',$parsed_url) &&!strlen($parsed_url['host'])  || !array_key_exists('host',$parsed_url) ){
                                $option['prob'] .= "absence de nom de domaines";
                            }
                        }
                        if(strlen($option['prob'])){
                            $status = 'non valide';
                        }
                        array_push($urls_list, $option);
                    }
                }

                foreach ($images as $image) {
                    $src = $image->getAttribute('src');
                    $alt = $image->getAttribute('alt');  
                    $src = str_starts_with($src,'/build')?$base_url.$src:$src;   
                    $all_url_list[]=$src;
                    if (!empty($src)) {  
                        $headers = @get_headers($src);  
                        if($src && strpos( $src, '200') == false) { 
                            $option=['url'=>$src,'prob'=> "URL n'exist pas"];
                            array_push($url_img, $option);
                            $status = 'non valide';
                        }
                        if (empty($alt)) {    
                            $option=['url'=>$src,'prob'=> "absence de alt"];
                            array_push($url_img, $option);
                            $status = 'non valide';
                        }  
                    }
                    
                }
                $dom2 = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom2->loadHTML($html);
                libxml_clear_errors();
                $xpath = new \DOMXPath($dom2 );

                // Extract text content from the HTML document
                $text_length = $xpath->query('//text()');
                for($i=0;$i<$text_length->length;$i++){

                    // Count the total number of words
                    $wordCount += str_word_count($xpath->query('//text()')->item($i)->nodeValue);
                }
                // Count the total number of HTML tags
                $tagCount = $dom2->getElementsByTagName('*')->length;
                // Calculate the word-to-HTML-tag ratio
                if ($tagCount > 0) {
                    $ratio = $wordCount / $tagCount;
                } else {
                    $ratio = 0; // Avoid division by zero
                }
            }
        }
        return array(
            'urls_list'=>$urls_list,
            'url_img'=>$url_img,
            'all_url_list'=>$all_url_list,
            'headeing'=>$msg,
            'ratio'=>$ratio,
            'wordCount'=>$wordCount,
        );
    }
    /**
     * get link of post
     * {$post}
     */
    public function getPostLink($post,$base_url=""){
        return "/tetss";
    }
    public function gethtml($post, $json_order_content,$url_post,$base_url,$post_type)
    {
        /*if($post->getPostName()=="accueil")
            $html= $this->renderView('front/'.$this->current_themes.'global/content-page.html.twig',['home_page_post'=>$post,'form'=>$this->createForm(ContactType::class, new Contact())->createView(),'json_order_content'=>$json_order_content,'meta_keys' => $this->meta_keys,"base_url"=>$base_url,"url_post"=>$url_post]);
        else*/
        // serialize post
        $post = json_decode($this->serializer->serialize($post, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        $html = $this->renderView('front/'.$this->current_themes.'/global/content-page.html.twig',['post'=>$post,'json_order_content'=>$json_order_content,'meta_keys' => [],"base_url"=>$base_url,"url_post"=>$url_post,"post_type"=>$post_type]);            
        return $html;
    }

    public function testHeadingStructure($html) {
        $status = true;
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        
        $xpath = new \DOMXPath($dom);
        
        $headings = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
        
        $hierarchy = [];

        foreach ($headings as $heading) {
            $hierarchy[]= $heading->tagName;
        }
        for($i=0;$i<count($hierarchy);$i++){
            if($i!=count($hierarchy)-1){
                $level = (int)substr($hierarchy[$i], 1);
                $level_next = (int)substr($hierarchy[$i+1], 1);
                if(!( $level+1 == $level_next || $level-1 == $level_next || $level == $level_next)){
                    $status = false;
                }
            }
        }
        return ["hierarchy" => $hierarchy, "status" => $status];

    }
}