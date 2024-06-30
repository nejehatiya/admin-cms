<?php

namespace App\Controller\Admin\Api;
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
#[Route('/api/permession')]
class ApiPermessionController extends AbstractController
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
    /**
     * edit file yaml
     */
    public function editFileYaml(){
        /**
         * $routes to yaml security 
         */
        $yaml_security = array();
        /***
         * get all routes
         */
        $all_post_type = $this->em->getRepository(PostType::class)->findAll();
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();
        /**
         * init array_modules
         */
        $modules = array();
        /**
         * filter the routes that need permession
         */
        /*$routes_need_permessions = array_filter($allRoutes,function($route) use(&$modules,$all_post_type) {
                $options = $route->getOptions();
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
                        $modules[$options['module']] = array('methods'=>$options['method'],'routes'=>array($options['order']=>$path));
                    }else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                        $path = $route->getPath();
                        $requirements = $route->getRequirements();
                        if(!empty($requirements)){
                            foreach($requirements as $key=>$value){
                                $path=str_replace('{'.$key.'}',$value,$path);
                            }
                        }
                        if(array_key_exists('order',$options)){ 
                            $modules[$options['module']]['routes'][$options['order']]=$path;
                        }else{
                            $modules[$options['module']]['routes'][]=$path;
                        }
                    }
                    return $return_condition;
                }else{
                    $return_condition = array_key_exists('need_permession',$options) && array_key_exists('module',$options) && !array_key_exists($options['module'],$modules);
                    if(array_key_exists('module',$options) && !array_key_exists($options['module'],$modules)){
                        $path = $route->getPath();
                        $requirements = $route->getRequirements();
                        if(!empty($requirements)){
                            foreach($requirements as $key=>$value){
                                if($key == "post_type"){
                                    foreach($all_post_type as $post_type){
                                        $path=str_replace('{'.$key.'}',$post_type->getSlugPostType(),$path);
                                    }
                                }else{
                                    $path=str_replace('{'.$key.'}',$value,$path);
                                }
                            }
                        }
                        foreach($all_post_type as $post_type){
                            $modules[$post_type->getNamePostType()] = array('methods'=>$options['method'],'routes'=>array($options['order']=>$path));
                        }
                        //$modules[$options['module']] = array('methods'=>$options['method'],'routes'=>array($options['order']=>$path));
                    }else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                        $path = $route->getPath();
                        $requirements = $route->getRequirements();
                        if(!empty($requirements)){
                            foreach($requirements as $key=>$value){
                                if($key == "post_type"){
                                    foreach($all_post_type as $post_type){
                                        $path=str_replace('{'.$key.'}',$post_type->getSlugPostType(),$path);
                                    }
                                }else{
                                    $path=str_replace('{'.$key.'}',$value,$path);
                                }
                            }
                        }
                        if(array_key_exists('order',$options)){ 
                            //$modules[$options['module']]['routes'][$options['order']]=$path;
                            
                            foreach($all_post_type as $post_type){
                                $modules[$post_type->getNamePostType()]['routes'][$options['order']]=$path;
                            }
                        }else{
                            foreach($all_post_type as $post_type){
                                $modules[$post_type->getNamePostType()]['routes'][]=$path;
                            }
                        }
                    }
                    return $return_condition;
                }
            }
        );*/

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
                    $modules[$options['module']] = array('methods'=>$options['method'],'routes'=>array($options['order']=>$path));
                }else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                    $path = $route->getPath();
                    $requirements = $route->getRequirements();
                    if(!empty($requirements)){
                        foreach($requirements as $key=>$value){
                            $path=str_replace('{'.$key.'}',$value,$path);
                        }
                    }
                    if(array_key_exists('order',$options)){ 
                        $modules[$options['module']]['routes'][$options['order']]=$path;
                    }else{
                        $modules[$options['module']]['routes'][]=$path;
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
                            $modules[$post_type_test->getNamePostType()]['routes'][$options['order']] = $path;
                        }else{
                            $modules[$post_type_test->getNamePostType()] = array('methods'=>$options['method'],'routes'=>array($options['order']=>$path));
                        }
                        
                        
                        //echo "<pre>";print_r($modules[$post_type_test->getNamePostType()]);echo "</pre>";
                    }
                }/*else if(array_key_exists('module',$options) && array_key_exists($options['module'],$modules)){
                    
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
                            $modules[$post_type_test->getNamePostType()]['routes'][$options['action']][] = $path;
                            
                        }else{
                        }
                    }
                }*/
                return $return_condition;
            }
        });
        /**
        * sort $routes_need_permessions seleon alphabet by keys
        */
        ksort($routes_need_permessions);
        /**
         * sort $modules seleon alphabet by keys
         */
        ksort($modules);
        /**  
         * sorte routes array
         */
        foreach($modules as $k=>$v){
            foreach($v as $key=>$val){
                if($key=="routes"){
                    ksort($modules[$k]["routes"]);
                    $modules[$k]["routes"]=array_values($modules[$k]["routes"]);
                }
            }
        }
        /**
         * get all roles
         */
        $roles_list = ['ROLE_SUPER_ADMIN'];
        /**
         * remplisser yaml file security
         */
        foreach($modules as $k=>$v){
            foreach($v as $key=>$val){
                if($key=="routes"){
                    for($i=0;$i<count($val);$i++){
                        $roles_list = ['ROLE_SUPER_ADMIN'];
                        /**
                         * get roles list by path
                         */
                        $router = $this->em->getRepository(Routes::class)->findOneBy(array('path'=>$val[$i]));
                        if(!empty($router)){
                            $roles_list_by_router = $router->getRole();
                            if(!empty($roles_list_by_router)){
                                foreach($roles_list_by_router as $role_list_by_router){
                                    $roles_list[] = $role_list_by_router->getRole();
                                }
                            }
                        }
                        $yaml_security[]= array('path'=>'^'.$val[$i],'roles'=>array_values(array_unique($roles_list)));
                    }
                }
            }
        }
        //$yaml_security[]= array('path'=>'^/admin','roles'=>array_values(array_unique(array('IS_AUTHENTICATED_FULLY'))));
        /**
         * end of traitement
         */
        return $yaml_security;
    }

    #[Route('/save-role-routes', name: 'app_permession_save_role_routes')]
    public function saveRoleRoutes(Request $request): Response
    {
        /**
         * get class name from request
         */
        $checked_role_collection = json_decode($request->request->get('checked_role_collection'),true);
        $role_id = (int)$request->request->get('role_id');
        /**
         * get role by id
         */
        $role = $this->em->getRepository(Roles::class)->find($role_id);
        /**
         * check if checked_role_collection not empty
         */
        if(!empty($role)){
            /** remove all routes from role */
            $routes = $role->getRoutes();
            foreach($routes as $route){
                $role->removeRoute($route);
                //$this->em->remove($route);
            }
            $this->em->flush();
            /*** insert new routes for role */
            if(!empty($checked_role_collection)){
                for($i=0;$i<count($checked_role_collection);$i++){
                    $router = $this->em->getRepository(Routes::class)->findOneBy(array('path'=>$checked_role_collection[$i]));
                   
                    if(empty($router)){
                        $router = new Routes();
                        $router->setPath($checked_role_collection[$i]);
                        $this->em->persist($router);
                        $this->em->flush();
                    }
                    $role->addRoute($router);
                }
                $this->em->flush();
            }
            /*** start edit yaml file */
            $filesystem = new Filesystem();
            $parse_to_array = array();
            /**
             * check if file is already existe
             */
            if($filesystem->exists($this->security_yaml)){
                /**
                 * parse yaml file 
                 */
                $parse_to_array = Yaml::parseFile($this->security_yaml);
                /**
                 * get and check security key
                 */
                if(array_key_exists('security',$parse_to_array)){
                    $security_array = $parse_to_array['security'];
                    /**
                     * check if access_control is existe
                     */
                    if(array_key_exists('access_control',$security_array)){
                        $access_control = $security_array['access_control'];
                        /**
                         * edit acces controle
                         */
                        $array = $this->editFileYaml();
                        $security_array['access_control'] = $array;
                        $parse_to_array['security'] = $security_array;
                        $yaml_newRoutes = Yaml::dump($parse_to_array, 3,4, Yaml::DUMP_OBJECT);
                        file_put_contents($this->security_yaml, $yaml_newRoutes);
                        return $this->json(array('success'=>true,'message'=>'security routes already set'),200);
                    }else{
                        /**
                         * return message d'erreures
                         */
                        return $this->json(array('success'=>false,'message'=>'index access_control manqué dans votre fichier'),200);
                    }
                }else{
                    /**
                     * return message d'erreures
                     */
                    return $this->json(array('success'=>false,'message'=>'index security manqué dans votre fichier'),200);
                }
            }
        }
        return $this->json(array('success'=>false),200);
    }


    #[Route('/charger-role-routes', name: 'app_permession_charger_role_routes')]
    public function chargerRoleRoutes(Request $request): Response
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
        });
        $roles_list = $this->em->getRepository(Roles::class)->findAll();
        ksort($modules);
        /**
         * init variable
         */
        $active_routes = array();
        $html = "";
        /**
         * get role_id from request
         */
        $role_id = (int)$request->request->get('role_id');
        /**
         * get role by id
         */
        $role = $this->em->getRepository(Roles::class)->find($role_id);
        /**
         * check if role existe
         */

        if(!empty($role)){
            foreach($role->getRoutes() as $v){
                $active_routes[] = $v->getPath();
            }
            $html = $this->renderView('admin/permession_admin/tbody.html.twig', [
                'modules'=>$modules,
                'active_routes'=>$active_routes,
            ]);
            return $this->json(array('success'=>true,'html'=>$html),200);
        }
        return $this->json(array('success'=>false),200);
    }
}
