<?php

namespace App\Controller\Admin\Views;
use App\Entity\Roles;
use App\Entity\Routes;
use App\Entity\PostType;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/permession', options:["need_permession"=>true,"module"=>"Permession", "method"=>["Afficher"]])]

class PermessionAdminController extends AbstractController
{
    public $em;
    private $router;
    private $security_yaml;
    public function __construct(EntityManagerInterface $EntityManagerInterface,RouterInterface $router,string $SECURITY_YAML="")
    {
        $this->em = $EntityManagerInterface;
        $this->router=$router;
        $this->security_yaml=$SECURITY_YAML;
    }
    #[Route('/', name: 'app_permession_admin', options:["action"=>"Afficher","need_permession"=>true,"module"=>"Permession", "method"=>["Afficher"], "order"=>5])]
    public function index(): Response
    {
        $all_post_type = $this->em->getRepository(PostType::class)->findAll();
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();
        $modules = array();
        $routes_need_permessions = array_filter($allRoutes,function($route) use(&$modules,$all_post_type) {
                $options = $route->getOptions();
                /*if(array_key_exists('module',$options)){
                    echo "<pre>";print_r($options['module']);echo "</pre>";
                }*/
                if(array_key_exists('module',$options) && $options['module'] !== "Post"){
                    $return_condition = array_key_exists('need_permession',$options) && array_key_exists('module',$options) && !array_key_exists($options['module'],$modules);
                    if(array_key_exists('module',$options) && !array_key_exists($options['module'],$modules)){
                        $path = $route->getPath();
                        $requirements = $route->getRequirements();
                        if(!empty($requirements)){
                            foreach($requirements as $key=>$value){
                                $path=str_replace('{'.$key.'}',$value,$path);
                            }
                        }
                        $modules[$options['module']] = array('methods'=>$options['method'],'routes'=>array($options['action']=>$path));
                    }else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                        $path = $route->getPath();
                        $requirements = $route->getRequirements();
                        if(!empty($requirements)){
                            foreach($requirements as $key=>$value){
                                $path=str_replace('{'.$key.'}',$value,$path);
                            }
                        }
                        if(array_key_exists('action',$options)){
                            $modules[$options['module']]['routes'][$options['action']]=$path;
                        }
                    }
                    return $return_condition;
                }else if(array_key_exists('module',$options) && $options['module'] === "Post"){
                    
                
                    
                    $return_condition = array_key_exists('need_permession',$options) && array_key_exists('module',$options) && !array_key_exists($options['module'],$modules);
                    if(array_key_exists('module',$options) && !array_key_exists($options['module'],$modules)){
                        
                        foreach($all_post_type as $post_type_test){
                            $path = $route->getPath();
                            $requirements = $route->getRequirements();
                            if(!empty($requirements)){
                                foreach($requirements as $key=>$value){
                                    if($key == "post_type"){
                                        $path=str_replace('{'.$key.'}',$post_type_test->getSlugPostType(),$path);
                                    }else{
                                        $path=str_replace('{'.$key.'}',$value,$path);
                                    }
                                }
                            }
                            if(array_key_exists($post_type_test->getNamePostType(),$modules)){
                                $modules[$post_type_test->getNamePostType()]['routes'][$options['action']] = $path;
                            }else{
                                $modules[$post_type_test->getNamePostType()] = array('methods'=>$options['method'],'routes'=>array($options['action']=>$path));
                            }
                            
                            
                            //echo "<pre>";print_r($modules[$post_type_test->getNamePostType()]);echo "</pre>";
                        }
                    }else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                        
                        foreach($all_post_type as $post_type_test){
                            $path = $route->getPath();
                            $requirements = $route->getRequirements();
                            if(!empty($requirements)){
                                foreach($requirements as $key=>$value){
                                    if($key == "post_type"){
                                            $path=str_replace('{'.$key.'}',$post_type_test->getSlugPostType(),$path);
                                    }else{
                                        $path=str_replace('{'.$key.'}',$value,$path);
                                    }
                                }
                            }
                            if(array_key_exists('action',$options)){
                                $modules[$post_type_test->getNamePostType()]['routes'][$options['action']] = $path;
                                
                            }else{
                            }
                        }
                    }
                    return $return_condition;
                }
            }
        );
        //exit;
        $roles_list = $this->em->getRepository(Roles::class)->findAll();
        //echo "<pre>";print_r($modules);echo "</pre>";
        //exit;
        ksort($modules);
        //dump($modules);
        /***
         * active role
         */
        $active_routes = array();
        foreach($roles_list as $role){
            if($role->getRole()!="ROLE_SUPER_ADMIN" && $role->getRole()!="ROLE_ROLE_USER" && $role->getRole()!="ROLE_USER"){
                $active_role = $role;
                foreach($role->getRoutes() as $v){
                    $active_routes[] = $v->getPath();
                }
                break;
            }
        }
        return $this->render('admin/permession_admin/index.html.twig', [
            'roles_list' => $roles_list,
            'modules'=>$modules,
            'active_routes'=>$active_routes,
        ]);
    }
}
